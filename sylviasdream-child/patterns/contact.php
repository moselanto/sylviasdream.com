<?php
/**
 * Title: Contact layout
 * Slug: sylviasdream-child/contact
 * Categories: sylviasdream
 * Description: Email, location and social links on the left; contact form (Jetpack) on the right.
 * Keywords: contact, form, email
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading -->
<h2 class="wp-block-heading">Get in touch</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">Sylvia reads every message personally and replies as soon as she can.</p>
<!-- /wp:paragraph -->
<!-- wp:group {"className":"is-style-sd-card"} -->
<div class="wp-block-group is-style-sd-card">
<!-- wp:paragraph -->
<p><strong>Email</strong><br><a href="mailto:sylviasuhr@gmail.com">sylviasuhr@gmail.com</a></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>Location</strong><br>Australia</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:social-links -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"https://www.instagram.com/sylviasuhr/","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://www.patreon.com/c/sylviasdream","service":"patreon"} /--></ul>
<!-- /wp:social-links -->
<!-- wp:details -->
<details class="wp-block-details"><summary>I did not receive my download link</summary>
<!-- wp:paragraph -->
<p>Check your spam folder first. You can also open My downloads with the email you used to purchase, or contact Sylvia and she will resend it.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details"><summary>Can I buy a paperback?</summary>
<!-- wp:paragraph -->
<p>Yes, most titles are available as paperbacks on Amazon in many countries.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details"><summary>Do you ship internationally?</summary>
<!-- wp:paragraph -->
<p>eBooks are delivered instantly worldwide. Paperbacks are printed and shipped by Amazon in your region.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-sd-card"} -->
<div class="wp-block-group is-style-sd-card">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Send a message</h3>
<!-- /wp:heading -->
<!-- wp:jetpack/contact-form {"subject":"Message from the Sylvia's Dream website"} -->
<div class="wp-block-jetpack-contact-form">
<!-- wp:jetpack/field-name {"label":"Name","required":true} /-->

<!-- wp:jetpack/field-email {"label":"Email","required":true} /-->

<!-- wp:jetpack/field-select {"label":"Topic","options":["General question","A book or download","Speaking invitation","Author Support","Dream Partners and giving"]} /-->

<!-- wp:jetpack/field-textarea {"label":"Message","required":true} /-->

<!-- wp:jetpack/button {"element":"button","text":"Send message"} /-->
</div>
<!-- /wp:jetpack/contact-form -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
