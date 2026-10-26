<?php
/**
 * Page not found.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( __( 'Page not found', 'sylviasdream-child' ), __( 'Error 404', 'sylviasdream-child' ), __( 'The page you were looking for has moved or no longer exists. Try a search, or start from one of the links below.', 'sylviasdream-child' ) ); ?>
	<div class="sd-wrap sd-section sd-section--tight">
		<div class="sd-prose">
			<?php get_search_form(); ?>
			<div class="sd-btn-row sd-404-links">
				<a class="sd-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'sylviasdream-child' ); ?></a>
				<?php // 1.8.4: same Books page as the menu (Customizer "Books page link", default /shop-for-books/). ?>
				<a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( sdc_shop_url() ); ?>"><?php esc_html_e( 'Browse the books', 'sylviasdream-child' ); ?></a>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
