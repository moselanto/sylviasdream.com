<?php
/**
 * 1.20.0: Journal (blog index, category archives, single posts).
 *
 * Presentation helpers only. Post content, titles, excerpts and categories
 * are read from WordPress and never changed. Wording on the author box and
 * the call to action comes from the author's profile or the existing
 * Customizer settings (Home: about Sylvia, Home: newsletter, Featured books).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is this a Journal view (posts page, post archive or single post)?
 *
 * @return bool
 */
function sdc_is_journal() {
	if ( is_home() || is_category() || is_tag() || is_date() || is_author() ) {
		return true;
	}
	return is_singular( 'post' );
}

/**
 * Body class for Journal views.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_journal_body_class( $classes ) {
	if ( sdc_is_journal() ) {
		$classes[] = 'sd-journal';
		$classes[] = is_singular( 'post' ) ? 'sd-journal--single' : 'sd-journal--index';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_journal_body_class' );

/**
 * Journal (posts page) URL.
 *
 * @return string
 */
function sdc_journal_url() {
	$page = (int) get_option( 'page_for_posts' );
	return $page ? (string) get_permalink( $page ) : home_url( '/' );
}

/**
 * Journal name (the posts page title, else the Customizer blog heading).
 *
 * @return string
 */
function sdc_journal_name() {
	$page = (int) get_option( 'page_for_posts' );
	if ( $page ) {
		return get_the_title( $page );
	}
	return sdc_get( 'blog_title' );
}

/**
 * Estimated reading time in minutes (200 words a minute, at least 1).
 *
 * @param int|WP_Post|null $post Post.
 * @return int
 */
function sdc_reading_minutes( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 1;
	}
	$text  = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
	$words = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * "N min read" label.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function sdc_reading_time_label( $post = null ) {
	$min = sdc_reading_minutes( $post );
	/* translators: %d: minutes. */
	return sprintf( _n( '%d min read', '%d min read', $min, 'sylviasdream-child' ), $min );
}

/**
 * Main category of a post (first non-"Uncategorized" category).
 *
 * @param int|WP_Post|null $post Post.
 * @return WP_Term|null
 */
function sdc_post_main_category( $post = null ) {
	$cats = get_the_category( get_post( $post ) ? get_post( $post )->ID : 0 );
	if ( ! $cats ) {
		return null;
	}
	$default = (int) get_option( 'default_category' );
	foreach ( $cats as $cat ) {
		if ( 'uncategorized' !== $cat->slug && ( $cat->term_id !== $default || count( $cats ) === 1 ) ) {
			return $cat;
		}
	}
	return ( 'uncategorized' === $cats[0]->slug ) ? null : $cats[0];
}

/**
 * Brand placeholder image URL (used when a post has no featured image).
 *
 * @return string
 */
function sdc_journal_placeholder_url() {
	return get_theme_file_uri( 'assets/images/journal-placeholder.svg' );
}

/**
 * Featured image of a post, or the brand placeholder. Never a broken image.
 *
 * @param string           $size    Image size.
 * @param array            $attr    Extra attributes.
 * @param int|WP_Post|null $post    Post.
 * @return string Escaped HTML.
 */
function sdc_journal_image( $size = 'sd-card', $attr = array(), $post = null ) {
	$post = get_post( $post );
	if ( $post && has_post_thumbnail( $post ) ) {
		$html = get_the_post_thumbnail( $post, $size, array_merge( array( 'alt' => '' ), $attr ) );
		if ( $html ) {
			return $html;
		}
	}
	$loading = isset( $attr['loading'] ) ? $attr['loading'] : 'lazy';
	return sprintf(
		'<img class="sd-jph" src="%1$s" width="800" height="600" alt="" loading="%2$s" decoding="async">',
		esc_url( sdc_journal_placeholder_url() ),
		esc_attr( $loading )
	);
}

/**
 * Category pill for a post.
 *
 * @param int|WP_Post|null $post Post.
 * @param bool             $link Link the pill to the category archive.
 */
function sdc_journal_pill( $post = null, $link = true ) {
	$cat = sdc_post_main_category( $post );
	if ( ! $cat ) {
		return;
	}
	if ( $link ) {
		printf( '<a class="sd-jpill" href="%1$s">%2$s</a>', esc_url( get_category_link( $cat ) ), esc_html( $cat->name ) );
	} else {
		printf( '<span class="sd-jpill">%s</span>', esc_html( $cat->name ) );
	}
}

/**
 * Date and reading time line.
 *
 * @param int|WP_Post|null $post Post.
 */
