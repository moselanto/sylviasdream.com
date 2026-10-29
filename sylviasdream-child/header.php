<?php
/**
 * Document head and site header. 1.21.0: the header itself is printed by
 * sdc_the_site_header(): an Elementor header template when one is chosen,
 * otherwise the built-in header in template-parts/site-header.php.
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
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to main content', 'sylviasdream-child' ); ?></a>

<?php sdc_the_site_header(); ?>
