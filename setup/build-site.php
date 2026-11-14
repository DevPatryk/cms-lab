<?php
/**
 * build-site.php — jednorazowy builder treści dla witryny „Auto Lśnienie".
 *
 * Uruchamiany przez krok runPHP w blueprint.json WordPress Playground.
 * Tworzy całą strukturę MP1 z laboratoriów L1–L3:
 *   - strony z mapy strony (+ podstrony Usług),
 *   - 2 kategorie i 3 wpisy w „Aktualnościach",
 *   - statyczną stronę główną i stronę wpisów (Ustawienia > Czytanie),
 *   - menu nawigacyjne + nadpisane części szablonu Nagłówek i Stopka,
 *   - dostępny formularz Contact Form 7,
 *   - stronę polityki prywatności (Ustawienia > Prywatność).
 *
 * Idempotentny: po pierwszym uruchomieniu ustawia flagę i nie powtarza pracy.
 */

if ( get_option( 'auto_lsnienie_built' ) ) {
	echo "already-built\n";
	return;
}

$active_theme = get_stylesheet(); // 'auto-lsnienie'

/* ------------------------------------------------------------------ *
 * 1. Kategorie
 * ------------------------------------------------------------------ */
$cat_porady = wp_insert_term( 'Porady detailingowe', 'category', array( 'slug' => 'porady' ) );
$cat_promo  = wp_insert_term( 'Promocje', 'category', array( 'slug' => 'promocje' ) );
$id_porady  = is_wp_error( $cat_porady ) ? (int) $cat_porady->error_data['term_exists'] : (int) $cat_porady['term_id'];
$id_promo   = is_wp_error( $cat_promo )  ? (int) $cat_promo->error_data['term_exists']  : (int) $cat_promo['term_id'];

/* ------------------------------------------------------------------ *
 * 2. Dostępny formularz Contact Form 7 (L3 B1)
 * ------------------------------------------------------------------ */
$cf7_id = 0;
if ( class_exists( 'WPCF7_ContactForm' ) ) {
	$form_body = <<<'CF7'
<label>Imię i nazwisko (wymagane)
	[text* your-name autocomplete:name] </label>

<label>E-mail (wymagany)
	[email* your-email autocomplete:email] </label>

<label>Telefon
	[tel your-phone autocomplete:tel] </label>

<label>Wiadomość (wymagana)
	[textarea* your-message] </label>

[acceptance zgoda-rodo] Zapoznałem/-am się z <a href="/polityka-prywatnosci/">polityką prywatności</a>. [/acceptance]

[submit "Wyślij wiadomość"]
CF7;

	$cf7 = WPCF7_ContactForm::get_template( array( 'title' => 'Formularz kontaktowy — Auto Lśnienie' ) );
	$props = $cf7->get_properties();
	$props['form'] = $form_body;
	$props['mail']['subject'] = 'Zapytanie ze strony Auto Lśnienie';
	$props['mail']['recipient'] = 'kontakt@autolsnienie.pl';
	$cf7->set_properties( $props );
	$cf7_id = $cf7->save();
}
$cf7_shortcode = $cf7_id
	? '[contact-form-7 id="' . $cf7_id . '" title="Formularz kontaktowy — Auto Lśnienie"]'
	: '<p><em>Formularz kontaktowy pojawi się po aktywacji wtyczki Contact Form 7.</em></p>';

/* ------------------------------------------------------------------ *
 * 3. Treść stron (bloki Gutenberga)
 * ------------------------------------------------------------------ */

// --- Strona główna: hero (H1) + usługi (H2/H3) + opinie (Cytat) + CTA ---
$home_content = <<<'HTML'
<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"primary","textColor":"base","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-base-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:heading {"level":1,"textColor":"base"} -->
<h1 class="wp-block-heading has-base-color has-text-color">Twoje auto jak nowe — detailing w Koninie</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"base","fontSize":"large"} -->
<p class="has-base-color has-text-color has-large-font-size">Mycie detailingowe, korekta lakieru i powłoki ceramiczne. Umów wizytę online i odbierz auto, które naprawdę lśni.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","textColor":"base"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-accent-background-color has-text-color has-background wp-element-button" href="/kontakt/">Umów wizytę</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Co dla Ciebie zrobimy</h2>
<!-- /wp:heading -->
<!-- wp:columns {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--50)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Detailing lakieru</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Korekta, polerowanie i trwałe zabezpieczenie lakieru. Usuwamy rysy i matowienie, przywracamy głęboki połysk.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Czyszczenie wnętrza</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Pranie tapicerki, czyszczenie plastików i skóry oraz dezynfekcja. Wnętrze pachnące świeżością.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Powłoki ceramiczne</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Ochrona ceramiczna na lata — łatwiejsze mycie, odporność na zabrudzenia i efekt hydrofobowy.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Opinie klientów</h2>
<!-- /wp:heading -->
<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>Auto wyglądało jak prosto z salonu. Lakier lśni, wnętrze jak nowe — polecam każdemu, kto ceni dokładność.</p>
<!-- /wp:paragraph --><cite>Marek, właściciel BMW z Konina</cite></blockquote>
<!-- /wp:quote -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
<!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Gotowy, żeby Twoje auto zalśniło?</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/kontakt/">Umów wizytę</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</section>
<!-- /wp:group -->
HTML;

