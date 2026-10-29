<?php
/**
 * Single reflection / post (1.20.0: Journal article layout).
 * Hero header (topic, title, date, reading time), featured image, readable
 * text column, share links, author box, previous / next, related posts and
 * the books / newsletter call to action. Post content is printed unchanged.
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
		$sdc_cat      = sdc_post_main_category();
		$sdc_is_post  = ( 'post' === get_post_type() );
		$sdc_journal  = sdc_journal_name();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'sd-article sd-jarticle' ); ?>>
			<header class="sd-jhero">
				<div class="sd-wrap sd-jhero__inner">
					<nav class="sd-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sylviasdream-child' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'sylviasdream-child' ); ?></a>
						<?php if ( $sdc_is_post && $sdc_journal ) : ?>
							<span aria-hidden="true">&rsaquo;</span>
							<a href="<?php echo esc_url( sdc_journal_url() ); ?>"><?php echo esc_html( $sdc_journal ); ?></a>
						<?php endif; ?>
						<?php if ( $sdc_cat ) : ?>
							<span aria-hidden="true">&rsaquo;</span>
							<a href="<?php echo esc_url( get_category_link( $sdc_cat ) ); ?>"><?php echo esc_html( $sdc_cat->name ); ?></a>
						<?php endif; ?>
					</nav>
					<?php if ( $sdc_cat ) : ?>
						<div class="sd-jhero__pill"><?php sdc_journal_pill(); ?></div>
					<?php elseif ( sdc_get( 'blog_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'blog_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h1 class="sd-jhero__title"><?php the_title(); ?></h1>
					<div class="sd-jhero__meta">
						<span class="sd-jhero__author"><?php echo esc_html( get_the_author() ); ?></span>
						<?php sdc_journal_meta(); ?>
					</div>
				</div>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="sd-wrap sd-jarticle__media">
					<?php the_post_thumbnail( 'sd-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					<?php
					$sdc_caption = get_the_post_thumbnail_caption();
					if ( $sdc_caption ) :
						?>
						<figcaption><?php echo esc_html( $sdc_caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>
			<div class="sd-wrap sd-jarticle__wrap">
				<div class="entry-content sd-prose sd-jprose">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<nav class="sd-pagelinks" aria-label="' . esc_attr__( 'Pages', 'sylviasdream-child' ) . '">',
						'after'  => '</nav>',
					) );
					?>
				</div>
				<div class="sd-jarticle__after">
					<?php
					$sdc_tags = get_the_tag_list( '<ul class="sd-tags"><li class="sd-pill">', '</li><li class="sd-pill">', '</li></ul>' );
					if ( $sdc_tags && ! is_wp_error( $sdc_tags ) ) {
						echo wp_kses_post( $sdc_tags );
					}
					sdc_share_links();
					if ( $sdc_is_post ) {
						sdc_author_box();
						sdc_journal_prevnext();
					}
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>
			</div>
		</article>
		<?php
		if ( $sdc_is_post ) {
			sdc_related_posts();
		}
	endwhile;
	?>
	<?php sdc_journal_cta(); ?>
</main>
<?php
get_footer();
