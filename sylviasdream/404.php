<?php
/**
 * 404 template.
 *
 * @package SylviasDream
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" class="site-main">
	<h1><?php esc_html_e( 'Page not found', 'sylviasdream' ); ?></h1>
	<p><?php esc_html_e( 'The page you were looking for has moved. Try the menu above or search below.', 'sylviasdream' ); ?></p>
	<?php get_search_form(); ?>
</main>
<?php
get_footer();