// --- Usługi: wstawiony wzorzec hero (niezsynchronizowany) + wstęp (L2 G3) ---
$uslugi_content = <<<'HTML'
<!-- wp:pattern {"slug":"auto-lsnienie/hero-cta"} /-->

<!-- wp:paragraph -->
<p>Kompleksowy detailing samochodowy w Koninie. Wybierz usługę, a my dopasujemy zakres prac do stanu Twojego auta.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item --><li><a href="/detailing-lakieru/">Detailing lakieru</a></li><!-- /wp:list-item -->
<!-- wp:list-item --><li><a href="/czyszczenie-wnetrza/">Czyszczenie wnętrza</a></li><!-- /wp:list-item -->
<!-- wp:list-item --><li><a href="/powloki-ceramiczne/">Powłoki ceramiczne</a></li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
HTML;

$lakier_content = '<!-- wp:paragraph --><p>Wielostopniowa korekta lakieru: dekontaminacja, usuwanie rys, polerowanie i zabezpieczenie. Efekt: głęboki, równy połysk i gładka powierzchnia.</p><!-- /wp:paragraph -->';
$wnetrze_content = '<!-- wp:paragraph --><p>Pranie tapicerki i dywaników, czyszczenie plastików, skóry i podsufitki oraz dezynfekcja ozonem. Wnętrze czyste i świeże.</p><!-- /wp:paragraph -->';
$ceramika_content = '<!-- wp:paragraph --><p>Nałożenie powłoki ceramicznej dającej ochronę nawet na kilka lat: efekt hydrofobowy, łatwiejsze mycie i odporność na promienie UV.</p><!-- /wp:paragraph -->';

$cennik_content = <<<'HTML'
<!-- wp:paragraph -->
<p>Ceny startowe najpopularniejszych usług. Każde auto wyceniamy indywidualnie po oględzinach.</p>
<!-- /wp:paragraph -->
<!-- wp:table -->
<figure class="wp-block-table"><table><thead><tr><th>Usługa</th><th>Cena od</th></tr></thead><tbody><tr><td>Mycie detailingowe</td><td>120 zł</td></tr><tr><td>Czyszczenie wnętrza</td><td>250 zł</td></tr><tr><td>Korekta lakieru</td><td>600 zł</td></tr><tr><td>Powłoka ceramiczna</td><td>1200 zł</td></tr></tbody></table><figcaption class="wp-element-caption">Ceny orientacyjne. Dokładną wycenę ustalamy po obejrzeniu auta.</figcaption></figure>
<!-- /wp:table -->
HTML;

$realizacje_content = <<<'HTML'
<!-- wp:paragraph -->
<p>Wybrane metamorfozy aut z naszego studia — efekt „przed i po". Galeria zdjęć zostanie uzupełniona realnymi realizacjami.</p>
<!-- /wp:paragraph -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"customOverlayColor":"#0b5d7a","minHeight":200} -->
<div class="wp-block-cover" style="min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#0b5d7a"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","textColor":"base"} -->
<p class="has-text-align-center has-base-color has-text-color">Przed</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"customOverlayColor":"#a3320b","minHeight":200} -->
<div class="wp-block-cover" style="min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim" style="background-color:#a3320b"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","textColor":"base"} -->
<p class="has-text-align-center has-base-color has-text-color">Po</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

$onas_content = <<<'HTML'
<!-- wp:paragraph -->
<p>Auto Lśnienie to studio detailingu z Konina. Dbamy o auta prywatnych właścicieli i firm z flotą — dokładnie, terminowo i z pasją do czystego lakieru.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Pracujemy na sprawdzonych produktach i stawiamy na uczciwą wycenę. Każde auto traktujemy jak własne.</p>
<!-- /wp:paragraph -->
HTML;

