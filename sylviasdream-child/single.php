<?php
/**
 * Single devotional / post.
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
		$sdc_cats = get_the_category_list( ', ' );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'sd-article' ); ?>>
			<header class="sd-pagehead sd-pagehead--post">
				<div class="sd-wrap sd-pagehead__inner">
					<?php if ( sdc_get( 'blog_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'blog_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h1 class="sd-pagehead__title"><?php the_title(); ?></h1>
					<p class="sd-meta">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<?php if ( $sdc_cats ) : ?>
							<span aria-hidden="true">&middot;</span> <?php echo wp_kses_post( $sdc_cats ); ?>
						<?php endif; ?>
					</p>
				</div>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="sd-wrap sd-article__media">
					<?php the_post_thumbnail( 'sd-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="sd-wrap sd-section sd-section--tight">
				<div class="entry-content sd-prose">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
				<?php
				$sdc_tags = get_the_tag_list( '<ul class="sd-tags"><li class="sd-pill">', '</li><li class="sd-pill">', '</li></ul>' );
				if ( $sdc_tags && ! is_wp_error( $sdc_tags ) ) {
					echo '<div class="sd-prose">' . wp_kses_post( $sdc_tags ) . '</div>';
				}
				?>
				<div class="sd-prose">
					<?php
					the_post_navigation( array(
						'prev_text'          => '<small>' . esc_html__( 'Previous', 'sylviasdream-child' ) . '</small><span>%title</span>',
						'next_text'          => '<small>' . esc_html__( 'Next', 'sylviasdream-child' ) . '</small><span>%title</span>',
						'screen_reader_text' => esc_html__( 'More reflections', 'sylviasdream-child' ),
						'class'              => 'sd-prevnext',
					) );
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
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
