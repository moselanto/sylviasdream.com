<?php
/**
 * Nothing found message.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="sd-prose sd-none">
	<h2><?php esc_html_e( 'Nothing found', 'sylviasdream-child' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No results matched your search. Please try different words.', 'sylviasdream-child' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'There is nothing here yet. Please try a search.', 'sylviasdream-child' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