$polityka_content = <<<'HTML'
<!-- wp:paragraph -->
<p>Administratorem danych osobowych jest Auto Lśnienie (dane fikcyjne na potrzeby projektu studenckiego), ul. Przykładowa 1, 62-500 Konin.</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Jakie dane zbieramy</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Dane podane w formularzu kontaktowym (imię i nazwisko, e-mail, telefon, treść wiadomości) przetwarzamy wyłącznie w celu odpowiedzi na zapytanie, na podstawie zgody (art. 6 ust. 1 lit. a RODO).</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Pliki cookies</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Strona używa wyłącznie niezbędnych plików cookies. Nie stosujemy śledzenia marketingowego ani zewnętrznych fontów hostowanych poza serwerem.</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Twoje prawa</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Masz prawo dostępu do danych, ich sprostowania, usunięcia oraz wycofania zgody w dowolnym momencie, pisząc na adres kontakt@autolsnienie.pl.</p>
<!-- /wp:paragraph -->
HTML;

$kontakt_content = '<!-- wp:paragraph --><p>Napisz do nas przez formularz — odpowiadamy w ciągu 24 godzin. Możesz też zadzwonić: <a href="tel:+48600000000">600 000 000</a>.</p><!-- /wp:paragraph -->'
	. '<!-- wp:shortcode -->' . "\n" . $cf7_shortcode . "\n" . '<!-- /wp:shortcode -->';

/* ------------------------------------------------------------------ *
 * 4. Wstawianie stron (z zachowaniem ID i hierarchii)
 * ------------------------------------------------------------------ */
$ids = array();
$make_page = function ( $title, $slug, $content, $parent = 0, $order = 0 ) {
	return wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_parent'  => $parent,
		'menu_order'   => $order,
	) );
};

$ids['home']       = $make_page( 'Strona główna', 'strona-glowna', $home_content, 0, 0 );
$ids['uslugi']     = $make_page( 'Usługi', 'uslugi', $uslugi_content, 0, 1 );
$ids['lakier']     = $make_page( 'Detailing lakieru', 'detailing-lakieru', $lakier_content, $ids['uslugi'], 0 );
$ids['wnetrze']    = $make_page( 'Czyszczenie wnętrza', 'czyszczenie-wnetrza', $wnetrze_content, $ids['uslugi'], 1 );
$ids['ceramika']   = $make_page( 'Powłoki ceramiczne', 'powloki-ceramiczne', $ceramika_content, $ids['uslugi'], 2 );
$ids['cennik']     = $make_page( 'Cennik', 'cennik', $cennik_content, 0, 2 );
$ids['realizacje'] = $make_page( 'Realizacje', 'realizacje', $realizacje_content, 0, 3 );
$ids['onas']       = $make_page( 'O nas', 'o-nas', $onas_content, 0, 4 );
$ids['aktualnosci']= $make_page( 'Aktualności', 'aktualnosci', '', 0, 5 );
$ids['kontakt']    = $make_page( 'Kontakt', 'kontakt', $kontakt_content, 0, 6 );
$ids['polityka']   = $make_page( 'Polityka prywatności', 'polityka-prywatnosci', $polityka_content, 0, 7 );

/* ------------------------------------------------------------------ *
 * 5. Wpisy w „Aktualnościach" (3 wpisy, 2 kategorie)
 * ------------------------------------------------------------------ */
$make_post = function ( $title, $slug, $content, $cats ) {
	return wp_insert_post( array(
		'post_type'     => 'post',
		'post_status'   => 'publish',
		'post_title'    => $title,
		'post_name'     => $slug,
		'post_content'  => $content,
		'post_category' => $cats,
	) );
};
$make_post( 'Jak przygotować auto do zimy', 'auto-do-zimy',
	'<!-- wp:paragraph --><p>Zimą lakier i wnętrze są narażone na sól oraz wilgoć. Podpowiadamy, jak zabezpieczyć auto przed sezonem.</p><!-- /wp:paragraph -->',
	array( $id_porady ) );
$make_post( 'Powłoka ceramiczna — czy warto?', 'powloka-ceramiczna-czy-warto',
	'<!-- wp:paragraph --><p>Powłoka ceramiczna ułatwia mycie i chroni lakier na lata. Wyjaśniamy, kiedy inwestycja się opłaca.</p><!-- /wp:paragraph -->',
	array( $id_porady ) );
$make_post( 'Promocja jesienna: -15% na detailing wnętrza', 'promocja-jesienna',
	'<!-- wp:paragraph --><p>Przez cały październik czyszczenie wnętrza taniej o 15%. Umów wizytę przez formularz kontaktowy.</p><!-- /wp:paragraph -->',
	array( $id_promo ) );

/* ------------------------------------------------------------------ *
 * 6. Ustawienia czytania + prywatność + permalinki
 * ------------------------------------------------------------------ */
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $ids['home'] );
update_option( 'page_for_posts', $ids['aktualnosci'] );
update_option( 'wp_page_for_privacy_policy', $ids['polityka'] );
update_option( 'permalink_structure', '/%postname%/' );

