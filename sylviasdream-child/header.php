<?php
/**
 * Site header: optional utility bar, sticky header with brand, primary
 * menu (desktop dropdowns / mobile slide-in drawer), search, cart, Give.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

$sdc_tagline    = sdc_get( 'tagline' );
$sdc_give_label = sdc_get( 'give_label' );
$sdc_give_url   = sdc_link( sdc_get( 'give_url' ) );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to main content', 'sylviasdream-child' ); ?></a>

<?php
if ( sdc_get_bool( 'show_utility_bar' ) ) :
	$sdc_u1       = sdc_get( 'utility_text_1' );
	$sdc_u2       = sdc_get( 'utility_text_2' );
	$sdc_ulabel   = sdc_get( 'utility_link_label' );
	$sdc_uurl     = sdc_get( 'utility_link_url' ) ? sdc_link( sdc_get( 'utility_link_url' ) ) : sdc_downloads_url();
	if ( $sdc_u1 || $sdc_u2 || ( $sdc_ulabel && $sdc_uurl ) ) :
		?>
		<div class="sd-utility">
			<div class="sd-wrap sd-utility__inner">
				<p class="sd-utility__notes">
					<?php if ( $sdc_u1 ) : ?>
						<span><?php echo esc_html( $sdc_u1 ); ?></span>
					<?php endif; ?>
					<?php if ( $sdc_u2 ) : ?>
						<span class="sd-hide-phone"><?php echo esc_html( $sdc_u2 ); ?></span>
					<?php endif; ?>
				</p>
				<div class="sd-utility__end">
				<?php sdc_language_select( 'sd-lang-bar', 'bar' ); ?>
				<?php if ( $sdc_ulabel && $sdc_uurl ) : ?>
					<a class="sd-utility__link" href="<?php echo esc_url( $sdc_uurl ); ?>"><?php echo esc_html( $sdc_ulabel ); ?></a>
				<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	endif;
endif;
?>

<header class="site-header" id="sd-header">
	<div class="site-header__bar">
		<div class="sd-wrap site-header__inner">
			<a class="sd-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( has_custom_logo() ) : ?>
					<?php sdc_header_logo(); ?>
				<?php else : ?>
					<svg class="sd-brand__mark" aria-hidden="true" focusable="false" width="42" height="42" viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" fill="none" stroke="currentColor" stroke-width="2"/><path d="M24 11c3 6 9 8 9 15a9 9 0 0 1-18 0c0-7 6-9 9-15z" fill="currentColor" opacity=".9"/><path d="M14 36h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
					<span class="sd-brand__text">
						<span class="sd-brand__name notranslate" translate="no"><?php bloginfo( 'name' ); ?></span>
						<?php if ( $sdc_tagline ) : ?>
							<span class="sd-brand__tagline"><?php echo esc_html( $sdc_tagline ); ?></span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</a>

			<nav id="sd-primary-nav" class="sd-nav" aria-label="<?php esc_attr_e( 'Main', 'sylviasdream-child' ); ?>">
				<div class="sd-nav__head">
					<span class="sd-nav__title"><?php esc_html_e( 'Menu', 'sylviasdream-child' ); ?></span>
					<button type="button" class="sd-iconbtn sd-nav__close" data-sd-close>
						<?php sdc_the_icon( 'close' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'sylviasdream-child' ); ?></span>
					</button>
				</div>
				<?php sdc_language_select( 'sd-lang-drawer', 'drawer' ); ?>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'sd-menu',
					'menu_id'        => 'sd-primary-menu',
					'depth'          => 3,
					'fallback_cb'    => false,
					'walker'         => new SDC_Walker_Primary(),
				) );
				?>
				<?php if ( $sdc_give_url && sdc_get( 'mobile_cta_label' ) ) : ?>
					<div class="sd-nav__cta">
						<a class="sd-btn sd-btn--block" href="<?php echo esc_url( $sdc_give_url ); ?>"><?php echo esc_html( sdc_get( 'mobile_cta_label' ) ); ?></a>
					</div>
				<?php endif; ?>
			</nav>

			<div class="sd-actions">
				<button type="button" class="sd-iconbtn sd-search-toggle" aria-expanded="false" aria-controls="sd-search-panel">
					<?php sdc_the_icon( 'search' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Search the site', 'sylviasdream-child' ); ?></span>
				</button>
				<?php echo sdc_cart_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
				<?php if ( $sdc_give_label && $sdc_give_url ) : ?>
					<a class="sd-btn sd-btn--sm sd-give" href="<?php echo esc_url( $sdc_give_url ); ?>"><?php sdc_the_icon( 'heart' ); ?><span><?php echo esc_html( $sdc_give_label ); ?></span></a>
				<?php endif; ?>
				<button type="button" class="sd-iconbtn sd-nav-toggle" aria-expanded="false" aria-controls="sd-primary-nav">
					<span class="sd-burger" aria-hidden="true"><span></span><span></span><span></span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'sylviasdream-child' ); ?></span>
				</button>
			</div>
		</div>
	</div>
	<div id="sd-search-panel" class="sd-search-panel" hidden>
		<div class="sd-wrap">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>
<div class="sd-overlay" data-sd-close hidden></div>
