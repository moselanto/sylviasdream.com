<?php
/**
 * Widget areas.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newsletter areas (hold the Jetpack Subscribe block or a form).
 */
function sdc_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer: Newsletter sign-up', 'sylviasdream-child' ),
		'id'            => 'sdc-footer-newsletter',
		'description'   => __( 'Add the Subscribe block here.', 'sylviasdream-child' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Home: Newsletter sign-up', 'sylviasdream-child' ),
		'id'            => 'sdc-home-newsletter',
		'description'   => __( 'Add the Subscribe block here. Shown in the newsletter band on the home page. When empty, a Contact button is shown instead.', 'sylviasdream-child' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="screen-reader-text">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'sdc_widgets_init' );

/**
 * 1.3.0: the newsletter widget areas only show sign-up widgets on the front end.
 * WordPress drops its default widgets (Archives, Categories, Meta, Recent posts,
 * Search) into a theme's first widget area on activation; they are hidden here
 * and stay visible in Appearance > Widgets so they can be removed there.
 *
 * @param array $sidebars Sidebar ID => widget IDs.
 * @return array
 */
function sdc_newsletter_widgets_only( $sidebars ) {
	if ( is_admin() || ! is_array( $sidebars ) ) {
		return $sidebars;
	}
	$blocks = get_option( 'widget_block', array() );
	foreach ( array( 'sdc-footer-newsletter', 'sdc-home-newsletter' ) as $area ) {
		if ( empty( $sidebars[ $area ] ) || ! is_array( $sidebars[ $area ] ) ) {
			continue;
		}
		$sidebars[ $area ] = array_values(
			array_filter(
				$sidebars[ $area ],
				function ( $widget_id ) use ( $blocks ) {
					if ( preg_match( '/^block-(\d+)$/', (string) $widget_id, $m ) ) {
						$content = isset( $blocks[ (int) $m[1] ]['content'] ) ? (string) $blocks[ (int) $m[1] ]['content'] : '';
						return (bool) preg_match( '/subscri|newsletter|mailpoet|mailchimp|jetpack\/|wp:shortcode/i', $content );
					}
					return (bool) preg_match( '/subscri|newsletter|mailpoet|mailchimp|jetpack/i', (string) $widget_id );
				}
			)
		);
	}
	return $sidebars;
}
add_filter( 'sidebars_widgets', 'sdc_newsletter_widgets_only' );
