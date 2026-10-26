<?php
/**
 * Title: Explore Sylvia's Books (book showcase link with QR code)
 * Slug: sylviasdream-child/books-showcase
 * Categories: sylviasdream
 * Description: Card linking to books.sylviasdream.com with a scannable QR code (QR hidden on phones, where the button is enough).
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
$sdc_qr = get_theme_file_uri( 'assets/images/qr-books.svg' );
?>
<!-- wp:group {"align":"full","className":"sd-home-band","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull sd-home-band"><!-- wp:group {"className":"sd-band__inner sd-showcase","layout":{"type":"default"}} -->
<div class="wp-block-group sd-band__inner sd-showcase"><!-- wp:columns {"verticalAlignment":"center","className":"sd-showcase__cols"} -->
<div class="wp-block-columns are-vertically-aligned-center sd-showcase__cols"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Book showcase</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Explore Sylvia’s Books</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">Faith-filled books for readers seeking a deeper relationship with God, renewed identity, freedom from fear and courage for the journey ahead.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://books.sylviasdream.com/" target="_blank" rel="noreferrer noopener">Visit books.sylviasdream.com<span class="screen-reader-text"> (opens in a new tab)</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","className":"sd-showcase__qr-col"} -->
<div class="wp-block-column is-vertically-aligned-center sd-showcase__qr-col"><!-- wp:image {"width":"180px","sizeSlug":"full","linkDestination":"custom","className":"sd-showcase__qr"} -->
<figure class="wp-block-image size-full is-resized sd-showcase__qr"><a href="https://books.sylviasdream.com/" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( $sdc_qr ); ?>" alt="QR code: scan to open books.sylviasdream.com" style="width:180px"/></a><figcaption class="wp-element-caption">Scan with your phone camera</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
