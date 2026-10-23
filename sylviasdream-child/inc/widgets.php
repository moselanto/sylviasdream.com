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
