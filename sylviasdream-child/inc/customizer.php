<?php
/**
 * Customizer settings for all editable theme text.
 *
 * Nothing on the home page, header or footer is hard-coded content:
 * every value is editable in Appearance > Customise > Sylvia's Dream.
 * Default wording is taken only from the approved design prototype
 * (prototype/index.html v3). Items the prototype marks "for Sylvia's
 * approval" default to blank, and blank items are simply not shown.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Customizer sections (inside the Sylvia's Dream panel).
 *
 * @return array<string,string>
 */
function sdc_customizer_sections() {
	return array(
		'sdc_header'       => __( 'Header and menu', 'sylviasdream-child' ),
		'sdc_home'         => __( 'Home: hero', 'sylviasdream-child' ),
		'sdc_strip'        => __( 'Home: trust strip', 'sylviasdream-child' ),
		'sdc_books'        => __( 'Home: featured books', 'sylviasdream-child' ),
		'sdc_about'        => __( 'Home: about Sylvia', 'sylviasdream-child' ),
		'sdc_ministry'     => __( 'Home: Heaven on Earth and giving', 'sylviasdream-child' ),
		'sdc_posts'        => __( 'Home and blog: devotionals', 'sylviasdream-child' ),
		'sdc_testimonials' => __( 'Home: reader reviews', 'sylviasdream-child' ),
		'sdc_newsletter'   => __( 'Home: newsletter and contact', 'sylviasdream-child' ),
		'sdc_shop'         => __( 'Shop page header', 'sylviasdream-child' ),
		'sdc_footer'       => __( 'Footer, contact and social', 'sylviasdream-child' ),
	);
}

/**
 * Field registry: key => array( section, label, type, default ).
 * Types: text, textarea, url, email, checkbox, number.
 * URL fields accept a site-relative path (e.g. /about/) or a full URL.
 *
 * @return array<string,array{0:string,1:string,2:string,3:mixed}>
 */
