<?php
/**
 * Journal (posts page) and general fallback.
 * 1.20.0: latest post as a hero on the first page, card grid with featured
 * image (brand placeholder when none), topic pill, date, reading time and
 * excerpt, topic chips and numbered pagination.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();

$sdc_posts_page = (int) get_option( 'page_for_posts' );
$sdc_title      = ( is_home() && $sdc_posts_page ) ? get_the_title( $sdc_posts_page ) : sdc_get( 'blog_title' );
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( $sdc_title ? $sdc_title : get_bloginfo( 'name' ), sdc_get( 'blog_eyebrow' ), sdc_get( 'blog_intro' ) ); ?>
	<div class="sd-wrap sd-section sd-jlist">
		<?php sdc_journal_listing( is_home() && ! is_paged() ); ?>
	</div>
	<?php sdc_journal_cta(); ?>
</main>
<?php
get_footer();
