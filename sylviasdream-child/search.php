<?php
/**
 * Search results (books, devotionals and pages).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
/* translators: %s: search query. */
$sdc_title = sprintf( __( 'Search results for "%s"', 'sylviasdream-child' ), get_search_query( false ) );
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( $sdc_title, __( 'Search', 'sylviasdream-child' ) ); ?>
	<div class="sd-wrap sd-section">
		<div class="sd-prose sd-search-again"><?php get_search_form(); ?></div>
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