function sdc_fields() {
	$f = array(
		// Header.
		'tagline'            => array( 'sdc_header', __( 'Tagline under the site name', 'sylviasdream-child' ), 'text', 'Author · Speaker · Missionary' ),
		'give_label'         => array( 'sdc_header', __( 'Give button label', 'sylviasdream-child' ), 'text', 'Give' ),
		'give_url'           => array( 'sdc_header', __( 'Give button link (Dream Partner page)', 'sylviasdream-child' ), 'link', '/give/' ),
		'mobile_cta_label'   => array( 'sdc_header', __( 'Mobile menu: giving button label', 'sylviasdream-child' ), 'text', 'Become a Dream Partner' ),
		'show_mobile_dock'   => array( 'sdc_header', __( 'Phones: show the bottom navigation bar (Home, Books, Ministry, Give, More)', 'sylviasdream-child' ), 'checkbox', true ),
		'dock_ministry_label' => array( 'sdc_header', __( 'Phone bottom bar: ministry label', 'sylviasdream-child' ), 'text', 'Ministry' ),
		'dock_ministry_url'  => array( 'sdc_header', __( 'Phone bottom bar: ministry link', 'sylviasdream-child' ), 'link', '/heaven-on-earth/' ),
		'mega_feature_label' => array( 'sdc_header', __( 'Books mega menu: heading above featured books (blank hides them)', 'sylviasdream-child' ), 'text', 'Start here' ),
		'show_language_switcher' => array( 'sdc_header', __( 'Show the language selector (English, Français, Español, Bahasa Indonesia; automatic translation)', 'sylviasdream-child' ), 'checkbox', true ),
		'show_utility_bar'   => array( 'sdc_header', __( 'Show the thin utility bar above the header', 'sylviasdream-child' ), 'checkbox', true ),
		'utility_text_1'     => array( 'sdc_header', __( 'Utility bar: first note', 'sylviasdream-child' ), 'text', 'Instant eBook delivery worldwide' ),
		'utility_text_2'     => array( 'sdc_header', __( 'Utility bar: second note (hidden on phones)', 'sylviasdream-child' ), 'text', 'Paperbacks via Amazon in your country' ),
		'utility_link_label' => array( 'sdc_header', __( 'Utility bar: link label', 'sylviasdream-child' ), 'text', 'My downloads' ),
		'utility_link_url'   => array( 'sdc_header', __( 'Utility bar: link (blank = My account > Downloads)', 'sylviasdream-child' ), 'link', '' ),
		'use_google_fonts'   => array( 'sdc_header', __( 'Load Cormorant Garamond and Source Sans 3 from Google Fonts (untick to use system fonts)', 'sylviasdream-child' ), 'checkbox', true ),

		// Hero (existing keys kept from 0.1.x).
		'hero_eyebrow'       => array( 'sdc_home', __( 'Hero: small heading', 'sylviasdream-child' ), 'text', 'Christian Author · Speaker · Missionary' ),
		'hero_title'         => array( 'sdc_home', __( 'Hero: main heading', 'sylviasdream-child' ), 'text', 'From fear to lasting peace in Christ' ),
		'hero_text'          => array( 'sdc_home', __( 'Hero: intro text', 'sylviasdream-child' ), 'textarea', 'Faith-based books and devotionals by Sylvia Suhr, written from more than twenty years of mission work across Africa and Asia.' ),
		'hero_quote'         => array( 'sdc_home', __( 'Hero: short quote (blank to hide)', 'sylviasdream-child' ), 'textarea', 'One life can make a difference. Restoring dignity, revealing Jesus.' ),
		'hero_cta_label'     => array( 'sdc_home', __( 'Hero: main button label', 'sylviasdream-child' ), 'text', 'Explore the Books' ),
		'hero_cta_url'       => array( 'sdc_home', __( 'Hero: main button link (blank = shop page)', 'sylviasdream-child' ), 'link', '' ),
		'hero_cta2_label'    => array( 'sdc_home', __( 'Hero: second button label', 'sylviasdream-child' ), 'text', 'Meet Sylvia' ),
		'hero_cta2_url'      => array( 'sdc_home', __( 'Hero: second button link', 'sylviasdream-child' ), 'link', '/my-story/' ),

		// Trust strip. Item 4 ("Every purchase helps") is blank until Sylvia approves the wording.
		'strip_1_title'      => array( 'sdc_strip', __( 'Item 1: heading', 'sylviasdream-child' ), 'text', '25 titles' ),
		'strip_1_text'       => array( 'sdc_strip', __( 'Item 1: text', 'sylviasdream-child' ), 'text', 'eBooks, paperbacks and audio' ),
		'strip_2_title'      => array( 'sdc_strip', __( 'Item 2: heading', 'sylviasdream-child' ), 'text', 'Instant download' ),
		'strip_2_text'       => array( 'sdc_strip', __( 'Item 2: text', 'sylviasdream-child' ), 'text', 'Secure link after purchase' ),
		'strip_3_title'      => array( 'sdc_strip', __( 'Item 3: heading', 'sylviasdream-child' ), 'text', 'Readers worldwide' ),
		'strip_3_text'       => array( 'sdc_strip', __( 'Item 3: text', 'sylviasdream-child' ), 'text', 'English, French, Spanish, Bahasa' ),
		'strip_4_title'      => array( 'sdc_strip', __( 'Item 4: heading (awaiting approval, blank hides it)', 'sylviasdream-child' ), 'text', '' ),
		'strip_4_text'       => array( 'sdc_strip', __( 'Item 4: text', 'sylviasdream-child' ), 'text', '' ),

		// Featured books.
		'books_eyebrow'      => array( 'sdc_books', __( 'Small heading', 'sylviasdream-child' ), 'text', 'Featured books' ),
		'books_title'        => array( 'sdc_books', __( 'Heading', 'sylviasdream-child' ), 'text', 'Books that bring hope and healing' ),
		'books_text'         => array( 'sdc_books', __( 'Intro text', 'sylviasdream-child' ), 'textarea', 'Start with the story that began it all, or choose the book for the season you are in.' ),
		'books_count'        => array( 'sdc_books', __( 'How many books to show (featured books first)', 'sylviasdream-child' ), 'number', 8 ),
		'books_link_label'   => array( 'sdc_books', __( 'Link to the shop: label', 'sylviasdream-child' ), 'text', 'See all books' ),

		// About band (biography wording is a prototype draft for Sylvia's review).
		'about_eyebrow'      => array( 'sdc_about', __( 'Small heading', 'sylviasdream-child' ), 'text', 'Meet Sylvia' ),
		'about_title'        => array( 'sdc_about', __( 'Heading', 'sylviasdream-child' ), 'text', 'Called to ministry at fifty-seven' ),
		'about_text'         => array( 'sdc_about', __( 'Intro paragraph', 'sylviasdream-child' ), 'textarea', 'Sylvia was born in post-war England, the youngest of six children. She came to know Jesus at thirty-eight and was called to ministry at fifty-seven.' ),
		'about_text_2'       => array( 'sdc_about', __( 'Second paragraph', 'sylviasdream-child' ), 'textarea', 'She travelled to Kenya often, preaching the gospel and visiting prisons, churches and schools. She helped establish Heaven on Earth Ministries for orphans and children in remote villages. Today, at eighty-one, she writes from Australia to help readers trust God more deeply.' ),
		'about_cta_label'    => array( 'sdc_about', __( 'Button label', 'sylviasdream-child' ), 'text', "Read Sylvia's story" ),
		'about_cta_url'      => array( 'sdc_about', __( 'Button link', 'sylviasdream-child' ), 'link', '/my-story/' ),

		// Heaven on Earth and Dream Partner bands.
		'hoe_eyebrow'        => array( 'sdc_ministry', __( 'Heaven on Earth: small heading', 'sylviasdream-child' ), 'text', 'Heaven on Earth, Kenya' ),
		'hoe_text'           => array( 'sdc_ministry', __( 'Heaven on Earth: statement', 'sylviasdream-child' ), 'textarea', 'Rescuing vulnerable children, educating the next generation and bringing the love of Christ to rural villages.' ),
		'hoe_cta_label'      => array( 'sdc_ministry', __( 'Heaven on Earth: button label', 'sylviasdream-child' ), 'text', 'About the ministry' ),
		'hoe_cta_url'        => array( 'sdc_ministry', __( 'Heaven on Earth: button link', 'sylviasdream-child' ), 'link', '/heaven-on-earth/' ),
		'give_eyebrow'       => array( 'sdc_ministry', __( 'Dream Partners: small heading', 'sylviasdream-child' ), 'text', 'Dream Partners' ),
		'give_title'         => array( 'sdc_ministry', __( 'Dream Partners: heading', 'sylviasdream-child' ), 'text', 'Become a Dream Partner' ),
		'give_text'          => array( 'sdc_ministry', __( 'Dream Partners: text', 'sylviasdream-child' ), 'textarea', 'Join a community of monthly givers bringing hope, education and the love of Christ to children in rural Kenya.' ),
		'give_cta_label'     => array( 'sdc_ministry', __( 'Dream Partners: button label (links to the Give page)', 'sylviasdream-child' ), 'text', 'Become a Dream Partner' ),
		'give_show_progress' => array( 'sdc_ministry', __( 'Show the Dream Partner progress bar (needs the Sylvia\'s Dream Core plugin)', 'sylviasdream-child' ), 'checkbox', true ),

		// Devotionals.
		'show_latest_posts'  => array( 'sdc_posts', __( 'Show the latest devotionals on the home page', 'sylviasdream-child' ), 'checkbox', true ),
		'posts_eyebrow'      => array( 'sdc_posts', __( 'Home: small heading', 'sylviasdream-child' ), 'text', 'Grace for the Journey' ),
		'posts_title'        => array( 'sdc_posts', __( 'Home: heading', 'sylviasdream-child' ), 'text', 'Latest from Sylvia' ),
		'posts_link_label'   => array( 'sdc_posts', __( 'Home: link label', 'sylviasdream-child' ), 'text', 'Read all reflections' ),
		'blog_eyebrow'       => array( 'sdc_posts', __( 'Blog page: small heading', 'sylviasdream-child' ), 'text', 'Grace for the Journey' ),
		'blog_title'         => array( 'sdc_posts', __( 'Blog page: heading (when no posts page is set)', 'sylviasdream-child' ), 'text', 'Reflections and encouragement' ),
		'blog_intro'         => array( 'sdc_posts', __( 'Blog page: intro', 'sylviasdream-child' ), 'textarea', 'Weekly devotional writing from Sylvia on trust, peace and walking with God.' ),

		// Testimonials: shown only when at least one quote is filled in.
		'testimonials_eyebrow' => array( 'sdc_testimonials', __( 'Small heading', 'sylviasdream-child' ), 'text', 'Reader reviews' ),
		'testimonials_title' => array( 'sdc_testimonials', __( 'Heading', 'sylviasdream-child' ), 'text', 'What readers are saying' ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		/* translators: %d: review number. */
		$f[ 'testimonial_' . $i . '_quote' ] = array( 'sdc_testimonials', sprintf( __( 'Review %d: text (only with the reader\'s permission)', 'sylviasdream-child' ), $i ), 'textarea', '' );
		/* translators: %d: review number. */
		$f[ 'testimonial_' . $i . '_name' ] = array( 'sdc_testimonials', sprintf( __( 'Review %d: reader name, country', 'sylviasdream-child' ), $i ), 'text', '' );
	}
	$f += array(
		// Newsletter and contact.
		'news_eyebrow'       => array( 'sdc_newsletter', __( 'Small heading', 'sylviasdream-child' ), 'text', 'Grace for the Journey' ),
		'news_title'         => array( 'sdc_newsletter', __( 'Heading', 'sylviasdream-child' ), 'text', 'Weekly encouragement in your inbox' ),
		'news_text'          => array( 'sdc_newsletter', __( 'Text', 'sylviasdream-child' ), 'textarea', "Receive Sylvia's devotional letter, new book announcements and news from Kenya." ),
		'news_note'          => array( 'sdc_newsletter', __( 'Small print under the form', 'sylviasdream-child' ), 'text', 'No spam. Unsubscribe at any time.' ),
		'contact_cta_label'  => array( 'sdc_newsletter', __( 'Contact button label', 'sylviasdream-child' ), 'text', 'Contact Sylvia' ),
		'contact_url'        => array( 'sdc_newsletter', __( 'Contact page link', 'sylviasdream-child' ), 'link', '/contact/' ),

		// Shop header.
		'shop_eyebrow'       => array( 'sdc_shop', __( 'Small heading', 'sylviasdream-child' ), 'text', 'The bookshop' ),
		'books_page_url'     => array( 'sdc_shop', __( 'Books page link used by every "Explore the Books" / "See all books" button (blank = WooCommerce shop page)', 'sylviasdream-child' ), 'link', '/shop-for-books/' ),
		'shop_intro'         => array( 'sdc_shop', __( 'Intro text', 'sylviasdream-child' ), 'textarea', 'eBooks delivered instantly by secure download. Paperbacks and Kindle editions through Amazon.' ),

		// Footer.
		'footer_about'       => array( 'sdc_footer', __( 'Footer: about text', 'sylviasdream-child' ), 'textarea', 'Faith-based books and devotionals by Sylvia Suhr, helping readers move from fear to lasting peace in Christ.' ),
		'footer_location'    => array( 'sdc_footer', __( 'Footer: location (country only)', 'sylviasdream-child' ), 'text', 'Australia' ),
		'footer_location_note' => array( 'sdc_footer', __( 'Footer: line under the location', 'sylviasdream-child' ), 'text', 'Serving readers worldwide' ),
		'copyright_name'     => array( 'sdc_footer', __( 'Footer: copyright holder', 'sylviasdream-child' ), 'text', 'Sylvia Suhr' ),
		'footer_credit'      => array( 'sdc_footer', __( 'Footer: credit line', 'sylviasdream-child' ), 'text', 'Website by Pimofy Digital.' ),
		'footer_payment_badges' => array( 'sdc_footer', __( 'Footer: payment badges, separated by commas (blank hides the row)', 'sylviasdream-child' ), 'text', 'VISA, Mastercard, AMEX, Apple Pay, Google Pay, PayPal' ),
		'footer_news_title'  => array( 'sdc_footer', __( 'Footer: newsletter line (blank hides it)', 'sylviasdream-child' ), 'text', 'Weekly encouragement in your inbox' ),
		'contact_email'      => array( 'sdc_footer', __( 'Footer: contact email', 'sylviasdream-child' ), 'email', '' ),
		'social_facebook'    => array( 'sdc_footer', __( 'Facebook URL', 'sylviasdream-child' ), 'url', '' ),
		'social_instagram'   => array( 'sdc_footer', __( 'Instagram URL', 'sylviasdream-child' ), 'url', 'https://www.instagram.com/sylviasuhr/' ),
		'social_youtube'     => array( 'sdc_footer', __( 'YouTube URL', 'sylviasdream-child' ), 'url', '' ),
		'social_patreon'     => array( 'sdc_footer', __( 'Patreon URL', 'sylviasdream-child' ), 'url', 'https://www.patreon.com/c/sylviasdream' ),
		'social_amazon'      => array( 'sdc_footer', __( 'Amazon author page URL', 'sylviasdream-child' ), 'url', '' ),
	);
	return $f;
}

/**
 * Image settings: key => array( section, label ).
 *
 * @return array<string,array{0:string,1:string}>
 */
function sdc_image_fields() {
	return array(
		'hero_image'  => array( 'sdc_home', __( 'Hero: book cover or photo (blank = covers of featured books)', 'sylviasdream-child' ) ),
		'about_image' => array( 'sdc_about', __( 'Photo of Sylvia (shown only when set)', 'sylviasdream-child' ) ),
		'hoe_image'   => array( 'sdc_ministry', __( 'Heaven on Earth: background photo (optional)', 'sylviasdream-child' ) ),
	);
}

/**
 * Default values, derived from the field registry.
 *
 * @return array<string,mixed>
 */
function sdc_defaults() {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = array();
		foreach ( sdc_fields() as $key => $field ) {
			$defaults[ $key ] = $field[3];
		}
		foreach ( array_keys( sdc_image_fields() ) as $key ) {
			$defaults[ $key ] = 0;
		}
	}
	return $defaults;
}

