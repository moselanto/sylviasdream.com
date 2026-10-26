<?php
/**
 * Language switcher (1.8.0): English, Français, Español, Bahasa Indonesia.
 * The site is written in English; other languages are shown with Google's
 * automatic website translation. Google's script loads only after a visitor
 * picks another language (privacy and speed), and the choice is remembered in
 * the standard "googtrans" cookie. Editors switch it off in
 * Appearance > Customize > Sylvia's Dream > Header and menu.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Languages offered: code => name in that language.
 *
 * @return array<string,string>
 */
function sdc_languages() {
	return (array) apply_filters(
		'sdc_languages',
		array(
			'en' => 'English',
			'fr' => 'Français',
			'es' => 'Español',
			'id' => 'Bahasa Indonesia',
		)
	);
}

/**
 * Is the switcher on?
 *
 * @return bool
 */
function sdc_language_switcher_enabled() {
	return function_exists( 'sdc_get_bool' ) ? sdc_get_bool( 'show_language_switcher' ) : true;
}

/**
 * Print a language select.
 *
 * @param string $id      Element id.
 * @param string $context "bar" or "drawer".
 */
function sdc_language_select( $id, $context = 'bar' ) {
	if ( ! sdc_language_switcher_enabled() ) {
		return;
	}
	?>
	<div class="sd-lang sd-lang--<?php echo esc_attr( $context ); ?> notranslate" translate="no">
		<label class="sd-lang__label" for="<?php echo esc_attr( $id ); ?>">
			<?php sdc_the_icon( 'globe' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Language', 'sylviasdream-child' ); ?></span>
			<?php if ( 'drawer' === $context ) : ?>
				<span class="sd-lang__title" aria-hidden="true"><?php esc_html_e( 'Language', 'sylviasdream-child' ); ?></span>
			<?php endif; ?>
		</label>
		<select id="<?php echo esc_attr( $id ); ?>" class="sd-lang__select" data-sd-lang>
			<?php foreach ( sdc_languages() as $code => $name ) : ?>
				<option value="<?php echo esc_attr( $code ); ?>" lang="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
}

/**
 * Script for the switcher (tiny, deferred).
 */
function sdc_language_assets() {
	if ( ! sdc_language_switcher_enabled() || is_admin() ) {
		return;
	}
	wp_enqueue_script(
		'sylviasdream-child-lang',
		SYLVIASDREAM_CHILD_URI . '/assets/js/lang.js',
		array(),
		sdc_asset_version( 'assets/js/lang.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
	wp_localize_script(
		'sylviasdream-child-lang',
		'sdLang',
		array(
			'codes' => array_keys( sdc_languages() ),
			'base'  => 'en',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sdc_language_assets', 25 );

/**
 * 1.8.1: start Google's translator as early as possible for visitors who chose
 * another language (connection warmed up and script requested from the <head>,
 * instead of after the page has loaded). English visitors load nothing.
 */
function sdc_language_head_boot() {
	if ( ! sdc_language_switcher_enabled() || is_admin() ) {
		return;
	}
	$codes = wp_json_encode( array_keys( sdc_languages() ) );
	?>
<script id="sd-lang-boot">
(function(){var m=document.cookie.match(/(?:^|;\s*)googtrans=\/[a-z-]+\/([a-zA-Z-]+)/),c=m?m[1]:'en',codes=<?php echo $codes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON of fixed codes. ?>;
if(c==='en'||codes.indexOf(c)<0){return;}
var d=document,h=d.documentElement;h.classList.add('sd-translated');
['https://translate.google.com','https://translate.googleapis.com','https://translate-pa.googleapis.com','https://www.gstatic.com'].forEach(function(u){var l=d.createElement('link');l.rel='preconnect';l.href=u;l.crossOrigin='';d.head.appendChild(l);});
window.sdGoogleInit=function(){var run=function(){var el=d.getElementById('sd-gt');if(!el){el=d.createElement('div');el.id='sd-gt';el.hidden=true;d.body.appendChild(el);}
if(window.google&&google.translate){new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:codes.join(','),autoDisplay:false},'sd-gt');}};
if(d.body){run();}else{d.addEventListener('DOMContentLoaded',run);}};
var s=d.createElement('script');s.src='https://translate.google.com/translate_a/element.js?cb=sdGoogleInit';s.async=true;d.head.appendChild(s);})();
</script>
	<?php
}
add_action( 'wp_head', 'sdc_language_head_boot', 1 );
