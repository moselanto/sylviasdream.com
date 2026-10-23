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
		?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
