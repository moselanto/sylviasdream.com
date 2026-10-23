<?php
/**
 * Site footer: about blurb and social links, Books and Ministry menus,
 * Policies menu, location, copyright.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

$sdc_about    = sdc_get( 'footer_about' );
$sdc_location = sdc_get( 'footer_location' );
$sdc_locnote  = sdc_get( 'footer_location_note' );
$sdc_holder   = sdc_get( 'copyright_name' );
$sdc_credit   = sdc_get( 'footer_credit' );
$sdc_email    = sdc_get( 'contact_email' );

/**
 * Print a footer menu column only when a menu is assigned.
 *
 * @param string $location Menu location.
 * @param string $title    Column heading.
 * @param string $id       Heading id.
 */
$sdc_footer_menu = static function ( $location, $title, $id ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	?>
	<nav class="sd-footer__col" aria-labelledby="<?php echo esc_attr( $id ); ?>">
		<h2 class="sd-footer__heading" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></h2>
		<?php
		wp_nav_menu( array(
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
		?>
	</nav>
	<?php
};
?>
<footer class="site-footer">
	<div class="sd-wrap">
		<div class="sd-footer__grid">
			<div class="sd-footer__col sd-footer__brand">
				<p class="sd-footer__name"><?php bloginfo( 'name' ); ?></p>
				<?php if ( $sdc_about ) : ?>
					<p><?php echo esc_html( $sdc_about ); ?></p>
				<?php endif; ?>
				<?php sdc_social_links(); ?>
				<?php if ( is_active_sidebar( 'sdc-footer-newsletter' ) ) : ?>
					<div class="sd-footer__newsletter"><?php dynamic_sidebar( 'sdc-footer-newsletter' ); ?></div>
				<?php endif; ?>
			</div>
			<?php
			$sdc_footer_menu( 'footer_books', __( 'Books', 'sylviasdream-child' ), 'sd-f-books' );
			$sdc_footer_menu( 'footer_about', __( 'Ministry', 'sylviasdream-child' ), 'sd-f-about' );
			?>
			<div class="sd-footer__col">
				<?php if ( has_nav_menu( 'footer_legal' ) ) : ?>
					<nav aria-labelledby="sd-f-help">
						<h2 class="sd-footer__heading" id="sd-f-help"><?php esc_html_e( 'Help', 'sylviasdream-child' ); ?></h2>
						<?php
						wp_nav_menu( array(
							'theme_location' => 'footer_legal',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						) );
						?>
					</nav>
				<?php endif; ?>
				<?php if ( $sdc_email && is_email( $sdc_email ) ) : ?>
					<p class="sd-footer__email"><a href="<?php echo esc_url( 'mailto:' . antispambot( $sdc_email ) ); ?>"><?php echo esc_html( antispambot( $sdc_email ) ); ?></a></p>
				<?php endif; ?>
				<?php if ( $sdc_location ) : ?>
					<p class="sd-footer__location"><?php echo esc_html( $sdc_location ); ?><?php if ( $sdc_locnote ) : ?><br><?php echo esc_html( $sdc_locnote ); ?><?php endif; ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="sd-footer__bottom">
			<p>
				&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?>
				<?php echo esc_html( $sdc_holder ? $sdc_holder : get_bloginfo( 'name' ) ); ?>.
				<?php esc_html_e( 'All rights reserved.', 'sylviasdream-child' ); ?>
				<?php if ( $sdc_location ) : ?>
					<span class="sd-footer__sep" aria-hidden="true">&middot;</span> <?php echo esc_html( $sdc_location ); ?>
				<?php endif; ?>
			</p>
			<?php if ( $sdc_credit ) : ?>
				<p><?php echo esc_html( $sdc_credit ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
