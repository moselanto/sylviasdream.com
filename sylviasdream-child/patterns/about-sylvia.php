<?php
/**
 * Title: About Sylvia band
 * Slug: sylviasdream-child/about-sylvia
 * Categories: sylviasdream
 * Description: Photo with Sylvia’s short biography and a button. Biography wording is a draft for Sylvia’s approval.
 * Keywords: about, biography, sylvia
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"cream-2","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-cream-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center","className":"sd-photo-wrap"} -->
<div class="wp-block-column is-vertically-aligned-center sd-photo-wrap">
<!-- wp:image {"sizeSlug":"large","className":"sd-photo-round"} -->
<figure class="wp-block-image size-large sd-photo-round"><img src="<?php echo esc_url( sdc_theme_image( 'bible' ) ); ?>" alt="An open Bible and journal by a sunny window"/></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"sd-photo-note","layout":{"type":"default"}} -->
<div class="wp-block-group sd-photo-note"><!-- wp:paragraph {"className":"sd-photo-note__title"} -->
<p class="sd-photo-note__title">Eighty-one and still becoming</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"sd-photo-note__text"} -->
<p class="sd-photo-note__text">Writing from Australia to help readers trust God more deeply.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Meet Sylvia</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Called to ministry at fifty-seven</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">Sylvia was born in post-war England, the youngest of six children. She came to know Jesus at thirty-eight and was called to ministry at fifty-seven.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>She travelled to Kenya often, preaching the gospel and visiting prisons, churches and schools. She helped establish Heaven on Earth Ministries for orphans and children in remote villages. Today, at eighty-one, she writes from Australia to help readers trust God more deeply.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/my-story/' ) ); ?>">Read Sylvia’s story</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( sdc_shop_url() ); ?>">One Life: the memoir</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
