<?php
/**
 * Customizer settings for editable home page and footer content.
 * Nothing here is hard-coded content: every value is editable in
 * Appearance > Customise > Sylvia's Dream.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values. Text is taken from the current live site and
 * confirmed with Sylvia at kick-off.
 *
 * @return array<string,string>
 */
function sdc_defaults() {
	return array(
		'hero_eyebrow'   => 'Christian Author &bull; Speaker &bull; Missionary',
		'hero_title'     => "Hope for the journey. Light for the next step.",
		'hero_text'      => 'Christian books and resources to help you find freedom in Christ and prepare your heart for God\'s calling.',
		'hero_cta_label' => 'Explore the Books',
		'hero_cta_url'   => '/shop/',
		// No free download is offered without Sylvia's approval, so the
		// second button points to the ministry page by default.
		'hero_cta2_label'=> 'Heaven on Earth',
		'hero_cta2_url'  => '/heaven-on-earth/',
		'hero_image'     => '',
		'contact_email'  => '',
		'social_facebook'=> '',
		'social_instagram'=> 'https://www.instagram.com/sylviasuhr/',
		'social_youtube' => '',
		'social_patreon' => 'https://www.patreon.com/c/sylviasdream',
	);
}

/**
 * Read a theme setting with its default.
 *
 * @param string $key Setting key.
 * @return string
 */
function sdc_get( $key ) {
	$defaults = sdc_defaults();
	return (string) get_theme_mod( 'sdc_' . $key, $defaults[ $key ] ?? '' );
}

/**
 * Register Customizer section and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function sdc_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'sdc_home', array(
		'title'    => __( "Sylvia's Dream", 'sylviasdream-child' ),
		'priority' => 30,
	) );

	$fields = array(
		'hero_eyebrow'     => array( __( 'Hero: small heading', 'sylviasdream-child' ), 'text' ),
		'hero_title'       => array( __( 'Hero: main heading', 'sylviasdream-child' ), 'text' ),
		'hero_text'        => array( __( 'Hero: intro text', 'sylviasdream-child' ), 'textarea' ),
		'hero_cta_label'   => array( __( 'Hero: main button label', 'sylviasdream-child' ), 'text' ),
		'hero_cta_url'     => array( __( 'Hero: main button link', 'sylviasdream-child' ), 'url' ),
		'hero_cta2_label'  => array( __( 'Hero: second button label', 'sylviasdream-child' ), 'text' ),
		'hero_cta2_url'    => array( __( 'Hero: second button link', 'sylviasdream-child' ), 'url' ),
		'contact_email'    => array( __( 'Footer: contact email', 'sylviasdream-child' ), 'email' ),
		'social_facebook'  => array( __( 'Facebook URL', 'sylviasdream-child' ), 'url' ),
		'social_instagram' => array( __( 'Instagram URL', 'sylviasdream-child' ), 'url' ),
		'social_youtube'   => array( __( 'YouTube URL', 'sylviasdream-child' ), 'url' ),
		'social_patreon'   => array( __( 'Patreon URL', 'sylviasdream-child' ), 'url' ),
	);
	$defaults = sdc_defaults();

	foreach ( $fields as $key => $field ) {
		list( $label, $type ) = $field;
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		}
		$wp_customize->add_setting( 'sdc_' . $key, array(
			'default'           => $defaults[ $key ],
			'sanitize_callback' => $sanitize,
		) );
		$wp_customize->add_control( 'sdc_' . $key, array(
			'label'   => $label,
			'section' => 'sdc_home',
			'type'    => 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : $type ),
		) );
	}

	$wp_customize->add_setting( 'sdc_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sdc_hero_image', array(
		'label'     => __( 'Hero: photo of Sylvia or latest book', 'sylviasdream-child' ),
		'section'   => 'sdc_home',
		'mime_type' => 'image',
	) ) );
}
add_action( 'customize_register', 'sdc_customize_register' );
