<?php
/**
 * 1.21.0: base class and classes for the Sylvia's Dream Elementor widgets.
 *
 * Loaded only from the elementor/widgets/register action, so Elementor's
 * classes always exist here. Every widget is built from its registry entry
 * (inc/elementor/registry.php) with the same plain-English panels.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

/**
 * One Sylvia's Dream widget. Subclasses only set KEY.
 */
abstract class SDC_Elementor_Widget extends Widget_Base {

	const KEY = '';

	/**
	 * Registry entry.
	 *
	 * @return array
	 */
	protected function sdc_entry() {
		return sdc_el_entry( static::KEY );
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'sd-' . static::KEY;
	}

	/**
	 * Title in the panel.
	 *
	 * @return string
	 */
	public function get_title() {
		$e = $this->sdc_entry();
		return "Sylvia's Dream: " . $e['title'];
	}

	/**
	 * Icon in the panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		$e = $this->sdc_entry();
		return ! empty( $e['icon'] ) ? $e['icon'] : 'eicon-heart';
	}

	/**
	 * Panel category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'sylviasdream' );
	}

	/**
	 * Search words.
	 *
	 * @return array
	 */
	public function get_keywords() {
		$e = $this->sdc_entry();
		return array_values( array_unique( array_merge( array( 'sylvia', 'sylviasdream', 'dream' ), preg_split( '/[^a-z]+/', strtolower( static::KEY . ' ' . $e['title'] ), -1, PREG_SPLIT_NO_EMPTY ) ) ) );
	}

	/**
	 * Rendered on the server, previewed live.
	 *
	 * @return bool
	 */
	public function is_reload_preview_required() {
		return false;
	}

	/**
	 * Help link shown under the widget in the panel.
	 *
	 * @return string
	 */
	public function get_custom_help_url() {
		return admin_url( 'index.php#sdc_edit_guide' );
	}

	/**
	 * Fields of the entry for one panel.
	 *
	 * @param string $panel Panel.
	 * @return array
	 */
	protected function sdc_fields( $panel ) {
		$out = array();
		foreach ( (array) $this->sdc_entry()['fields'] as $f ) {
			if ( $panel === $f[1] ) {
				$out[] = $f;
			}
		}
		return $out;
	}