/* ------------------------------------------------------------------ *
 * 7. Menu nawigacyjne (wp_navigation) zgodne z mapą strony
 * ------------------------------------------------------------------ */
$nav_markup =
	'<!-- wp:navigation-link {"label":"Strona główna","type":"page","id":' . $ids['home'] . ',"url":"/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"Usługi","type":"page","id":' . $ids['uslugi'] . ',"url":"/uslugi/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"Cennik","type":"page","id":' . $ids['cennik'] . ',"url":"/cennik/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"Realizacje","type":"page","id":' . $ids['realizacje'] . ',"url":"/realizacje/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"O nas","type":"page","id":' . $ids['onas'] . ',"url":"/o-nas/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"Aktualności","type":"page","id":' . $ids['aktualnosci'] . ',"url":"/aktualnosci/","kind":"post-type"} /-->' .
	'<!-- wp:navigation-link {"label":"Kontakt","type":"page","id":' . $ids['kontakt'] . ',"url":"/kontakt/","kind":"post-type"} /-->';

$nav_id = wp_insert_post( array(
	'post_type'    => 'wp_navigation',
	'post_status'  => 'publish',
	'post_title'   => 'Menu główne',
	'post_name'    => 'menu-glowne',
	'post_content' => $nav_markup,
) );

/* ------------------------------------------------------------------ *
 * 8. Części szablonu: Nagłówek i Stopka (FSE, L2 G3)
 * ------------------------------------------------------------------ */
$header_content =
	'<!-- wp:group {"tagName":"header","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->' .
	'<header class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--50)">' .
	'<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->' .
	'<div class="wp-block-group">' .
	'<!-- wp:site-title {"level":0,"fontSize":"large"} /-->' .
	'<!-- wp:navigation {"ref":' . $nav_id . ',"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right"}} /-->' .
	'</div><!-- /wp:group --></header><!-- /wp:group -->';

$footer_content =
	'<!-- wp:group {"tagName":"footer","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->' .
	'<footer class="wp-block-group alignfull has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">' .
	'<!-- wp:paragraph --><p><strong>Auto Lśnienie</strong> · ul. Przykładowa 1, 62-500 Konin · tel. <a href="tel:+48600000000">600 000 000</a> · <a href="mailto:kontakt@autolsnienie.pl">kontakt@autolsnienie.pl</a></p><!-- /wp:paragraph -->' .
	'<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size"><a href="/polityka-prywatnosci/">Polityka prywatności</a> · © 2026 Auto Lśnienie</p><!-- /wp:paragraph -->' .
	'</footer><!-- /wp:group -->';

$make_template_part = function ( $slug, $title, $area, $content, $theme ) {
	$tp_id = wp_insert_post( array(
		'post_type'    => 'wp_template_part',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
	) );
	if ( $tp_id && ! is_wp_error( $tp_id ) ) {
		wp_set_object_terms( $tp_id, $theme, 'wp_theme' );
		wp_set_object_terms( $tp_id, $area, 'wp_template_part_area' );
	}
	return $tp_id;
};
$make_template_part( 'header', 'Nagłówek', 'header', $header_content, $active_theme );
$make_template_part( 'footer', 'Stopka', 'footer', $footer_content, $active_theme );

// Szablon bez tytułu wpisu — jedyny H1 pochodzi z treści (hero). Używany na stronie
// głównej oraz na „Usługach", gdzie landingiem jest wstawiony wzorzec hero.
$no_title_template =
	'<!-- wp:template-part {"slug":"header"} /-->' .
	'<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} --><main class="wp-block-group"><!-- wp:post-content {"layout":{"type":"constrained"}} /--></main><!-- /wp:group -->' .
	'<!-- wp:template-part {"slug":"footer"} /-->';
$make_template = function ( $slug, $title, $content, $theme ) {
	$t_id = wp_insert_post( array(
		'post_type'    => 'wp_template',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
	) );
	if ( $t_id && ! is_wp_error( $t_id ) ) {
		wp_set_object_terms( $t_id, $theme, 'wp_theme' );
	}
	return $t_id;
};
$make_template( 'front-page', 'Strona główna', $no_title_template, $active_theme );
$make_template( 'page-uslugi', 'Usługi (landing)', $no_title_template, $active_theme );

/* ------------------------------------------------------------------ *
 * 9. Zakończenie
 * ------------------------------------------------------------------ */
if ( function_exists( 'flush_rewrite_rules' ) ) {
	flush_rewrite_rules( false );
}
update_option( 'auto_lsnienie_built', 1 );

echo "built ok | home={$ids['home']} aktualnosci={$ids['aktualnosci']} nav={$nav_id} cf7={$cf7_id}\n";
