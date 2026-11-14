<?php
/**
 * Auto Lśnienie — motyw potomny Twenty Twenty-Five.
 *
 * Motyw blokowy ładuje theme.json automatycznie, więc nie kolejkujemy tu
 * arkuszy stylów. Rejestrujemy tylko domenę tłumaczeń dla tekstów we wzorcach.
 */

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'auto-lsnienie', get_stylesheet_directory() . '/languages' );
} );
