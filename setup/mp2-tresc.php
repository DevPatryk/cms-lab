<?php
/**
 * mp2-tresc.php – treść testowa do sprawdzenia motywów z MP2 (L4 i L5).
 * Uruchamiany przez setup/mp2-blueprint.json (krok runPHP).
 */
require_once '/wordpress/wp-load.php';

// Menu dla motywu klasycznego (Twenty Twenty-One / AL Child) – lokalizacja "primary".
// Motyw blokowy ma menu wpisane w parts/header.html, więc go nie potrzebuje.
function al_mp2_menu() {
	if ( wp_get_nav_menu_object( 'Menu główne' ) ) {
		return;
	}
	$menu_id = wp_create_nav_menu( 'Menu główne' );
	foreach ( array( 'uslugi', 'cennik', 'aktualnosci', 'o-nas', 'kontakt' ) as $slug ) {
		$strona = get_page_by_path( $slug );
		if ( $strona ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-object-id' => $strona->ID,
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}
	}
	set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id ) );
}

// Ustawienia motywu klasycznego: zajawki na listach + blok dynamiczny w stopce (widżet).
function al_mp2_klasyczny() {
	set_theme_mod( 'display_excerpt_or_full_post', 'excerpt' );
	update_option(
		'widget_block',
		array(
			2              => array( 'content' => '<!-- wp:al-child/info-firmy /-->' ),
			'_multiwidget' => 1,
		)
	);
	update_option(
		'sidebars_widgets',
		array(
			'wp_inactive_widgets' => array(),
			'sidebar-1'           => array( 'block-2' ),
			'array_version'       => 3,
		)
	);
}

if ( get_option( 'al_mp2_tresc' ) ) {
	if ( 'al-child' === get_stylesheet() ) {
		al_mp2_menu();
		al_mp2_klasyczny();
	}
	return;
}

update_option( 'blogname', 'Auto Lśnienie' );
update_option( 'blogdescription', 'Detailing samochodowy w Koninie' );
update_option( 'permalink_structure', '/%postname%/' );

function al_mp2_strona( $tytul, $slug, $tresc, $zajawka = '' ) {
	return wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $tytul,
			'post_name'    => $slug,
			'post_content' => $tresc,
			'post_excerpt' => $zajawka,
		)
	);
}

$glowna = al_mp2_strona(
	'Strona główna',
	'strona-glowna',
	'<!-- wp:paragraph --><p>Działamy w Koninie od 2019 roku. Zapraszamy od poniedziałku do soboty.</p><!-- /wp:paragraph -->'
);
al_mp2_strona( 'Usługi', 'uslugi', '<!-- wp:paragraph --><p>Detailing lakieru, czyszczenie wnętrza i powłoki ceramiczne.</p><!-- /wp:paragraph -->' );
al_mp2_strona( 'Cennik', 'cennik', '<!-- wp:paragraph --><p>Mycie detailingowe od 250 zł, korekta lakieru od 900 zł, powłoka ceramiczna od 1500 zł.</p><!-- /wp:paragraph -->' );
$aktualnosci = al_mp2_strona( 'Aktualności', 'aktualnosci', '' );
al_mp2_strona(
	'O nas',
	'o-nas',
	'<!-- wp:paragraph --><p>Jesteśmy małym studiem detailingu z Konina. Każde auto traktujemy jak własne.</p><!-- /wp:paragraph -->'
	. '<!-- wp:group {"className":"is-style-al-karta","layout":{"type":"constrained"}} --><div class="wp-block-group is-style-al-karta"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Dlaczego my?</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Używamy bezpiecznych kosmetyków i dajemy gwarancję na powłoki.</p><!-- /wp:paragraph --></div><!-- /wp:group -->'
	. '<!-- wp:al-child/info-firmy /-->'
	. '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-al-cien"} --><div class="wp-block-button is-style-al-cien"><a class="wp-block-button__link wp-element-button" href="/kontakt/">Umów wizytę</a></div><!-- /wp:button --></div><!-- /wp:buttons -->',
	'Pasjonaci detailingu z Konina – dbamy o lakier, wnętrze i Twój czas.'
);
al_mp2_strona( 'Kontakt', 'kontakt', '<!-- wp:paragraph --><p>Zadzwoń: +48 600 000 000 albo napisz: kontakt@example.com.</p><!-- /wp:paragraph -->' );
al_mp2_strona( 'Polityka prywatności', 'polityka-prywatnosci', '<!-- wp:paragraph --><p>Administratorem danych jest Auto Lśnienie (firma fikcyjna, projekt studencki).</p><!-- /wp:paragraph -->' );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $glowna );
update_option( 'page_for_posts', $aktualnosci );

$porady   = wp_insert_term( 'Porady detailingowe', 'category', array( 'slug' => 'porady' ) );
$promocje = wp_insert_term( 'Promocje', 'category', array( 'slug' => 'promocje' ) );

$wpisy = array(
	array( 'Jak myć auto zimą, żeby nie porysować lakieru', $porady['term_id'], 'Zimą sól i piasek działają jak papier ścierny. Najpierw spłucz auto wodą pod ciśnieniem, potem użyj piany aktywnej, a dopiero na końcu rękawicy i metody dwóch wiader. Nie myj auta na mrozie poniżej minus pięciu stopni i zawsze dokładnie osusz uszczelki drzwi.' ),
	array( 'Powłoka ceramiczna czy wosk – co wybrać?', $porady['term_id'], 'Wosk daje piękny połysk, ale wytrzymuje kilka tygodni. Powłoka ceramiczna chroni lakier nawet kilka lat, ułatwia mycie i lepiej znosi chemię drogową. Jeśli auto stoi pod chmurką i jeździsz codziennie, ceramika zwykle wychodzi taniej w dłuższym czasie.' ),
	array( 'Jesienna promocja: czyszczenie wnętrza -20%', $promocje['term_id'], 'Do końca listopada pranie tapicerki i czyszczenie wnętrza tańsze o dwadzieścia procent. Wystarczy umówić wizytę przez formularz kontaktowy i podać hasło JESIEŃ. Promocja nie łączy się z innymi rabatami.' ),
);
foreach ( $wpisy as $w ) {
	wp_insert_post(
		array(
			'post_status'   => 'publish',
			'post_title'    => $w[0],
			'post_content'  => '<!-- wp:paragraph --><p>' . $w[2] . '</p><!-- /wp:paragraph -->',
			'post_category' => array( $w[1] ),
		)
	);
}

al_mp2_menu();
flush_rewrite_rules();
update_option( 'al_mp2_tresc', 1 );
