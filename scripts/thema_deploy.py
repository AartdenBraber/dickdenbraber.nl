"""Zet bestanden uit het thema in de repo op de server.

    scripts/deploy-thema inc/jetpack.php     dit bestand, pad binnen het thema
    scripts/deploy-thema functions.php inc/template-tags.php
                                             meerdere bestanden

Er is geen testomgeving, en een fout in het thema legt ook wp-admin plat.
Vandaar deze volgorde:

1. PHP-syntax lokaal controleren met php -l.
2. Wat er staat eerst naar back-ups/ halen.
3. Uploaden onder een tijdelijke naam, met LF als regelovergang.
4. Hernoemen naar de echte naam, zodat een bezoeker nooit een half bestand
   leest.
5. Groottes vergelijken.
6. De site opvragen, buiten de LiteSpeed-cache om, en kijken of er een pagina
   terugkomt in plaats van een kritieke fout.

Gaat stap 5 of 6 mis, dan zet het script de vorige versie meteen terug.
"""

import os
import secrets
import subprocess
import sys
import urllib.error
import urllib.request
from datetime import datetime, timezone
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
import ddb_server

REPO = ddb_server.REPO
THEMA = "dickdenbraber"
LOKAAL = REPO / "wp-content" / "themes" / THEMA

FOUTMELDINGEN = (
    "Fatal error",
    "Parse error",
    "There has been a critical error",
    "Er heeft zich een kritieke fout voorgedaan",
)


def controleer_syntax(namen):
    print("1/6 PHP-syntax controleren")
    for naam in namen:
        if not naam.endswith(".php"):
            print("  " + naam + ": geen php, overgeslagen")
            continue
        uitkomst = subprocess.run(["php", "-l", str(LOKAAL / naam)],
                                  capture_output=True, text=True)
        if uitkomst.returncode != 0:
            print("  " + naam + ": FOUT")
            print(uitkomst.stdout.strip())
            print(uitkomst.stderr.strip())
            sys.exit(1)
        print("  " + naam + ": ok")
    print()


def haal_op(url):
    """Geeft (status, eerste stuk van de pagina).

    Een verzonnen parameter achter het adres, zodat LiteSpeed geen bewaarde
    kopie geeft en PHP echt draait.
    """
    url += ("&" if "?" in url else "?") + "ddbcontrole=" + secrets.token_hex(4)
    verzoek = urllib.request.Request(url, headers={"User-Agent": "dickdenbraber-controle"})
    try:
        with urllib.request.urlopen(verzoek, timeout=40) as antwoord:
            return antwoord.status, antwoord.read(400000).decode("utf-8", "replace")
    except urllib.error.HTTPError as fout:
        return fout.code, fout.read(200000).decode("utf-8", "replace")
    except Exception as fout:  # noqa: BLE001
        return 0, str(fout)


def controleer_site():
    print("6/6 De site opvragen")
    basis = os.environ.get("DDB_URL", "https://dickdenbraber.nl").rstrip("/")
    goed = True
    for pad in ("/", "/robots.txt"):
        status, tekst = haal_op(basis + pad)
        problemen = []
        if status != 200:
            problemen.append("status " + str(status))
        problemen += [m for m in FOUTMELDINGEN if m in tekst]
        if problemen:
            goed = False
            print("  " + pad + ": " + ", ".join(problemen))
        else:
            print("  " + pad + ": ok")
    print()
    return goed


def zet_terug(verbinding, extern, backupmap, namen):
    print("Terugzetten van de vorige versie")
    for naam in namen:
        bron = backupmap / naam
        if not bron.is_file():
            print("  " + naam + ": geen back-up, overgeslagen")
            continue
        verbinding.schrijf(extern + "/" + naam + ".ddbtmp", bron.read_bytes())
        verbinding.hernoem(extern + "/" + naam + ".ddbtmp", extern + "/" + naam)
        print("  " + naam + ": teruggezet")
    print()


def main():
    namen = [a.replace("\\", "/").strip("/") for a in sys.argv[1:]]
    if not namen or any(n.startswith("-") for n in namen):
        print(__doc__)
        sys.exit(1)
    for naam in namen:
        if not (LOKAAL / naam).is_file():
            print("Bestaat niet: " + str(LOKAAL / naam))
            sys.exit(1)

    controleer_syntax(namen)

    verbinding = ddb_server.verbind()
    extern = ddb_server.wortel(verbinding) + "/wp-content/themes/" + THEMA
    print("Doel: " + extern)
    print()

    stempel = datetime.now(timezone.utc).strftime("%Y%m%d-%H%M%S") + "-utc"
    backupmap = REPO / "back-ups" / ("thema-" + stempel)

    print("2/6 Bestaande versies veiligstellen")
    for naam in namen:
        if verbinding.soort(extern + "/" + naam) != "file":
            print("  " + naam + ": staat er nog niet, nieuw bestand")
            continue
        ruw = verbinding.lees(extern + "/" + naam, maximum=8 * 1024 * 1024)
        if ruw is None:
            print("  " + naam + ": kon de huidige versie niet lezen, gestopt")
            verbinding.sluit()
            sys.exit(1)
        (backupmap / naam).parent.mkdir(parents=True, exist_ok=True)
        (backupmap / naam).write_bytes(ruw)
        print("  " + naam + ": " + str(len(ruw)) + " bytes bewaard")
    if backupmap.is_dir():
        print("  in " + str(backupmap.relative_to(REPO)))
    print()

    print("3/6 Uploaden onder een tijdelijke naam")
    lengtes = {}
    for naam in namen:
        data = (LOKAAL / naam).read_bytes().replace(b"\r\n", b"\n")
        verbinding.schrijf(extern + "/" + naam + ".ddbtmp", data)
        lengtes[naam] = len(data)
        print("  " + naam + ": " + str(len(data)) + " bytes")
    print()

    print("4/6 Op hun plek zetten")
    for naam in namen:
        verbinding.hernoem(extern + "/" + naam + ".ddbtmp", extern + "/" + naam)
        print("  " + naam)
    print()

    print("5/6 Groottes vergelijken")
    fouten = 0
    for naam, lengte in lengtes.items():
        op_server = verbinding.grootte(extern + "/" + naam)
        goed = op_server == lengte
        fouten += 0 if goed else 1
        print("  " + naam.ljust(28) + " server " + str(op_server)
              + " / lokaal " + str(lengte) + ("  ok" if goed else "  LET OP"))
    print()

    if fouten or not controleer_site():
        zet_terug(verbinding, extern, backupmap, namen)
        controleer_site()
        verbinding.sluit()
        print("Niet goed gegaan. De vorige versie staat er weer op.")
        sys.exit(1)

    verbinding.sluit()
    print("Klaar.")


if __name__ == "__main__":
    main()
