"""Haalt bestanden van de server naar back-ups/ op deze machine.

    python scripts/haal_op.py                de standaardset hieronder
    python scripts/haal_op.py wp-config.php  losse paden, relatief aan de
                                             WordPress-map op de server

Leest alleen. Er wordt niets op de server gewijzigd, hernoemd of verwijderd.

Bij elk bestand wordt een sha256 vastgelegd in manifest.txt, zodat later te
controleren is of wat er staat nog hetzelfde is als wat we bewaard hebben.
Grote bestanden worden overgeslagen en aan het eind gemeld.
"""

import hashlib
import sys
from datetime import datetime, timezone
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))
import ddb_server

REPO = ddb_server.REPO

# Per bestand. Alles daarboven slaan we over en melden we.
MAX_BESTAND = 50 * 1024 * 1024

STANDAARD = [
    "wp-config.php",
    ".htaccess",
    "wp-content/themes/dickdenbraber",
    "wp-content/mu-plugins",
]


def loop_af(verbinding, basis, pad, diepte=0):
    """Alle bestandspaden onder `pad`, of `pad` zelf als het een bestand is."""
    if diepte > 20:
        print("  te diep genest, gestopt bij " + pad)
        return
    vol = basis + "/" + pad
    soort = verbinding.soort(vol)
    if soort == "file":
        yield pad
        return
    if soort != "dir":
        return
    for naam, soort, _ in verbinding.lijst(vol):
        onder = pad + "/" + naam
        if soort == "dir":
            yield from loop_af(verbinding, basis, onder, diepte + 1)
        else:
            yield onder


def main():
    paden = [p.strip("/") for p in sys.argv[1:]] or list(STANDAARD)

    stempel = datetime.now(timezone.utc).strftime("%Y%m%d-%H%M%S") + "-utc"
    doel = REPO / "back-ups" / ("site-" + stempel)

    verbinding = ddb_server.verbind()
    basis = ddb_server.wortel(verbinding)
    print("WordPress staat in " + basis)
    print()

    print("Uitzoeken wat er staat")
    lijst = []
    for pad in paden:
        gevonden = list(loop_af(verbinding, basis, pad))
        if not gevonden:
            print("  " + pad + ": niets gevonden")
            continue
        print("  " + pad.ljust(46) + " " + str(len(gevonden)) + " bestanden")
        lijst.extend(gevonden)
    print()

    if not lijst:
        print("Niets op te halen.")
        verbinding.sluit()
        sys.exit(1)

    print("Ophalen naar " + str(doel.relative_to(REPO)))
    manifest = []
    overgeslagen = []
    mislukt = []
    totaal = 0

    for pad in sorted(lijst):
        try:
            ruw = verbinding.lees(basis + "/" + pad, maximum=MAX_BESTAND)
        except Exception as fout:  # noqa: BLE001
            mislukt.append((pad, str(fout)))
            continue
        if ruw is None:
            overgeslagen.append((pad, verbinding.grootte(basis + "/" + pad)))
            continue
        hier = doel / pad
        hier.parent.mkdir(parents=True, exist_ok=True)
        hier.write_bytes(ruw)
        manifest.append((pad, len(ruw), hashlib.sha256(ruw).hexdigest()))
        totaal += len(ruw)

    print("  " + str(len(manifest)) + " bestanden, "
          + format(totaal, ",").replace(",", ".") + " bytes")
    print()

    regels = ["# opgehaald " + stempel + " van " + basis,
              "# sha256  bytes  pad", ""]
    for pad, lengte, digest in manifest:
        regels.append(digest + "  " + str(lengte).rjust(10) + "  " + pad)
    doel.mkdir(parents=True, exist_ok=True)
    (doel / "manifest.txt").write_text("\n".join(regels) + "\n", encoding="utf-8")

    if overgeslagen:
        print("Overgeslagen, groter dan " + str(MAX_BESTAND // (1024 * 1024)) + " MB:")
        for pad, grootte in overgeslagen:
            print("  " + pad.ljust(46) + " " + str(grootte) + " bytes")
        print()

    if mislukt:
        print("Niet gelukt:")
        for pad, fout in mislukt:
            print("  " + pad.ljust(46) + " " + fout)
        print()

    verbinding.sluit()
    if mislukt:
        sys.exit(1)
    print("Klaar: " + str(doel.relative_to(REPO)))


if __name__ == "__main__":
    main()
