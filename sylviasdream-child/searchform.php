<?php
/**
 * Search form.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

$sdc_search_id = wp_unique_id( 'sd-search-' );
?>
<form role="search" method="get" class="sd-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $sdc_search_id ); ?>"><?php esc_html_e( 'Search books, devotionals and pages', 'sylviasdream-child' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $sdc_search_id ); ?>" class="sd-searchform__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search books, devotionals and pages', 'sylviasdream-child' ); ?>" autocomplete="off">
	<button type="submit" class="sd-btn"><?php esc_html_e( 'Search', 'sylviasdream-child' ); ?></button>
</form>
