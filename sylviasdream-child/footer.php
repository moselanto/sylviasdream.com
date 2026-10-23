<?php
/**
 * Site footer (1.1.0): matches prototype v3. Brand column with about text,
 * social icons and newsletter line; Books, Ministry and Help columns from
 * the footer menus; copyright, credit and payment badges row.
 * Every text comes from Appearance > Customize > Footer or Appearance > Menus.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'sdc_footer_menu' ) ) {
	/**
	 * Print a footer menu column only when a menu is assigned.
	 *
	 * @param string $location Menu location.
	 * @param string $title    Column heading.
	 * @param string $id       Heading id.
	 */
	function sdc_footer_menu( $location, $title, $id ) {
		if ( ! has_nav_menu( $location ) ) {
			return;
		}
		?>
		<nav class="sd-footer__col" aria-labelledby="<?php echo esc_attr( $id ); ?>">
			<h2 class="sd-footer__heading" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => $location,
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<?php
	}
}

$sdc_about     = sdc_get( 'footer_about' );
$sdc_location  = sdc_get( 'footer_location' );
$sdc_locnote   = sdc_get( 'footer_location_note' );
$sdc_holder    = sdc_get( 'copyright_name' );
$sdc_credit    = sdc_get( 'footer_credit' );
$sdc_email     = sdc_get( 'contact_email' );
$sdc_news      = sdc_get( 'footer_news_title' );
$sdc_contact   = sdc_link( sdc_get( 'contact_url' ) );
$sdc_badges    = array_filter( array_map( 'trim', explode( ',', sdc_get( 'footer_payment_badges' ) ) ) );
?>
<?php if ( ! is_page_template( 'template-canvas.php' ) ) : ?>
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
				<?php elseif ( $sdc_news ) : ?>
					<div class="sd-footer__newsletter">
						<p class="sd-footer__news-title"><?php echo esc_html( $sdc_news ); ?></p>
						<?php if ( shortcode_exists( 'jetpack_subscription_form' ) ) : ?>
							<?php echo do_shortcode( '[jetpack_subscription_form show_subscribers_total="false" button_on_newline="false" show_only_email_and_button="true"]' ); ?>
						<?php elseif ( $sdc_contact && sdc_get( 'contact_cta_label' ) ) : ?>
							<a class="sd-footer__news-link" href="<?php echo esc_url( $sdc_contact ); ?>"><?php echo esc_html( sdc_get( 'contact_cta_label' ) ); ?> <span aria-hidden="true">&rarr;</span></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			sdc_footer_menu( 'footer_books', __( 'Books', 'sylviasdream-child' ), 'sd-f-books' );
			sdc_footer_menu( 'footer_about', __( 'Ministry', 'sylviasdream-child' ), 'sd-f-about' );
			?>
			<div class="sd-footer__col">
				<?php if ( has_nav_menu( 'footer_legal' ) ) : ?>
					<nav aria-labelledby="sd-f-help">
						<h2 class="sd-footer__heading" id="sd-f-help"><?php esc_html_e( 'Help', 'sylviasdream-child' ); ?></h2>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer_legal',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php else : ?>
					<h2 class="sd-footer__heading"><?php esc_html_e( 'Help', 'sylviasdream-child' ); ?></h2>
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
				<?php if ( $sdc_credit ) : ?>
					<span class="sd-footer__credit"><?php echo esc_html( $sdc_credit ); ?></span>
				<?php endif; ?>
			</p>
			<?php if ( $sdc_badges ) : ?>
				<ul class="sd-paybadges" aria-label="<?php esc_attr_e( 'Payment methods', 'sylviasdream-child' ); ?>">
					<?php foreach ( $sdc_badges as $sdc_badge ) : ?>
						<li><?php echo esc_html( $sdc_badge ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
