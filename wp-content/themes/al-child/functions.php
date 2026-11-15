<?php
/**
 * AL Child – funkcje motywu potomnego.
 * Wszystkie funkcje mają prefiks al_, żeby nie kolidowały z rodzicem i wtyczkami.
 */

// 1. Arkusz stylów potomka ładowany PO stylu rodzica (zależność 'twenty-twenty-one-style').
add_action( 'wp_enqueue_scripts', 'al_child_enqueue_styles' );
function al_child_enqueue_styles() {
	wp_enqueue_style(
		'al-child-style',
		get_stylesheet_uri(),
		array( 'twenty-twenty-one-style' ),
		wp_get_theme()->get( 'Version' )
	);
}

// 2. Tłumaczenia motywu + zajawki dla stron (potrzebne w page-o-nas.php).
add_action( 'after_setup_theme', 'al_child_setup' );
function al_child_setup() {
	load_child_theme_textdomain( 'al-child', get_stylesheet_directory() . '/languages' );
	add_post_type_support( 'page', 'excerpt' );
}

// 3. Filtr: krótsze zajawki (liczba słów).
add_filter( 'excerpt_length', 'al_excerpt_length', 20 );
function al_excerpt_length( $length ) {
	return 25;
}

// 4. Filtr: notka pod treścią pojedynczego wpisu.
add_filter( 'the_content', 'al_add_note' );
function al_add_note( $content ) {
	if ( is_single() && in_the_loop() && is_main_query() ) {
		$note     = esc_html__( 'Masz pytania o detailing? Napisz do nas przez formularz kontaktowy.', 'al-child' );
		$content .= '<p class="al-note">' . $note . '</p>';
	}
	return $content; // filtr ZAWSZE zwraca wartość
}

// 5. Własne style bloków (widoczne w edytorze w panelu „Style").
add_action( 'init', 'al_register_block_styles' );
function al_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'         => 'al-cien',
			'label'        => __( 'Z cieniem', 'al-child' ),
			'inline_style' => '.wp-block-button.is-style-al-cien .wp-block-button__link {'
				. ' box-shadow: 0 4px 0 var(--al-accent, #0b5d7a); }',
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'         => 'al-karta',
			'label'        => __( 'Karta', 'al-child' ),
			'inline_style' => '.wp-block-group.is-style-al-karta {'
				. ' padding: 1.5rem; border: 2px solid var(--al-accent, #0b5d7a);'
				. ' border-radius: 12px; background: #ffffff; color: #14181c; }',
		)
	);
}

// 6. Blok dynamiczny „Informacja o firmie" (blocks/info-firmy/block.json).
add_action( 'init', 'al_register_blocks' );
function al_register_blocks() {
	register_block_type( get_stylesheet_directory() . '/blocks/info-firmy' );
}

// 7. Telefon firmy jako opcja motywu (Wygląd → Dostosuj → Dane firmy).
add_action( 'customize_register', 'al_customize_register' );
function al_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'al_dane_firmy',
		array( 'title' => __( 'Dane firmy', 'al-child' ) )
	);
	$wp_customize->add_setting(
		'al_telefon',
		array(
			'default'           => '+48 600 000 000',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'al_telefon',
		array(
			'label'   => __( 'Telefon', 'al-child' ),
			'section' => 'al_dane_firmy',
			'type'    => 'text',
		)
	);
}