function sdc_journal_meta( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}
	printf(
		'<p class="sd-jmeta"><time datetime="%1$s">%2$s</time><span aria-hidden="true">&middot;</span><span>%3$s</span></p>',
		esc_attr( get_the_date( DATE_W3C, $post ) ),
		esc_html( get_the_date( '', $post ) ),
		esc_html( sdc_reading_time_label( $post ) )
	);
}

/**
 * Journal card (grid item).
 *
 * @param string $heading_tag h2 or h3.
 */
function sdc_journal_card( $heading_tag = 'h2' ) {
	$tag = in_array( $heading_tag, array( 'h2', 'h3' ), true ) ? $heading_tag : 'h2';
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'sd-jcard' ); ?>>
		<a class="sd-jcard__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php echo wp_kses_post( sdc_journal_image( 'sd-card', array( 'loading' => 'lazy' ) ) ); ?>
		</a>
		<div class="sd-jcard__body">
			<div class="sd-jcard__top"><?php sdc_journal_pill( null, false ); ?></div>
			<<?php echo esc_html( $tag ); ?> class="sd-jcard__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_html( $tag ); ?>>
			<?php sdc_journal_meta(); ?>
			<div class="sd-jcard__excerpt"><?php the_excerpt(); ?></div>
			<span class="sd-jcard__more" aria-hidden="true"><?php esc_html_e( 'Read reflection', 'sylviasdream-child' ); ?> <?php echo wp_kses( sdc_icon( 'arrow' ), sdc_svg_kses() ); ?></span>
		</div>
	</article>
	<?php
}

/**
 * Featured (latest) post hero on the first Journal page.
 */
function sdc_journal_feature() {
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'sd-jfeature' ); ?>>
		<a class="sd-jfeature__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php echo wp_kses_post( sdc_journal_image( 'sd-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ) ); ?>
		</a>
		<div class="sd-jfeature__body">
			<p class="sd-eyebrow"><?php esc_html_e( 'Latest reflection', 'sylviasdream-child' ); ?></p>
			<div class="sd-jcard__top"><?php sdc_journal_pill(); ?></div>
			<h2 class="sd-jfeature__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php sdc_journal_meta(); ?>
			<div class="sd-jfeature__excerpt"><?php the_excerpt(); ?></div>
			<p class="sd-jfeature__cta"><a class="sd-btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read reflection', 'sylviasdream-child' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span></a></p>
		</div>
	</article>
	<?php
}

/**
 * Category filter chips (All + categories that have posts).
 */
function sdc_journal_filters() {
	$cats = get_categories( array(
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 12,
	) );
	$cats = array_values( array_filter( $cats, static function ( $c ) {
		return 'uncategorized' !== $c->slug;
	} ) );
	if ( ! $cats ) {
		return;
	}
	$current = is_category() ? (int) get_queried_object_id() : 0;
	echo '<nav class="sd-chips sd-jfilters" aria-label="' . esc_attr__( 'Browse by topic', 'sylviasdream-child' ) . '"><ul>';
	printf(
		'<li><a href="%1$s"%2$s>%3$s</a></li>',
		esc_url( sdc_journal_url() ),
		( is_home() ) ? ' aria-current="page"' : '',
		esc_html__( 'All', 'sylviasdream-child' )
	);
	foreach ( $cats as $cat ) {
		printf(
			'<li><a href="%1$s"%2$s>%3$s</a></li>',
			esc_url( get_category_link( $cat ) ),
			( $current === (int) $cat->term_id ) ? ' aria-current="page"' : '',
			esc_html( $cat->name )
		);
	}
	echo '</ul></nav>';
}

/**
 * Listing body shared by the Journal index and archives.
 *
 * @param bool $feature Show the latest post as a hero (first page of the Journal only).
 */
function sdc_journal_listing( $feature = false ) {
	sdc_journal_filters();
	if ( ! have_posts() ) {
		get_template_part( 'template-parts/content', 'none' );
		return;
	}
	if ( $feature ) {
		the_post();
		sdc_journal_feature();
	}
	if ( have_posts() ) {
		echo '<div class="sd-jgrid">';
		while ( have_posts() ) {
			the_post();
			sdc_journal_card( 'h2' );
		}
		echo '</div>';
	}
	sdc_pagination();
}

/**
 * Share links (plain share URLs; no scripts, no tracking).
 */
