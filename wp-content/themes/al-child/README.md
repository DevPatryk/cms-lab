# AL Child — motyw potomny Twenty Twenty-One (MP2, L5)

Motyw potomny (child theme) dla fikcyjnego studia detailingu **Auto Lśnienie**. Rodzic: klasyczny
motyw **Twenty Twenty-One** (`Template: twentytwentyone`). Plików rodzica nie zmieniam, więc jego aktualizacja
nie kasuje moich zmian.

**Autor:** Patryk Matuszak · **Wersja:** 1.0.0 · **Licencja:** GPL v2 or later · **Prefiks:** `al_` · **Text domain:** `al-child`

## Struktura

```
al-child/
├─ style.css                 ← nagłówek (Template: twentytwentyone) + nadpisane style
├─ functions.php             ← enqueue, hooki, style bloków, rejestracja bloku, Customizer
├─ page-o-nas.php            ← szablon tylko dla strony o slugu "o-nas"
├─ languages/                ← miejsce na tłumaczenia (.mo) dla domeny al-child
└─ blocks/info-firmy/        ← blok dynamiczny (bez npm, bez budowania)
   ├─ block.json             ← opis bloku (nazwa, atrybuty, pliki)
   ├─ index.js               ← edytor: podgląd z serwera + przełącznik „Pokaż telefon”
   ├─ index.asset.php        ← zależności skryptu (wp-blocks, wp-element…)
   └─ render.php             ← HTML generowany po stronie serwera
```

## Decyzje projektowe

- **Dlaczego potomny, a nie edycja rodzica?** Zmiany w rodzicu zniknęłyby przy jego aktualizacji. Potomek
  nadpisuje tylko to, co trzeba: kolory, krój nagłówków i jeden szablon.
- **Kolejność stylów:** `wp_enqueue_style( 'al-child-style', …, array( 'twenty-twenty-one-style' ) )`.
  Dzięki zależności arkusz potomka ładuje się **po** arkuszu rodzica i może go nadpisać (sprawdzone w HTML strony).
- **Kolory i krój:** jak w MP1: kolor marki `#0b5d7a`, akcent `#a3320b`, białe tło, systemowy stos fontów
  (bez Google Fonts, ze względu na RODO). Twenty Twenty-One ustawia krój i grubość nagłówków przez zmienne CSS
  (`--heading--font-family`, `--heading--font-weight…`), więc nadpisuję zmienne zamiast pisać nowe selektory.
- **Szablon `page-o-nas.php`:** Twenty Twenty-One otwiera `<main>` w `header.php`, dlatego w szablonie jest tylko
  `<article>`. Widoczna klasa `al-o-nas` (jasne tło nagłówka) pokazuje, że działa nowy szablon. Inne strony
  dalej używają `page.php` rodzica (sprawdzone: `/uslugi/` nie ma klasy `al-o-nas`).
- **Zajawka na „O nas”:** strony domyślnie nie mają pola zajawki, więc włączam je
  `add_post_type_support( 'page', 'excerpt' )` i wypisuję tylko wtedy, gdy jest wpisana (`has_excerpt()`).

## Użyte hooki

| Hook | Typ | Funkcja | Co robi |
|---|---|---|---|
| `wp_enqueue_scripts` | akcja | `al_child_enqueue_styles` | ładuje `style.css` potomka po stylu rodzica |
| `after_setup_theme` | akcja | `al_child_setup` | ładuje tłumaczenia `al-child`, włącza zajawki stron |
| `excerpt_length` | filtr | `al_excerpt_length` | zajawki 25 słów (priorytet 20, po rodzicu) |
| `the_content` | filtr | `al_add_note` | notka „Masz pytania…” pod treścią pojedynczego wpisu |
| `init` | akcja | `al_register_block_styles` | style bloków „Z cieniem” (przycisk) i „Karta” (grupa) |
| `init` | akcja | `al_register_blocks` | rejestruje blok `al-child/info-firmy` z `block.json` |
| `customize_register` | akcja | `al_customize_register` | pole „Telefon” w *Wygląd → Dostosuj → Dane firmy* |

Filtr zawsze **zwraca** wartość (`return $content;`). Bez tego treść wpisu byłaby pusta.

## Blok dynamiczny „Informacja o firmie”

Blok jest **dynamiczny**: `save` zwraca `null`, a HTML za każdym razem tworzy `render.php` na serwerze.
Dzięki temu zmiana telefonu w *Dostosuj* albo nazwy strony od razu zmienia blok **wszędzie**:
na stronie „O nas” i w stopce (widżet blokowy). Blok statyczny zapisałby HTML w treści każdej strony
i każdą trzeba by było edytować ręcznie.

## Bezpieczeństwo i i18n

- Każda wartość wypisywana na stronę przechodzi przez `esc_html()` albo `esc_url()` (nazwa firmy, telefon,
  link `tel:`, zajawka). Gdyby ktoś wpisał w telefonie `<script>…</script>`, wyświetli się jako tekst, a nie
  wykona (ochrona przed XSS). Pole w Customizerze dodatkowo czyści `sanitize_text_field`.
- Teksty interfejsu są w `__()`, `esc_html__()` i `esc_html_e()` z domeną `al-child`.
- Wszystkie funkcje mają prefiks `al_`, żeby nie było konfliktu nazw z rodzicem i wtyczkami.

## Kontrast (WCAG 2.2)

Link `#0b5d7a` na białym tle: 7,34:1. Tekst `#14181c` na tle sekcji `#eef3f6`: 15,96:1.
Przycisk rodzica (biały tekst na `#14181c`) ma 17,84:1, a styl „Z cieniem” zmienia tylko cień.
Wszystkie pary ≥ 4,5:1.

## Trend 2026 (P1): umiejętności motywu dla agentów AI — projekt bez kodu

Projekt 3 umiejętności (abilities), które mała wtyczka firmy mogłaby udostępnić agentowi AI przez
MCP Adapter w WordPress 7.0. To tylko projekt, kodu Abilities API nie piszę (sygnatury trzeba sprawdzić
w dokumentacji developer.wordpress.org).

| Umiejętność | Cel | Wejście | Wyjście | Kto może | Ryzyko nadużycia |
|---|---|---|---|---|---|
| `auto-lsnienie/dane-kontaktowe` | podać agentowi aktualne dane firmy | brak | nazwa, telefon, adres, godziny | każdy (dane publiczne) | małe: dane i tak są na stronie |
| `auto-lsnienie/cennik` | odpowiadać klientom na pytania o ceny | nazwa usługi (opcjonalnie) | lista usług z cenami „od” | każdy | agent może podać nieaktualną cenę jako ofertę, więc w odpowiedzi musi być „ceny orientacyjne” |
| `auto-lsnienie/szkic-aktualnosci` | przygotować wpis (np. promocję) | tytuł, treść | ID **szkicu** | tylko redaktor / administrator | agent publikuje treść bez akceptacji, dlatego umiejętność tworzy tylko szkic, a publikuje człowiek |

## Licencje

Kod: GPL v2 or later (jak rodzic Twenty Twenty-One). Fonty systemowe, bez grafik.
