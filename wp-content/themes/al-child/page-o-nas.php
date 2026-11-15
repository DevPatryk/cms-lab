<?php
/**
 * page-o-nas.php – szablon tylko dla strony o slugu "o-nas".
 * Hierarchia: page-o-nas.php (potomek) -> page.php (rodzic) -> index.php
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'al-o-nas' ); ?>>
		<header class="entry-header alignwide">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="al-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>

			<section class="al-dane-firmy">
				<h2><?php esc_html_e( 'Dane firmy', 'al-child' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Adres: ul. Przykładowa 12, 62-500 Konin', 'al-child' ); ?></li>
					<li><?php esc_html_e( 'Godziny: Pon–Pt 8:00–18:00, Sob 9:00–14:00', 'al-child' ); ?></li>
					<li>
						<?php esc_html_e( 'Telefon:', 'al-child' ); ?>
						<?php echo esc_html( get_theme_mod( 'al_telefon', '+48 600 000 000' ) ); ?>
					</li>
				</ul>
			</section>
		</div>
	</article>
	<?php
endwhile;

get_footer();
