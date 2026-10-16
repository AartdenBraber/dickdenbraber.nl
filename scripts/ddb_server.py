"""Verbinding met de server van dickdenbraber.nl.

Cloud86 draait Plesk. Wat werkt is FTP met expliciete TLS op poort 21. SFTP
op poort 22 zit er ook in, maar dit account heeft geen SSH-toegang en dan
sluit de server het SFTP-kanaal direct na het inloggen. Dit is de enige plek
in de repo waar een van die twee wordt opgezet. De rest van de scripts praat met het object dat verbind()
teruggeeft en hoeft niet te weten welke van de twee het is.

Bij SFTP wordt de sleutel van de server de eerste keer vastgelegd in
scripts/known_hosts, en die staat in git. Verandert hij daarna, dan weigert
paramiko te verbinden. Dat is de bedoeling: dan zit er iets tussen.

Bij FTPS zitten de twee voorzorgen uit warmonbikes erin: TLS onderhandelen voor
het inloggen, zodat het wachtwoord nooit in platte tekst gaat, en de TLS-sessie
hergebruiken op het datakanaal.
"""

import base64
import ftplib
import hashlib
import io
import os
import ssl
import stat
import sys
import time
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent
KNOWN_HOSTS = REPO / "scripts" / "known_hosts"


def lees_env():
    """Zet wat in .env staat in os.environ, zonder te overschrijven."""
    pad = REPO / ".env"
    if not pad.exists():
        return
    for regel in pad.read_text(encoding="utf-8").splitlines():
        regel = regel.strip()
        if not regel or regel.startswith("#") or "=" not in regel:
            continue
        naam, waarde = regel.split("=", 1)
        naam = naam.strip()
        waarde = waarde.strip().strip('"').strip("'")
        if naam and naam not in os.environ:
            os.environ[naam] = waarde


# --- SFTP ---


class Sftp:
    def __init__(self, client, sftp):
        self.client = client
        self.sftp = sftp
        self.soort_verbinding = "sftp"

    def lijst(self, pad):
        """Mappen en bestanden in `pad`, als lijst van (naam, soort, grootte)."""
        uit = []
        for item in self.sftp.listdir_attr(pad or "."):
            soort = "dir" if stat.S_ISDIR(item.st_mode or 0) else "file"
            uit.append((item.filename, soort, item.st_size or 0))
        return sorted(uit)

    def soort(self, pad):
        """'dir', 'file' of None als het pad niet bestaat."""
        try:
            info = self.sftp.stat(pad)
        except FileNotFoundError:
            return None
        except OSError:
            return None
        return "dir" if stat.S_ISDIR(info.st_mode or 0) else "file"

    def grootte(self, pad):
        try:
            return self.sftp.stat(pad).st_size
        except OSError:
            return None

    def lees(self, pad, maximum=2 * 1024 * 1024):
        """Het bestand als bytes. Groter dan `maximum` geeft None."""
        grootte = self.grootte(pad)
        if grootte is not None and grootte > maximum:
            return None
        buffer = io.BytesIO()
        self.sftp.getfo(pad, buffer)
        return buffer.getvalue()

    def schrijf(self, pad, data):
        self.sftp.putfo(io.BytesIO(data), pad, confirm=False)

    def hernoem(self, van, naar):
        """Over een bestaand bestand heen, in een stap als de server dat kan."""
        try:
            self.sftp.posix_rename(van, naar)
            return
        except OSError:
            pass
        try:
            self.sftp.remove(naar)
        except OSError:
            pass
        self.sftp.rename(van, naar)

    def verwijder(self, pad):
        self.sftp.remove(pad)

    def sluit(self):
        try:
            self.sftp.close()
        finally:
            self.client.close()


