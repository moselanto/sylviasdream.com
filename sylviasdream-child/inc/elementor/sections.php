<?php
/**
 * 1.21.0: section renderers used by the Sylvia's Dream Elementor widgets.
 *
 * Each function prints one site section with the same markup and classes as
 * the theme (front-page.php, inner pages, patterns), so the widgets look
 * exactly like the existing site. Values come from the widget; the widget's
 * defaults are the current Customizer wording, so nothing is invented.
 * Links are only displayed, never rewritten. Everything is escaped here.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Value from an array with a fallback.
 *
 * @param array  $a   Values.
 * @param string $key Key.
 * @param mixed  $def Default.
 * @return mixed
 */
function sdc_v( $a, $key, $def = '' ) {
	return ( is_array( $a ) && isset( $a[ $key ] ) && null !== $a[ $key ] ) ? $a[ $key ] : $def;
}

/**
 * A link value as a URL (site-relative paths become full addresses).
 *
 * @param string $value Link.
 * @return string
 */
function sdc_section_url( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	return function_exists( 'sdc_link' ) ? sdc_link( $value ) : $value;
}

/**
 * Safe heading tag.
 *
 * @param string $tag Tag.
 * @param string $def Default.
 * @return string
 */
function sdc_section_tag( $tag, $def = 'h2' ) {
	return in_array( $tag, array( 'h1', 'h2', 'h3', 'p' ), true ) ? $tag : $def;
}

/**
 * Unique id for aria-labelledby.
 *
 * @param string $base Base.
 * @return string
 */
function sdc_section_id( $base ) {
	static $n = 0;
	++$n;
	return 'sd-' . sanitize_html_class( $base ) . '-' . $n;
}

/**
 * Image markup from an attachment ID or a URL.
 *
 * @param int    $id    Attachment ID.
 * @param string $url   Fallback URL.
 * @param string $size  Size.
 * @param array  $attr  Attributes.
 * @param string $alt   Alt text for a URL image.
 * @return string
 */
function sdc_section_img( $id, $url, $size = 'large', $attr = array(), $alt = '' ) {
	$id = absint( $id );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return (string) wp_get_attachment_image( $id, $size, false, $attr );
	}
	if ( '' === (string) $url ) {
		return '';
	}
	$extra = '';
	foreach ( $attr as $k => $v ) {
		if ( 'alt' === $k ) {
			$alt = $v;
			continue;
		}
		$extra .= ' ' . esc_attr( $k ) . '="' . esc_attr( (string) $v ) . '"';
	}
	return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"' . $extra . '>';
}

/**
 * Buttons row.
 *
 * @param array $buttons List of array( label, url, class ).
 * @param string $extra  Extra row class.
 */
