<?php
/**
 * Home page. Hero from the Customizer, then the page's own block
 * content (books, Heaven on Earth, blog) so Sylvia can edit it.
 *
 * @package SylviasDreamChild
 */
defined( 'ABSPATH' ) || exit;
get_header();
$sdc_image = (int) get_theme_mod( 'sdc_hero_image' );
?>
<main id="main-content">
	<section class="sd-hero">
		<div class="container">
			<div>
				<p class="eyebrow"><?php echo wp_kses_post( sdc_get( 'hero_eyebrow' ) ); ?></p>
				<h1><?php echo esc_html( sdc_get( 'hero_title' ) ); ?></h1>
				<p><?php echo esc_html( sdc_get( 'hero_text' ) ); ?></p>
				<div class="actions">
					<?php if ( sdc_get( 'hero_cta_label' ) ) : ?>
						<a class="sd-btn" href="<?php echo esc_url( sdc_link( sdc_get( 'hero_cta_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'hero_cta_label' ) ); ?></a>
					<?php endif; ?>
					<?php if ( sdc_get( 'hero_cta2_label' ) ) : ?>
						<a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( sdc_link( sdc_get( 'hero_cta2_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'hero_cta2_label' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $sdc_image ) : ?>
				<div class="sd-hero__media"><?php echo wp_get_attachment_image( $sdc_image, 'sd-cover', false, array( 'fetchpriority' => 'high', 'loading' => 'eager' ) ); ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="entry-content container sd-section"><?php the_content(); ?></div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
