<?php
/**
 * Title: Hero z wezwaniem do działania
 * Slug: auto-lsnienie/hero-cta
 * Categories: featured, banner
 * Description: Sekcja otwierająca: nagłówek H1, obietnica korzyści dla persony i jedno wyraźne CTA.
 */
?>
<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"primary","textColor":"base","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-base-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"level":1,"textColor":"base"} -->
	<h1 class="wp-block-heading has-base-color has-text-color"><?php esc_html_e( 'Twoje auto jak nowe — detailing w Koninie', 'auto-lsnienie' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"base","fontSize":"large"} -->
	<p class="has-base-color has-text-color has-large-font-size"><?php esc_html_e( 'Mycie detailingowe, korekta lakieru i powłoki ceramiczne. Umów wizytę online i odbierz auto, które naprawdę lśni.', 'auto-lsnienie' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent","textColor":"base"} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-accent-background-color has-text-color has-background wp-element-button" href="/kontakt/"><?php esc_html_e( 'Umów wizytę', 'auto-lsnienie' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</section>
<!-- /wp:group -->
