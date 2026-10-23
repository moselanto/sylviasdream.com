<?php
/**
 * Primary menu walker: adds an accessible submenu toggle button after
 * top-level items that have children, and gives each submenu an id so
 * the button can point to it with aria-controls.
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
	 * Start an element, then append the toggle button for parents.
	 *
	 * @param string   $output            Output (passed by reference).
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Depth.
	 * @param stdClass $args              Arguments.
	 * @param int      $current_object_id Current object id.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );

		$classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
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
	 * Start a submenu list with an id.
	 *
	 * @param string   $output Output (passed by reference).
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$id                       = $this->sdc_pending_sub_id;
		$this->sdc_pending_sub_id = '';
		$output                  .= "\n" . '<ul class="sub-menu"' . ( $id ? ' id="' . esc_attr( $id ) . '"' : '' ) . '>' . "\n";
	}
}
