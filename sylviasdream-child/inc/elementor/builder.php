<?php
/**
 * 1.21.0: Elementor header and footer (free Elementor, no Pro needed).
 *
 * A header or footer is an ordinary Elementor template (Templates > Saved
 * Templates). Sylvia's Dream Theme Options chooses the site-wide header and
 * footer; any page can choose its own, the built-in one, or hide them, in
 * Elementor > Page settings or in the "Header and footer" box of the editor.
 *
 * Safe fallback: when nothing is chosen, Elementor is off, or the chosen
 * template is missing, unpublished, broken or empty, the theme's own header
 * (sticky header, mega menu, mobile drawer) and footer are shown exactly as
 * before. The phone bottom bar is printed by footer.php in every case.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is Elementor loaded and able to render templates?
 *
 * @return bool
 */
function sdc_builder_ready() {
	return did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->frontend );
}

/**
 * Is this post built with Elementor (and is Elementor active)?
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sdc_is_elementor_page( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
	if ( ! $post_id || ! did_action( 'elementor/loaded' ) ) {
		return false;
	}
	return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/**
 * Published Elementor templates usable as a header or footer.
 *
 * @return array<string,string> id => title
 */
function sdc_builder_template_choices() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	// No post_type_exists() check: this may run before Elementor registers its
	// post type on init (Redux builds its panel on after_setup_theme).
	$cache = array();
	$posts = get_posts(
		array(
			'post_type'        => 'elementor_library',
			'post_status'      => 'publish',
			'posts_per_page'   => 200,
			'orderby'          => 'title',
			'order'            => 'ASC',
			'no_found_rows'    => true,
			'suppress_filters' => true,
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- small table, admin screens and one cached front-end lookup.
			'meta_query'       => array(
				array(
					'key'     => '_elementor_template_type',
					'value'   => array( 'section', 'container', 'page', 'header', 'footer' ),
					'compare' => 'IN',
				),
			),
		)
	);
	foreach ( $posts as $p ) {
		$cache[ (string) $p->ID ] = $p->post_title ? $p->post_title : sprintf( '#%d', $p->ID );
	}
	return $cache;
}

/**
 * Per-page choices: '' = site setting, 'theme' = built in, 'none' = hide, or a template ID.
 *
 * @return array<string,string>
 */
function sdc_builder_page_choices() {
	return array(
		''      => __( 'Use the website setting', 'sylviasdream-child' ),
		'theme' => __( 'The normal Sylvia\'s Dream one (built in)', 'sylviasdream-child' ),
		'none'  => __( 'Hide it on this page', 'sylviasdream-child' ),
	) + sdc_builder_template_choices();
}

/**
 * A page's own header/footer choice.
 *
 * @param int    $post_id Post ID.
 * @param string $where   header|footer.
 * @return string
 */
function sdc_builder_page_choice( $post_id, $where ) {
	$settings = get_post_meta( $post_id, '_elementor_page_settings', true );
	$key      = 'sdc_' . $where;
	if ( is_array( $settings ) && isset( $settings[ $key ] ) && '' !== (string) $settings[ $key ] ) {
		return (string) $settings[ $key ];
	}
	return (string) get_post_meta( $post_id, '_' . $key, true );
}

/**
 * Template to show: >0 template ID, 0 built-in, -1 hidden.
 *
 * @param string $where header|footer.
 * @return int
 */
function sdc_builder_resolve( $where ) {
	$id = absint( sdc_opt( $where . '_template' ) );
	if ( is_singular() ) {
		$choice = sdc_builder_page_choice( get_queried_object_id(), $where );
		if ( 'none' === $choice ) {
			$id = -1;
		} elseif ( 'theme' === $choice ) {
			$id = 0;
		} elseif ( absint( $choice ) ) {
			$id = absint( $choice );
		}
	}
	/**
	 * Filter the header/footer template. 0 = built in, -1 = hidden.
	 *
	 * @param int    $id    Template ID.
	 * @param string $where header|footer.
	 */
	$id = (int) apply_filters( 'sdc_builder_template', $id, $where );
	if ( $id > 0 ) {
		$post = get_post( $id );
		if ( ! $post || 'elementor_library' !== $post->post_type || 'publish' !== $post->post_status ) {
			$id = 0;
		}
	}
	return $id;
}

