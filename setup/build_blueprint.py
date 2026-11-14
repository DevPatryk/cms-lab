#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""Składa master blueprint.json dla WordPress Playground z plików motywu + buildera."""
import json, pathlib, urllib.parse

ROOT = pathlib.Path(__file__).resolve().parent.parent   # cms-lab/
THEME = ROOT / "theme"
WP = "/wordpress/wp-content"
THEME_DIR = f"{WP}/themes/auto-lsnienie"

def read(p):
    return pathlib.Path(p).read_text(encoding="utf-8")

build_site = read(ROOT / "setup" / "build-site.php")

blueprint = {
    "$schema": "https://playground.wordpress.net/blueprint-schema.json",
    "landingPage": "/",
    "preferredVersions": {"php": "8.3", "wp": "latest"},
    "login": True,
    "steps": [
        {"step": "setSiteLanguage", "language": "pl_PL"},
        {"step": "installTheme",
         "themeData": {"resource": "wordpress.org/themes", "slug": "twentytwentyfive"},
         "options": {"activate": False}},
        {"step": "mkdir", "path": THEME_DIR},
        {"step": "mkdir", "path": f"{THEME_DIR}/patterns"},
        {"step": "writeFile", "path": f"{THEME_DIR}/style.css", "data": read(THEME / "style.css")},
        {"step": "writeFile", "path": f"{THEME_DIR}/functions.php", "data": read(THEME / "functions.php")},
        {"step": "writeFile", "path": f"{THEME_DIR}/theme.json", "data": read(THEME / "theme.json")},
        {"step": "writeFile", "path": f"{THEME_DIR}/patterns/hero-cta.php", "data": read(THEME / "patterns" / "hero-cta.php")},
        {"step": "activateTheme", "themeFolderName": "auto-lsnienie"},
        {"step": "installPlugin",
         "pluginData": {"resource": "wordpress.org/plugins", "slug": "contact-form-7"},
         "options": {"activate": True}},
        {"step": "setSiteOptions", "options": {
            "blogname": "Auto Lśnienie",
            "blogdescription": "Detailing samochodowy w Koninie — Twoje auto jak nowe",
            "permalink_structure": "/%postname%/"
        }},
        {"step": "writeFile", "path": f"{WP}/build-site.php", "data": build_site},
        {"step": "runPHP",
         "code": "<?php require_once '/wordpress/wp-load.php'; require '" + WP + "/build-site.php';"},
    ],
}

# zapis JSON (czytelny, do repo)
out_json = ROOT / "blueprint.json"
out_json.write_text(json.dumps(blueprint, ensure_ascii=False, indent=2), encoding="utf-8")

# zapis gotowego URL do Playground (fragment zakodowany)
compact = json.dumps(blueprint, ensure_ascii=False, separators=(",", ":"))
url = "https://playground.wordpress.net/#" + urllib.parse.quote(compact, safe="")
(ROOT / "setup" / "playground_url.txt").write_text(url, encoding="utf-8")

# lokalna strona-przekierowanie (żeby nie wklejać 50 KB URL ręcznie)
go = (
    "<!doctype html><meta charset=utf-8><title>Otwieram Playground…</title>"
    "<body style=\"font-family:sans-serif;padding:2rem\">"
    "<p>Otwieram WordPress Playground z blueprintem „Auto Lśnienie”… "
    "jeśli nic się nie dzieje, <a id=l href=\"#\">kliknij tutaj</a>.</p>"
    "<script>var u=" + json.dumps(url) + ";"
    "document.getElementById('l').href=u;location.replace(u);</script>"
)
(ROOT / "setup" / "go.html").write_text(go, encoding="utf-8")

print("blueprint.json:", out_json.stat().st_size, "B")
print("url length:", len(url))
print("go.html:", (ROOT / "setup" / "go.html").stat().st_size, "B")
