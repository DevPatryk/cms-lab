# Źródła i licencje materiałów — Auto Lśnienie

Zestawienie każdego elementu użytego na stronie: autor, licencja, link oraz zobowiązania.
(Laboratorium L3, zadanie G4 — zgodność z prawem i RODO.)

| Element | Rodzaj | Autor / źródło | Licencja | Zobowiązuje do |
|---|---|---|---|---|
| **WordPress** (rdzeń) | oprogramowanie | WordPress.org | GPLv2+ | zachowania licencji GPL przy redystrybucji |
| **Twenty Twenty-Five** | motyw (rodzic) | WordPress.org | GPLv2+ | jw. |
| **Auto Lśnienie** | motyw potomny (własny) | Patryk Matuszak | GPLv2+ | — (praca własna) |
| **Contact Form 7** | wtyczka | Takayuki Miyoshi | GPLv2+ | zachowania GPL |
| **Teksty na stronie** | treść | Patryk Matuszak + asystent AI | praca własna | oznaczenia treści AI (patrz README → Użycie AI) |
| **Fonty** | typografia | **systemowy stos fontów** (system-ui) | — (fonty systemu operacyjnego) | nic — brak pobierania z zewnątrz |
| **Kolory / paleta** | design | własna (`theme.json`) | — | — |

## Fonty — decyzja i uzasadnienie (RODO)

Strona **nie używa Google Fonts ani innych zewnętrznych fontów**. Zastosowano systemowy stos
czcionek (`system-ui`), który renderuje się fontem systemu użytkownika. Dzięki temu:

- **zero zapytań do serwerów stron trzecich** — nie wysyłamy adresu IP użytkownika do Google
  (znany problem RODO z Google Fonts, por. wyroki sądów w UE);
- szybsze ładowanie i brak „migania" tekstu (FOUT).

To mocniejsza ochrona prywatności niż nawet lokalnie hostowany font. Gdyby prowadzący wymagał
konkretnego kroju, należy dodać font **na licencji OFL** (np. Inter, Source Sans) przez
*Wygląd → Edytor → Style → Biblioteka fontów* (hostowanie lokalne) i dopisać go do tej tabeli.

## Zdjęcia

Wersja oddawana **nie zawiera zdjęć fotograficznych** — sekcja „Realizacje" używa dekoracyjnych
bloków kolorowych (placeholderów „przed/po"). Po dodaniu realnych zdjęć realizacji każde należy:

- pozyskać z **własnych zdjęć** (prawa autora = właściciel) **lub** z banków na wolnych licencjach:
  [Openverse](https://openverse.org/) (licencje CC — wymagają atrybucji wg typu CC),
  [Unsplash](https://unsplash.com/) (Unsplash License — bez atrybucji, ale warto podać autora);
- opatrzyć tekstem `alt` opisującym efekt;
- dopisać do tej tabeli: autor, licencja, link.

## Pliki cookies

Strona używa wyłącznie **niezbędnych** cookies (sesja logowania WordPress). Brak cookies
marketingowych i śledzących, brak zewnętrznych skryptów analitycznych — baner zgody na cookies
nie jest wymagany dla tego zakresu. Informacja w *Polityce prywatności*.