function sdc_share_links() {
	$url   = (string) get_permalink();
	$title = wp_strip_all_tags( get_the_title() );
	$links = array(
		'facebook' => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ) ),
		'x'        => array( 'X', 'https://twitter.com/intent/tweet?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title ) ),
		'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . rawurlencode( $title . ' ' . $url ) ),
		'email'    => array( __( 'Email', 'sylviasdream-child' ), 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url ) ),
	);
	$icons = array(
		'facebook' => sdc_icon( 'facebook' ),
		'x'        => '<svg class="sd-icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4l16 16M20 4L4 20"/></svg>',
		'whatsapp' => '<svg class="sd-icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l1.3-4A8 8 0 1 1 8 18.8z"/></svg>',
		'email'    => sdc_icon( 'mail' ),
	);
	?>
	<div class="sd-share">
		<p class="sd-share__label"><?php esc_html_e( 'Share this reflection', 'sylviasdream-child' ); ?></p>
		<ul class="sd-share__list">
			<?php foreach ( $links as $key => $link ) : ?>
				<li>
					<a class="sd-share__link sd-share__link--<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $link[1] ); ?>"<?php echo ( 'email' === $key ) ? '' : ' target="_blank" rel="noopener nofollow"'; ?>>
						<?php echo wp_kses( $icons[ $key ], sdc_svg_kses() ); ?>
						<span class="screen-reader-text">
							<?php
							/* translators: %s: network name. */
							echo esc_html( sprintf( 'email' === $key ? __( 'Share by %s', 'sylviasdream-child' ) : __( 'Share on %s (opens in a new tab)', 'sylviasdream-child' ), $link[0] ) );
							?>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		$fb = sdc_get( 'social_facebook' );
		if ( '' === $fb ) {
			// Owner-confirmed page (29 Oct). Change it in Customizer > Footer > Facebook URL.
			$fb = 'https://www.facebook.com/sylviasdream';
		}
		?>
		<a class="sd-share__follow" href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener"><?php echo wp_kses( sdc_icon( 'facebook' ), sdc_svg_kses() ); ?><span><?php esc_html_e( 'Follow on Facebook', 'sylviasdream-child' ); ?></span><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'sylviasdream-child' ); ?></span></a>
	</div>
	<?php
}

/**
 * Author box. Text comes only from the author's WordPress profile
 * (Biographical Info) or, when that is empty, the existing Customizer
 * "Home: about Sylvia" wording. Omitted when neither has text.
 */