def _sftp_verbind(host, poort, gebruiker, wachtwoord, stil):
    import paramiko

    class EersteKeer(paramiko.MissingHostKeyPolicy):
        """Onbekende server: sleutel tonen en vastleggen in scripts/known_hosts."""

        def missing_host_key(self, client, hostname, key):
            vingerafdruk = base64.b64encode(
                hashlib.sha256(key.asbytes()).digest()).decode().rstrip("=")
            print(f"Nieuwe serversleutel voor {hostname}: {key.get_name()} "
                  f"SHA256:{vingerafdruk}")
            print("  vastgelegd in scripts/known_hosts")
            client.get_host_keys().add(hostname, key.get_name(), key)
            client.save_host_keys(str(KNOWN_HOSTS))

    client = paramiko.SSHClient()
    if KNOWN_HOSTS.exists():
        client.load_host_keys(str(KNOWN_HOSTS))
    client.set_missing_host_key_policy(EersteKeer())

    for poging in range(1, 4):
        try:
            client.connect(
                host, port=poort, username=gebruiker, password=wachtwoord,
                timeout=30, banner_timeout=30, auth_timeout=30,
                look_for_keys=False, allow_agent=False,
            )
            break
        except paramiko.AuthenticationException:
            print("SFTP: inloggen geweigerd. Klopt de gebruiker of het wachtwoord,\n"
                  "en mag dit account SFTP? Een los FTP-account in Plesk mag dat\n"
                  "vaak niet; zet dan DDB_PROTOCOL=ftps en DDB_PORT=21.",
                  file=sys.stderr)
            sys.exit(1)
        except (TimeoutError, ConnectionError, OSError, paramiko.SSHException) as fout:
            if poging == 3:
                raise
            if not stil:
                print(f"  geen verbinding ({fout.__class__.__name__}), opnieuw")
            time.sleep(3)

    sftp = client.open_sftp()
    if not stil:
        print("Verbonden via SFTP.")
    return Sftp(client, sftp)


# --- FTPS ---


class FTPSHergebruik(ftplib.FTP_TLS):
    """FTP_TLS die de TLS-sessie van het besturingskanaal hergebruikt."""

    def ntransfercmd(self, cmd, rest=None):
        conn, size = ftplib.FTP.ntransfercmd(self, cmd, rest)
        if self._prot_p:
            conn = self.context.wrap_socket(
                conn,
                server_hostname=self.host,
                session=self.sock.session,
            )
        return conn, size


class Ftps:
    def __init__(self, ftp):
        self.ftp = ftp
        self.soort_verbinding = "ftps"

    def lijst(self, pad):
        uit = []
        try:
            for naam, feiten in self.ftp.mlsd(pad, facts=["type", "size"]):
                if naam in (".", ".."):
                    continue
                soort = "dir" if feiten.get("type") == "dir" else "file"
                uit.append((naam, soort, int(feiten.get("size", 0) or 0)))
            return sorted(uit)
        except (ftplib.error_perm, ftplib.error_proto):
            pass
        regels = []
        self.ftp.retrlines(f"LIST {pad}", regels.append)
        for regel in regels:
            delen = regel.split(maxsplit=8)
            if len(delen) < 9 or delen[8] in (".", ".."):
                continue
            try:
                grootte = int(delen[4])
            except ValueError:
                grootte = 0
            uit.append((delen[8], "dir" if regel[0] == "d" else "file", grootte))
        return sorted(uit)

    def soort(self, pad):
        # In binaire modus geeft SIZE een getal voor een bestand en een fout
        # voor een map. Daarna proberen we of het een map is.
        try:
            self.ftp.size(pad)
            return "file"
        except ftplib.all_errors:
            pass
        hier = self.ftp.pwd()
        try:
            self.ftp.cwd(pad)
            self.ftp.cwd(hier)
            return "dir"
        except ftplib.all_errors:
            return None

    def grootte(self, pad):
        try:
            return self.ftp.size(pad)
        except ftplib.all_errors:
            return None

    def lees(self, pad, maximum=2 * 1024 * 1024):
        grootte = self.grootte(pad)
        if grootte is not None and grootte > maximum:
            return None
        buffer = io.BytesIO()
        self.ftp.retrbinary(f"RETR {pad}", buffer.write)
        return buffer.getvalue()

    def schrijf(self, pad, data):
        self.ftp.storbinary(f"STOR {pad}", io.BytesIO(data))

    def hernoem(self, van, naar):
        try:
            self.ftp.rename(van, naar)
        except ftplib.error_perm:
            # Sommige servers hernoemen niet over een bestaand bestand heen.
            self.ftp.delete(naar)
            self.ftp.rename(van, naar)

    def verwijder(self, pad):
        self.ftp.delete(pad)

    def sluit(self):
        try:
            self.ftp.quit()
        except ftplib.all_errors:
            self.ftp.close()


