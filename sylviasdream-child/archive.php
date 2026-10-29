<?php
/**
 * Category, tag, date and author archives (1.20.0: Journal layout).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
$sdc_eyebrow = sdc_journal_name();
if ( '' === $sdc_eyebrow ) {
	$sdc_eyebrow = sdc_get( 'blog_eyebrow' );
}
$sdc_archive_title = is_category() || is_tag() ? single_term_title( '', false ) : wp_strip_all_tags( get_the_archive_title() );
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( $sdc_archive_title, $sdc_eyebrow, wp_strip_all_tags( get_the_archive_description() ) ); ?>
	<div class="sd-wrap sd-section sd-jlist">
		<?php sdc_journal_listing( false ); ?>
	</div>
	<?php sdc_journal_cta(); ?>
</main>
<?php
get_footer();
