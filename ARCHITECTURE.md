# Architecture

How the Sylvia's Dream website is built. One-page version: [ARCHITECTURE-ESSENTIALS.md](ARCHITECTURE-ESSENTIALS.md). Covers parent theme 1.0.1, child theme 1.17.0 and core plugin 0.3.0. Last updated 27 October 2026.

## 1. System context

```
Visitor (phone / desktop)
   |  HTTPS
   v
WordPress site
   |-- Child theme   sylviasdream-child  (presentation, navigation, performance)
   |-- Parent theme  sylviasdream        (shell)
   |-- Core plugin   sylviasdream-core   (protection, redirects, schema, sharing,
   |                                      security, preview, giving progress)
   |-- WooCommerce   books, cart, checkout, secure downloads
   |-- Jetpack       Forms, Newsletter (subscribers live on WordPress.com)
   |-- Stripe / PayPal through WooCommerce; existing Stripe payment and
   |   Dream Partner links (buy.stripe.com) used as they are
   `-- Embeds: YouTube, GoFundMe widget (Heaven on Earth), Google Fonts
```

| Environment | Address | Notes |
|---|---|---|
| Dev | https://topnotchmall.co.ke/sylviasdream/ | cPanel host, WordPress in a subfolder, password-gated, search engines discouraged. All build work. No page cache; Site Health reports about 4.2 s server response. |
| Live | https://sylviasdream.com | WordPress.com Personal: no staging, SFTP/SSH, GitHub Deployments or one-click restore. Currently Twenty Twenty; WooCommerce not installed. Untouched until approval. |

Nothing deploys automatically. Releases are zips uploaded in WP Admin (section 11).

## 2. Layers

| Layer | Owns | Changes when |
|---|---|---|
| Content (WordPress) | Pages, blocks, posts, products, menus, Customizer values, plugin settings | Sylvia or Pimofy edits in WP Admin |
| Child theme | Templates, CSS, patterns, navigation, page header, WooCommerce presentation, performance | Design work |
| Parent theme | Document structure, menu locations, fallbacks | Rarely |
| Core plugin | Behaviour that must survive a theme switch | Protection, SEO, security, launch tooling |

The child theme and core plugin talk through one filter: the theme asks `sdc_product_statuses` which product statuses to list (default `publish`), and the core plugin adds `draft` in preview mode.

## 3. Child theme (`sylviasdream-child/`)

### 3.1 Bootstrap (`functions.php`)

- Constants: `SYLVIASDREAM_CHILD_VERSION` (`1.17.0`), `SYLVIASDREAM_CHILD_DIR`, `SYLVIASDREAM_CHILD_URI`.
- `sdc_setup()` (`after_setup_theme`, 20): text domain, WooCommerce support (thumbnail 600, single 900, grid 4 columns), gallery zoom/lightbox/slider, `align-wide`, `responsive-embeds`, `wp-block-styles`, editor styles (`editor.css`, `blocks.css`), menu locations `primary`, `footer_books`, `footer_about`, `footer_legal`.
- `sdc_assets()` (`wp_enqueue_scripts`, 20): Google Fonts (if the Customizer setting `use_google_fonts` is on), `style.css`, `woocommerce.css` (when WooCommerce is active), `blocks.css`, `about.css` (ordinary pages and the Journal, see `sdc_is_about_page()` and filter `sdc_polish_page`), `nav.js` (deferred, footer). Every asset goes through `sdc_asset()` so the minified build is used when present.
- `sdc_asset_version()` versions files by modification time.
- Loads `inc/customizer.php`, `performance.php`, `blocks.php`, `template-tags.php`, `class-sdc-walker-primary.php`, `navigation.php`, `widgets.php`, `inner-pages.php`, `languages.php`, `hero-books.php`, `cover-alt.php`, and `woocommerce.php` only when WooCommerce is active.

### 3.2 Modules (`inc/`)

| File | Responsibility |
|---|---|
| `customizer.php` | All editable theme texts and links, read with `sdc_get()` / `sdc_get_bool()` |
| `template-tags.php` | Helpers (`sdc_link()`, `sdc_shop_url()`, icons) and the inner-page header band |
| `navigation.php` | Submenu descriptions (`nav_menu_item_title`), Books mega menu "Start here" column (up to 3 featured products, CSS-drawn placeholder covers until products are published), phone bottom bar (Home, Books, Ministry, Give) with `aria-current`, `sd-has-dock` body class |
| `class-sdc-walker-primary.php` | Primary menu markup: submenu toggles, mega-menu column headings |
| `inner-pages.php` | Section eyebrow, header photo, display-only tidy-up of older content, video grid, Keep exploring |
| `blocks.php` | Pattern category, block styles, theme photos, banner detection |
| `widgets.php` | Newsletter widget areas |
| `languages.php` | Language selector (English, Français, Español, Bahasa Indonesia) and its boot script (JSON hex-escaped since 1.17.0) |
| `hero-books.php` | Three book covers on the home hero (wide screens), read from the book pages; filter `sdc_hero_book_pages` |
| `cover-alt.php` | Alt text "Book cover of <title> by Sylvia Suhr" for cover blocks (`sd-book-cover`) and product images with empty alt; Media Library alt fills empty alt in image, slideshow and gallery blocks. Typed alt text always wins. |
| `woocommerce.php` | `sdc_product_statuses()`, 2:3 thumbnails, 4 columns, 12 per page, related products, format badges (from a Format attribute, else "eBook" for downloadable), single product layout, cart-count fragment, breadcrumb markup, `sdc_home_products()`, "Return to shop" to the Books page, hides "Uncategorized" product-category menu items |
| `performance.php` | Front-end performance layer (section 6) |

Templates: `header.php`, `footer.php`, `front-page.php`, `page.php`, `template-full-width.php`, `template-canvas.php` (no dock), `single.php`, `archive.php`, `search.php`, `searchform.php`, `404.php`, `woocommerce.php`, `index.php`. Assets: `style.css`, `assets/css/{blocks,about,woocommerce,editor}.css`, `assets/js/{nav,lang,yt-facade}.js`. Page layouts: `content/blocks/*.html`.

## 4. Core plugin (`sylviasdream-core/`)

`sylviasdream-core.php` defines `SD_CORE_VERSION` (`0.3.0`), `SD_CORE_DIR`, `SD_CORE_URL` and loads, in order: `settings.php`, `protect-downloads.php`, `redirects.php`, `schema.php`, `dream-partners.php`, `social.php`, `security.php`, `preview.php`.

| File | Responsibility | Main hooks |
|---|---|---|
| `settings.php` | Settings > Sylvia's Dream page, option `sd_core`, sanitising, `sd_core_opt()` | `admin_menu`, `admin_init` |
| `protect-downloads.php` | Thank-you / download pages keep working but get `noindex, nofollow` (meta and `X-Robots-Tag`), and are removed from the Jetpack sitemap, the core sitemap and site search | `wp_robots` 99, `template_redirect`, `jetpack_sitemap_skip_post`, `wp_sitemaps_posts_query_args`, `pre_get_posts` |
| `redirects.php` | 301 map for merged pages, subfolder-aware, off by default | `template_redirect` 1 |
| `schema.php` | JSON-LD: Person, WebSite, Organization on Home; Book (with Offer when published and priced) on product pages; graph filter `sd_core_schema_graph` | `wp_head` 20 |
| `social.php` | Open Graph and Twitter tags; BlogPosting, WebPage and BreadcrumbList nodes | `wp_head` 5, `sd_core_schema_graph` |
| `dream-partners.php` | `[sd_partner_progress]` and `[sd_charity_reg]` shortcodes | `add_shortcode` |
| `security.php` | Hardening module (section 7) | many, see section 7 |
| `preview.php` | Reviewer preview mode (section 8) | `pre_get_posts` 20, `posts_where`, WooCommerce filters, `wp_robots` 100 |

### 4.1 Settings (option `sd_core`)

| Key | Default | Use |
|---|---|---|
| `partners_current` | 0 | Dream Partners so far (progress bar) |
| `partners_goal` | 100 | Dream Partner goal |
| `charity_name` | Heaven on Earth Ministries | Organization schema, `[sd_charity_reg]` |
| `charity_reg` | empty | Registration line; blank until confirmed |
| `redirects_enabled` | 0 | Turns the redirect map on (launch day only) |
| `protected_slugs` | 19 thank-you / download slugs (audit 13 Oct 2026) | One per line; protected pages |
| `og_default_image` | empty | Default sharing image URL; empty uses `assets/og-default.jpg` |

Saved through the Settings API (`options.php`), so WordPress checks the nonce and `manage_options`; the screen itself also checks `manage_options`. `sd_core_sanitize()` uses `absint`, `sanitize_text_field`, `sanitize_title` and `esc_url_raw`.

## 5. Request flows

### 5.1 Page view
1. `template_redirect`: HTTPS redirect (-1), author-enumeration block (0), redirects (1), protected-page `X-Robots-Tag` (10).
2. `send_headers`: security headers (front end only).
3. `wp_head`: preload links (2), social tags (5), JSON-LD (20); generator tag removed; emoji script removed.
4. `page.php` prints the header band (unless the content starts with its own banner), the content in a 760px reading column, then Keep exploring. `render_block` and `the_content` filters (alt text, YouTube facade, tidy-up) change display only; saved content is never written.

### 5.2 Buying a book (after launch)
Books page or product > cart > WooCommerce checkout (Stripe / PayPal) > order email with expiring download links (limit and expiry chosen by Sylvia) > My account > Downloads. Card data never touches the theme or plugin. Draft products are never purchasable.

### 5.3 Giving
The Give page uses the existing Stripe Dream Partner payment links (on dev only the existing A$5 monthly link is live; other levels wait for links from Sylvia). Giving never goes through the cart. The progress bar reads the count entered in Settings.

### 5.4 Forms and newsletter
Jetpack Forms (Contact, My Story, Author Support, Heaven on Earth) handle their own nonce, sanitising and Akismet spam checks. Newsletter subscribers stay in Jetpack on the live WordPress.com site; dev is never connected to it.

## 6. Performance layer (child `inc/performance.php`)

Every feature is on by default and can be switched off with `add_filter( 'sdc_perf_feature', fn( $on, $feature ) => 'yt_facade' === $feature ? false : $on, 10, 2 );`.

| Key | What it does |
|---|---|
| `min_assets` | `sdc_asset()` loads `*.min.css` / `*.min.js` when the file exists and is at least as new as its source; otherwise the source. `SCRIPT_DEBUG` always loads the source. Built by `scripts/build-assets.js` (conservative minifier, no dependencies; removes comments and whitespace only), which `build-theme-zip.sh` runs. `.min` files are git-ignored and ship only in the zip. |
| `emoji` | Removes the emoji detection script, styles, feed/email staticizing and the emoji CDN DNS prefetch. |
| `preload` | Preloads the Google Fonts stylesheet (when used) and the LCP image (home hero cover block, else the inner-page header photo) with `imagesrcset`, `imagesizes` and `fetchpriority="high"`. |
| `img_attrs` | Adds `decoding="async"` to content images. WordPress core adds `loading="lazy"` and width/height; the mega menu and YouTube thumbnails are also lazy. |
| `yt_facade` | Replaces YouTube embed iframes with a click-to-play thumbnail button (`i.ytimg.com` thumbnail); the original iframe stays in `<noscript>`; `assets/js/yt-facade.js` (deferred) loads the player with `autoplay=1` on click. No YouTube scripts before the click. |
| `defer` | `comment-reply` async; `wc-order-attribution` and `sourcebuster-js` deferred. Filter `sdc_defer_script_handles`. |
| `wc_assets` | Dequeues `woocommerce-general`, `woocommerce-layout`, `woocommerce-smallscreen`, `wc-blocks-style`, `wc-blocks-vendors-style` on pages with no shop content. Kept on shop, product, category, cart, checkout, My account, front page, search, the Books page, and any page with WooCommerce blocks or shortcodes. Filter `sdc_request_needs_wc`. The theme's own `woocommerce.css` and the header cart stay. |

Also: `nav.js` and `yt-facade.js` are deferred; asset URLs are versioned by file time; Google Fonts preconnect.

**Performance target and hosting.** The target is a 1-3 s page load. The dev host has no page cache and about 4.2 s server response time (Site Health, `docs/ALT-TEXT-AND-SEO-AUDIT.md`), which no theme change can remove. Reaching the target needs page caching on the final host (WordPress.com provides its own caching on live); measure again on live after launch.

## 7. Security module (core `inc/security.php`)

`sd_core_security_on( $feature )` returns false when `SD_SECURITY_DISABLE` is `'all'` or an array containing the key, then applies filter `sd_core_security_feature`. Uses WordPress APIs only.

| Key | Control | Hook | Tuning |
|---|---|---|---|
| `headers` | `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `X-Frame-Options: SAMEORIGIN`, `Permissions-Policy` (camera, microphone, geolocation, USB, interest-cohort off; payment for self and Stripe) | `send_headers` (front end only) | `sd_core_security_headers` |
| `hsts` | `Strict-Transport-Security: max-age=31536000` over HTTPS only (no includeSubDomains, no preload) | same | |
| `csp` | `Content-Security-Policy-Report-Only` (never blocks) | same | |
| `login_limit` | 5 failed logins per 15 minutes per IP, then refused | `authenticate` 30, `wp_login_failed` | `sd_core_login_limit` |
| `form_limit` | Password-reset requests 5 per hour per IP; comments 10 per 10 minutes per IP (moderators exempt, HTTP 429) | `lostpassword_post`, `preprocess_comment` 1 | `sd_core_lostpassword_limit`, `sd_core_comment_limit` |
| `session_expiry` | Login cookie 8 hours, 7 days with Remember me | `auth_cookie_expiration` 20 | |
| `xmlrpc` | XML-RPC off, pingback methods and `X-Pingback` removed | filters | |
| `rest_users` | `/wp/v2/users` routes hidden from signed-out visitors | `rest_endpoints` | |
| `author_enum` | `?author=N` and author archives 301 to home for visitors; no users sitemap; no oEmbed `author_url` | `template_redirect` 0 and filters | |
| `version` | WordPress generator tag removed | | |
| `cors` | REST API sends CORS headers only for the site's own origin | `rest_pre_serve_request` 9 | `sd_core_cors_origins` |
| `https` | HTTP to HTTPS 301 on the front end when the home URL is https (skips AJAX, cron, REST, CLI, proxy-reported HTTPS) | `template_redirect` -1 | |
| `password_length` | Minimum 12 characters on profile save, password reset and WooCommerce account details; hashing stays with WordPress core | user/WooCommerce validation hooks | `sd_core_min_password_length` (floor 8) |
| `upload_mimes` | Upload allow-list: JPG, PNG, GIF, WebP, AVIF, ICO, PDF, EPUB, DOCX, MP3/M4A/M4B, MP4/M4V; SVG/SVGZ refused | `upload_mimes` 99, `wp_handle_upload_prefilter` | `sd_core_upload_mimes`, `sd_core_allow_svg` |

Rate limits are stored in transients keyed by an MD5 of bucket and IP (no raw IPs stored). The IP is `REMOTE_ADDR` only; behind a proxy correct it with `sd_core_client_ip`. Findings and the OWASP mapping are in [SECURITY-AUDIT.md](SECURITY-AUDIT.md).

## 8. Reviewer preview mode (core `inc/preview.php`)

Purpose: let reviewers see the shop exactly as it will look at launch while every product stays Draft.

- **Off** unless `wp-config.php` has `define( 'SD_PREVIEW_MODE', true );`. Dev site only; never on live.
- Active only for signed-in users with `edit_posts` or the `sd_reviewer` capability (filter `sd_core_preview_can_view`). The plugin does not create an `sd_reviewer` role; grant the capability with a role manager if a reviewer should not have `edit_posts`.
- When active, Draft products appear in the main product queries (shop, categories, tags, single product), in search (only Draft *products* are added, with a `$wpdb->prepare()` clause), in `[products]` shortcodes, query-loop and product-grid blocks, and on the home page and Books mega menu (through `sdc_product_statuses`).
- Draft products are **never purchasable**, for anyone and whether or not preview is on: `woocommerce_is_purchasable` and `woocommerce_variation_is_purchasable` return false for non-published products, and `woocommerce_add_to_cart_validation` refuses them (including direct `?add-to-cart=` requests) with the notice "This book is not on sale yet."
- Preview requests get `noindex, nofollow`; Draft product pages show a "Preview: this book is still a draft" note.
- Product statuses are never changed.

## 9. Social sharing and structured data (core `inc/social.php`, `inc/schema.php`)

- Open Graph (`og:site_name`, `og:type`, `og:title`, `og:description`, `og:url`, `og:image` with width, height and alt, `og:locale`) and Twitter tags (`summary_large_image` when there is an image) on Home, singular pages and posts, the posts page, term archives and post-type archives.
- Skipped when Yoast SEO, Rank Math, SEOPress or Jetpack Open Graph already print them (filter `sd_core_og_handled_elsewhere`).
- Description: the excerpt, else the page's own first sentence(s) up to 160 characters, else the site tagline. Nothing is invented.
- Image order: featured image, else the first image in the content (so book pages share their cover; filter `sd_core_og_use_content_image`), else the default sharing image.
- Default sharing image: bundled `assets/og-default.jpg`, 1200 x 630, the wordmark and the theme's default tagline "Author · Speaker · Missionary". Override in Settings > Sylvia's Dream > Default sharing image (filter `sd_core_default_og_image`).
- Relative image URLs are made absolute with the site's scheme and host only (`sd_core_absolute_url()`), so the subfolder is not doubled on dev.
- JSON-LD is printed with `JSON_HEX_TAG | JSON_HEX_AMP` so content cannot close the script element.

## 10. Migration safety

| Mechanism | How it protects the move |
|---|---|
| Relative URLs in code | All links are built from `home_url()`, theme file URIs and site-relative paths; the redirect path lookup strips the home path, so the same code works in `/sylviasdream/` on dev and at the root on live. |
| Launch content pack | `content/live/` repeats `content/blocks/` with `https://topnotchmall.co.ke/sylviasdream/` and `/sylviasdream/` rewritten for live. Regenerate it whenever `content/blocks/` changes. It contains no payment-link addresses: buttons are `#KEEP-LIVE-STRIPE-BUTTON` and `#KEEP-LIVE-DREAM-PARTNER-LINK-*`, filled from the same live page at paste time. |
| Redirects off until launch | `redirects_enabled` defaults to 0. Map: `/home/` and `/new-home/` to `/`; `/sylvias-dream/` and `/from-fear-to-freedom/` to `/from-fear-to-freedom-3/`; `/the-hidden-key-to-healing-and-breakthrough/` to its `-3` page; `/my-autobiography/` to `/one-life/`. Targets must be site-relative (start with `/`, not `//`) and go through `wp_safe_redirect()`. Thank-you pages are never in the map. |
| Protected download slugs | Live slugs are used (for example `all-shall-be-well` is the thank-you page on live). Protected pages stay reachable for past buyers. |
| Draft-first | Products stay Draft; preview mode shows them to reviewers without publishing. |
| Rollback | Reactivate Twenty Twenty, Redirects off, reassign the old menu and front page. |

Plan: [docs/MIGRATION-PLAN.md](docs/MIGRATION-PLAN.md). Runbook: [docs/BACKUP-AND-LAUNCH.md](docs/BACKUP-AND-LAUNCH.md).

## 11. Filters and constants reference

**Constants**

| Constant | Defined in | Purpose |
|---|---|---|
| `SYLVIASDREAM_CHILD_VERSION`, `_DIR`, `_URI` | child `functions.php` | Version and paths |
| `SD_CORE_VERSION`, `SD_CORE_DIR`, `SD_CORE_URL` | core main file | Version and paths |
| `SD_PREVIEW_MODE` | `wp-config.php` (dev only) | Reviewer preview |
| `SD_SECURITY_DISABLE` | `wp-config.php` | Hardening keys to switch off, or `'all'` |
| `FORCE_SSL_ADMIN` | `wp-config.php` | Admin and login over HTTPS (recommended) |
| `DISALLOW_FILE_EDIT` | `wp-config.php` | No code editor in WP Admin (recommended on self-managed hosts) |
| `SCRIPT_DEBUG` | `wp-config.php` | Readable CSS/JS instead of `.min` |

**Core filters:** `sd_core_security_feature`, `sd_core_security_headers`, `sd_core_client_ip`, `sd_core_login_limit`, `sd_core_lostpassword_limit`, `sd_core_comment_limit`, `sd_core_cors_origins`, `sd_core_min_password_length`, `sd_core_upload_mimes`, `sd_core_allow_svg`, `sd_core_preview_can_view`, `sd_core_redirect_map`, `sd_core_schema_graph`, `sd_core_og_handled_elsewhere`, `sd_core_og_use_content_image`, `sd_core_default_og_image`.

**Theme filters:** `sdc_perf_feature`, `sdc_defer_script_handles`, `sdc_request_needs_wc`, `sdc_product_statuses`, `sdc_polish_page`, `sdc_hero_book_pages`.

## 12. Data

No custom tables. Content in posts, pages and products; theme settings in theme mods; plugin settings in option `sd_core`; rate-limit counters in `sd_rl_*` transients; orders and download permissions in WooCommerce; subscribers in Jetpack/WordPress.com. Private data (Stripe exports, buyer and subscriber lists, backups) is never in Git; it lives in the private Google Drive folder.

## 13. Build and release

1. `node scripts/check-theme.js`.
2. PHP syntax check (`php -l` where available; CI `.github/workflows/checks.yml` runs it on PHP 8.0-8.3).
3. `bash scripts/build-theme-zip.sh` (runs the check and `build-assets.js`, writes `dist/sylviasdream-child-<version>.zip` and `dist/sylviasdream-core-<version>.zip`).
4. Dev: WP Admin upload with Replace active, or cPanel Git plus File Manager.
5. Paste changed `content/blocks/*.html` into the matching dev page.
6. QA: `docs/QA-CHECKLIST.md`, `docs/LAUNCH-QA-CHECKLIST.md`, `docs/PHONE-QA-v4-NAV.md`.
7. Live: only at launch, per `docs/MIGRATION-PLAN.md`.
