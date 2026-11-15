# Auto Lśnienie Blok — motyw blokowy (MP2, L4)

Motyw blokowy (block theme) zbudowany od zera dla fikcyjnego studia detailingu **Auto Lśnienie** z Konina.
Kolory, typografia i odstępy pochodzą z `theme.json` z L3, więc motyw jest spójny z identyfikacją z MP1.

**Autor:** Patryk Matuszak · **Wersja:** 0.1.0 (tag `v0.1.0`) · **Licencja:** GPL v2 or later

## Struktura

```
auto-lsnienie-blok/
├─ style.css              ← nagłówek motywu (wymagany); stylów tu nie ma, są w theme.json
├─ theme.json             ← paleta, fonty, rozmiary, odstępy, style elementów, CSS dostępności
├─ templates/
│  ├─ index.html          ← szablon awaryjny (wymagany), lista wpisów
│  ├─ front-page.html     ← strona główna z 3 wzorców (zadanie B2)
│  ├─ single.html         ← pojedynczy wpis
│  ├─ page.html           ← pojedyncza strona
│  ├─ archive.html        ← kategorie, archiwa
│  └─ 404.html            ← błąd 404 z wyszukiwarką
├─ parts/
│  ├─ header.html         ← nazwa strony + menu (blok Nawigacja)
│  └─ footer.html         ← dane kontaktowe + link do polityki prywatności
├─ patterns/              ← wzorce PHP rejestrowane automatycznie
│  ├─ hero-cta.php        ← hero z L2 (H1 + CTA)
│  ├─ uslugi.php          ← 3 kafelki usług
│  └─ opinie.php          ← opinie klientów (przykładowe, oznaczone na stronie)
└─ styles/                ← warianty stylu (zadanie B1)
   ├─ ciemny.json
   └─ wysoki-kontrast.json
```

## Hierarchia szablonów — co sprawdziłem

Każdy szablon ma w `<main>` klasę `szablon-…`, żeby na stronie było widać, który plik wybrał WordPress.
Sprawdzone na działającym WordPressie (lokalnie, Playground CLI):

| Widok | Adres | Użyty szablon | Dlaczego |
|---|---|---|---|
| Strona główna | `/` | `front-page.html` | `front-page` ma pierwszeństwo przed `home` i `page` |
| Strona „O nas” | `/o-nas/` | `page.html` | brak `page-o-nas.html` → `page` |
| Strona „Usługi” | `/uslugi/` | `page.html` | jw. |
| Strona wpisów | `/aktualnosci/` | `index.html` | brak `home.html` → `index` |
| Kategoria | `/category/porady/` | `archive.html` | brak `category.html` → `archive` |
| Wpis | `/jak-myc-auto-zima…/` | `single.html` | brak `single-post.html` → `single` |
| Nieistniejący adres | `/nie-ma-takiej/` | `404.html` | błąd 404 |

Gdybym dodał `templates/page-kontakt.html`, strona „Kontakt” użyłaby jego zamiast `page.html`
(`page-{slug}` → `page` → `singular` → `index`).

## Warianty stylu i kontrast (WCAG 2.2)

Warianty mają **te same slugi kolorów** co `theme.json` (`base`, `contrast`, `primary`, `accent`, `surface`),
więc podmieniają tylko wartości, a szablony i wzorce działają bez zmian. Wybór: *Wygląd → Edytor → Style*.

| Para kolorów | Domyślny | Ciemny | Wysoki kontrast |
|---|---|---|---|
| tekst na tle | 17,84:1 | 16,73:1 | 21,00:1 |
| link na tle | 7,34:1 | 9,66:1 | 12,16:1 |
| tekst na kolorze marki (hero, przycisk) | 7,34:1 | 9,66:1 | 12,16:1 |
| tekst na akcencie (CTA) | 6,96:1 | 10,84:1 | 10,38:1 |
| tekst w sekcji / nagłówku | 15,96:1 | 13,69:1 | 21,00:1 |
| link w sekcji / nagłówku | 6,57:1 | 7,90:1 | 12,16:1 |
| stopka | 17,84:1 | 16,73:1 | 21,00:1 |

Wszystkie pary ≥ 4,5:1 (wzór WCAG na luminancję względną).

## Create Block Theme i Git

1. Wtyczka **Create Block Theme** (2.10.2): po zmianach w edytorze witryny „Zapisz zmiany w motywie”
   zapisuje je z bazy do plików motywu, a potem widać je w `git diff`.
2. Eksport: `auto-lsnienie-blok-v0.1.0.zip` w katalogu głównym repo (eksport z Create Block Theme).
3. Wersja: `Version: 0.1.0` w `style.css` + tag `git tag -a v0.1.0`.
4. `.gitignore` w motywie: `*.zip`, `node_modules/`, `.DS_Store`.

## Licencje

Kod: GPL v2 or later. Fonty: systemowe (bez Google Fonts — brak wysyłania IP użytkownika, RODO).
Grafik i zdjęć motyw nie zawiera. Opinie we wzorcu są przykładowe i oznaczone na stronie.
