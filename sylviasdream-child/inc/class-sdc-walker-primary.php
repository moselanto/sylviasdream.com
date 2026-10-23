<?php
/**
 * Primary menu walker.
 *
 * - Adds an accessible submenu toggle button after top-level items that
 *   have children, and gives each submenu an id for aria-controls.
 * - Mega menu (1.2.0): a top-level item with the CSS class "sd-mega"
 *   (Appearance > Menus > Screen Options > CSS Classes) opens a wide
 *   panel. Its children become columns; a child whose URL is "#" is shown
 *   as a column heading, and that child's own children are the column's
 *   links. Published featured books are added as a "Start here" column.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker for the primary navigation.
 */
class SDC_Walker_Primary extends Walker_Nav_Menu {

	/**
	 * Id of the submenu that the next start_lvl() call opens.
	 *
	 * @var string
	 */
	private $sdc_pending_sub_id = '';

	/**
	 * Is the current top-level item a mega menu?
	 *
	 * @var bool
	 */
	private $sdc_in_mega = false;

	/**
	 * Start an element, then append the toggle button for parents.
	 *
	 * @param string   $output            Output (passed by reference).
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Depth.
	 * @param stdClass $args              Arguments.
	 * @param int      $current_object_id Current object id.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;

		if ( 0 === (int) $depth ) {
			$this->sdc_in_mega = in_array( 'sd-mega', $classes, true ) && in_array( 'menu-item-has-children', $classes, true );
		}

		// Mega menu column heading: a "#" (or empty) custom link directly under the mega item.
		if ( 1 === (int) $depth && $this->sdc_in_mega ) {
			$url = isset( $data_object->url ) ? trim( (string) $data_object->url ) : '';
			if ( '' === $url || '#' === $url ) {
				$title   = apply_filters( 'the_title', $data_object->title, $data_object->ID );
				$output .= '<li class="menu-item sd-mega__col sd-mega__col--heading">';
				$output .= '<span class="sd-mega__heading">' . esc_html( wp_strip_all_tags( $title ) ) . '</span>';
				return;
			}
		}

		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );

		if ( 0 === (int) $depth && in_array( 'menu-item-has-children', $classes, true ) ) {
			$sub_id                   = 'sd-sub-' . (int) $data_object->ID;
			$this->sdc_pending_sub_id = $sub_id;
			$title                    = wp_strip_all_tags( apply_filters( 'the_title', $data_object->title, $data_object->ID ) );
			$output                  .= sprintf(
				'<button type="button" class="sd-subtoggle" aria-expanded="false" aria-controls="%1$s"><span class="screen-reader-text">%2$s</span>%3$s</button>',
				esc_attr( $sub_id ),
				/* translators: %s: menu item title. */
				esc_html( sprintf( __( 'Show %s submenu', 'sylviasdream-child' ), $title ) ),
				sdc_icon( 'chevron' ) // Static SVG markup from the theme.
			);
		}
	}

	/**
	 * Start a submenu list with an id (top level) and the mega panel class.
	 *
	 * @param string   $output Output (passed by reference).
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$id                       = $this->sdc_pending_sub_id;
		$this->sdc_pending_sub_id = '';
		$class                    = 'sub-menu';
		if ( 0 === (int) $depth && $this->sdc_in_mega ) {
			$class .= ' sd-mega__panel';
		}
		$output .= "\n" . '<ul class="' . esc_attr( $class ) . '"' . ( $id ? ' id="' . esc_attr( $id ) . '"' : '' ) . '>' . "\n";
	}

	/**
	 * End a submenu list; the mega panel gets its "Start here" column.
	 *
	 * @param string   $output Output (passed by reference).
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === (int) $depth && $this->sdc_in_mega && function_exists( 'sdc_mega_featured_html' ) ) {
			$output .= sdc_mega_featured_html(); // Escaped inside.
		}
		$output .= "</ul>\n";
	}
}
