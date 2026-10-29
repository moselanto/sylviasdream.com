# Architecture essentials

One page for anyone about to change this site. Full detail: [ARCHITECTURE.md](ARCHITECTURE.md). Rules: [AGENTS.md](AGENTS.md).

## Stack

WordPress + WooCommerce (books, cart, secure downloads) + Jetpack (Forms, Newsletter). Live: WordPress.com Personal (sylviasdream.com). Dev: cPanel at https://topnotchmall.co.ke/sylviasdream/. Three packages from this repo:

| Package | Version | Owns |
|---|---|---|
| `sylviasdream/` parent theme | 1.0.1 | Document structure, menu locations, fallbacks. Never activated alone. |
| `sylviasdream-child/` child theme | 1.21.0 | All presentation, navigation, WooCommerce styling, performance layer. |
| `sylviasdream-core/` plugin | 0.4.4 | Download protection, redirects, schema, social tags, security hardening, reviewer preview, Dream Partner progress. |

## The one rule

Words, prices, photos and links live in WordPress (pages, blocks, products, Customizer, menus, Settings > Sylvia's Dream), never as literals in PHP. Nothing is invented. Stripe is never touched; every existing Stripe, Dream Partner and thank-you link keeps working.

## Where things are

| To change | Go to |
|---|---|
| Design tokens, layout | `sylviasdream-child/style.css`, `theme.json`, `assets/css/blocks.css`, `assets/css/about.css` |
| Header, menu, phone bar | `header.php`, `inc/navigation.php`, `inc/class-sdc-walker-primary.php`, `assets/js/nav.js` |
| Inner-page header, Keep exploring | `inc/inner-pages.php`, `inc/template-tags.php` |
| Editable theme texts and links | `inc/customizer.php` (Appearance > Customize > Sylvia's Dream) |
| Shop look and product lists | `inc/woocommerce.php`, `assets/css/woocommerce.css` |
| Book-cover alt text | `inc/cover-alt.php` |
| Speed features | `inc/performance.php`, `scripts/build-assets.js`, `assets/js/yt-facade.js` |
| Page layouts | `content/blocks/*.html` (dev), `content/live/*.html` (launch pack) |
| Elementor widgets, header/footer, Theme Options | `inc/elementor/*.php`, `inc/theme-options.php`, `template-parts/site-header.php`, `site-footer.php` (ARCHITECTURE.md 14) |
| Protection, redirects, schema, sharing, security, preview | `sylviasdream-core/inc/` |
| Plugin settings | Settings > Sylvia's Dream (option `sd_core`, `inc/settings.php`) |

## Switches

| Switch | Where | Effect |
|---|---|---|
| `SD_PREVIEW_MODE` = `true` | `wp-config.php`, **dev only** | Signed-in users with `edit_posts` or `sd_reviewer` see Draft products; drafts never buyable; preview pages noindex. Visitors never see drafts. |
| `SD_SECURITY_DISABLE` = array of keys or `'all'` | `wp-config.php` | Turns hardening features off. Keys: `headers`, `csp`, `hsts`, `login_limit`, `form_limit`, `session_expiry`, `xmlrpc`, `rest_users`, `author_enum`, `version`, `cors`, `https`, `password_length`, `upload_mimes`. Also filter `sd_core_security_feature`. |
| `sdc_perf_feature` filter | theme | Turns performance features off. Keys: `min_assets`, `emoji`, `preload`, `img_attrs`, `yt_facade`, `defer`, `wc_assets`. |
| `sdc_elementor_layer` / `sdc_el_simple_editor` filters | theme | Elementor widgets and set-up screen on/off; simple editor screen on/off. Theme Options also switch sticky header, simple editor and the Dashboard guide. |
| `SCRIPT_DEBUG` | `wp-config.php` | Loads readable CSS/JS instead of `.min` files. |
| Redirects checkbox | Settings > Sylvia's Dream | 301 map on/off. **Off until launch day.** |
| Default sharing image | Settings > Sylvia's Dream | Overrides bundled 1200 x 630 `assets/og-default.jpg` (wordmark and tagline "Author · Speaker · Missionary"). |

## Migration safety in brief

- Code builds URLs with `home_url()` and site-relative paths, so it works in the `/sylviasdream/` subfolder on dev and at the root on live.
- `content/live/` is the launch pack: dev addresses rewritten for live, payment buttons marked `#KEEP-LIVE-*` to fill from the same live page.
- Redirects stay off until launch day; protected thank-you/download slugs are noindexed and kept out of sitemaps and search, never redirected.
- Dev server time is about 4.2 s with no page cache; the 1-3 s target needs page caching on the final host.

## Release in brief

Change code, bump versions (child `style.css` + `functions.php`; core header + `SD_CORE_VERSION` + `readme.txt`), add a CHANGELOG entry, run `node scripts/check-theme.js`, check PHP syntax, `bash scripts/build-theme-zip.sh` (also runs `build-assets.js`), upload in WP Admin > Appearance > Themes > Upload > Replace active, smoke-test, QA. CI lints PHP on 8.0-8.3.

## Never do

- Edit the live site before backups, or before Sylvia's written approval.
- Publish products before launch, or define `SD_PREVIEW_MODE` on live.
- Change Stripe, or replace, redirect or delete a payment, Dream Partner or thank-you link.
- Hard-code dev URLs, content or secrets in code.
- Mention the repo to Sylvia, or send her anything without Moses's approval.
