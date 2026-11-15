<?php
/**
 * Title: Opinie klientów
 * Slug: auto-lsnienie-blok/opinie
 * Categories: testimonials
 * Description: Dwie opinie klientów w blokach cytatu. Opinie są przykładowe (fikcyjna firma).
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<section class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Opinie klientów', 'auto-lsnienie-blok' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:quote -->
			<blockquote class="wp-block-quote">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Auto po korekcie lakieru wygląda lepiej niż w salonie. Polecam!', 'auto-lsnienie-blok' ); ?></p>
				<!-- /wp:paragraph -->
				<cite><?php esc_html_e( 'Marek, Konin', 'auto-lsnienie-blok' ); ?></cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:quote -->
			<blockquote class="wp-block-quote">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Szybko przygotowują nasze auta do zdjęć i sprzedaży. Stała współpraca bez problemów.', 'auto-lsnienie-blok' ); ?></p>
				<!-- /wp:paragraph -->
				<cite><?php esc_html_e( 'Auto Bracia, komis', 'auto-lsnienie-blok' ); ?></cite>
			</blockquote>
			<!-- /wp:quote -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:paragraph {"fontSize":"small"} -->
	<p class="has-small-font-size"><?php esc_html_e( 'Opinie przykładowe – projekt studencki, firma fikcyjna.', 'auto-lsnienie-blok' ); ?></p>
	<!-- /wp:paragraph -->
</section>
<!-- /wp:group -->
