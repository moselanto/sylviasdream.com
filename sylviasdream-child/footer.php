<?php
/**
 * Site footer: books, about, contact, social, newsletter and policies.
 *
 * @package SylviasDreamChild
 */
defined( 'ABSPATH' ) || exit;
?>
<footer class="site-footer">
	<div class="container">
		<div class="cols">
			<div>
				<h2><?php bloginfo( 'name' ); ?></h2>
				<p><?php bloginfo( 'description' ); ?></p>
				<?php sdc_social_links(); ?>
			</div>
			<div>
				<h2><?php esc_html_e( 'Books & Resources', 'sylviasdream-child' ); ?></h2>
				<?php wp_nav_menu( array( 'theme_location' => 'footer_books', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
			</div>
			<div>
				<h2><?php esc_html_e( 'About & Ministry', 'sylviasdream-child' ); ?></h2>
				<?php wp_nav_menu( array( 'theme_location' => 'footer_about', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
			</div>
			<div>
				<h2><?php esc_html_e( 'Stay Connected', 'sylviasdream-child' ); ?></h2>
				<?php $sdc_email = sdc_get( 'contact_email' ); ?>
				<?php if ( $sdc_email ) : ?>
					<p><a href="mailto:<?php echo esc_attr( antispambot( $sdc_email ) ); ?>"><?php echo esc_html( antispambot( $sdc_email ) ); ?></a></p>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'sdc-footer-newsletter' ) ) : ?>
					<?php dynamic_sidebar( 'sdc-footer-newsletter' ); ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="legal">
			<?php wp_nav_menu( array( 'theme_location' => 'footer_legal', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Website by Pimofy Digital.', 'sylviasdream-child' ); ?></p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
