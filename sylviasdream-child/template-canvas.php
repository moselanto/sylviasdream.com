<?php
/**
 * Template Name: Canvas (no header or footer)
 * Template Post Type: page
 *
 * A blank page with only the page content: for landing pages built with
 * blocks or Elementor. No site header, menu or footer.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
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
<?php wp_footer(); ?>
</body>
</html>
