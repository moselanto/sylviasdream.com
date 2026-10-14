<?php
/**
 * [sd_partner_progress] and [sd_charity_reg] shortcodes.
 *
 * The partner count is entered by Sylvia in Settings > Sylvia's Dream.
 * Existing Stripe subscriptions are never touched by this plugin.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Progress bar toward the Dream Partner goal.
 *
 * @return string
 */
function sd_core_partner_progress() {
	$current = (int) sd_core_opt( 'partners_current' );
	$goal    = max( 1, (int) sd_core_opt( 'partners_goal' ) );
	$pct     = min( 100, (int) round( $current / $goal * 100 ) );
	/* translators: 1: current partners, 2: goal. */
	$label = sprintf( __( '%1$d of %2$d Dream Partners', 'sylviasdream-core' ), $current, $goal );
	ob_start();
	?>
	<div class="sd-progress">
		<div class="sd-progress__bar" role="progressbar" aria-valuemin="0" aria-valuemax="<?php echo esc_attr( $goal ); ?>" aria-valuenow="<?php echo esc_attr( $current ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
			<div class="sd-progress__fill" style="width:<?php echo esc_attr( $pct ); ?>%"></div>
		</div>
		<p class="sd-progress__label"><?php echo esc_html( $label ); ?></p>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'sd_partner_progress', 'sd_core_partner_progress' );

/**
 * Charity registration line (blank until confirmed).
 *
 * @return string
 */
function sd_core_charity_reg() {
	$reg = (string) sd_core_opt( 'charity_reg' );
	if ( '' === $reg ) {
		return '';
	}
	/* translators: 1: charity name, 2: registration number. */
	return '<p class="sd-charity-reg">' . esc_html( sprintf( __( '%1$s, registration no. %2$s', 'sylviasdream-core' ), (string) sd_core_opt( 'charity_name' ), $reg ) ) . '</p>';
}
add_shortcode( 'sd_charity_reg', 'sd_core_charity_reg' );