/**
 * Read a text theme setting with its default.
 *
 * @param string $key Setting key (without the sdc_ prefix).
 * @return string
 */
function sdc_get( $key ) {
	$defaults = sdc_defaults();
	$value    = get_theme_mod( 'sdc_' . $key, $defaults[ $key ] ?? '' );
	return is_scalar( $value ) ? trim( (string) $value ) : '';
}

/**
 * Read a checkbox theme setting.
 *
 * @param string $key Setting key.
 * @return bool
 */
function sdc_get_bool( $key ) {
	$defaults = sdc_defaults();
	return (bool) get_theme_mod( 'sdc_' . $key, $defaults[ $key ] ?? false );
}

/**
 * Read an image (attachment id) theme setting.
 *
 * @param string $key Setting key.
 * @return int
 */
function sdc_get_image( $key ) {
	return absint( get_theme_mod( 'sdc_' . $key, 0 ) );
}

/**
 * Sanitise a link that may be a site-relative path or a full URL.
 *
 * @param string $value Raw value.
 * @return string
 */
function sdc_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( str_starts_with( $value, '/' ) && ! str_starts_with( $value, '//' ) ) {
		// Site-relative path: keep only URL-safe characters.
		return '/' . ltrim( (string) preg_replace( '#[^A-Za-z0-9/_\-\.~%?=&\#+]#', '', $value ), '/' );
	}
	return esc_url_raw( $value );
}

