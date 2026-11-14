# Auto Lśnienie — projekt MP1 (CMS, laboratoria L1–L3)

Strona studia detailingu samochodowego **Auto Lśnienie** (Konin) zbudowana w WordPressie
jako mini-projekt **MP1** na przedmiot *Systemy zarządzania treścią (CMS)* —
Collegium Da Vinci, rok akademicki 2026/2027, prowadzący mgr inż. Łukasz Grala.

**Autor:** Patryk Matuszak · **GitHub:** DevPatryk

---

## Jak uruchomić / obejrzeć

Masz dwie równoważne drogi (regulamin dopuszcza ZIP z Playground **albo** repozytorium):

1. **Gotowy ZIP** — `auto-lsnienie-playground.zip` wczytaj w [WordPress Playground](https://playground.wordpress.net/)
   → menu *Import* → wskaż plik ZIP. Odtworzy całą stronę 1:1.
2. **Z blueprintu (kod)** — otwórz Playground z plikiem `blueprint.json`. Blueprint stawia
   stronę od zera: motyw, wtyczkę, wszystkie strony, wpisy, menu, nagłówek/stopkę, wzorzec,
   paletę i CSS. Szczegóły w `setup/README-setup.md`.

Dane logowania w Playground: użytkownik **admin** (bez hasła, środowisko lokalne).

---

## 1. Karta MP1 — firma, persony, mapa strony (L1)

**Nazwa:** Auto Lśnienie · **Branża:** detailing samochodowy (usługi) · **Miasto:** Konin
**Oferta:** mycie detailingowe, korekta i zabezpieczenie lakieru, powłoki ceramiczne,
czyszczenie i pielęgnacja wnętrza, przygotowanie auta do sprzedaży. **Hasło:** „Twoje auto jak nowe".

### Persony

**Persona A — Marek, 34 lata (klient prywatny)**
- Cel: żeby jego BMW wyglądało jak nowe przed sezonem i przed sprzedażą.
- Problem: nie wie, gdzie w Koninie zrobią to porządnie i ile to kosztuje.
- Urządzenie: telefon (szuka wieczorem). CTA: zobaczyć cennik i realizacje, **umówić wizytę przez formularz**.

**Persona B — firma „Auto Bracia" (handlarz aut)**
- Cel: szybko i regularnie przygotować kilka aut do zdjęć i sprzedaży.
- Problem: potrzebuje stałego partnera i wyceny współpracy bez dzwonienia za każdym razem.
- Urządzenie: laptop (biuro). CTA: **wysłać zapytanie o stałą współpracę przez formularz**.

### Mapa strony (sitemap)

- **Strona główna**
- **Usługi** → Detailing lakieru · Czyszczenie wnętrza · Powłoki ceramiczne
- **Cennik**
- **Realizacje**
- **O nas**
- **Aktualności** (wpisy: kategorie *Porady detailingowe*, *Promocje*)
- **Kontakt** (formularz Contact Form 7)
- **Polityka prywatności**

Maksymalnie 2 poziomy zagnieżdżenia. „Kontakt" i „Polityka prywatności" wynikają wprost z CTA person oraz z obowiązku RODO.

### Dlaczego WordPress, a nie Wix / Shopify / Drupal

1. **Dane rynkowe:** WordPress ma największy udział w rynku CMS, więc jest najlepiej udokumentowany,
   ma ogromną bazę darmowych motywów i wtyczek (formularz, galeria) i łatwo o specjalistę do utrzymania.
2. **Koszt i kontrola:** jako self-hosted nie płacę abonamentu jak w Wix/Shopify i nie jestem
   uzależniony od jednego dostawcy (brak lock-in), a dane trzymam u siebie.
3. **Dopasowanie do skali:** mała firma usługowa nie potrzebuje sklepu (Shopify) ani ciężkiego
   Drupala — WordPress + wtyczka formularza w pełni pokrywa potrzeby (prezentacja usług + zapytania).

**Wtyczka:** Contact Form 7 — obie persony kończą ścieżkę na akcji „wyślij zapytanie / umów wizytę".

---

## 2. Struktura i edytor blokowy (L2)

- Wszystkie strony z mapy strony + **2 kategorie** i **3 wpisy** w „Aktualnościach"; permalinki na nazwę wpisu.
- **Strona główna** zbudowana z bloków (Okładka/Grupa, Kolumny, Przyciski, Cytat): jeden **H1** (hero),
  logiczna kolejność nagłówków (H1 → H2 → H3), jedno wyraźne **CTA**. Ustawiona jako statyczna strona
  główna, wpisy na stronie „Aktualności" (*Ustawienia → Czytanie*).
- **Części szablonu** (Full Site Editing): własny **Nagłówek** (nazwa + blok Nawigacja wg mapy strony)
  i **Stopka** (dane kontaktowe + link do polityki prywatności).
- **Własny wzorzec bloków** `auto-lsnienie/hero-cta` (niezsynchronizowany) — hero zapisany jako wzorzec
  i wstawiony na stronie „Usługi". Kod: `theme/patterns/hero-cta.php` (realizacja zadania rozszerzonego B1).

## 3. CSS, theme.json i dostępność WCAG 2.2 (L3)

- **Paleta i typografia** zdefiniowane w `theme/theme.json` (presety kolorów i rozmiarów czcionek,
  płynna typografia `clamp`). Kontrast każdej pary tekst/tło ≥ 4,5:1 — patrz `docs/raport-dostepnosci.md`.
- **Dostępnościowy CSS** (widoczny fokus, cele dotykowe ≥ 24 px, RWD mobile-first, `prefers-reduced-motion`,
  `forced-colors`) w `theme/theme.json` → `styles.css`.
- **Audyt WCAG 2.2** z listą kryteriów i poprawek: `docs/raport-dostepnosci.md`.
- **Elementy prawne:** strona *Polityka prywatności* (podpięta w *Ustawienia → Prywatność* i w stopce),
  lista źródeł i licencji materiałów: `docs/licencje.md`.

---

## Struktura repozytorium

```
cms-lab/
├─ README.md                      ← ten plik (karta MP1 + opis)
├─ blueprint.json                 ← master blueprint: stawia całą stronę w Playground
├─ auto-lsnienie-playground.zip   ← eksport działającej strony (deliverable)
├─ theme/                         ← motyw potomny „Auto Lśnienie" (Twenty Twenty-Five)
│  ├─ style.css · functions.php
│  ├─ theme.json                  ← paleta, typografia, style, CSS dostępności (L3)
│  └─ patterns/hero-cta.php       ← własny wzorzec bloków (L2/L3)
├─ setup/
│  ├─ build-site.php              ← builder treści (uruchamiany przez blueprint)
│  └─ build_blueprint.py          ← składa blueprint.json z plików motywu + buildera
└─ docs/
   ├─ raport-dostepnosci.md       ← audyt WCAG 2.2 (kontrast, kryteria, poprawki)
   ├─ licencje.md                 ← źródła i licencje materiałów
   ├─ obrona-pytania.md           ← odpowiedzi na pytania kontrolne L1–L3
   ├─ wireframe-strona-glowna.svg ← wireframe strony głównej
   └─ screeny/                    ← zrzuty ekranu (desktop; telefon do dołożenia)
```

---

## Użycie AI

- **Narzędzie:** asystent AI (Claude, w Claude Code) + lokalne modele pomocniczo.
- **Do czego użyte:**
  - szkic karty MP1 (opis firmy, persony, mapa strony) i treści na strony;
  - wygenerowanie motywu potomnego (`theme.json`, wzorzec `hero-cta.php`) oraz skryptu
    budującego stronę (`build-site.php`) i blueprintu Playground;
  - projekt palety i reguł dostępności (CSS WCAG 2.2) oraz draft raportu dostępności.
- **Co zrobiłem / sprawdziłem samodzielnie:**
  - dobór firmy, person i mapy strony do realiów Konina i do moich person;
  - **uruchomienie i weryfikacja środowiska na żywo** (polski panel, motyw, wtyczka, wszystkie
    strony, menu, nagłówek/stopka, wzorzec, formularz) oraz **eksport ZIP**;
  - kontrola hierarchii nagłówków (jeden H1 na stronie), kontrastu i działania formularza;
  - decyzje projektowe (np. systemowy stos fontów zamiast Google Fonts — lepsze dla RODO).
- Treści tekstowe i kod powstały z pomocą AI; **umiem wyjaśnić każdy element** — patrz `docs/obrona-pytania.md`.

---

## Pytania kontrolne (obrona)

Pełne odpowiedzi na pytania z L1, L2 i L3: **`docs/obrona-pytania.md`**.