function sdc_author_box() {
	$author_id = (int) get_the_author_meta( 'ID' );
	$bio       = trim( (string) get_the_author_meta( 'description', $author_id ) );
	$eyebrow   = sdc_get( 'about_eyebrow' );
	$name      = get_the_author();
	if ( '' === $bio ) {
		$bio = sdc_get( 'about_text' );
	}
	if ( '' === $bio ) {
		return;
	}
	$photo     = sdc_get_image( 'about_image' );
	$cta_label = sdc_get( 'about_cta_label' );
	$cta_url   = sdc_link( sdc_get( 'about_cta_url' ) );
	?>
	<aside class="sd-authorbox" aria-label="<?php esc_attr_e( 'About the author', 'sylviasdream-child' ); ?>">
		<div class="sd-authorbox__photo">
			<?php
			if ( $photo ) {
				echo wp_get_attachment_image( $photo, 'thumbnail', false, array( 'loading' => 'lazy', 'alt' => '' ) );
			} else {
				echo wp_kses( sdc_icon( 'book' ), sdc_svg_kses() );
			}
			?>
		</div>
		<div class="sd-authorbox__text">
			<?php if ( $eyebrow ) : ?>
				<p class="sd-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<p class="sd-authorbox__name"><?php echo esc_html( $name ); ?></p>
			<p class="sd-authorbox__bio"><?php echo esc_html( $bio ); ?></p>
			<?php if ( $cta_label && $cta_url ) : ?>
				<a class="sd-authorbox__link" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?> <?php echo wp_kses( sdc_icon( 'arrow' ), sdc_svg_kses() ); ?></a>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/**
 * Previous / next posts with thumbnails.
 */
function sdc_journal_prevnext() {
	$prev = get_previous_post();
	$next = get_next_post();
	if ( ! $prev && ! $next ) {
		return;
	}
	echo '<nav class="sd-jprevnext" aria-label="' . esc_attr__( 'More reflections', 'sylviasdream-child' ) . '">';
	foreach ( array( 'prev' => $prev, 'next' => $next ) as $dir => $p ) {
		if ( ! $p ) {
			echo '<span class="sd-jprevnext__spacer" aria-hidden="true"></span>';
			continue;
		}
		printf(
			'<a class="sd-jprevnext__item sd-jprevnext__item--%1$s" href="%2$s"><span class="sd-jprevnext__thumb">%3$s</span><span class="sd-jprevnext__text"><small>%4$s</small><span>%5$s</span></span></a>',
			esc_attr( $dir ),
			esc_url( get_permalink( $p ) ),
			wp_kses_post( sdc_journal_image( 'thumbnail', array( 'loading' => 'lazy' ), $p ) ),
			'prev' === $dir ? esc_html__( 'Previous', 'sylviasdream-child' ) : esc_html__( 'Next', 'sylviasdream-child' ),
			esc_html( wp_strip_all_tags( get_the_title( $p ) ) )
		);
	}
	echo '</nav>';
}

/**
 * Up to three related posts from the same category.
 */
function sdc_related_posts() {
	$post = get_post();
	$cat  = sdc_post_main_category( $post );
	if ( ! $post || ! $cat ) {
		return;
	}
	$q = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'cat'                 => (int) $cat->term_id,
		'post__not_in'        => array( (int) $post->ID ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
	if ( ! $q->have_posts() ) {
		return;
	}
	?>
	<section class="sd-jrelated" aria-labelledby="sd-jrelated-title">
		<div class="sd-wrap">
			<p class="sd-eyebrow"><?php echo esc_html( $cat->name ); ?></p>
			<h2 id="sd-jrelated-title"><?php esc_html_e( 'More reflections', 'sylviasdream-child' ); ?></h2>
			<div class="sd-jgrid">
				<?php
				while ( $q->have_posts() ) {
					$q->the_post();
					sdc_journal_card( 'h3' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Books and newsletter call to action. Reuses the Customizer wording of the
 * home page "Featured books" and "Newsletter" sections and the Jetpack
 * Subscribe block used by the Newsletter pattern (Contact button fallback).
 */
function sdc_journal_cta() {
	$books_title = sdc_get( 'books_title' );
	$books_text  = sdc_get( 'books_text' );
	$books_label = sdc_get( 'books_link_label' );
	$news_title  = sdc_get( 'news_title' );
	$news_text   = sdc_get( 'news_text' );
	$news_note   = sdc_get( 'news_note' );
	$contact     = sdc_link( sdc_get( 'contact_url' ) );
	$has_jetpack = class_exists( 'WP_Block_Type_Registry' ) && WP_Block_Type_Registry::get_instance()->is_registered( 'jetpack/subscriptions' );
	if ( ! $books_title && ! $news_title ) {
		return;
	}
	?>
	<section class="sd-jcta" aria-label="<?php esc_attr_e( 'Books and newsletter', 'sylviasdream-child' ); ?>">
		<div class="sd-wrap sd-jcta__grid">
			<?php if ( $books_title ) : ?>
				<div class="sd-jcta__card sd-jcta__card--books">
					<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'books_eyebrow' ) ); ?></p>
					<h2><?php echo esc_html( $books_title ); ?></h2>
					<?php if ( $books_text ) : ?>
						<p><?php echo esc_html( $books_text ); ?></p>
					<?php endif; ?>
					<?php if ( $books_label ) : ?>
						<a class="sd-btn" href="<?php echo esc_url( sdc_shop_url() ); ?>"><?php echo wp_kses( sdc_icon( 'book' ), sdc_svg_kses() ); ?><span><?php echo esc_html( $books_label ); ?></span></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $news_title ) : ?>
				<div class="sd-jcta__card sd-jcta__card--news">
					<p class="sd-eyebrow"><?php echo esc_html( sdc_get( 'news_eyebrow' ) ); ?></p>
					<h2><?php echo esc_html( $news_title ); ?></h2>
					<?php if ( $news_text ) : ?>
						<p><?php echo esc_html( $news_text ); ?></p>
					<?php endif; ?>
					<?php
					if ( $has_jetpack ) {
						echo do_blocks( '<!-- wp:jetpack/subscriptions /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core block renderer output.
					} elseif ( $contact && sdc_get( 'contact_cta_label' ) ) {
						echo '<a class="sd-btn sd-btn--light" href="' . esc_url( $contact ) . '">' . esc_html( sdc_get( 'contact_cta_label' ) ) . '</a>';
					}
					?>
					<?php if ( $news_note && $has_jetpack ) : ?>
						<p class="sd-jcta__note"><?php echo esc_html( $news_note ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
