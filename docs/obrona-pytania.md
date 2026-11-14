# Pytania kontrolne (obrona) — odpowiedzi · Auto Lśnienie

Odpowiedzi odwołane do tego konkretnego projektu. Wszystko, co tu jest, umiem pokazać na stronie.

---

## L1 — Środowiska i rynek CMS

**1. Self-hosted vs SaaS vs headless?**
- *Self-hosted* (mój przypadek): WordPress stawiam na własnym serwerze/środowisku, mam pełną kontrolę
  nad kodem, motywem, bazą i danymi; sam odpowiadam za hosting i aktualizacje. Brak lock-inu.
- *SaaS* (Wix, Shopify): dostawca trzyma wszystko, płacę abonament, szybko startuję, ale jestem
  zależny od ich zasad i cennika, ograniczona kontrola nad kodem.
- *Headless*: CMS dostarcza treść tylko przez API, a front buduję osobno (np. w React). Elastyczny
  front, ale więcej pracy programistycznej — przerost formy dla małej strony usługowej.
- Dla Auto Lśnienia wybrałem self-hosted WordPress — kontrola, brak abonamentu, niski próg utrzymania.

**2. Co dzieje się z Playground po zamknięciu karty?**
Playground działa w przeglądarce (WebAssembly, PHP + SQLite w pamięci). Po zamknięciu karty stan
**znika** (jeśli nie zapisano „Store permanently"). Dlatego trwałym nośnikiem jest **eksport ZIP**
albo **blueprint**, który odtwarza stronę od zera. W repo mam oba.

**3. Co robią kroki blueprintu `setSiteLanguage`, `installTheme`, `setSiteOptions`?**
- `setSiteLanguage` — ustawia język witryny (pobiera i aktywuje paczkę językową, u mnie `pl_PL`).
- `installTheme` — instaluje motyw z repozytorium WordPress.org (u mnie rodzic *Twenty Twenty-Five*).
- `setSiteOptions` — zapisuje opcje w tabeli `wp_options` (u mnie `blogname`, `blogdescription`,
  struktura permalinków).

**4. Dlaczego WordPress i baza to osobne usługi i jak WP znajduje bazę?**
WordPress (PHP) i baza danych (MariaDB/MySQL) to dwie różne warstwy — rozdzielenie pozwala skalować
i podmieniać je niezależnie. W `docker-compose.yml` są dwa serwisy: `wordpress` i `db`. WordPress
łączy się z bazą po **nazwie serwisu**: `WORDPRESS_DB_HOST: db` — Docker tworzy wewnętrzną sieć,
w której `db` jest rozwiązywane na adres kontenera bazy. (Na żywo użyłem Playground, gdzie bazą jest
SQLite w przeglądarce, ale architektura „dwie usługi" jest pokazana w pliku compose.)

**5. Jak persony ukształtowały mapę strony?**
Obie persony kończą ścieżkę na akcji „umów wizytę / wyślij zapytanie" → stąd osobna strona **Kontakt**
z formularzem i CTA powtórzone na stronie głównej. Marek (prywatny) chce zobaczyć **Cennik** i
**Realizacje** przed decyzją → te strony są w menu. Firma „Auto Bracia" pracuje na laptopie i chce
szybkiej oferty → czytelne **Usługi** z podstronami. Obowiązek RODO → **Polityka prywatności**.

---

## L2 — Struktura i edytor blokowy

**1. Kiedy strona, a kiedy wpis? (na przykładzie firmy)**
- **Strony** — treści stałe, hierarchiczne: *O nas, Usługi, Cennik, Kontakt*. Nie mają daty.
- **Wpisy** — treści chronologiczne: *Aktualności* (porady, promocje) grupowane kategoriami
  (*Porady detailingowe*, *Promocje*). Mają datę i pojawiają się na blogu od najnowszego.

**2. Wzorzec zsynchronizowany vs niezsynchronizowany?**
- *Zsynchronizowany* — zmiana w jednym miejscu zmienia go **wszędzie** (jak blok wielokrotnego użytku).
- *Niezsynchronizowany* — po wstawieniu jest **kopią do edycji**; zmiany lokalne nie wpływają na inne.
- Mój hero `auto-lsnienie/hero-cta` jest **niezsynchronizowany** — na stronie „Usługi" mogę zmienić
  tekst pod ofertę, nie ruszając strony głównej.

**3. Co to część szablonu i dlaczego nagłówek w niej trzymać?**
Część szablonu (*template part*) to wspólny fragment używany na wielu widokach — np. **Nagłówek**
i **Stopka**. Nagłówek trzymam w części szablonu, bo jest na każdej stronie: zmiana menu czy logo
w jednym miejscu aktualizuje całą witrynę. Zmiany zapisują się w bazie (`wp_template_part`).

**4. Jak zapewniłem hierarchię nagłówków i jedno CTA na stronie głównej?**
Jeden `H1` (hero), potem `H2` dla sekcji (usługi, opinie, CTA) i `H3` dla pojedynczych usług —
sprawdzone w *Widoku listy / konspekcie*. Żeby nie było dwóch `H1`, strona główna ma własny szablon
`front-page` bez tytułu wpisu. Jedno główne CTA „Umów wizytę" prowadzące do `/kontakt/`.

**5. Jak WordPress zapisuje bloki w bazie?**
Treść wpisu/strony trafia do kolumny `post_content` w tabeli `wp_posts` jako **HTML z komentarzami
bloków** `<!-- wp:nazwa {atrybuty-JSON} --> ... <!-- /wp:nazwa -->`. Przy wyświetlaniu `do_blocks()`
parsuje te komentarze i renderuje bloki. (Widać to np. w `theme/patterns/hero-cta.php`.)

---

## L3 — CSS, theme.json i dostępność

**1. Jak sprawdzisz kontrast przycisku 4,5:1 i co, jeśli nie spełnia?**
Biorę kolor tła przycisku i tekstu, liczę współczynnik (WebAIM Contrast Checker / DevTools / wzór
WCAG). Mój przycisk: biały tekst na `#0b5d7a` = **7,34:1** ✅. Gdyby było < 4,5:1 — przyciemniłbym
kolor marki albo zmienił kolor tekstu, aż próg zostanie osiągnięty (patrz `docs/raport-dostepnosci.md`).

**2. WCAG 2.2 Focus Not Obscured i Target Size na przykładzie mojej strony?**
- *Focus Not Obscured (2.4.11)* — element z fokusem nie może chować się pod przyklejonym nagłówkiem.
  U mnie `html{scroll-padding-top:6rem}` odsuwa cel kotwicy spod nagłówka.
- *Target Size (2.5.8)* — cele dotykowe ≥ 24×24 px. U mnie pozycje menu i linki mają
  `min-height/min-width:24px` + padding, a przyciski ≥ 44 px.

**3. Kiedy obraz powinien mieć pusty `alt`?**
Gdy jest **czysto dekoracyjny** i nie niesie treści (tło, ozdobnik) — wtedy `alt=""`, żeby czytnik
ekranu go pominął. Obraz niosący informację (np. zdjęcie realizacji „przed/po") musi mieć opisowy `alt`.

**4. Czy firma podlega Polskiemu Aktowi o Dostępności?**
Polski Akt o Dostępności (wdrożenie EAA, obowiązuje od 28.06.2025) obejmuje m.in. **e-commerce**.
Auto Lśnienie to **usługowa mikrofirma bez sklepu internetowego** — mikroprzedsiębiorcy usługowi są
z zakresu wyłączeni, więc formalnie **nie podlega** obowiązkowi. Mimo to stronę robię zgodnie z
WCAG 2.2 — dostępność to dobra praktyka i przewaga, a przy rozwoju w e-commerce będzie już gotowa.

**5. Na jakiej licencji są zdjęcia i fonty i do czego Cię zobowiązują?**
- **Fonty:** systemowe (brak pobierania z zewnątrz) — nie zobowiązują do niczego i chronią prywatność
  (brak wysyłki IP do Google). Alternatywnie font OFL hostowany lokalnie.
- **Zdjęcia:** w wersji oddawanej brak; po dodaniu — własne (prawa moje) lub z Openverse (CC, często
  wymaga atrybucji) / Unsplash (bez obowiązku atrybucji). Każde dopiszę do `docs/licencje.md`.
- **Oprogramowanie** (WordPress, motyw, Contact Form 7): GPLv2+ — zobowiązuje do zachowania GPL
  przy ewentualnej redystrybucji.