	/**
	 * Add one control from a registry field.
	 *
	 * @param array $f Field.
	 */
	protected function sdc_add_field( $f ) {
		list( $id, , $type, $label ) = $f;
		$default = isset( $f[4] ) ? $f[4] : '';
		$extra   = isset( $f[5] ) ? $f[5] : array();
		switch ( $type ) {
			case 'text':
				$this->add_control( $id, array( 'label' => $label, 'type' => Controls_Manager::TEXT, 'default' => (string) $default, 'label_block' => true ) );
				break;
			case 'textarea':
				$this->add_control( $id, array( 'label' => $label, 'type' => Controls_Manager::TEXTAREA, 'default' => (string) $default, 'rows' => 4 ) );
				break;
			case 'number':
				$this->add_control( $id, array( 'label' => $label, 'type' => Controls_Manager::NUMBER, 'default' => (int) $default, 'min' => 0, 'max' => 9999999 ) );
				break;
			case 'select':
				$options = 'products' === $extra ? sdc_el_product_choices() : (array) $extra;
				$this->add_control( $id, array( 'label' => $label, 'type' => Controls_Manager::SELECT, 'options' => $options, 'default' => (string) $default, 'label_block' => true ) );
				break;
			case 'media':
				$this->add_control( $id, array( 'label' => $label, 'type' => Controls_Manager::MEDIA, 'default' => is_array( $default ) ? $default : array( 'id' => '', 'url' => '' ) ) );
				break;
			case 'note':
				$this->add_control( $id, array( 'type' => Controls_Manager::RAW_HTML, 'raw' => '<p class="sd-el-note">' . esc_html( $label ) . '</p>', 'content_classes' => 'elementor-descriptor' ) );
				break;
			case 'hidden':
				$this->add_control( $id, array( 'type' => Controls_Manager::HIDDEN, 'default' => (string) $default ) );
				break;
			case 'repeater':
				$rep = new Repeater();
				foreach ( (array) $extra['fields'] as $rf ) {
					$args = array( 'label' => $rf[2], 'label_block' => true, 'default' => '' );
					if ( 'select' === $rf[1] ) {
						$args['type']    = Controls_Manager::SELECT;
						$args['options'] = (array) $rf[3];
						$args['default'] = (string) key( (array) $rf[3] );
					} elseif ( 'textarea' === $rf[1] ) {
						$args['type'] = Controls_Manager::TEXTAREA;
					} else {
						$args['type'] = Controls_Manager::TEXT;
					}
					$rep->add_control( $rf[0], $args );
				}
				$this->add_control(
					$id,
					array(
						'label'       => $label,
						'type'        => Controls_Manager::REPEATER,
						'fields'      => $rep->get_controls(),
						'default'     => (array) $default,
						'title_field' => isset( $extra['title_field'] ) ? $extra['title_field'] : '',
					)
				);
				break;
			case 'kept_texts':
				$rep = new Repeater();
				$rep->add_control( 'orig', array( 'type' => Controls_Manager::HIDDEN, 'default' => '' ) );
				$rep->add_control( 'text', array( 'label' => __( 'Words', 'sylviasdream-child' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );
				$this->add_control(
					$id,
					array(
						'label'        => $label,
						'type'         => Controls_Manager::REPEATER,
						'fields'       => $rep->get_controls(),
						'default'      => array(),
						'title_field'  => '{{{ text }}}',
						'item_actions' => array( 'add' => false, 'duplicate' => false, 'remove' => false, 'sort' => false ),
						'prevent_empty' => false,
					)
				);
				break;
			case 'kept_photos':
				$rep = new Repeater();
				$rep->add_control( 'orig', array( 'type' => Controls_Manager::HIDDEN, 'default' => '' ) );
				$rep->add_control( 'photo', array( 'label' => __( 'Picture', 'sylviasdream-child' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'id' => '', 'url' => '' ) ) );
				$this->add_control(
					$id,
					array(
						'label'        => $label,
						'type'         => Controls_Manager::REPEATER,
						'fields'       => $rep->get_controls(),
						'default'      => array(),
						'title_field'  => '{{{ orig.split("/").pop() }}}',
						'item_actions' => array( 'add' => false, 'duplicate' => false, 'remove' => false, 'sort' => false ),
						'prevent_empty' => false,
					)
				);
				break;
		}
	}

	/**
	 * Controls: Texts, Photos, Links and buttons, Settings; Style tab.
	 */
	protected function register_controls() {
		$e      = $this->sdc_entry();
		$panels = array(
			'texts'    => array( __( 'Texts', 'sylviasdream-child' ), __( 'This part has no words to change here.', 'sylviasdream-child' ) ),
			'photos'   => array( __( 'Photos', 'sylviasdream-child' ), __( 'This part has no photos.', 'sylviasdream-child' ) ),
			'links'    => array( __( 'Links and buttons', 'sylviasdream-child' ), __( 'This part has no buttons.', 'sylviasdream-child' ) ),
			'settings' => array( __( 'Settings', 'sylviasdream-child' ), __( 'Nothing else to set for this part.', 'sylviasdream-child' ) ),
		);
		$first  = true;
		foreach ( $panels as $panel => $labels ) {
			$this->start_controls_section( 'sdc_' . $panel, array( 'label' => $labels[0], 'tab' => Controls_Manager::TAB_CONTENT ) );
			if ( $first && ! empty( $e['help'] ) ) {
				$this->add_control( 'sdc_help', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => '<p class="sd-el-help">' . esc_html( $e['help'] ) . '</p>', 'content_classes' => 'elementor-descriptor' ) );
			}
			$first  = false;
			$fields = $this->sdc_fields( $panel );
			if ( 'settings' === $panel ) {
				$fields = array_merge( $fields, $this->sdc_fields( 'hidden' ) );
			}
			$visible = 0;
			foreach ( $fields as $f ) {
				$this->sdc_add_field( $f );
				if ( 'hidden' !== $f[2] ) {
					++$visible;
				}
			}
			if ( ! $visible ) {
				$this->add_control( 'sdc_' . $panel . '_empty', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => '<p class="sd-el-note">' . esc_html( $labels[1] ) . '</p>', 'content_classes' => 'elementor-descriptor' ) );
			}
			$this->end_controls_section();
		}
		$this->sdc_style_controls();
	}

