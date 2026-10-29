<?php
/**
 * 1.21.0: the list of Sylvia's Dream Elementor widgets.
 *
 * Each entry: title, help (plain English), icon, render (function in
 * sections.php or widgets.php), and fields. A field is
 * array( id, panel, type, label, default, extra ). Panels: texts, photos,
 * links, settings, hidden. Defaults are the CURRENT wording from the
 * Customizer (sdc_get()) or the existing patterns, so nothing is invented and
 * a widget added to a page looks the same as the section it replaces.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Customizer image as an Elementor media default.
 *
 * @param string $key Image setting key.
 * @param string $url Fallback URL.
 * @return array
 */
function sdc_el_media_default( $key = '', $url = '' ) {
	$id = $key ? sdc_get_image( $key ) : 0;
	if ( $id ) {
		return array(
			'id'  => $id,
			'url' => (string) wp_get_attachment_url( $id ),
		);
	}
	return array(
		'id'  => '',
		'url' => $url,
	);
}

/**
 * Book choices for the button widgets.
 *
 * @return array
 */
function sdc_el_product_choices() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array( '' => __( 'The book of this page (on a book page)', 'sylviasdream-child' ) );
	if ( ! post_type_exists( 'product' ) ) {
		return $cache;
	}
	$ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => 200,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	foreach ( $ids as $id ) {
		$cache[ (string) $id ] = get_the_title( $id );
	}
	return $cache;
}

/**
 * Widget registry.
 *
 * @return array<string,array>
 */