/**
 * Inside Elementor's own template preview the built-in header/footer stay.
 *
 * @return bool
 */
function sdc_builder_is_template_screen() {
	return is_singular( 'elementor_library' );
}

/**
 * Render the header or footer from Elementor.
 *
 * @param string $where header|footer.
 * @return bool True when handled (rendered or hidden); false = use the built-in design.
 */
function sdc_builder_render( $where ) {
	if ( ! sdc_builder_ready() || sdc_builder_is_template_screen() ) {
		return false;
	}
	$id = sdc_builder_resolve( $where );
	if ( -1 === $id ) {
		return true;
	}
	if ( $id < 1 ) {
		return false;
	}
	static $depth = 0;
	if ( $depth > 0 ) {
		return false; // A template never includes itself.
	}
	++$depth;
	try {
		$html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $id, false );
	} catch ( \Throwable $e ) {
		$html = '';
	}
	--$depth;
	if ( ! is_string( $html ) || '' === trim( $html ) ) {
		return false;
	}
	$sticky = ( 'header' === $where && (int) sdc_opt( 'sticky_header' ) ) ? ' sd-el-sticky' : '';
	printf( '<div class="sd-el-%1$s%2$s" id="sd-el-%1$s" data-template="%3$d">', esc_attr( $where ), esc_attr( $sticky ), (int) $id );
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor's rendered template.
	echo '</div>';
	return true;
}

/**
 * Print the header: Elementor template, nothing, or the built-in header.
 */
function sdc_the_site_header() {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
		return; // Elementor Pro Theme Builder header.
	}
	if ( ! sdc_builder_render( 'header' ) ) {
		get_template_part( 'template-parts/site-header' );
	}
}

/**
 * Print the footer: Elementor template, nothing, or the built-in footer.
 */
function sdc_the_site_footer() {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
		return; // Elementor Pro Theme Builder footer.
	}
	if ( ! sdc_builder_render( 'footer' ) ) {
		get_template_part( 'template-parts/site-footer' );
	}
}

/**
 * Load the templates' CSS in the head so a header never flashes unstyled.
 */
