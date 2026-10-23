<?php
/**
 * Page template: page header and a readable 760px column. Blocks set to
 * Wide (1200px) or Full width break out of the column. When a page starts
 * with its own banner (Page header pattern or a Cover block), the theme's
 * page header is skipped so there is only one h1.
 * Cart, checkout and account pages get a wider column.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		$sdc_own_header = sdc_content_starts_with_banner( get_the_content() );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( $sdc_own_header ? 'sd-has-banner' : '' ); ?>>
			<?php
			if ( ! $sdc_own_header ) {
				sdc_page_header( get_the_title(), '', has_excerpt() ? get_the_excerpt() : '' );
			}
			?>
			<div class="sd-wrap<?php echo $sdc_own_header ? '' : ' sd-section sd-section--tight'; ?> sd-page-body">
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="sd-page-links" aria-label="' . esc_attr__( 'Page', 'sylviasdream-child' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