function sdc_el_registry() {
	static $reg = null;
	if ( null !== $reg ) {
		return $reg;
	}
	$h      = array(
		'h1' => __( 'Main page title (only one per page)', 'sylviasdream-child' ),
		'h2' => __( 'Section heading', 'sylviasdream-child' ),
	);
	$h2     = array(
		'h2' => __( 'Section heading', 'sylviasdream-child' ),
		'h3' => __( 'Smaller heading', 'sylviasdream-child' ),
	);
	$yesno  = array(
		'yes' => __( 'Yes', 'sylviasdream-child' ),
		'no'  => __( 'No', 'sylviasdream-child' ),
	);
	$orders = array(
		'home'          => __( 'Chosen books first (as on the Home page)', 'sylviasdream-child' ),
		'rand'          => __( 'Different books on each visit (random)', 'sylviasdream-child' ),
		'featured_only' => __( 'Only books marked as featured (random)', 'sylviasdream-child' ),
		'date'          => __( 'Newest first', 'sylviasdream-child' ),
		'title'         => __( 'A to Z by title', 'sylviasdream-child' ),
		'price'         => __( 'Lowest price first', 'sylviasdream-child' ),
	);
	$cols   = array(
		'2' => '2',
		'3' => '3',
		'4' => '4',
	);
	$icons  = array(
		'book'     => __( 'Book', 'sylviasdream-child' ),
		'download' => __( 'Download', 'sylviasdream-child' ),
		'globe'    => __( 'Globe', 'sylviasdream-child' ),
		'heart'    => __( 'Heart', 'sylviasdream-child' ),
	);
	$strip  = array();
	foreach ( array( 1 => 'book', 2 => 'download', 3 => 'globe', 4 => 'heart' ) as $n => $icon ) {
		$strip[] = array(
			'title' => sdc_get( 'strip_' . $n . '_title' ),
			'text'  => sdc_get( 'strip_' . $n . '_text' ),
			'icon'  => $icon,
		);
	}
	$reviews = array();
	for ( $n = 1; $n <= 3; $n++ ) {
		$reviews[] = array(
			'quote' => sdc_get( 'testimonial_' . $n . '_quote' ),
			'name'  => sdc_get( 'testimonial_' . $n . '_name' ),
		);
	}
	$contact_page = get_page_by_path( 'contact' );

	$reg = array(
		'home-hero'          => array(
			'title'  => __( 'Home: big welcome banner', 'sylviasdream-child' ),
			'help'   => __( 'The large welcome area at the top of the Home page, with the main heading, a short introduction and two buttons.', 'sylviasdream-child' ),
			'icon'   => 'eicon-banner',
			'render' => 'sdc_section_home_hero',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'hero_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Main heading', 'sylviasdream-child' ), sdc_get( 'hero_title' ) ),
				array( 'text', 'texts', 'textarea', __( 'Introduction', 'sylviasdream-child' ), sdc_get( 'hero_text' ) ),
				array( 'quote', 'texts', 'textarea', __( 'Short quote (leave empty to hide)', 'sylviasdream-child' ), sdc_get( 'hero_quote' ) ),
				array( 'image', 'photos', 'media', __( 'Photo or book cover (leave empty to show covers of the featured books)', 'sylviasdream-child' ), sdc_el_media_default( 'hero_image' ) ),
				array( 'image_alt', 'photos', 'text', __( 'Describe the photo (for blind readers)', 'sylviasdream-child' ), '' ),
				array( 'cta_label', 'links', 'text', __( 'First button: words', 'sylviasdream-child' ), sdc_get( 'hero_cta_label' ) ),
				array( 'cta_url', 'links', 'text', __( 'First button: goes to (empty = Books page)', 'sylviasdream-child' ), sdc_get( 'hero_cta_url' ) ),
				array( 'cta2_label', 'links', 'text', __( 'Second button: words', 'sylviasdream-child' ), sdc_get( 'hero_cta2_label' ) ),
				array( 'cta2_url', 'links', 'text', __( 'Second button: goes to', 'sylviasdream-child' ), sdc_get( 'hero_cta2_url' ) ),
				array( 'show_covers', 'settings', 'select', __( 'Show book covers when no photo is chosen', 'sylviasdream-child' ), 'yes', $yesno ),
				array( 'heading_tag', 'settings', 'select', __( 'Heading type', 'sylviasdream-child' ), 'h1', $h ),
			),
		),
		'highlights'         => array(
			'title'  => __( 'Highlights strip (four short points)', 'sylviasdream-child' ),
			'help'   => __( 'A thin band with up to four short points, for example "Instant download". Type {count} to show the number of books automatically. An empty heading hides that point.', 'sylviasdream-child' ),
			'icon'   => 'eicon-info-box',
			'render' => 'sdc_section_highlights',
			'fields' => array(
				array(
					'items',
					'texts',
					'repeater',
					__( 'Points', 'sylviasdream-child' ),
					$strip,
					array(
						'fields'      => array(
							array( 'title', 'text', __( 'Heading', 'sylviasdream-child' ) ),
							array( 'text', 'text', __( 'Text', 'sylviasdream-child' ) ),
							array( 'icon', 'select', __( 'Picture', 'sylviasdream-child' ), $icons ),
						),
						'title_field' => '{{{ title }}}',
					),
				),
				array( 'photos_note', 'photos', 'note', __( 'The small pictures are chosen for each point in Texts above.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'This strip has no links.', 'sylviasdream-child' ) ),
			),
		),
		'featured-books'     => array(
			'title'  => __( 'Featured books', 'sylviasdream-child' ),
			'help'   => __( 'A row of books from the shop. The books, covers and prices come from Products, so they are always up to date.', 'sylviasdream-child' ),
			'icon'   => 'eicon-products',
			'render' => 'sdc_section_featured_books',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'books_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'books_title' ) ),
				array( 'text', 'texts', 'textarea', __( 'Introduction', 'sylviasdream-child' ), sdc_get( 'books_text' ) ),
				array( 'photos_note', 'photos', 'note', __( 'Book covers come from each book in Products > Product image. Change a cover there and it changes everywhere.', 'sylviasdream-child' ) ),
				array( 'link_label', 'links', 'text', __( 'Button under the books: words (empty hides it)', 'sylviasdream-child' ), sdc_get( 'books_link_label' ) ),
				array( 'link_url', 'links', 'text', __( 'Button goes to (empty = Books page)', 'sylviasdream-child' ), '' ),
				array( 'count', 'settings', 'number', __( 'How many books', 'sylviasdream-child' ), absint( sdc_get( 'books_count' ) ) ? absint( sdc_get( 'books_count' ) ) : 8 ),
				array( 'columns', 'settings', 'select', __( 'Books in a row (on a computer)', 'sylviasdream-child' ), '4', $cols ),
				array( 'order', 'settings', 'select', __( 'Which books', 'sylviasdream-child' ), 'home', $orders ),
				array( 'category', 'settings', 'text', __( 'Only books from one topic (topic short name, optional)', 'sylviasdream-child' ), '' ),
				array( 'heading_tag', 'settings', 'select', __( 'Heading type', 'sylviasdream-child' ), 'h2', $h2 ),
			),
		),
		'meet-sylvia'        => array(
			'title'  => __( 'Meet Sylvia', 'sylviasdream-child' ),
			'help'   => __( 'A short introduction to Sylvia with an optional photo and a button to her story.', 'sylviasdream-child' ),
			'icon'   => 'eicon-person',
			'render' => 'sdc_section_meet_sylvia',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'about_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'about_title' ) ),
				array( 'text', 'texts', 'textarea', __( 'First paragraph', 'sylviasdream-child' ), sdc_get( 'about_text' ) ),
				array( 'text_2', 'texts', 'textarea', __( 'Second paragraph', 'sylviasdream-child' ), sdc_get( 'about_text_2' ) ),
				array( 'image', 'photos', 'media', __( 'Photo of Sylvia (shown only when chosen)', 'sylviasdream-child' ), sdc_el_media_default( 'about_image' ) ),
				array( 'image_alt', 'photos', 'text', __( 'Describe the photo (for blind readers)', 'sylviasdream-child' ), '' ),
				array( 'cta_label', 'links', 'text', __( 'Button words', 'sylviasdream-child' ), sdc_get( 'about_cta_label' ) ),
				array( 'cta_url', 'links', 'text', __( 'Button goes to', 'sylviasdream-child' ), sdc_get( 'about_cta_url' ) ),
			),
		),
		'heaven-on-earth'    => array(
			'title'  => __( 'Heaven on Earth band', 'sylviasdream-child' ),
			'help'   => __( 'A dark band with one sentence about the ministry in Kenya and a button.', 'sylviasdream-child' ),
			'icon'   => 'eicon-globe',
			'render' => 'sdc_section_heaven_on_earth',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small heading', 'sylviasdream-child' ), sdc_get( 'hoe_eyebrow' ) ),
				array( 'text', 'texts', 'textarea', __( 'Sentence', 'sylviasdream-child' ), sdc_get( 'hoe_text' ) ),
				array( 'image', 'photos', 'media', __( 'Background photo (optional)', 'sylviasdream-child' ), sdc_el_media_default( 'hoe_image' ) ),
				array( 'cta_label', 'links', 'text', __( 'Button words', 'sylviasdream-child' ), sdc_get( 'hoe_cta_label' ) ),
				array( 'cta_url', 'links', 'text', __( 'Button goes to', 'sylviasdream-child' ), sdc_get( 'hoe_cta_url' ) ),
			),
		),
		'dream-partners'     => array(
			'title'  => __( 'Dream Partners (giving)', 'sylviasdream-child' ),
			'help'   => __( 'Invites visitors to become a Dream Partner and shows how many partners there are. The number is changed in Settings > Sylvia\'s Dream. The button goes to the Give page; the payment links on that page are never changed here.', 'sylviasdream-child' ),
			'icon'   => 'eicon-price-table',
			'render' => 'sdc_section_dream_partners',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'give_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'give_title' ) ),
				array( 'text', 'texts', 'textarea', __( 'Text', 'sylviasdream-child' ), sdc_get( 'give_text' ) ),
				array( 'image', 'photos', 'media', __( 'Photo (shown only when the progress bar is switched off)', 'sylviasdream-child' ), sdc_el_media_default() ),
				array( 'image_alt', 'photos', 'text', __( 'Describe the photo (for blind readers)', 'sylviasdream-child' ), '' ),
				array( 'cta_label', 'links', 'text', __( 'Button words', 'sylviasdream-child' ), sdc_get( 'give_cta_label' ) ),
				array( 'cta_url', 'links', 'text', __( 'Button goes to (the Give page)', 'sylviasdream-child' ), sdc_get( 'give_url' ) ),
				array( 'show_progress', 'settings', 'select', __( 'Show the Dream Partner progress bar', 'sylviasdream-child' ), sdc_get_bool( 'give_show_progress' ) ? 'yes' : 'no', $yesno ),
			),
		),
		'latest-devotionals' => array(
			'title'  => __( 'Latest devotionals', 'sylviasdream-child' ),
			'help'   => __( 'Shows the newest Journal posts automatically. Write new posts in Posts > Add New.', 'sylviasdream-child' ),
			'icon'   => 'eicon-posts-grid',
			'render' => 'sdc_section_latest_posts',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'posts_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'posts_title' ) ),
				array( 'photos_note', 'photos', 'note', __( 'Each card shows the post\'s Featured image. Set it on the post itself.', 'sylviasdream-child' ) ),
				array( 'link_label', 'links', 'text', __( 'Button under the posts: words', 'sylviasdream-child' ), sdc_get( 'posts_link_label' ) ),
				array( 'count', 'settings', 'number', __( 'How many posts', 'sylviasdream-child' ), 3 ),
				array( 'category', 'settings', 'text', __( 'Only one topic (topic short name, optional)', 'sylviasdream-child' ), '' ),
				array( 'heading_tag', 'settings', 'select', __( 'Heading type', 'sylviasdream-child' ), 'h2', $h2 ),
			),
		),
		'reader-reviews'     => array(
			'title'  => __( 'Reader reviews', 'sylviasdream-child' ),
			'help'   => __( 'Up to three real reviews. Only use a reader\'s words with their permission. Empty reviews are not shown.', 'sylviasdream-child' ),
			'icon'   => 'eicon-testimonial',
			'render' => 'sdc_section_reviews',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'testimonials_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'testimonials_title' ) ),
				array(
					'items',
					'texts',
					'repeater',
					__( 'Reviews', 'sylviasdream-child' ),
					$reviews,
					array(
						'fields'      => array(
							array( 'quote', 'textarea', __( 'Review words', 'sylviasdream-child' ) ),
							array( 'name', 'text', __( 'Reader name and country', 'sylviasdream-child' ) ),
						),
						'title_field' => '{{{ name }}}',
					),
				),
				array( 'photos_note', 'photos', 'note', __( 'Reviews have no photos.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'Reviews have no links.', 'sylviasdream-child' ) ),
			),
		),
		'newsletter'         => array(
			'title'  => __( 'Newsletter sign-up', 'sylviasdream-child' ),
			'help'   => __( 'Invites readers to receive Sylvia\'s letter by email, with the sign-up box and a Contact button.', 'sylviasdream-child' ),
			'icon'   => 'eicon-mail',
			'render' => 'sdc_section_newsletter',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), sdc_get( 'news_eyebrow' ) ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), sdc_get( 'news_title' ) ),
				array( 'text', 'texts', 'textarea', __( 'Text', 'sylviasdream-child' ), sdc_get( 'news_text' ) ),
				array( 'note', 'texts', 'text', __( 'Small print under the sign-up box', 'sylviasdream-child' ), sdc_get( 'news_note' ) ),
				array( 'photos_note', 'photos', 'note', __( 'This section has no photos.', 'sylviasdream-child' ) ),
				array( 'cta_label', 'links', 'text', __( 'Contact button words', 'sylviasdream-child' ), sdc_get( 'contact_cta_label' ) ),
				array( 'cta_url', 'links', 'text', __( 'Contact button goes to', 'sylviasdream-child' ), sdc_get( 'contact_url' ) ),
				array( 'show_form', 'settings', 'select', __( 'Show the email sign-up box', 'sylviasdream-child' ), 'yes', $yesno ),
			),
		),
		'author-support'     => array(
			'title'  => __( 'Author Support invitation', 'sylviasdream-child' ),
			'help'   => __( 'Invites writers to ask Sylvia for help with their book, with a round photo.', 'sylviasdream-child' ),
			'icon'   => 'eicon-call-to-action',
			'render' => 'sdc_section_promo',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), 'Author Support' ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), 'Have a book on your heart?' ),
				array( 'text', 'texts', 'textarea', __( 'Text', 'sylviasdream-child' ), 'Personal guidance with manuscript preparation, self-publishing, book launches, and marketing.' ),
				array( 'image', 'photos', 'media', __( 'Round photo', 'sylviasdream-child' ), sdc_el_media_default( '', sdc_theme_image( 'desk' ) ) ),
				array( 'image_alt', 'photos', 'text', __( 'Describe the photo (for blind readers)', 'sylviasdream-child' ), "A writer's desk with books and wildflowers" ),
				array( 'cta_label', 'links', 'text', __( 'Button words', 'sylviasdream-child' ), "Let\u{2019}s Talk About Your Book" ),
				array( 'cta_url', 'links', 'text', __( 'Button goes to', 'sylviasdream-child' ), '/author-support-publishing-services/#book-enquiry' ),
				array( 'tinted', 'settings', 'select', __( 'Light gold background', 'sylviasdream-child' ), 'yes', $yesno ),
			),
		),
		'book-showcase'      => array(
			'title'  => __( 'Book showcase with QR code', 'sylviasdream-child' ),
			'help'   => __( 'A card that sends readers to the book showcase website, with a code they can scan with a phone.', 'sylviasdream-child' ),
			'icon'   => 'eicon-barcode',
			'render' => 'sdc_section_showcase',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the heading', 'sylviasdream-child' ), 'Book showcase' ),
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), "Explore Sylvia\u{2019}s Books" ),
				array( 'text', 'texts', 'textarea', __( 'Text', 'sylviasdream-child' ), 'Faith-filled books for readers seeking a deeper relationship with God, renewed identity, freedom from fear and courage for the journey ahead.' ),
				array( 'caption', 'texts', 'text', __( 'Words under the QR code', 'sylviasdream-child' ), 'Scan with your phone camera' ),
				array( 'image', 'photos', 'media', __( 'QR code picture', 'sylviasdream-child' ), sdc_el_media_default( '', get_theme_file_uri( 'assets/images/qr-books.svg' ) ) ),
				array( 'image_alt', 'photos', 'text', __( 'Describe the picture (for blind readers)', 'sylviasdream-child' ), 'QR code: scan to open books.sylviasdream.com' ),
				array( 'cta_label', 'links', 'text', __( 'Button words', 'sylviasdream-child' ), 'Visit the book showcase' ),
				array( 'cta_url', 'links', 'text', __( 'Button goes to', 'sylviasdream-child' ), 'https://books.sylviasdream.com/' ),
			),
		),
		'books-grid'         => array(
			'title'  => __( 'Books page: search, topics and all books', 'sylviasdream-child' ),
			'help'   => __( 'The book search box, the topic buttons and the grid of books used on the Books page.', 'sylviasdream-child' ),
			'icon'   => 'eicon-search',
			'render' => 'sdc_section_book_filters',
			'fields' => array(
				array( 'texts_note', 'texts', 'note', __( 'The words in the search box are changed in Appearance > Customise > Sylvia\'s Dream > Shop and book pages.', 'sylviasdream-child' ) ),
				array( 'photos_note', 'photos', 'note', __( 'Book covers come from each book in Products.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'Each book links to its own page. Buy buttons are on the book pages and are not changed here.', 'sylviasdream-child' ) ),
				array( 'show_filters', 'settings', 'select', __( 'Show the search box and topic buttons', 'sylviasdream-child' ), 'yes', $yesno ),
				array( 'show_grid', 'settings', 'select', __( 'Show the books', 'sylviasdream-child' ), 'yes', $yesno ),
				array( 'count', 'settings', 'number', __( 'How many books', 'sylviasdream-child' ), 30 ),
				array( 'columns', 'settings', 'select', __( 'Books in a row (on a computer)', 'sylviasdream-child' ), '4', $cols ),
				array( 'order', 'settings', 'select', __( 'Order', 'sylviasdream-child' ), 'default', array( 'default' => __( 'Shop order (as on the Books page now)', 'sylviasdream-child' ) ) + array_diff_key( $orders, array( 'home' => 1 ) ) ),
			),
		),
		'page-banner'        => array(
			'title'  => __( 'Page top banner (page title)', 'sylviasdream-child' ),
			'help'   => __( 'The photo banner at the top of a page with the page title. Use only one on each page.', 'sylviasdream-child' ),
			'icon'   => 'eicon-header',
			'render' => 'sdc_section_page_banner',
			'fields' => array(
				array( 'eyebrow', 'texts', 'text', __( 'Small words above the title (empty = menu section)', 'sylviasdream-child' ), '' ),
				array( 'title', 'texts', 'text', __( 'Title (empty = the page name)', 'sylviasdream-child' ), '' ),
				array( 'intro', 'texts', 'textarea', __( 'Short line under the title', 'sylviasdream-child' ), '' ),
				array( 'image', 'photos', 'media', __( 'Banner photo (empty = the page\'s usual photo)', 'sylviasdream-child' ), sdc_el_media_default() ),
				array( 'links_note', 'links', 'note', __( 'The "Home" link in the banner always goes to the Home page.', 'sylviasdream-child' ) ),
			),
		),
		'keep-exploring'     => array(
			'title'  => __( 'Keep exploring (links to other pages)', 'sylviasdream-child' ),
			'help'   => __( 'Three cards that lead visitors to other pages. The cards are chosen automatically for each page.', 'sylviasdream-child' ),
			'icon'   => 'eicon-arrow-right',
			'render' => 'sdc_section_keep_exploring',
			'fields' => array(
				array( 'title', 'texts', 'text', __( 'Heading', 'sylviasdream-child' ), __( 'Keep exploring', 'sylviasdream-child' ) ),
				array( 'photos_note', 'photos', 'note', __( 'These cards have no photos.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The cards link to the Books page, Sylvia\'s story, the ministry, Give and Contact.', 'sylviasdream-child' ) ),
			),
		),
		'kept-section'       => array(
			'title'  => __( 'Section from your page', 'sylviasdream-child' ),
			'help'   => __( 'A part of your page kept exactly as it was. Change its words in Texts and its pictures in Photos. Buttons and payment links stay exactly as they are.', 'sylviasdream-child' ),
			'icon'   => 'eicon-document-file',
			'render' => 'sdc_section_old',
			'fields' => array(
				array( 'markup', 'hidden', 'hidden', '', '' ),
				array( 'texts', 'texts', 'kept_texts', __( 'Words in this section', 'sylviasdream-child' ), array() ),
				array( 'photos', 'photos', 'kept_photos', __( 'Pictures in this section', 'sylviasdream-child' ), array() ),
				array( 'links_note', 'links', 'note', __( 'Buttons and links in this section are kept exactly as they were, so payment, Dream Partner and download links always keep working. Ask your web designer if a button should go somewhere else.', 'sylviasdream-child' ) ),
			),
		),
		'site-header'        => array(
			'title'  => __( 'Site header (logo, menu, phone menu)', 'sylviasdream-child' ),
			'help'   => __( 'The complete website header: logo, main menu with the Books mega menu, search, cart, Give button and the phone menu. Use it in an Elementor header template.', 'sylviasdream-child' ),
			'icon'   => 'eicon-header',
			'render' => 'sdc_el_render_site_header',
			'fields' => array(
				array( 'give_label', 'texts', 'text', __( 'Give button words (empty = website setting)', 'sylviasdream-child' ), '' ),
				array( 'photos_note', 'photos', 'note', __( 'The logo is changed in Appearance > Customise > Site Identity.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The menu is changed in Appearance > Menus.', 'sylviasdream-child' ) ),
				array(
					'utility',
					'settings',
					'select',
					__( 'Thin bar above the header', 'sylviasdream-child' ),
					'',
					array(
						''    => __( 'Website setting', 'sylviasdream-child' ),
						'yes' => __( 'Show', 'sylviasdream-child' ),
						'no'  => __( 'Hide', 'sylviasdream-child' ),
					),
				),
			),
		),
		'site-footer'        => array(
			'title'  => __( 'Site footer', 'sylviasdream-child' ),
			'help'   => __( 'The complete website footer with the about text, social icons, newsletter line and footer menus.', 'sylviasdream-child' ),
			'icon'   => 'eicon-footer',
			'render' => 'sdc_el_render_site_footer',
			'fields' => array(
				array( 'about', 'texts', 'textarea', __( 'About text (empty = website setting)', 'sylviasdream-child' ), '' ),
				array( 'photos_note', 'photos', 'note', __( 'The footer has no photos.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'Footer menus are changed in Appearance > Menus; social links in Appearance > Sylvia\'s Dream Theme Options.', 'sylviasdream-child' ) ),
			),
		),
		'logo'               => array(
			'title'  => __( 'Logo', 'sylviasdream-child' ),
			'help'   => __( 'The Sylvia\'s Dream logo, linking to the Home page.', 'sylviasdream-child' ),
			'icon'   => 'eicon-site-logo',
			'render' => 'sdc_el_render_logo',
			'fields' => array(
				array( 'texts_note', 'texts', 'note', __( 'Without a logo picture the site name and the line under it are shown.', 'sylviasdream-child' ) ),
				array( 'image', 'photos', 'media', __( 'Logo picture (empty = the website logo)', 'sylviasdream-child' ), sdc_el_media_default() ),
				array( 'links_note', 'links', 'note', __( 'The logo always links to the Home page.', 'sylviasdream-child' ) ),
				array( 'width', 'settings', 'number', __( 'Logo width in pixels', 'sylviasdream-child' ), 180 ),
			),
		),
		'menu'               => array(
			'title'  => __( 'Menu (simple list of links)', 'sylviasdream-child' ),
			'help'   => __( 'A simple list of links from one of your menus. For the full main menu with the phone menu, use "Site header" instead.', 'sylviasdream-child' ),
			'icon'   => 'eicon-nav-menu',
			'render' => 'sdc_el_render_menu',
			'fields' => array(
				array( 'title', 'texts', 'text', __( 'Heading above the links (optional)', 'sylviasdream-child' ), '' ),
				array( 'photos_note', 'photos', 'note', __( 'Menus have no photos.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The links are changed in Appearance > Menus.', 'sylviasdream-child' ) ),
				array(
					'location',
					'settings',
					'select',
					__( 'Which menu', 'sylviasdream-child' ),
					'footer_books',
					array(
						'primary'      => __( 'Main menu', 'sylviasdream-child' ),
						'footer_books' => __( 'Footer: Books', 'sylviasdream-child' ),
						'footer_about' => __( 'Footer: Ministry', 'sylviasdream-child' ),
						'footer_legal' => __( 'Footer: Help', 'sylviasdream-child' ),
					),
				),
				array(
					'layout',
					'settings',
					'select',
					__( 'Show the links', 'sylviasdream-child' ),
					'down',
					array(
						'down'   => __( 'One under the other', 'sylviasdream-child' ),
						'across' => __( 'Side by side', 'sylviasdream-child' ),
					),
				),
			),
		),
		'social-icons'       => array(
			'title'  => __( 'Social icons', 'sylviasdream-child' ),
			'help'   => __( 'Facebook, Instagram, YouTube, Patreon, Amazon and email icons. Only the ones with an address are shown.', 'sylviasdream-child' ),
			'icon'   => 'eicon-social-icons',
			'render' => 'sdc_el_render_social',
			'fields' => array(
				array( 'texts_note', 'texts', 'note', __( 'The icons have no words to change.', 'sylviasdream-child' ) ),
				array( 'photos_note', 'photos', 'note', __( 'The icons are drawn by the website.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The addresses are set in Appearance > Sylvia\'s Dream Theme Options > Contact and social links.', 'sylviasdream-child' ) ),
			),
		),
		'buy-ebook'          => array(
			'title'  => __( 'Buy ebook button', 'sylviasdream-child' ),
			'help'   => __( 'The "Buy ebook" button of one book. It uses the payment link saved on that book, so it always goes to the right place.', 'sylviasdream-child' ),
			'icon'   => 'eicon-button',
			'render' => 'sdc_el_render_buy_ebook',
			'fields' => array(
				array( 'label', 'texts', 'text', __( 'Button words', 'sylviasdream-child' ), __( 'Buy ebook', 'sylviasdream-child' ) ),
				array( 'photos_note', 'photos', 'note', __( 'This button has no photo.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The payment link comes from the book\'s "Buy ebook link (Stripe)" box in Products and cannot be changed here. Books without that link show no button.', 'sylviasdream-child' ) ),
				array( 'product', 'settings', 'select', __( 'Which book', 'sylviasdream-child' ), '', 'products' ),
			),
		),
		'amazon-button'      => array(
			'title'  => __( 'Amazon or Kindle button', 'sylviasdream-child' ),
			'help'   => __( 'A button to one book on Amazon, using the Amazon address saved on that book.', 'sylviasdream-child' ),
			'icon'   => 'eicon-button',
			'render' => 'sdc_el_render_amazon',
			'fields' => array(
				array( 'label', 'texts', 'text', __( 'Button words (empty = usual words)', 'sylviasdream-child' ), '' ),
				array( 'photos_note', 'photos', 'note', __( 'This button has no photo.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The Amazon address comes from the book in Products (Amazon paperback, hardcover or Kindle box).', 'sylviasdream-child' ) ),
				array( 'product', 'settings', 'select', __( 'Which book', 'sylviasdream-child' ), '', 'products' ),
				array(
					'format',
					'settings',
					'select',
					__( 'Which edition', 'sylviasdream-child' ),
					'_sdc_amazon_kindle',
					array(
						'_sdc_amazon_kindle'    => __( 'Kindle', 'sylviasdream-child' ),
						'_sdc_amazon_paperback' => __( 'Paperback', 'sylviasdream-child' ),
						'_sdc_amazon_hardcover' => __( 'Hardcover', 'sylviasdream-child' ),
					),
				),
			),
		),
		'contact-form'       => array(
			'title'  => __( 'Contact form', 'sylviasdream-child' ),
			'help'   => __( 'Shows the contact form from your Contact page (the same form, so messages still reach Sylvia).', 'sylviasdream-child' ),
			'icon'   => 'eicon-form-horizontal',
			'render' => 'sdc_el_render_contact_form',
			'fields' => array(
				array( 'title', 'texts', 'text', __( 'Heading above the form (optional)', 'sylviasdream-child' ), '' ),
				array( 'text', 'texts', 'textarea', __( 'Words above the form (optional)', 'sylviasdream-child' ), '' ),
				array( 'photos_note', 'photos', 'note', __( 'The form has no photos.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The form fields and who receives the messages are changed on the Contact page in the normal WordPress editor.', 'sylviasdream-child' ) ),
				array( 'page', 'settings', 'number', __( 'Take the form from page number (the Contact page)', 'sylviasdream-child' ), $contact_page ? (int) $contact_page->ID : 0 ),
			),
		),
		'button'             => array(
			'title'  => __( 'Button', 'sylviasdream-child' ),
			'help'   => __( 'One button in the Sylvia\'s Dream style.', 'sylviasdream-child' ),
			'icon'   => 'eicon-button',
			'render' => 'sdc_el_render_button',
			'fields' => array(
				array( 'label', 'texts', 'text', __( 'Button words', 'sylviasdream-child' ), sdc_get( 'hero_cta_label' ) ),
				array( 'photos_note', 'photos', 'note', __( 'Buttons have no photo.', 'sylviasdream-child' ) ),
				array( 'url', 'links', 'text', __( 'Button goes to (a page such as /my-story/ or a full address)', 'sylviasdream-child' ), sdc_get( 'books_page_url' ) ),
				array(
					'style',
					'settings',
					'select',
					__( 'Look', 'sylviasdream-child' ),
					'',
					array(
						''              => __( 'Gold', 'sylviasdream-child' ),
						'sd-btn--ghost' => __( 'Outline', 'sylviasdream-child' ),
						'sd-btn--light' => __( 'Light (for dark backgrounds)', 'sylviasdream-child' ),
					),
				),
				array(
					'align',
					'settings',
					'select',
					__( 'Position', 'sylviasdream-child' ),
					'left',
					array(
						'left'   => __( 'Left', 'sylviasdream-child' ),
						'center' => __( 'Centre', 'sylviasdream-child' ),
						'right'  => __( 'Right', 'sylviasdream-child' ),
					),
				),
			),
		),
		'partner-progress'   => array(
			'title'  => __( 'Dream Partner progress bar', 'sylviasdream-child' ),
			'help'   => __( 'Only the progress bar showing how many Dream Partners there are. The numbers are changed in Settings > Sylvia\'s Dream.', 'sylviasdream-child' ),
			'icon'   => 'eicon-skill-bar',
			'render' => 'sdc_el_render_progress',
			'fields' => array(
				array( 'texts_note', 'texts', 'note', __( 'The words come from the number of partners and the goal.', 'sylviasdream-child' ) ),
				array( 'photos_note', 'photos', 'note', __( 'The progress bar has no photo.', 'sylviasdream-child' ) ),
				array( 'links_note', 'links', 'note', __( 'The progress bar has no links.', 'sylviasdream-child' ) ),
				array( 'card', 'settings', 'select', __( 'Show on a white card', 'sylviasdream-child' ), 'yes', $yesno ),
			),
		),
	);
	$reg = (array) apply_filters( 'sdc_el_registry', $reg );
	return $reg;
}