function sdc_builder_enqueue() {
	if ( ! sdc_builder_ready() || sdc_builder_is_template_screen() || is_admin() ) {
		return;
	}
	$ids = array();
	foreach ( array( 'header', 'footer' ) as $where ) {
		$id = sdc_builder_resolve( $where );
		if ( $id > 0 ) {
			$ids[] = $id;
		}
	}
	if ( ! $ids ) {
		return;
	}
	$frontend = \Elementor\Plugin::$instance->frontend;
	if ( method_exists( $frontend, 'enqueue_styles' ) ) {
		$frontend->enqueue_styles();
	}
	if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
		foreach ( $ids as $id ) {
			try {
				\Elementor\Core\Files\CSS\Post::create( $id )->enqueue();
			} catch ( \Throwable $e ) {
				continue; // Elementor prints the CSS with the content instead.
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'sdc_builder_enqueue', 25 );

/**
 * Body classes for the header/footer in use.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_builder_body_classes( $classes ) {
	if ( sdc_builder_ready() && ! sdc_builder_is_template_screen() ) {
		foreach ( array( 'header', 'footer' ) as $where ) {
			$id = sdc_builder_resolve( $where );
			if ( $id > 0 ) {
				$classes[] = 'sd-el-' . $where . '-on';
			} elseif ( -1 === $id ) {
				$classes[] = 'sd-no-' . $where;
			}
		}
	}
	if ( is_singular() && sdc_is_elementor_page() ) {
		$classes[] = 'sd-elementor-page';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_builder_body_classes' );

/* -------------------------------------------------------------------------
 * Per-page choice inside Elementor (Page settings)
 * ---------------------------------------------------------------------- */

/**
 * Add "Header and footer" to Elementor's Page settings.
 *
 * @param object $document Elementor document.
 */
function sdc_builder_document_controls( $document ) {
	if ( ! class_exists( '\Elementor\Controls_Manager' ) || ! is_object( $document ) || ! method_exists( $document, 'start_controls_section' ) ) {
		return;
	}
	$post = method_exists( $document, 'get_main_post' ) ? $document->get_main_post() : null;
	if ( ! $post || 'elementor_library' === $post->post_type ) {
		return;
	}
	$document->start_controls_section(
		'sdc_layout_section',
		array(
			'label' => __( 'Header and footer on this page', 'sylviasdream-child' ),
			'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
		)
	);
	$choices = sdc_builder_page_choices();
	foreach ( array( 'header' => __( 'Top of the page (header)', 'sylviasdream-child' ), 'footer' => __( 'Bottom of the page (footer)', 'sylviasdream-child' ) ) as $where => $label ) {
		$document->add_control(
			'sdc_' . $where,
			array(
				'label'   => $label,
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $choices,
				'default' => '',
			)
		);
	}
	$document->add_control(
		'sdc_layout_note',
		array(
			'type'            => \Elementor\Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Click Update, then reload the page to see a different header or footer.', 'sylviasdream-child' ),
			'content_classes' => 'elementor-descriptor',
		)
	);
	$document->end_controls_section();
}
add_action( 'elementor/documents/register_controls', 'sdc_builder_document_controls' );

/* -------------------------------------------------------------------------
 * Per-page choice in the WordPress editor
 * ---------------------------------------------------------------------- */

/**
 * Register the box on pages and posts.
 */
function sdc_builder_meta_box() {
	add_meta_box( 'sdc-layout', __( 'Header and footer', 'sylviasdream-child' ), 'sdc_builder_meta_box_html', array( 'page', 'post' ), 'side', 'low' );
}
add_action( 'add_meta_boxes', 'sdc_builder_meta_box' );

/**
 * Box contents.
 *
 * @param WP_Post $post Post.
 */
function sdc_builder_meta_box_html( $post ) {
	wp_nonce_field( 'sdc_layout_save', 'sdc_layout_nonce' );
	$choices = sdc_builder_page_choices();
	foreach ( array( 'header' => __( 'Header', 'sylviasdream-child' ), 'footer' => __( 'Footer', 'sylviasdream-child' ) ) as $where => $label ) {
		$current = sdc_builder_page_choice( $post->ID, $where );
		echo '<p><label for="sdc-layout-' . esc_attr( $where ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
		echo '<select style="width:100%" id="sdc-layout-' . esc_attr( $where ) . '" name="sdc_layout[' . esc_attr( $where ) . ']">';
		foreach ( $choices as $val => $name ) {
			echo '<option value="' . esc_attr( (string) $val ) . '"' . selected( $current, (string) $val, false ) . '>' . esc_html( $name ) . '</option>';
		}
		echo '</select></p>';
	}
}

/**
 * Save the box into the same place Elementor's Page settings use.
 *
 * @param int $post_id Post ID.
 */
function sdc_builder_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['sdc_layout_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sdc_layout_nonce'] ) ), 'sdc_layout_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$in       = isset( $_POST['sdc_layout'] ) && is_array( $_POST['sdc_layout'] ) ? wp_unslash( $_POST['sdc_layout'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- checked against the allowed list below.
	$allowed  = array_map( 'strval', array_keys( sdc_builder_page_choices() ) );
	$settings = get_post_meta( $post_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();
	foreach ( array( 'header', 'footer' ) as $where ) {
		$v = isset( $in[ $where ] ) ? sanitize_key( (string) $in[ $where ] ) : '';
		$v = in_array( $v, $allowed, true ) ? $v : '';
		update_post_meta( $post_id, '_sdc_' . $where, $v );
		if ( '' === $v ) {
			unset( $settings[ 'sdc_' . $where ] );
		} else {
			$settings[ 'sdc_' . $where ] = $v;
		}
	}
	if ( $settings ) {
		update_post_meta( $post_id, '_elementor_page_settings', $settings );
	} elseif ( metadata_exists( 'post', $post_id, '_elementor_page_settings' ) ) {
		delete_post_meta( $post_id, '_elementor_page_settings' );
	}
}
add_action( 'save_post', 'sdc_builder_meta_box_save' );
