# MP2 — jak uruchomić motywy lokalnie (bez Dockera)

Motywy testowałem na WordPress Playground CLI (Node.js 20+), czyli prawdziwym WordPressie z PHP 8.3 na własnym komputerze.

## Pliki

- `mp2-blueprint.json`: blueprint, który loguje admina, ustawia język polski, instaluje rodzica
  Twenty Twenty-One, wgrywa treść testową i włącza motyw blokowy.
- `mp2-tresc.php`: treść testowa: strony z mapy strony (w tym „O nas” z zajawką, stylem „Karta”, blokiem
  „Informacja o firmie” i przyciskiem „Z cieniem”), 2 kategorie, 3 wpisy, strona główna i strona wpisów.
  Po przełączeniu na „AL Child” wystarczy otworzyć `/mp2-setup/mp2-tresc.php`. Skrypt doda wtedy menu,
  widżet z blokiem w stopce i zajawki na listach.

## Uruchomienie (z katalogu głównego repo)

```bash
npx @wp-playground/cli@latest server --port 9400 --login \
  --mount-dir "./wp-content/themes/auto-lsnienie-blok" /wordpress/wp-content/themes/auto-lsnienie-blok \
  --mount-dir "./wp-content/themes/al-child" /wordpress/wp-content/themes/al-child \
  --mount-dir "./setup" /wordpress/mp2-setup \
  --blueprint ./setup/mp2-blueprint.json
```

Na Windowsie w Git Bash poprzedź komendę `MSYS_NO_PATHCONV=1`, bo inaczej Git Bash zamieni `/wordpress/...` na ścieżkę Windows.

Strona: http://127.0.0.1:9400 · panel: http://127.0.0.1:9400/wp-admin (zalogowany automatycznie).
Pliki motywów są podmontowane, więc zmiany w repo widać od razu po odświeżeniu strony.
