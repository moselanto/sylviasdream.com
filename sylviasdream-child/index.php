<?php
/**
 * Blog (devotionals) index and general fallback.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sdc_posts_page = (int) get_option( 'page_for_posts' );
$sdc_title      = ( is_home() && $sdc_posts_page ) ? get_the_title( $sdc_posts_page ) : sdc_get( 'blog_title' );
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( $sdc_title ? $sdc_title : get_bloginfo( 'name' ), sdc_get( 'blog_eyebrow' ), sdc_get( 'blog_intro' ) ); ?>
	<div class="sd-wrap sd-section">
		<?php if ( have_posts() ) : ?>
			<div class="sd-cards">
				<?php
				while ( have_posts() ) :
					the_post();
					sdc_post_card( 'h2' );
				endwhile;
				?>
			</div>
			<?php sdc_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
