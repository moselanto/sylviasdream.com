<?php
/**
 * Site footer. 1.21.0: the footer itself is printed by sdc_the_site_footer():
 * an Elementor footer template when one is chosen, otherwise the built-in
 * footer in template-parts/site-footer.php. The phone bottom bar, the cart
 * drawer and wp_footer() are always printed here.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_page_template( 'template-canvas.php' ) ) {
	sdc_the_site_footer();
}
?>
<?php sdc_mobile_dock(); ?>
<?php wp_footer(); ?>
</body>
</html>