function sdc_section_buttons( $buttons, $extra = '' ) {
	$out = '';
	foreach ( $buttons as $b ) {
		$url = sdc_section_url( $b[1] );
		if ( '' === trim( (string) $b[0] ) || '' === $url ) {
			continue;
		}
		$out .= '<a class="' . esc_attr( trim( 'sd-btn ' . $b[2] ) ) . '" href="' . esc_url( $url ) . '">' . esc_html( $b[0] ) . '</a>';
	}
	if ( $out ) {
		echo '<div class="sd-btn-row' . ( $extra ? ' ' . esc_attr( $extra ) : '' ) . '">' . $out . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
}

/**
 * Home hero.
 *
 * @param array $a Values.
 */
function sdc_section_home_hero( $a ) {
	$img_id  = absint( sdc_v( $a, 'image_id', 0 ) );
	$img_url = (string) sdc_v( $a, 'image_url', '' );
	$covers  = array();
	if ( ! $img_id && '' === $img_url && 'yes' === sdc_v( $a, 'show_covers', 'yes' ) && function_exists( 'sdc_home_products' ) ) {
		foreach ( sdc_home_products( 8 ) as $p ) {
			if ( $p->get_image_id() ) {
				$covers[] = $p;
			}
			if ( count( $covers ) >= 3 ) {
				break;
			}
		}
	}
	$tag   = sdc_section_tag( sdc_v( $a, 'heading_tag', 'h1' ), 'h1' );
	$hid   = sdc_section_id( 'hero' );
	$media = $img_id || $img_url || $covers;
	?>
	<section class="sd-hero<?php echo $media ? ' sd-hero--media' : ''; ?>" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap sd-hero__inner">
			<div class="sd-hero__text">
				<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
					<p class="sd-eyebrow"><?php echo esc_html( wp_specialchars_decode( (string) sdc_v( $a, 'eyebrow' ) ) ); ?></p>
				<?php endif; ?>
				<<?php echo esc_html( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ? sdc_v( $a, 'title' ) : get_bloginfo( 'name' ) ); ?></<?php echo esc_html( $tag ); ?>>
				<?php if ( sdc_v( $a, 'text' ) ) : ?>
					<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
				<?php endif; ?>
				<?php if ( sdc_v( $a, 'quote' ) ) : ?>
					<blockquote class="sd-hero__quote"><p><?php echo esc_html( sdc_v( $a, 'quote' ) ); ?></p></blockquote>
				<?php endif; ?>
				<?php
				sdc_section_buttons(
					array(
						array( sdc_v( $a, 'cta_label' ), sdc_v( $a, 'cta_url' ) ? sdc_v( $a, 'cta_url' ) : sdc_shop_url(), '' ),
						array( sdc_v( $a, 'cta2_label' ), sdc_v( $a, 'cta2_url' ), 'sd-btn--ghost' ),
					)
				);
				?>
			</div>
			<?php if ( $img_id || $img_url ) : ?>
				<div class="sd-hero__media">
					<div class="sd-hero__single"><?php echo sdc_section_img( $img_id, $img_url, 'sd-cover', array( 'fetchpriority' => 'high', 'loading' => 'eager', 'decoding' => 'async' ), (string) sdc_v( $a, 'image_alt', '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></div>
				</div>
			<?php elseif ( $covers ) : ?>
				<div class="sd-hero__media">
					<ul class="sd-hero__covers sd-hero__covers--<?php echo esc_attr( (string) count( $covers ) ); ?>">
						<?php foreach ( $covers as $i => $p ) : ?>
							<li class="sd-hero__cover"><a href="<?php echo esc_url( $p->get_permalink() ); ?>"><?php echo wp_get_attachment_image( $p->get_image_id(), 'woocommerce_thumbnail', false, array( 'alt' => $p->get_name(), 'loading' => 0 === $i ? 'eager' : 'lazy' ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Highlights (trust strip). Items: array( title, text, icon ).
 *
 * @param array $a Values.
 */
function sdc_section_highlights( $a ) {
	$items = array();
	foreach ( (array) sdc_v( $a, 'items', array() ) as $it ) {
		$title = trim( (string) sdc_v( $it, 'title' ) );
		if ( '' === $title ) {
			continue;
		}
		if ( false !== strpos( $title, '{count}' ) && function_exists( 'sdc_book_count_label' ) ) {
			$label = sdc_book_count_label();
			$title = '0' === $label ? trim( str_replace( '{count}', '', $title ) ) : str_replace( '{count}', $label, $title );
		}
		$items[] = array( $title, (string) sdc_v( $it, 'text' ), (string) sdc_v( $it, 'icon', 'book' ) );
	}
	if ( ! $items ) {
		return;
	}
	?>
	<section class="sd-strip" aria-label="<?php echo esc_attr( sdc_v( $a, 'label', __( 'Why readers choose these books', 'sylviasdream-child' ) ) ); ?>">
		<ul class="sd-wrap sd-strip__list">
			<?php foreach ( $items as $it ) : ?>
				<li class="sd-strip__item">
					<span class="sd-strip__icon"><?php sdc_the_icon( in_array( $it[2], array( 'book', 'download', 'globe', 'heart' ), true ) ? $it[2] : 'book' ); ?></span>
					<span><strong><?php echo esc_html( $it[0] ); ?></strong><?php if ( '' !== $it[1] ) : ?> <span><?php echo esc_html( $it[1] ); ?></span><?php endif; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}

/**
 * Section heading block (eyebrow, heading, intro).
 *
 * @param array  $a   Values.
 * @param string $hid Heading id.
 */
function sdc_section_head( $a, $hid ) {
	$tag = sdc_section_tag( sdc_v( $a, 'heading_tag', 'h2' ) );
	?>
	<div class="sd-section__head">
		<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
			<p class="sd-eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
		<?php endif; ?>
		<?php if ( sdc_v( $a, 'title' ) ) : ?>
			<<?php echo esc_html( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></<?php echo esc_html( $tag ); ?>>
		<?php endif; ?>
		<?php if ( sdc_v( $a, 'text' ) ) : ?>
			<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Books grid using the WooCommerce [products] shortcode.
 *
 * @param array $a Values: count, columns, order (featured|rand|date|title|price), category.
 * @return string Shortcode output.
 */
function sdc_section_products_html( $a ) {
	if ( ! shortcode_exists( 'products' ) ) {
		return '';
	}
	$count   = max( 1, min( 48, absint( sdc_v( $a, 'count', 8 ) ) ) );
	$columns = max( 2, min( 4, absint( sdc_v( $a, 'columns', 4 ) ) ) );
	$order   = (string) sdc_v( $a, 'order', 'featured' );
	$atts    = array(
		'limit'   => $count,
		'columns' => $columns,
	);
	switch ( $order ) {
		case 'rand':
			$atts['orderby'] = 'rand';
			break;
		case 'date':
			$atts['orderby'] = 'date';
			$atts['order']   = 'DESC';
			break;
		case 'title':
			$atts['orderby'] = 'title';
			$atts['order']   = 'ASC';
			break;
		case 'price':
			$atts['orderby'] = 'price';
			$atts['order']   = 'ASC';
			break;
		case 'featured_only':
			$atts['visibility'] = 'featured';
			$atts['orderby']    = 'rand';
			break;
		default:
			$atts['orderby'] = 'menu_order title';
			$atts['order']   = 'ASC';
	}
	$cat = sanitize_title( (string) sdc_v( $a, 'category', '' ) );
	if ( '' !== $cat ) {
		$atts['category'] = $cat;
	}
	$sc = '[products';
	foreach ( $atts as $k => $v ) {
		$sc .= ' ' . $k . '="' . esc_attr( (string) $v ) . '"';
	}
	$sc .= ']';
	return (string) do_shortcode( $sc );
}

/**
 * Featured books.
 *
 * @param array $a Values.
 */
function sdc_section_featured_books( $a ) {
	$hid  = sdc_section_id( 'books' );
	$grid = '';
	if ( 'home' === sdc_v( $a, 'order', 'home' ) && function_exists( 'sdc_home_products' ) && function_exists( 'wc_get_template_part' ) ) {
		$products = sdc_home_products( max( 1, min( 24, absint( sdc_v( $a, 'count', 8 ) ) ) ) );
		if ( $products ) {
			ob_start();
			wc_set_loop_prop( 'columns', max( 2, min( 4, absint( sdc_v( $a, 'columns', 4 ) ) ) ) );
			wc_set_loop_prop( 'name', 'sd-home' );
			woocommerce_product_loop_start();
			foreach ( $products as $p ) {
				$GLOBALS['post'] = get_post( $p->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $GLOBALS['post'] );
				wc_get_template_part( 'content', 'product' );
			}
			woocommerce_product_loop_end();
			wp_reset_postdata();
			wc_reset_loop();
			$grid = (string) ob_get_clean();
		}
	} else {
		$grid = sdc_section_products_html( $a );
	}
	if ( '' === trim( wp_strip_all_tags( $grid, true ) ) && false === strpos( $grid, '<li' ) ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'No books to show yet. Books appear here when they are published in Products.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$more = (string) sdc_v( $a, 'link_url', '' );
	?>
	<section class="sd-section" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap">
			<?php sdc_section_head( $a, $hid ); ?>
			<div class="woocommerce sd-home-books"><?php echo $grid; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template output. ?></div>
			<?php if ( sdc_v( $a, 'link_label' ) ) : ?>
				<p class="sd-center sd-section__more"><a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( $more ? sdc_section_url( $more ) : sdc_shop_url() ); ?>"><?php echo esc_html( sdc_v( $a, 'link_label' ) ); ?></a></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Meet Sylvia.
 *
 * @param array $a Values.
 */
function sdc_section_meet_sylvia( $a ) {
	if ( ! sdc_v( $a, 'title' ) && ! sdc_v( $a, 'text' ) ) {
		return;
	}
	$hid = sdc_section_id( 'about' );
	$img = sdc_section_img( sdc_v( $a, 'image_id', 0 ), sdc_v( $a, 'image_url', '' ), 'large', array( 'loading' => 'lazy' ), (string) sdc_v( $a, 'image_alt', '' ) );
	?>
	<section class="sd-section sd-section--white" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap sd-split<?php echo $img ? '' : ' sd-split--text'; ?>">
			<?php if ( $img ) : ?>
				<div class="sd-photo sd-photo--portrait"><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></div>
			<?php endif; ?>
			<div>
				<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
					<p class="sd-eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></h2>
				<?php if ( sdc_v( $a, 'text' ) ) : ?>
					<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
				<?php endif; ?>
				<?php if ( sdc_v( $a, 'text_2' ) ) : ?>
					<p><?php echo esc_html( sdc_v( $a, 'text_2' ) ); ?></p>
				<?php endif; ?>
				<?php sdc_section_buttons( array( array( sdc_v( $a, 'cta_label' ), sdc_v( $a, 'cta_url' ), '' ) ) ); ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Heaven on Earth band.
 *
 * @param array $a Values.
 */
function sdc_section_heaven_on_earth( $a ) {
	if ( ! sdc_v( $a, 'text' ) ) {
		return;
	}
	$hid = sdc_section_id( 'hoe' );
	$bg  = sdc_section_img( sdc_v( $a, 'image_id', 0 ), sdc_v( $a, 'image_url', '' ), 'sd-hero', array( 'class' => 'sd-band__bg', 'alt' => '', 'loading' => 'lazy' ) );
	?>
	<section class="sd-band<?php echo $bg ? ' sd-band--image' : ''; ?>" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<?php echo $bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
		<div class="sd-wrap sd-band__inner">
			<h2 class="sd-eyebrow sd-band__eyebrow" id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ? sdc_v( $a, 'eyebrow' ) : __( 'Heaven on Earth', 'sylviasdream-child' ) ); ?></h2>
			<p class="sd-band__statement"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
			<?php sdc_section_buttons( array( array( sdc_v( $a, 'cta_label' ), sdc_v( $a, 'cta_url' ), 'sd-btn--light' ) ), 'sd-btn-row--center' ); ?>
		</div>
	</section>
	<?php
}

/**
 * Dream Partners / Give band with the partner progress bar.
 *
 * @param array $a Values.
 */
function sdc_section_dream_partners( $a ) {
	if ( ! sdc_v( $a, 'title' ) ) {
		return;
	}
	$hid = sdc_section_id( 'give' );
	$img = sdc_section_img( sdc_v( $a, 'image_id', 0 ), sdc_v( $a, 'image_url', '' ), 'large', array( 'loading' => 'lazy' ), (string) sdc_v( $a, 'image_alt', '' ) );
	?>
	<section class="sd-section sd-section--tint" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap sd-split sd-split--give">
			<div>
				<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
					<p class="sd-eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></h2>
				<?php if ( sdc_v( $a, 'text' ) ) : ?>
					<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
				<?php endif; ?>
				<?php sdc_section_buttons( array( array( sdc_v( $a, 'cta_label' ), sdc_v( $a, 'cta_url' ), '' ) ) ); ?>
			</div>
			<?php if ( 'yes' === sdc_v( $a, 'show_progress', 'yes' ) && shortcode_exists( 'sd_partner_progress' ) ) : ?>
				<div class="sd-card sd-give-card"><?php echo do_shortcode( '[sd_partner_progress]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode escapes its own output. ?></div>
			<?php elseif ( $img ) : ?>
				<div class="sd-photo"><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Latest devotionals.
 *
 * @param array $a Values.
 */
function sdc_section_latest_posts( $a ) {
	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => max( 1, min( 12, absint( sdc_v( $a, 'count', 3 ) ) ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	$cat = sanitize_title( (string) sdc_v( $a, 'category', '' ) );
	if ( '' !== $cat ) {
		$args['category_name'] = $cat;
	}
	$q = new WP_Query( $args );
	if ( ! $q->have_posts() ) {
		return;
	}
	$hid  = sdc_section_id( 'posts' );
	$blog = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : '';
	?>
	<section class="sd-section" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap">
			<?php sdc_section_head( $a, $hid ); ?>
			<div class="sd-cards">
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					sdc_post_card( 'h3' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<?php if ( $blog && sdc_v( $a, 'link_label' ) ) : ?>
				<p class="sd-center sd-section__more"><a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( $blog ); ?>"><?php echo esc_html( sdc_v( $a, 'link_label' ) ); ?></a></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Reader reviews (only real, approved quotes; empty = hidden).
 *
 * @param array $a Values.
 */
function sdc_section_reviews( $a ) {
	$reviews = array();
	foreach ( (array) sdc_v( $a, 'items', array() ) as $it ) {
		if ( '' !== trim( (string) sdc_v( $it, 'quote' ) ) ) {
			$reviews[] = array( (string) sdc_v( $it, 'quote' ), (string) sdc_v( $it, 'name' ) );
		}
	}
	if ( ! $reviews ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'Reader reviews show here once a real review is typed in (only with the reader\'s permission).', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$hid = sdc_section_id( 'reviews' );
	?>
	<section class="sd-section sd-section--white" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap">
			<?php sdc_section_head( $a, $hid ); ?>
			<div class="sd-cards sd-cards--<?php echo esc_attr( (string) count( $reviews ) ); ?>">
				<?php foreach ( $reviews as $r ) : ?>
					<figure class="sd-card sd-review">
						<blockquote><p><?php echo esc_html( $r[0] ); ?></p></blockquote>
						<?php if ( $r[1] ) : ?>
							<figcaption><?php echo esc_html( $r[1] ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Newsletter and contact band.
 *
 * @param array $a Values.
 */
function sdc_section_newsletter( $a ) {
	if ( ! sdc_v( $a, 'title' ) ) {
		return;
	}
	$hid = sdc_section_id( 'news' );
	?>
	<section class="sd-section" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="sd-wrap">
			<div class="sd-news">
				<div>
					<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
						<p class="sd-eyebrow sd-news__eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></h2>
					<?php if ( sdc_v( $a, 'text' ) ) : ?>
						<p><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
					<?php endif; ?>
				</div>
				<div class="sd-news__action">
					<?php
					$form = false;
					if ( 'yes' === sdc_v( $a, 'show_form', 'yes' ) ) {
						if ( is_active_sidebar( 'sdc-home-newsletter' ) ) {
							dynamic_sidebar( 'sdc-home-newsletter' );
							$form = true;
						} elseif ( shortcode_exists( 'jetpack_subscription_form' ) ) {
							echo do_shortcode( '[jetpack_subscription_form show_subscribers_total="false" button_on_newline="false" show_only_email_and_button="true"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Jetpack output.
							$form = true;
						}
					}
					if ( $form && sdc_v( $a, 'note' ) ) {
						echo '<p class="sd-news__note">' . esc_html( sdc_v( $a, 'note' ) ) . '</p>';
					}
					sdc_section_buttons( array( array( sdc_v( $a, 'cta_label' ), sdc_v( $a, 'cta_url' ), 'sd-btn--light' ) ) );
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Split band with text, button and a round photo (Author Support promotion style).
 *
 * @param array $a Values.
 */
function sdc_section_promo( $a ) {
	if ( ! sdc_v( $a, 'title' ) ) {
		return;
	}
	$hid = sdc_section_id( 'promo' );
	$img = sdc_section_img( sdc_v( $a, 'image_id', 0 ), sdc_v( $a, 'image_url', '' ), 'large', array( 'loading' => 'lazy' ), (string) sdc_v( $a, 'image_alt', '' ) );
	$alt = 'yes' === sdc_v( $a, 'tinted', 'yes' ) ? ' sd-home-band--alt' : '';
	?>
	<section class="wp-block-group alignfull sd-home-band<?php echo esc_attr( $alt ); ?>" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="wp-block-group sd-band__inner">
			<div class="wp-block-columns are-vertically-aligned-center sd-split">
				<div class="wp-block-column is-vertically-aligned-center">
					<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 class="wp-block-heading" id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></h2>
					<?php if ( sdc_v( $a, 'text' ) ) : ?>
						<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
					<?php endif; ?>
					<?php
					$url = sdc_section_url( sdc_v( $a, 'cta_url' ) );
					if ( sdc_v( $a, 'cta_label' ) && $url ) :
						?>
						<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( sdc_v( $a, 'cta_label' ) ); ?></a></div></div>
					<?php endif; ?>
				</div>
				<?php if ( $img ) : ?>
					<div class="wp-block-column is-vertically-aligned-center"><figure class="wp-block-image size-large sd-photo-round"><?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></figure></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Books page grid with search and topic chips.
 *
 * @param array $a Values.
 */
function sdc_section_book_filters( $a ) {
	echo '<div class="sd-wrap sd-section sd-section--tight">';
	if ( 'yes' === sdc_v( $a, 'show_filters', 'yes' ) && shortcode_exists( 'sd_book_filters' ) ) {
		echo do_shortcode( '[sd_book_filters]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme shortcode escapes its output.
	}
	if ( 'yes' === sdc_v( $a, 'show_grid', 'yes' ) ) {
		echo '<div class="woocommerce">' . sdc_section_products_html( $a ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce output.
	}
	echo '</div>';
}

/**
 * Inner-page banner (photo, breadcrumb, small heading, title, intro).
 *
 * @param array $a Values.
 */
function sdc_section_page_banner( $a ) {
	$title = (string) sdc_v( $a, 'title', '' );
	if ( '' === $title ) {
		$title = is_singular() ? get_the_title( get_queried_object_id() ) : get_bloginfo( 'name' );
	}
	$image = '';
	$id    = absint( sdc_v( $a, 'image_id', 0 ) );
	if ( $id && wp_attachment_is_image( $id ) ) {
		$image = (string) wp_get_attachment_image_url( $id, 'full' );
	} elseif ( sdc_v( $a, 'image_url' ) ) {
		$image = (string) sdc_v( $a, 'image_url' );
	}
	$GLOBALS['sdc_el_has_banner'] = true;
	// 1.21.2: the band keeps the theme banner classes (sd-pagehead, sd-pagehead--photo)
	// and adds its text colour and shade, so the light words stay light inside
	// Elementor and .entry-content (see style.css "1.21.2 banner safety").
	$colour  = 'dark' === sdc_v( $a, 'text_colour', 'light' ) ? 'dark' : 'light';
	$overlay = (string) sdc_v( $a, 'overlay', 'theme' );
	$classes = array( 'sd-pagehead--el', 'dark' === $colour ? 'sd-pagehead--dark-text' : 'sd-pagehead--light' );
	if ( in_array( $overlay, array( 'darker', 'lighter', 'none' ), true ) ) {
		$classes[] = 'sd-pagehead--shade-' . $overlay;
	}
	sdc_page_header(
		$title,
		(string) sdc_v( $a, 'eyebrow', '' ),
		(string) sdc_v( $a, 'intro', '' ),
		$image,
		array(
			'classes'  => $classes,
			'position' => (string) sdc_v( $a, 'photo_position', '' ),
		)
	);
}

/**
 * "Keep exploring" cards.
 *
 * @param array $a Values.
 */
function sdc_section_keep_exploring( $a ) {
	if ( ! function_exists( 'sdc_related_links' ) ) {
		return;
	}
	$heading = trim( (string) sdc_v( $a, 'title', '' ) );
	ob_start();
	sdc_related_links();
	$html = (string) ob_get_clean();
	if ( '' === $html && sdc_widget_is_editor() ) {
		echo '<p class="sd-el-empty">' . esc_html__( 'The "Keep exploring" links appear on the published page.', 'sylviasdream-child' ) . '</p>';
		return;
	}
	if ( '' !== $heading && __( 'Keep exploring', 'sylviasdream-child' ) !== $heading ) {
		$html = str_replace( '>' . esc_html__( 'Keep exploring', 'sylviasdream-child' ) . '</h2>', '>' . esc_html( $heading ) . '</h2>', $html );
	}
	$GLOBALS['sdc_el_has_next'] = true;
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by sdc_related_links() and above.
}

/**
 * Are we inside the Elementor editor or its preview?
 *
 * @return bool
 */
function sdc_widget_is_editor() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	$el = \Elementor\Plugin::$instance;
	if ( isset( $el->editor ) && method_exists( $el->editor, 'is_edit_mode' ) && $el->editor->is_edit_mode() ) {
		return true;
	}
	return isset( $el->preview ) && method_exists( $el->preview, 'is_preview_mode' ) && $el->preview->is_preview_mode();
}

/* -------------------------------------------------------------------------
 * "Section from your page": an existing block section kept exactly as it was,
 * with its wording and photos editable in Elementor.
 * ---------------------------------------------------------------------- */

/**
 * Is a link a payment, Dream Partner or download link whose button must stay
 * exactly as it is (label included)?
 *
 * @param string $href Link.
 * @return bool
 */
function sdc_is_protected_link( $href ) {
	$href = (string) $href;
	if ( '' === $href ) {
		return false;
	}
	if ( false !== strpos( $href, '#KEEP-LIVE' ) ) {
		return true;
	}
	$host = strtolower( (string) wp_parse_url( $href, PHP_URL_HOST ) );
	foreach ( array( 'stripe.com', 'paypal.com', 'paypal.me', 'gofundme.com' ) as $h ) {
		if ( $host === $h || str_ends_with( $host, '.' . $h ) ) {
			return true;
		}
	}
	return (bool) preg_match( '#/(thank-you|all-shall-be-well)#i', $href );
}

/**
 * Load block markup into a DOM (UTF-8 safe). Returns null on failure.
 *
 * @param string $markup Markup.
 * @return DOMDocument|null
 */
function sdc_old_section_dom( $markup ) {
	if ( ! class_exists( 'DOMDocument' ) ) {
		return null;
	}
	$dom  = new DOMDocument();
	$prev = libxml_use_internal_errors( true );
	$ok   = $dom->loadHTML( '<?xml encoding="utf-8" ?><div id="sdc-root">' . $markup . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $prev );
	return $ok ? $dom : null;
}

/**
 * The editable text nodes of a section, in document order.
 *
 * @param DOMDocument $dom DOM.
 * @return DOMText[]
 */
function sdc_old_section_text_nodes( $dom ) {
	$xp    = new DOMXPath( $dom );
	$nodes = array();
	foreach ( $xp->query( '//text()' ) as $node ) {
		$text = trim( $node->nodeValue );
		if ( '' === $text || preg_match( '/^\[[a-z_]+/i', $text ) || preg_match( '#^https?://\S+$#i', $text ) ) {
			continue; // Whitespace, shortcodes and embed addresses are not wording.
		}
		$skip = false;
		for ( $p = $node->parentNode; $p && XML_ELEMENT_NODE === $p->nodeType; $p = $p->parentNode ) {
			$name = strtolower( $p->nodeName );
			if ( in_array( $name, array( 'script', 'style', 'svg' ), true ) ) {
				$skip = true;
				break;
			}
			if ( 'a' === $name && sdc_is_protected_link( $p->getAttribute( 'href' ) ) ) {
				$skip = true; // Payment / Dream Partner / download button labels stay as they are.
				break;
			}
			if ( $p instanceof DOMElement && false !== strpos( ' ' . $p->getAttribute( 'class' ) . ' ', ' screen-reader-text ' ) ) {
				$skip = true;
				break;
			}
		}
		if ( ! $skip ) {
			$nodes[] = $node;
		}
	}
	return $nodes;
}

/**
 * Wording and photos found in a section (used when a page starter is built).
 *
 * @param string $markup Block markup.
 * @return array{texts:string[],photos:array<int,array{src:string,alt:string}>}
 */
function sdc_old_section_parts( $markup ) {
	$out = array(
		'texts'  => array(),
		'photos' => array(),
	);
	$dom = sdc_old_section_dom( $markup );
	if ( ! $dom ) {
		return $out;
	}
	foreach ( sdc_old_section_text_nodes( $dom ) as $node ) {
		$out['texts'][] = trim( $node->nodeValue );
	}
	foreach ( $dom->getElementsByTagName( 'img' ) as $img ) {
		$out['photos'][] = array(
			'src' => (string) $img->getAttribute( 'src' ),
			'alt' => (string) $img->getAttribute( 'alt' ),
		);
	}
	return $out;
}

/**
 * Apply the widget's wording and photo changes to the section markup.
 *
 * @param string $markup Block markup.
 * @param array  $texts  List of array( orig, text ).
 * @param array  $photos List of array( orig, id ).
 * @return string
 */
function sdc_old_section_apply( $markup, $texts, $photos ) {
	$changed_t = array();
	foreach ( (array) $texts as $i => $t ) {
		$orig = trim( (string) sdc_v( $t, 'orig' ) );
		$new  = trim( (string) sdc_v( $t, 'text' ) );
		if ( '' !== $orig && $new !== $orig ) {
			$changed_t[ $i ] = array( $orig, $new );
		}
	}
	$changed_p = array();
	foreach ( (array) $photos as $i => $p ) {
		$id = absint( sdc_v( $p, 'id', 0 ) );
		if ( $id && wp_attachment_is_image( $id ) ) {
			$changed_p[ $i ] = $id;
		}
	}
	if ( ! $changed_t && ! $changed_p ) {
		return $markup; // Untouched: the original markup, byte for byte.
	}
	$dom = sdc_old_section_dom( $markup );
	if ( ! $dom ) {
		return $markup;
	}
	$nodes = sdc_old_section_text_nodes( $dom );
	foreach ( $changed_t as $i => $pair ) {
		if ( isset( $nodes[ $i ] ) && trim( $nodes[ $i ]->nodeValue ) === $pair[0] ) {
			$raw              = $nodes[ $i ]->nodeValue;
			$lead             = (string) substr( $raw, 0, strlen( $raw ) - strlen( ltrim( $raw ) ) );
			$tail             = (string) substr( $raw, strlen( rtrim( $raw ) ) );
			$nodes[ $i ]->nodeValue = $lead . $pair[1] . $tail; // DOM escapes the text.
		}
	}
	$imgs = $dom->getElementsByTagName( 'img' );
	foreach ( $changed_p as $i => $id ) {
		$img = $imgs->item( $i );
		if ( ! $img ) {
			continue;
		}
		$src = wp_get_attachment_image_url( $id, 'large' );
		if ( $src ) {
			$img->setAttribute( 'src', $src );
			$img->removeAttribute( 'srcset' );
			$img->removeAttribute( 'sizes' );
			$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
			if ( '' !== $alt && '' !== $img->getAttribute( 'alt' ) ) {
				$img->setAttribute( 'alt', $alt );
			}
		}
	}
	$root = $dom->getElementById( 'sdc-root' );
	if ( ! $root ) {
		return $markup;
	}
	$html = '';
	foreach ( $root->childNodes as $child ) {
		$html .= $dom->saveHTML( $child );
	}
	return $html;
}

/**
 * Render a kept section.
 *
 * @param array $a Values: markup, texts, photos.
 */
function sdc_section_old( $a ) {
	$markup = (string) sdc_v( $a, 'markup', '' );
	if ( '' === trim( $markup ) ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'This box is empty. It is filled automatically when a page is built from its old version.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$markup = wp_kses_post( $markup ); // The stored markup is treated like post content from any author.
	$markup = sdc_old_section_apply( $markup, sdc_v( $a, 'texts', array() ), sdc_v( $a, 'photos', array() ) );
	$html   = do_blocks( $markup );
	$html   = do_shortcode( shortcode_unautop( $html ) );
	echo '<div class="entry-content sd-el-old">' . $html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- kses-filtered block markup rendered by WordPress.
}

/**
 * Book showcase card with QR code (Home).
 *
 * @param array $a Values.
 */
function sdc_section_showcase( $a ) {
	if ( ! sdc_v( $a, 'title' ) ) {
		return;
	}
	$hid = sdc_section_id( 'showcase' );
	$url = sdc_section_url( sdc_v( $a, 'cta_url' ) );
	$img = sdc_section_img( sdc_v( $a, 'image_id', 0 ), sdc_v( $a, 'image_url', '' ), 'medium', array( 'style' => 'width:180px', 'loading' => 'lazy' ), (string) sdc_v( $a, 'image_alt', '' ) );
	?>
	<section class="wp-block-group alignfull sd-home-band" aria-labelledby="<?php echo esc_attr( $hid ); ?>">
		<div class="wp-block-group sd-band__inner sd-showcase">
			<div class="wp-block-columns are-vertically-aligned-center sd-showcase__cols">
				<div class="wp-block-column is-vertically-aligned-center">
					<?php if ( sdc_v( $a, 'eyebrow' ) ) : ?>
						<p class="sd-eyebrow"><?php echo esc_html( sdc_v( $a, 'eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 class="wp-block-heading" id="<?php echo esc_attr( $hid ); ?>"><?php echo esc_html( sdc_v( $a, 'title' ) ); ?></h2>
					<?php if ( sdc_v( $a, 'text' ) ) : ?>
						<p class="sd-lead"><?php echo esc_html( sdc_v( $a, 'text' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sdc_v( $a, 'cta_label' ) && $url ) : ?>
						<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html( sdc_v( $a, 'cta_label' ) ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'sylviasdream-child' ); ?></span></a></div></div>
					<?php endif; ?>
				</div>
				<?php if ( $img ) : ?>
					<div class="wp-block-column is-vertically-aligned-center sd-showcase__qr-col">
						<figure class="wp-block-image size-full is-resized sd-showcase__qr">
							<?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noreferrer noopener"><?php endif; ?>
							<?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
							<?php if ( $url ) : ?></a><?php endif; ?>
							<?php if ( sdc_v( $a, 'caption' ) ) : ?>
								<figcaption class="wp-element-caption"><?php echo esc_html( sdc_v( $a, 'caption' ) ); ?></figcaption>
							<?php endif; ?>
						</figure>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}
