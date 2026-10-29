<?php
/**
 * Template Name: Full width (page builder)
 * Template Post Type: page, post
 *
 * No theme page header and no reading column: the page is built entirely
 * from blocks (Sylvia's Dream patterns) or Elementor. Header and footer stay.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main sd-builder" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		// 1.4.0: pages on this template that do not open with their own banner get the
		// standard page header (a matching first H1 in the content is then hidden).
		// 1.21.0: Elementor pages build their own banner (Page top banner widget).
		$sdc_is_el      = function_exists( 'sdc_is_elementor_page' ) && sdc_is_elementor_page( get_the_ID() );
		$sdc_own_header = $sdc_is_el || sdc_content_starts_with_banner( get_the_content() );
		if ( ! $sdc_own_header ) {
			sdc_page_header( get_the_title(), '', has_excerpt() ? get_the_excerpt() : '' );
		}
		?>
		<div class="entry-content<?php echo $sdc_own_header ? '' : ' sd-builder__body'; ?>">
			<?php the_content(); ?>
		</div>
		<?php
		if ( empty( $GLOBALS['sdc_el_has_next'] ) ) {
			sdc_related_links();
		}
	endwhile;
	?>
</main>
<?php
get_footer();
