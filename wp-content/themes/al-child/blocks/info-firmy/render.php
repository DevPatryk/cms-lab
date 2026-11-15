<?php
/**
 * render.php – blok al-child/info-firmy
 * Dostępne zmienne: $attributes, $content, $block
 */
$al_firma   = get_bloginfo( 'name' );
$al_telefon = get_theme_mod( 'al_telefon', '+48 600 000 000' );
$al_tel_url = 'tel:' . preg_replace( '/\s+/', '', $al_telefon );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<p><strong><?php echo esc_html( $al_firma ); ?></strong></p>
	<?php if ( ! empty( $attributes['pokazTelefon'] ) ) : ?>
		<p>
			<?php esc_html_e( 'Telefon:', 'al-child' ); ?>
			<a href="<?php echo esc_url( $al_tel_url ); ?>"><?php echo esc_html( $al_telefon ); ?></a>
		</p>
	<?php endif; ?>
</div>
