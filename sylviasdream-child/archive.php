<?php
/**
 * Category, tag, date and author archives.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php sdc_page_header( wp_strip_all_tags( get_the_archive_title() ), sdc_get( 'blog_eyebrow' ), wp_strip_all_tags( get_the_archive_description() ) ); ?>
	<div class="sd-wrap sd-section">
		<?php if ( have_posts() ) : ?>
			<div class="sd-cards">
				<?php
				while ( have_posts() ) :
					the_post();
					sdc_post_card( 'h2' );
				endwhile;
				?>
			</div>
			<?php sdc_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
