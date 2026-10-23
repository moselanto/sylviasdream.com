<?php
/**
 * Page template: elegant page header and a readable 760px column.
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
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php sdc_page_header( get_the_title(), '', has_excerpt() ? get_the_excerpt() : '' ); ?>
			<div class="sd-wrap sd-section sd-section--tight">
				<div class="entry-content sd-prose">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<nav class="sd-page-links" aria-label="' . esc_attr__( 'Page', 'sylviasdream-child' ) . '">',
						'after'  => '</nav>',
					) );
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
