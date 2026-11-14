# setup/ — jak odtworzyć stronę z kodu

Ten katalog zawiera kod, który stawia całą stronę „Auto Lśnienie" w WordPress Playground od zera.

## Pliki

- **`build-site.php`** — builder treści uruchamiany przez blueprint (krok `runPHP`). Tworzy strony,
  wpisy, kategorie, menu, części szablonu (nagłówek/stopka + szablony `front-page`, `page-uslugi`),
  formularz Contact Form 7, ustawienia czytania i prywatności. Idempotentny (flaga `auto_lsnienie_built`).
- **`build_blueprint.py`** — składa `../blueprint.json` z plików motywu (`../theme/*`) + `build-site.php`,
  z poprawnym escapowaniem. Dodatkowo generuje `playground_url.txt` i `go.html`.

## Odtworzenie strony

**Najprościej — z gotowego ZIP:** w [playground.wordpress.net](https://playground.wordpress.net/)
→ *Import* → `../auto-lsnienie-playground.zip`.

**Z kodu (blueprint):**
1. `python build_blueprint.py` → wygeneruje `../blueprint.json`.
2. Wczytaj blueprint w Playground. Najwygodniej przez lokalny serwer, bo URL blueprintu jest długi:
   ```
   python -m http.server 8745 --bind 127.0.0.1
   ```
   i otwórz `http://127.0.0.1:8745/go.html` (przekieruje do Playground z blueprintem).

   Alternatywnie, gdy `blueprint.json` jest w repo na GitHubie, można użyć:
   `https://playground.wordpress.net/?blueprint-url=<RAW_URL_do_blueprint.json>`.

## Co powstaje

Polski panel WP, motyw potomny „Auto Lśnienie" (paleta + typografia + CSS WCAG z `theme.json`),
wtyczka Contact Form 7, komplet stron z mapy strony, 2 kategorie + 3 wpisy, menu, nagłówek i stopka,
własny wzorzec `auto-lsnienie/hero-cta`, polityka prywatności.