def _ftps_verbind(host, poort, gebruiker, wachtwoord, stil):
    ctx = ssl.create_default_context()
    for poging in range(1, 4):
        ftp = FTPSHergebruik(context=ctx)
        ftp.encoding = "utf-8"
        try:
            ftp.connect(host, poort, timeout=30)
            break
        except (TimeoutError, ConnectionError, OSError) as fout:
            if poging == 3:
                raise
            if not stil:
                print(f"  geen antwoord ({fout.__class__.__name__}), opnieuw")
            time.sleep(3)

    # AUTH TLS voor het inloggen. Lukt dat niet, dan stoppen we voordat het
    # wachtwoord over de lijn gaat.
    try:
        ftp.auth()
    except (ftplib.error_perm, ftplib.error_proto, ssl.SSLError) as fout:
        print("De server bood geen expliciete TLS aan: " + str(fout) + "\n"
              "Gestopt voor het inloggen, het wachtwoord is niet verstuurd.",
              file=sys.stderr)
        ftp.close()
        sys.exit(1)

    ftp.login(gebruiker, wachtwoord)
    ftp.prot_p()
    ftp.set_pasv(True)
    ftp.voidcmd("TYPE I")
    if not stil:
        print(f"Verbonden via FTPS ({ftp.sock.version()}).")
    return Ftps(ftp)


# --- voor de scripts ---


def verbind(stil=False):
    lees_env()

    protocol = os.environ.get("DDB_PROTOCOL", "ftps").strip().lower()
    host = os.environ.get("DDB_HOST", "dickdenbraber.nl")
    standaardpoort = 22 if protocol == "sftp" else 21
    poort = int(os.environ.get("DDB_PORT", "") or standaardpoort)
    gebruiker = os.environ.get("DDB_USER", "")
    wachtwoord = os.environ.get("DDB_PASS", "")

    ontbreekt = [n for n, v in (("DDB_USER", gebruiker), ("DDB_PASS", wachtwoord)) if not v]
    if ontbreekt:
        print("Niet ingevuld in .env: " + ", ".join(ontbreekt), file=sys.stderr)
        sys.exit(1)

    if not stil:
        print(f"Verbinden met {host}:{poort} via {protocol} als {gebruiker}")

    if protocol == "sftp":
        return _sftp_verbind(host, poort, gebruiker, wachtwoord, stil)
    if protocol == "ftps":
        return _ftps_verbind(host, poort, gebruiker, wachtwoord, stil)
    print("DDB_PROTOCOL moet sftp of ftps zijn, niet " + protocol, file=sys.stderr)
    sys.exit(1)


def wortel(verbinding):
    """De map met wp-config.php, als pad dat de verbinding begrijpt.

    DDB_ROOT wint. Anders zoeken we in de beginmap, in httpdocs en een niveau
    diep. Bij Plesk is het vrijwel altijd httpdocs.
    """
    gezet = os.environ.get("DDB_ROOT", "").strip().rstrip("/")
    if gezet:
        return gezet
    for kandidaat in ("httpdocs", "."):
        if verbinding.soort(kandidaat + "/wp-config.php") == "file":
            return kandidaat
    for naam, soort, _ in verbinding.lijst("."):
        if soort == "dir" and verbinding.soort(naam + "/wp-config.php") == "file":
            return naam
    print("Geen wp-config.php gevonden. Zet DDB_ROOT in .env.", file=sys.stderr)
    sys.exit(1)
