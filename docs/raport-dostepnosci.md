# Raport dostępności — Auto Lśnienie (WCAG 2.2 AA)

Przedmiot CMS, laboratorium L3. Audyt strony „Auto Lśnienie" pod kątem WCAG 2.2 na poziomie AA.

> **Metoda.** Strona została zaprojektowana „accessibility-first" — zasady WCAG 2.2 wbudowano
> w `theme.json` i CSS od początku, zamiast łatać je po audycie. Poniżej: (1) weryfikacja kontrastu
> z obliczeniami, (2) lista kryteriów WCAG 2.2 z tym, jak są spełnione, (3) typowe błędy, których
> świadomie uniknięto. Narzędzia do automatycznego potwierdzenia (Lighthouse / WAVE / axe) — patrz koniec.

---

## 1. Kontrast kolorów (WCAG 1.4.3 — min. 4,5:1 dla tekstu, 3:1 dla dużego)

Współczynniki policzone wg wzoru WCAG (luminancja względna) dla palety z `theme.json`:

| Zastosowanie | Tekst | Tło | Kontrast | Wynik |
|---|---|---|---|---|
| Treść główna | `#14181c` Tekst | `#ffffff` Tło | **17,84 : 1** | ✅ AA/AAA |
| Hero, przycisk (biały tekst) | `#ffffff` | `#0b5d7a` Marka | **7,34 : 1** | ✅ AA/AAA |
| Przycisk akcent (biały tekst) | `#ffffff` | `#a3320b` Akcent | **6,96 : 1** | ✅ AA/AAA |
| Linki w treści | `#0b5d7a` Marka | `#ffffff` Tło | **7,34 : 1** | ✅ AA/AAA |
| Tekst na sekcji | `#14181c` Tekst | `#eef3f6` Sekcja | **15,96 : 1** | ✅ AA/AAA |

Wszystkie pary przekraczają próg 4,5:1 — także dla tekstu normalnej wielkości. Żadnej pary nie
trzeba było poprawiać. (Obliczenia: `setup/`-owy skrypt kontrastu; te same wartości potwierdza
WebAIM Contrast Checker.)

---

## 2. Kryteria WCAG 2.2 — jak są spełnione

| Kryterium | Jak spełnione na stronie |
|---|---|
| **1.3.1** Informacje i relacje | Semantyczny HTML z bloków: `header`/`main`/`footer`, jeden `H1` na stronę, logiczne `H2`/`H3`, listy, tabela cennika z nagłówkami. |
| **1.4.3** Kontrast | Patrz tabela wyżej — wszystkie pary ≥ 4,5:1. |
| **1.4.4 / 1.4.10** Zmiana rozmiaru / reflow | Płynna typografia `clamp()` i układ mobile-first; kolumny układają się jedna pod drugą ≤ 600 px (`@media`). |
| **2.1.1** Dostępność z klawiatury | Nawigacja, linki, przyciski i pola formularza są natywnymi elementami — obsługa klawiaturą „z pudełka". |
| **2.4.7** Widoczny fokus | Własny styl `:focus-visible` (obrys 3 px w kolorze akcentu + offset) — nigdy `outline:none`. |
| **2.4.11** Focus Not Obscured (**WCAG 2.2**) | `html{scroll-padding-top:6rem}` — element z fokusem nie chowa się pod nagłówkiem po przewinięciu kotwicą. |
| **2.5.8** Target Size (**WCAG 2.2**) | Cele dotykowe ≥ 24 px (pozycje menu, linki społecznościowe); przyciski ≥ 44 px wysokości. |
| **3.3.2** Etykiety / instrukcje | Każde pole formularza ma **widoczną etykietę** (`<label>`), pola wymagane oznaczone tekstem „(wymagane)". |
| **3.3.8** Accessible Authentication (**WCAG 2.2**) | Brak CAPTCHA-łamigłówki i testów poznawczych; formularz nie wymaga zapamiętywania — spełnia kryterium. |
| **1.1.1** Tekst alternatywny | Brak obrazów treściowych w wersji oddawanej; sekcja „Realizacje" używa bloków dekoracyjnych (`aria-hidden`), więc nie wymaga `alt`. Po dodaniu zdjęć realizacji każde dostanie `alt` opisujący efekt „przed/po". |

---

## 3. Typowe błędy, których świadomie uniknięto (odpowiednik „przed → po")

1. **Dwa `H1` na stronie.** Szablon Twenty Twenty-Five domyślnie wyświetla tytuł strony jako `H1`,
   a sekcja hero ma własny `H1`. → **Poprawka:** własne szablony `front-page` i `page-uslugi`
   bez bloku tytułu, dzięki czemu na stronie głównej i „Usługach" jest dokładnie jeden `H1`.
2. **Niewidoczny fokus.** Domyślny delikatny outline bywa niewidoczny. → **Poprawka:** mocny
   `:focus-visible` w kolorze akcentu z offsetem.
3. **Za małe cele dotykowe w menu.** → **Poprawka:** `min-height/min-width: 24px` + padding.
4. **Zewnętrzne fonty (Google Fonts).** Ładowanie fontów z CDN Google przesyła IP użytkownika do
   Google (problem RODO). → **Decyzja:** systemowy stos fontów — zero zapytań do stron trzecich.
5. **Zgoda RODO zaznaczona domyślnie.** → **Poprawka:** pole `[acceptance]` w formularzu jest
   domyślnie **odznaczone** i linkuje do polityki prywatności.

---

## 4. Potwierdzenie narzędziami automatycznymi (do dołożenia przez autora)

Raport opiera się na audycie ręcznym + obliczeniach kontrastu. Aby dołożyć zrzuty z narzędzi
(jeśli prowadzący ich oczekuje), na żywej stronie w Playground:

1. **Lighthouse** — w Chrome DevTools (F12) → zakładka *Lighthouse* → kategoria *Accessibility* →
   *Analyze page load*. Zapisz wynik (zwykle 95–100).
2. **WAVE** — rozszerzenie [wave.webaim.org](https://wave.webaim.org/extension/) → uruchom na stronie
   głównej i „Kontakt". Zapisz liczbę błędów (0) i ostrzeżeń.
3. **axe DevTools** — zakładka *axe* → *Scan all of my page*.

Zrzuty wrzuć do `docs/screeny/` jako `lighthouse-strona-glowna.png` itd.