	/**
	 * Style tab: colours and text, buttons, space and background.
	 */
	protected function sdc_style_controls() {
		$this->start_controls_section( 'sdc_style_text', array( 'label' => __( 'Colours and text', 'sylviasdream-child' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control(
			'sdc_align',
			array(
				'label'     => __( 'Line up the text', 'sylviasdream-child' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => __( 'Left', 'sylviasdream-child' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => __( 'Centre', 'sylviasdream-child' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => __( 'Right', 'sylviasdream-child' ), 'icon' => 'eicon-text-align-right' ),
				),
				'selectors' => array( '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};' ),
			)
		);
		$this->add_control( 'sdc_heading_color', array( 'label' => __( 'Heading colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3' => 'color: {{VALUE}};' ) ) );
		if ( class_exists( '\Elementor\Group_Control_Typography' ) ) {
			$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'sdc_heading_typo', 'label' => __( 'Heading letters', 'sylviasdream-child' ), 'selector' => '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3' ) );
		}
		$this->add_control( 'sdc_text_color', array( 'label' => __( 'Text colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'separator' => 'before', 'selectors' => array( '{{WRAPPER}} p, {{WRAPPER}} li, {{WRAPPER}} figcaption' => 'color: {{VALUE}};' ) ) );
		if ( class_exists( '\Elementor\Group_Control_Typography' ) ) {
			$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), array( 'name' => 'sdc_text_typo', 'label' => __( 'Text letters', 'sylviasdream-child' ), 'selector' => '{{WRAPPER}} p, {{WRAPPER}} li' ) );
		}
		$this->add_control( 'sdc_link_color', array( 'label' => __( 'Link colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} a:not(.sd-btn):not(.wp-block-button__link)' => 'color: {{VALUE}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'sdc_style_buttons', array( 'label' => __( 'Buttons', 'sylviasdream-child' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$btn = '{{WRAPPER}} .sd-btn, {{WRAPPER}} .wp-block-button__link';
		$this->add_control( 'sdc_btn_bg', array( 'label' => __( 'Button colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( $btn => 'background-color: {{VALUE}}; border-color: {{VALUE}};' ) ) );
		$this->add_control( 'sdc_btn_color', array( 'label' => __( 'Button words colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( $btn => 'color: {{VALUE}};' ) ) );
		$this->add_control(
			'sdc_btn_radius',
			array(
				'label'      => __( 'Button corners (0 = square)', 'sylviasdream-child' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( $btn => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'sdc_style_space', array( 'label' => __( 'Space and background', 'sylviasdream-child' ), 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'sdc_bg', array( 'label' => __( 'Background colour', 'sylviasdream-child' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} > .elementor-widget-container' => 'background-color: {{VALUE}};' ) ) );
		$this->add_responsive_control(
			'sdc_padding',
			array(
				'label'      => __( 'Space inside the edges', 'sylviasdream-child' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} > .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Clean the widget settings into the values the section renderer expects.
	 *
	 * @param array $s Settings.
	 * @return array
	 */
	protected function sdc_values( $s ) {
		$a = array();
		foreach ( (array) $this->sdc_entry()['fields'] as $f ) {
			list( $id, , $type ) = $f;
			$v = isset( $s[ $id ] ) ? $s[ $id ] : null;
			switch ( $type ) {
				case 'note':
					break;
				case 'media':
					$prefix            = 'image' === $id ? 'image' : $id;
					$a[ $prefix . '_id' ]  = is_array( $v ) && ! empty( $v['id'] ) ? absint( $v['id'] ) : 0;
					$a[ $prefix . '_url' ] = is_array( $v ) && ! empty( $v['url'] ) ? esc_url_raw( (string) $v['url'] ) : '';
					break;
				case 'repeater':
				case 'kept_texts':
				case 'kept_photos':
					$items = array();
					foreach ( is_array( $v ) ? $v : array() as $it ) {
						if ( ! is_array( $it ) ) {
							continue;
						}
						if ( 'kept_photos' === $type ) {
							$items[] = array(
								'orig' => isset( $it['orig'] ) ? (string) $it['orig'] : '',
								'id'   => isset( $it['photo']['id'] ) ? absint( $it['photo']['id'] ) : 0,
							);
							continue;
						}
						$clean = array();
						foreach ( $it as $k => $iv ) {
							if ( is_scalar( $iv ) && '_id' !== $k ) {
								$clean[ $k ] = sanitize_textarea_field( (string) $iv );
							}
						}
						$items[] = $clean;
					}
					$a[ $id ] = $items;
					break;
				case 'hidden':
					$a[ $id ] = is_scalar( $v ) ? (string) $v : '';
					break;
				case 'number':
					$a[ $id ] = absint( $v );
					break;
				case 'textarea':
					$a[ $id ] = is_scalar( $v ) ? sanitize_textarea_field( (string) $v ) : '';
					break;
				default:
					$a[ $id ] = is_scalar( $v ) ? trim( wp_strip_all_tags( (string) $v ) ) : '';
			}
		}
		return $a;
	}

	/**
	 * Output.
	 */
	protected function render() {
		$e = $this->sdc_entry();
		if ( empty( $e['render'] ) || ! function_exists( $e['render'] ) ) {
			return;
		}
		$a = $this->sdc_values( $this->get_settings_for_display() );
		try {
			call_user_func( $e['render'], $a );
		} catch ( \Throwable $err ) {
			if ( sdc_widget_is_editor() ) {
				echo '<p class="sd-el-empty">' . esc_html__( 'This part could not be shown. Please tell your web designer.', 'sylviasdream-child' ) . '</p>';
			}
		}
	}
}

/**
 * Class name for each widget key.
 *
 * @return string[]
 */
function sdc_el_widget_classes() {
	$out = array();
	foreach ( array_keys( sdc_el_registry() ) as $key ) {
		$class = 'SDC_EW_' . str_replace( '-', '_', $key );
		if ( class_exists( $class ) ) {
			$out[] = $class;
		}
	}
	return $out;
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound, Squiz.Commenting.ClassComment.Missing
final class SDC_EW_home_hero extends SDC_Elementor_Widget { const KEY = 'home-hero'; }
final class SDC_EW_highlights extends SDC_Elementor_Widget { const KEY = 'highlights'; }
final class SDC_EW_featured_books extends SDC_Elementor_Widget { const KEY = 'featured-books'; }
final class SDC_EW_meet_sylvia extends SDC_Elementor_Widget { const KEY = 'meet-sylvia'; }
final class SDC_EW_heaven_on_earth extends SDC_Elementor_Widget { const KEY = 'heaven-on-earth'; }
final class SDC_EW_dream_partners extends SDC_Elementor_Widget { const KEY = 'dream-partners'; }
final class SDC_EW_latest_devotionals extends SDC_Elementor_Widget { const KEY = 'latest-devotionals'; }
final class SDC_EW_reader_reviews extends SDC_Elementor_Widget { const KEY = 'reader-reviews'; }
final class SDC_EW_newsletter extends SDC_Elementor_Widget { const KEY = 'newsletter'; }
final class SDC_EW_author_support extends SDC_Elementor_Widget { const KEY = 'author-support'; }
final class SDC_EW_book_showcase extends SDC_Elementor_Widget { const KEY = 'book-showcase'; }
final class SDC_EW_books_grid extends SDC_Elementor_Widget { const KEY = 'books-grid'; }
final class SDC_EW_page_banner extends SDC_Elementor_Widget { const KEY = 'page-banner'; }
final class SDC_EW_keep_exploring extends SDC_Elementor_Widget { const KEY = 'keep-exploring'; }
final class SDC_EW_kept_section extends SDC_Elementor_Widget { const KEY = 'kept-section'; }
final class SDC_EW_site_header extends SDC_Elementor_Widget { const KEY = 'site-header'; }
final class SDC_EW_site_footer extends SDC_Elementor_Widget { const KEY = 'site-footer'; }
final class SDC_EW_logo extends SDC_Elementor_Widget { const KEY = 'logo'; }
final class SDC_EW_menu extends SDC_Elementor_Widget { const KEY = 'menu'; }
final class SDC_EW_social_icons extends SDC_Elementor_Widget { const KEY = 'social-icons'; }
final class SDC_EW_buy_ebook extends SDC_Elementor_Widget { const KEY = 'buy-ebook'; }
final class SDC_EW_amazon_button extends SDC_Elementor_Widget { const KEY = 'amazon-button'; }
final class SDC_EW_contact_form extends SDC_Elementor_Widget { const KEY = 'contact-form'; }
final class SDC_EW_button extends SDC_Elementor_Widget { const KEY = 'button'; }
final class SDC_EW_partner_progress extends SDC_Elementor_Widget { const KEY = 'partner-progress'; }
// phpcs:enable
