# Architecture essentials

One page for anyone about to change this site. The full picture is in [ARCHITECTURE.md](ARCHITECTURE.md).

## The stack

WordPress (WordPress.com Personal plan) + WooCommerce + Jetpack (Forms, Subscriptions). Three packages from this repo: the parent theme `sylviasdream/` (never edited, never activated alone), the child theme `sylviasdream-child/` (all design), and the plugin `sylviasdream-core/` (anything that must survive a theme change).

## The one rule

Words, prices, photos and links live in WordPress (pages, blocks, products, Customizer, menus), never as literals in PHP. Nothing about books, charity figures or reviews is invented: it comes from Sylvia or the live site.

## Where things are

| Need to change | Go to |
|---|---|
| Colours, type, spacing, layout | `sylviasdream-child/style.css` (sections numbered; dated release blocks at the end) |
| Block styles used in the editor | `sylviasdream-child/assets/css/blocks.css` |
| Header, menu, bottom bar | `header.php`, `inc/navigation.php`, `inc/class-sdc-walker-primary.php`, `assets/js/nav.js` |
| Inner-page header, breadcrumb, Keep exploring, content tidy-up | `inc/inner-pages.php`, `inc/template-tags.php` |
| Footer | `footer.php` + footer menus |
| Text settings | `inc/customizer.php` (Appearance > Customize > Sylvia's Dream) |
| Shop look | `inc/woocommerce.php`, `assets/css/woocommerce.css` |
| Reusable sections | `patterns/*.php` |
| Page layouts as built on the site | `content/blocks/*.html` (pasted into the Code editor) |
| Download protection, redirects, schema, giving progress | `sylviasdream-core/inc/` |

## How a release happens

Change code, bump the version in `style.css` and `functions.php`, add a CHANGELOG entry, run `node scripts/check-theme.js`, commit to `main` (GitHub Actions lints PHP 8.0-8.3), build the zip with `bash scripts/build-theme-zip.sh`, upload it in WP Admin > Appearance > Themes > Add New > Upload > **Replace active with uploaded**, then run the QA checklist.

## Security in one breath

Escape on output, sanitise on input, nonce + `current_user_can()` on every save, no secrets or private data in Git, payment and Stripe settings never touched from code. Details: [SECURITY-AUDIT.md](SECURITY-AUDIT.md).

## Never do

- Edit the live site from code, sync a database over production, or delete thank-you/download pages before past buyers have new links.
- Change Stripe subscriptions, payment links or giving settings.
- Offer anything free, or publish wording, without Sylvia's written approval.
- Send anything to Sylvia without Moses's approval.
