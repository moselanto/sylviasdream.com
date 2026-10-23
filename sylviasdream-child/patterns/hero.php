<?php
/**
 * Title: Hero
 * Slug: sylviasdream-child/hero
 * Categories: sylviasdream
 * Description: Large photo with heading, intro, quote and two buttons.
 * Keywords: hero, banner, top
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( sdc_theme_image( 'hero' ) ); ?>","alt":"Sunrise over golden fields in Australia","dimRatio":60,"overlayColor":"ink","minHeight":720,"minHeightUnit":"px","align":"full","className":"sd-hero-cover","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover alignfull sd-hero-cover" style="min-height:720px"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Sunrise over golden fields in Australia" src="<?php echo esc_url( sdc_theme_image( 'hero' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Christian Author · Speaker · Missionary</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-white-color has-text-color">From fear to lasting peace in Christ</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"white","className":"sd-lead"} -->
<p class="has-white-color has-text-color sd-lead">Faith-based books and devotionals by Sylvia Suhr, written from more than twenty years of mission work across Africa and Asia.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"textColor":"gold-light"} -->
<p class="has-gold-light-color has-text-color"><em>“One life can make a difference. Restoring dignity, revealing Jesus.”</em></p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( sdc_shop_url() ); ?>">Explore the Books</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/my-story/' ) ); ?>">Meet Sylvia</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->
