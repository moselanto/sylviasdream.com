<?php
/**
 * Home page. Every section is driven by Customizer settings or live
 * content (WooCommerce products, posts). Empty sections are skipped:
 * nothing is shown as a placeholder.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

// 1.1.0: when the Home page has its own block content (built from the
// "Sylvia's Dream" patterns), show only that, full width. Otherwise fall
// back to the Customizer-driven 1.0.0 sections below.
if ( function_exists( 'sdc_front_page_has_blocks' ) && sdc_front_page_has_blocks() ) {
	get_header();
	?>
	<main id="main-content" class="site-main sd-builder" tabindex="-1">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<?php
		endwhile;
		?>
	</main>
	<?php
	get_footer();
	return;
}

get_header();

$sdc_has_wc   = class_exists( 'WooCommerce' ) && function_exists( 'sdc_home_products' );
$sdc_products = $sdc_has_wc ? sdc_home_products( (int) sdc_get( 'books_count' ) ) : array();

// Hero visual: chosen image, otherwise up to three featured covers.
$sdc_hero_image  = sdc_get_image( 'hero_image' );
$sdc_hero_covers = array();
if ( ! $sdc_hero_image ) {
	foreach ( $sdc_products as $sdc_p ) {
		if ( $sdc_p->get_image_id() ) {
			$sdc_hero_covers[] = $sdc_p;
		}
		if ( count( $sdc_hero_covers ) >= 3 ) {
			break;
		}
	}
}
$sdc_cta_url = sdc_get( 'hero_cta_url' ) ? sdc_link( sdc_get( 'hero_cta_url' ) ) : sdc_shop_url();
?>
<main id="main-content" class="site-main site-main--home" tabindex="-1">

	<section class="sd-hero<?php echo ( $sdc_hero_image || $sdc_hero_covers ) ? ' sd-hero--media' : ''; ?>" aria-labelledby="sd-hero-title">
		<div class="sd-wrap sd-hero__inner">
			<div class="sd-hero__text">
				<?php if ( sdc_get( 'hero_eyebrow' ) ) : ?>
					<p class="sd-eyebrow"><?php echo esc_html( wp_specialchars_decode( sdc_get( 'hero_eyebrow' ) ) ); ?></p>
				<?php endif; ?>
				<h1 id="sd-hero-title"><?php echo esc_html( sdc_get( 'hero_title' ) ? sdc_get( 'hero_title' ) : get_bloginfo( 'name' ) ); ?></h1>
				<?php if ( sdc_get( 'hero_text' ) ) : ?>
					<p class="sd-lead"><?php echo esc_html( sdc_get( 'hero_text' ) ); ?></p>
				<?php endif; ?>
				<?php if ( sdc_get( 'hero_quote' ) ) : ?>
					<blockquote class="sd-hero__quote"><p><?php echo esc_html( sdc_get( 'hero_quote' ) ); ?></p></blockquote>
				<?php endif; ?>
				<div class="sd-btn-row">
					<?php if ( sdc_get( 'hero_cta_label' ) ) : ?>
						<a class="sd-btn" href="<?php echo esc_url( $sdc_cta_url ); ?>"><?php echo esc_html( sdc_get( 'hero_cta_label' ) ); ?></a>
					<?php endif; ?>
					<?php if ( sdc_get( 'hero_cta2_label' ) && sdc_get( 'hero_cta2_url' ) ) : ?>
						<a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( sdc_link( sdc_get( 'hero_cta2_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'hero_cta2_label' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $sdc_hero_image ) : ?>
				<div class="sd-hero__media">
					<div class="sd-hero__single"><?php echo wp_get_attachment_image( $sdc_hero_image, 'sd-cover', false, array( 'fetchpriority' => 'high', 'loading' => 'eager', 'decoding' => 'async' ) ); ?></div>
				</div>
			<?php elseif ( $sdc_hero_covers ) : ?>
				<div class="sd-hero__media">
					<ul class="sd-hero__covers sd-hero__covers--<?php echo esc_attr( (string) count( $sdc_hero_covers ) ); ?>">
						<?php foreach ( $sdc_hero_covers as $sdc_i => $sdc_p ) : ?>
							<li class="sd-hero__cover">
								<a href="<?php echo esc_url( $sdc_p->get_permalink() ); ?>">
									<?php
									echo wp_get_attachment_image(
										$sdc_p->get_image_id(),
										'woocommerce_thumbnail',
										false,
										array(
											'alt'           => $sdc_p->get_name(),
											'loading'       => 0 === $sdc_i ? 'eager' : 'lazy',
											'fetchpriority' => 0 === $sdc_i ? 'high' : 'auto',
										)
									);
									?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	$sdc_strip_icons = array( 1 => 'book', 2 => 'download', 3 => 'globe', 4 => 'heart' );
	$sdc_strip       = array();
	foreach ( $sdc_strip_icons as $sdc_n => $sdc_icon ) {
		if ( sdc_get( 'strip_' . $sdc_n . '_title' ) ) {
			$sdc_strip[ $sdc_n ] = $sdc_icon;
		}
	}
	if ( $sdc_strip ) :
		?>
		<section class="sd-strip" aria-label="<?php esc_attr_e( 'Why readers choose these books', 'sylviasdream-child' ); ?>">
			<ul class="sd-wrap sd-strip__list">
				<?php foreach ( $sdc_strip as $sdc_n => $sdc_icon ) : ?>
					<li class="sd-strip__item">
						<span class="sd-strip__icon"><?php sdc_the_icon( $sdc_icon ); ?></span>
						<span>
							<strong><?php echo esc_html( sdc_strip_title( $sdc_n ) ); ?></strong>
							<?php if ( sdc_get( 'strip_' . $sdc_n . '_text' ) ) : ?>
								<span><?php echo esc_html( sdc_get( 'strip_' . $sdc_n . '_text' ) ); ?></span>
							<?php endif; ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( $sdc_products ) : ?>
		<section class="sd-section" aria-labelledby="sd-books-title">
			<div class="sd-wrap">
				<div class="sd-section__head">
					<?php if ( sdc_get( 'books_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'books_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 id="sd-books-title"><?php echo esc_html( sdc_get( 'books_title' ) ); ?></h2>
					<?php if ( sdc_get( 'books_text' ) ) : ?>
						<p class="sd-lead"><?php echo esc_html( sdc_get( 'books_text' ) ); ?></p>
					<?php endif; ?>
				</div>
				<div class="woocommerce sd-home-books">
					<?php
					wc_set_loop_prop( 'columns', 4 );
					wc_set_loop_prop( 'name', 'sd-home' );
					woocommerce_product_loop_start();
					foreach ( $sdc_products as $sdc_p ) {
						$post_object = get_post( $sdc_p->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
						setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
						wc_get_template_part( 'content', 'product' );
					}
					woocommerce_product_loop_end();
					wp_reset_postdata();
					wc_reset_loop();
					?>
				</div>
				<?php if ( sdc_get( 'books_link_label' ) ) : ?>
					<p class="sd-center sd-section__more"><a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( sdc_shop_url() ); ?>"><?php echo esc_html( sdc_get( 'books_link_label' ) ); ?></a></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$sdc_about_img = sdc_get_image( 'about_image' );
	if ( sdc_get( 'about_title' ) || sdc_get( 'about_text' ) ) :
		?>
		<section class="sd-section sd-section--white" aria-labelledby="sd-about-title">
			<div class="sd-wrap sd-split<?php echo $sdc_about_img ? '' : ' sd-split--text'; ?>">
				<?php if ( $sdc_about_img ) : ?>
					<div class="sd-photo sd-photo--portrait"><?php echo wp_get_attachment_image( $sdc_about_img, 'large', false, array( 'loading' => 'lazy' ) ); ?></div>
				<?php endif; ?>
				<div>
					<?php if ( sdc_get( 'about_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'about_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 id="sd-about-title"><?php echo esc_html( sdc_get( 'about_title' ) ); ?></h2>
					<?php if ( sdc_get( 'about_text' ) ) : ?>
						<p class="sd-lead"><?php echo esc_html( sdc_get( 'about_text' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sdc_get( 'about_text_2' ) ) : ?>
						<p><?php echo esc_html( sdc_get( 'about_text_2' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sdc_get( 'about_cta_label' ) && sdc_get( 'about_cta_url' ) ) : ?>
						<div class="sd-btn-row"><a class="sd-btn" href="<?php echo esc_url( sdc_link( sdc_get( 'about_cta_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'about_cta_label' ) ); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$sdc_hoe_img = sdc_get_image( 'hoe_image' );
	if ( sdc_get( 'hoe_text' ) ) :
		?>
		<section class="sd-band<?php echo $sdc_hoe_img ? ' sd-band--image' : ''; ?>" aria-labelledby="sd-hoe-title">
			<?php if ( $sdc_hoe_img ) : ?>
				<?php echo wp_get_attachment_image( $sdc_hoe_img, 'sd-hero', false, array( 'class' => 'sd-band__bg', 'alt' => '', 'loading' => 'lazy' ) ); ?>
			<?php endif; ?>
			<div class="sd-wrap sd-band__inner">
				<h2 class="sd-eyebrow sd-band__eyebrow" id="sd-hoe-title"><?php echo esc_html( sdc_get( 'hoe_eyebrow' ) ? sdc_get( 'hoe_eyebrow' ) : __( 'Heaven on Earth', 'sylviasdream-child' ) ); ?></h2>
				<p class="sd-band__statement"><?php echo esc_html( sdc_get( 'hoe_text' ) ); ?></p>
				<?php if ( sdc_get( 'hoe_cta_label' ) && sdc_get( 'hoe_cta_url' ) ) : ?>
					<div class="sd-btn-row sd-btn-row--center">
						<a class="sd-btn sd-btn--light" href="<?php echo esc_url( sdc_link( sdc_get( 'hoe_cta_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'hoe_cta_label' ) ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$sdc_give_url = sdc_link( sdc_get( 'give_url' ) );
	if ( sdc_get( 'give_title' ) ) :
		?>
		<section class="sd-section sd-section--tint" aria-labelledby="sd-give-title">
			<div class="sd-wrap sd-split sd-split--give">
				<div>
					<?php if ( sdc_get( 'give_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'give_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 id="sd-give-title"><?php echo esc_html( sdc_get( 'give_title' ) ); ?></h2>
					<?php if ( sdc_get( 'give_text' ) ) : ?>
						<p class="sd-lead"><?php echo esc_html( sdc_get( 'give_text' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sdc_get( 'give_cta_label' ) && $sdc_give_url ) : ?>
						<div class="sd-btn-row"><a class="sd-btn" href="<?php echo esc_url( $sdc_give_url ); ?>"><?php echo esc_html( sdc_get( 'give_cta_label' ) ); ?></a></div>
					<?php endif; ?>
				</div>
				<?php if ( sdc_get_bool( 'give_show_progress' ) && shortcode_exists( 'sd_partner_progress' ) ) : ?>
					<div class="sd-card sd-give-card"><?php echo do_shortcode( '[sd_partner_progress]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode escapes its own output. ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	if ( sdc_get_bool( 'show_latest_posts' ) ) :
		$sdc_posts = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $sdc_posts->have_posts() ) :
			$sdc_blog_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : '';
			?>
			<section class="sd-section" aria-labelledby="sd-posts-title">
				<div class="sd-wrap">
					<div class="sd-section__head">
						<?php if ( sdc_get( 'posts_eyebrow' ) ) : ?>
							<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'posts_eyebrow' ) ); ?></p>
						<?php endif; ?>
						<h2 id="sd-posts-title"><?php echo esc_html( sdc_get( 'posts_title' ) ); ?></h2>
					</div>
					<div class="sd-cards">
						<?php
						while ( $sdc_posts->have_posts() ) :
							$sdc_posts->the_post();
							sdc_post_card( 'h3' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php if ( $sdc_blog_url && sdc_get( 'posts_link_label' ) ) : ?>
						<p class="sd-center sd-section__more"><a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( $sdc_blog_url ); ?>"><?php echo esc_html( sdc_get( 'posts_link_label' ) ); ?></a></p>
					<?php endif; ?>
				</div>
			</section>
			<?php
		endif;
	endif;
	?>

	<?php
	$sdc_reviews = array();
	for ( $sdc_n = 1; $sdc_n <= 3; $sdc_n++ ) {
		$sdc_q = sdc_get( 'testimonial_' . $sdc_n . '_quote' );
		if ( $sdc_q ) {
			$sdc_reviews[] = array( $sdc_q, sdc_get( 'testimonial_' . $sdc_n . '_name' ) );
		}
	}
	if ( $sdc_reviews ) :
		?>
		<section class="sd-section sd-section--white" aria-labelledby="sd-reviews-title">
			<div class="sd-wrap">
				<div class="sd-section__head">
					<?php if ( sdc_get( 'testimonials_eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'testimonials_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 id="sd-reviews-title"><?php echo esc_html( sdc_get( 'testimonials_title' ) ); ?></h2>
				</div>
				<div class="sd-cards sd-cards--<?php echo esc_attr( (string) count( $sdc_reviews ) ); ?>">
					<?php foreach ( $sdc_reviews as $sdc_r ) : ?>
						<figure class="sd-card sd-review">
							<blockquote><p><?php echo esc_html( $sdc_r[0] ); ?></p></blockquote>
							<?php if ( $sdc_r[1] ) : ?>
								<figcaption><?php echo esc_html( $sdc_r[1] ); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( sdc_get( 'news_title' ) ) : ?>
		<section class="sd-section" aria-labelledby="sd-news-title">
			<div class="sd-wrap">
				<div class="sd-news">
					<div>
						<?php if ( sdc_get( 'news_eyebrow' ) ) : ?>
							<p class="sd-eyebrow sd-news__eyebrow"><?php echo esc_html( sdc_get( 'news_eyebrow' ) ); ?></p>
						<?php endif; ?>
						<h2 id="sd-news-title"><?php echo esc_html( sdc_get( 'news_title' ) ); ?></h2>
						<?php if ( sdc_get( 'news_text' ) ) : ?>
							<p><?php echo esc_html( sdc_get( 'news_text' ) ); ?></p>
						<?php endif; ?>
					</div>
					<div class="sd-news__action">
						<?php if ( is_active_sidebar( 'sdc-home-newsletter' ) ) : ?>
							<?php dynamic_sidebar( 'sdc-home-newsletter' ); ?>
							<?php if ( sdc_get( 'news_note' ) ) : ?>
								<p class="sd-news__note"><?php echo esc_html( sdc_get( 'news_note' ) ); ?></p>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( sdc_get( 'contact_cta_label' ) && sdc_get( 'contact_url' ) ) : ?>
							<div class="sd-btn-row"><a class="sd-btn sd-btn--light" href="<?php echo esc_url( sdc_link( sdc_get( 'contact_url' ) ) ); ?>"><?php echo esc_html( sdc_get( 'contact_cta_label' ) ); ?></a></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// The Home page's own block content (optional extra sections).
	while ( have_posts() ) :
		the_post();
		if ( '' !== trim( (string) get_the_content() ) ) :
			?>
			<div class="sd-section sd-wrap entry-content"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</main>
<?php
get_footer();
