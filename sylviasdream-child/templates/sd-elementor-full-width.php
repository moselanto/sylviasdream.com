<?php
/**
 * Template Name: Elementor full width (Sylvia's Dream)
 * Template Post Type: page
 *
 * 1.21.1: used for pages built with "Build this page in Elementor". The theme
 * header and footer stay (sticky header, mega menu, phone drawer and bottom bar,
 * cart drawer, all printed by header.php and footer.php), but there is no theme
 * page header and no reading column: each Elementor section runs edge to edge and
 * the Sylvia's Dream widgets box their own inner content at the theme width
 * (--max). The page's h1 comes from its own banner widget (Home welcome banner or
 * Page top banner), so there is still exactly one h1.
 *
 * When Elementor is off (or the page is not an Elementor page) the page falls back
 * to the "Full width (page builder)" template, which shows the block content with
 * the normal page header.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'sdc_is_elementor_page' ) || ! sdc_is_elementor_page( get_queried_object_id() ) ) {
	require locate_template( 'template-full-width.php' );
	return;
}

get_header();
?>
<main id="main-content" class="site-main sd-builder sd-el-full" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="entry-content sd-el-full__content">
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
