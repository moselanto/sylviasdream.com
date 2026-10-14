<?php
/**
 * Widget areas.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Footer newsletter area (holds the Jetpack Subscribe block).
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
}
add_action( 'widgets_init', 'sdc_widgets_init' );
