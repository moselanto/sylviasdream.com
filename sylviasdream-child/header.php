<?php
/**
 * Site header.
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
<header class="site-header">
	<div class="container">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			if ( has_custom_logo() ) {
				$logo_id = (int) get_theme_mod( 'custom_logo' );
				echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ) ) );
			} else {
				bloginfo( 'name' );
			}
			?>
		</a>
		<button class="sd-btn sd-btn--ghost nav-toggle" aria-expanded="false" aria-controls="primary-nav"><?php esc_html_e( 'Menu', 'sylviasdream-child' ); ?></button>
		<nav id="primary-nav" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'sylviasdream-child' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 2,
				'fallback_cb'    => false,
			) );
			?>
		</nav>
	</div>
</header>