/**
 * Sanitise a checkbox.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function sdc_sanitize_checkbox( $value ) {
	return ! empty( $value );
}

/**
 * Sanitise the number of books (1-24).
 *
 * @param mixed $value Raw value.
 * @return int
 */
function sdc_sanitize_count( $value ) {
	return max( 1, min( 24, absint( $value ) ) );
}

/**
 * Register the Customizer panel, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function sdc_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'sdc_panel', array(
		'title'       => __( "Sylvia's Dream", 'sylviasdream-child' ),
		'description' => __( 'All text on the home page, header and footer. Leave a field blank to hide that item.', 'sylviasdream-child' ),
		'priority'    => 30,
	) );

	$priority = 10;
	foreach ( sdc_customizer_sections() as $id => $title ) {
		$wp_customize->add_section( $id, array(
			'title'    => $title,
			'panel'    => 'sdc_panel',
			'priority' => $priority,
		) );
		$priority += 10;
	}

	foreach ( sdc_fields() as $key => $field ) {
		list( $section, $label, $type, $default ) = $field;

		switch ( $type ) {
			case 'textarea':
				$sanitize = 'sanitize_textarea_field';
				$control  = 'textarea';
				break;
			case 'url':
				$sanitize = 'esc_url_raw';
				$control  = 'url';
				break;
			case 'link':
				$sanitize = 'sdc_sanitize_link';
				$control  = 'text';
				break;
			case 'email':
				$sanitize = 'sanitize_email';
				$control  = 'email';
				break;
			case 'checkbox':
				$sanitize = 'sdc_sanitize_checkbox';
				$control  = 'checkbox';
				break;
			case 'number':
				$sanitize = 'sdc_sanitize_count';
				$control  = 'number';
				break;
			default:
				$sanitize = 'sanitize_text_field';
				$control  = 'text';
		}

		$wp_customize->add_setting( 'sdc_' . $key, array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		) );
		$args = array(
			'label'   => $label,
			'section' => $section,
			'type'    => $control,
		);
		if ( 'number' === $control ) {
			$args['input_attrs'] = array(
				'min' => 1,
				'max' => 24,
			);
		}
		$wp_customize->add_control( 'sdc_' . $key, $args );
	}

	foreach ( sdc_image_fields() as $key => $field ) {
		$wp_customize->add_setting( 'sdc_' . $key, array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sdc_' . $key, array(
			'label'     => $field[1],
			'section'   => $field[0],
			'mime_type' => 'image',
		) ) );
	}
}
add_action( 'customize_register', 'sdc_customize_register' );
