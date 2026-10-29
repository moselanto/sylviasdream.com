# Changelog

## 2026-10-29 - Child theme 1.21.0: whole site editable in Elementor (optional)
Requested by Agnes (29 Oct). Presentation only, in the child theme; the core plugin is unchanged (0.4.4). Stripe, Dream Partner and thank-you/download links are untouched: widgets only display links, never rewrite them. WooCommerce shop, product pages, cart drawer and checkout are unchanged. Everything works the same without Elementor or Redux.
- Sylvia's Dream Theme Options (`inc/theme-options.php`, Appearance menu, option `sdc_options`): uses the free Redux Framework when active, otherwise a built-in screen (Settings API). Header/footer template for the whole site, sticky header, brand colours (defaults = design tokens), content width, extra CSS, simple editor screen, dashboard guide box, and the contact email, social links, tagline and Give button, which stay the existing Customizer settings (read from and written back to `sdc_get()`), so nothing already set is lost.
- Elementor header and footer (`inc/elementor/builder.php`): site-wide choice, per-page choice (Elementor Page settings and a "Header and footer" box in the editor: website setting / built in / hide / a template), safe fallback to the built-in header and footer when nothing is chosen, Elementor is off or the template is missing, unpublished, broken or empty; Elementor Pro Theme Builder locations still win. Built-in header and footer moved unchanged to `template-parts/site-header.php` and `template-parts/site-footer.php`; the phone bottom bar, cart drawer and `wp_footer()` stay in `footer.php` in every case. Elementor header templates are sticky (wrapper on `<body>`).
- 25 widgets in a "Sylvia's Dream" panel category (`inc/elementor/registry.php`, `widgets.php`, `widget-base.php`, `sections.php`): Home welcome banner, Highlights strip, Featured books (WooCommerce, random/featured/newest/A-Z/price order), Meet Sylvia, Heaven on Earth band, Dream Partners (with `[sd_partner_progress]`), Latest devotionals, Reader reviews, Newsletter sign-up, Author Support invitation, Book showcase with QR code, Books page search/topics/grid (`[sd_book_filters]` + `[products]`), Page top banner, Keep exploring, Section from your page, Site header (full header with mega menu and mobile drawer), Site footer, Logo, Menu, Social icons, Buy ebook button (reads the product's "Buy ebook link (Stripe)" field), Amazon/Kindle button, Contact form (renders the existing Jetpack form from the Contact page), Button, Dream Partner progress bar. Every widget has Texts, Photos, Links and buttons, Settings, and Style (Colours and text, Buttons, Space and background) panels with plain-English labels and help; defaults are the current Customizer/pattern wording.
- "Section from your page" keeps an existing block section byte for byte (kses-filtered, rendered with `do_blocks()`), lists its words and pictures for editing, and never makes payment, Dream Partner or download button labels or any link editable.
- Appearance > Set up page editor (`inc/elementor/setup.php`): install/activate Elementor and Redux from wordpress.org, create the Elementor default Kit if missing (also an admin notice) and seed its colours and fonts from theme.json, create starter header/footer templates, and "Build this page in Elementor" for Home, Books, My Story, Speaking, Podcasts, Author Support, Heaven on Earth, Give and Contact (the Journal stays the posts page). Building forces a revision first, never runs without an administrator clicking, and "Use old version" switches back. admin-post handlers with nonce + capability checks only (SECURITY-AUDIT S15).
- Simplicity: Dashboard box "How to edit your website"; for non-administrators the Elementor panel hides Advanced, Pro and WordPress widget groups and adverts and uses larger text (switch: Theme Options or filter `sdc_el_simple_editor`). Whole layer switch: filter `sdc_elementor_layer`.
- Pages built with Elementor render full width without the theme page header (`page.php`, `template-full-width.php`, `front-page.php`). New `assets/css/elementor.css` (loaded only with Elementor).
- Docs: `docs/ELEMENTOR-EDITING-GUIDE.md` (plain English for Sylvia), ARCHITECTURE.md section 14, HANDOFF, OPEN-ITEMS, DECISIONS, PROGRESS-LOG, SECURITY-AUDIT S15, README badges.
- Checks: `node scripts/check-theme.js` OK; no PHP runtime could be installed in the sandbox (no root, no internet), so `php -l` was NOT run: every PHP file was checked with a bracket/string/alt-syntax balance script and reviewed by hand; CI runs `php -l` on 8.0-8.3. `build-assets.js` run before the zip.

## 2026-10-29 - Child theme 1.20.0: Journal redesign, side cart drawer, checkout polish, buy-box button fix
Presentation only. Post and product content, Stripe links, Dream Partner links and payment gateways are untouched; products stay Draft.
- Journal (posts page, category/tag/date archives, single posts): new `inc/journal.php` and `assets/css/journal.css` (loaded only on Journal views; body class `sd-journal`). Index: latest post as a hero on the first page, card grid (featured image or the brand placeholder `assets/images/journal-placeholder.svg`, never a broken image), topic pill, date, reading time (200 words/min), excerpt, topic chips (All + categories with posts, "Uncategorized" hidden) and numbered pagination. Single: light hero header (breadcrumb Home > Journal > topic, topic pill, title, author, date, reading time), featured image with caption, 68ch text column with styled headings, quotes, lists, images and captions; share links (Facebook, X, WhatsApp, email; plain share URLs, no scripts) and a Follow on Facebook link (Customizer Facebook URL, else https://www.facebook.com/sylviasdream); author box (author's WordPress Biographical Info, else the existing Customizer "Meet Sylvia" text, omitted when both are empty); previous / next with thumbnails; 3 related posts from the same topic; books + newsletter call to action reusing the Customizer Featured books / Newsletter wording and the Jetpack Subscribe block (Contact button fallback).
- Side cart drawer: new `inc/cart-drawer.php` and `assets/js/cart-drawer.js`. Opens after AJAX add to cart (WooCommerce archive buttons, other shop cards, and the single book page form) and from the header cart icon. Shows cover, title, eBook label, price, remove, subtotal, eBook "no shipping" note, View cart and Checkout, secure-payment line; empty state links to the Books page. Dialog with focus trap, Escape, backdrop close, focus return, scroll lock, live status message; works without JavaScript (plain links). Uses only WooCommerce's own wc-ajax endpoints (add_to_cart, remove_from_cart, get_refreshed_fragments) and the cart fragments filter; no custom AJAX handler. Not shown on Cart and Checkout. Respects "Redirect to the cart page after successful addition". Switches: Customizer checkbox, filters `sdc_cart_drawer_enabled`, `sdc_single_ajax_add_to_cart`.
- Cart, checkout and order received (classic shortcodes): new `inc/checkout.php`. eBook-only carts ask only for first name, last name, email (with "receipt and download link" note) and country; no order notes; heading "Your details" (switch: Customizer, filter `sdc_checkout_short_form`). Trust row (secure payment, instant download) under cart totals and the order summary. Cart page two-column with sticky totals card; checkout details card left and sticky order summary card right; clearer fields. Order received: green "Your eBook is ready" note for paid orders with downloads, downloads box highlighted; My account > Downloads table styled, phone layout stacked.
- Bug: product page buy box "Buy paperback on Amazon" rendered about 220px tall (the old `flex: 1 1 220px` basis became a height inside the 1.19.3 column layout). All buy-box buttons now the normal 48px+ height; Add to cart matches them.
- New Customizer fields (Shop and book pages): cart drawer on/off, drawer eBook note, short eBook checkout on/off, trust notes, order-received download note. Header cart link gains `aria-controls` / `aria-haspopup`.
- Checks: `node scripts/check-theme.js` OK; PHP not available in the sandbox (bracket/string balance review of every changed PHP file, manual review); `node scripts/build-assets.js` run before the zip.

## 2026-10-27 - Core plugin 0.4.1: QR code for two-factor setup
- Two-factor setup (Users > Profile) now shows a scannable QR code of the `otpauth://` address for Google Authenticator, Microsoft Authenticator, Authy or similar. The base32 key and the "open in your authenticator app" link stay as a fallback.
- The QR code is made on this server as inline SVG: new `inc/qr-svg.php` (`sd_core_qr_svg( $text, $size_px )`, `sd_core_qr_matrix()`, `sd_core_qr_kses()`), byte mode, error correction M, automatic version 1-40, all 8 masks with penalty scoring, 4-module quiet zone, dark #2B2116 on white, one `<path>`, `shape-rendering="crispEdges"`, `role="img"` with aria-label "QR code for your authenticator app". No external service, CDN or third-party request.
- Encoder: new `inc/lib/qrcode.php`, a namespaced (`SylviasDreamCore\QR`) port of the MIT-licensed qrcode-generator by Kazuhiko Arase (PHP and JS versions), licence in `inc/lib/qrcode-LICENSE.txt`. Loaded only when a QR code is drawn; any failure returns '' and the key is shown alone.
- Shown only to the profile owner while setup is pending (never after setup, never on another user's profile). Filter `sd_core_2fa_show_qr` (return false to hide it).
- Tests: PHP files parsed with glayzzle php-parser (PHP 8 mode): 0 errors. JS reference (library `js/dist/qrcode.js`) and the PHP port (executed with a small php-parser AST interpreter, since no PHP runtime was available) produced identical module matrices for 10 inputs (versions 1, 2, 8, 10, 11, 15, including a realistic otpauth URI); all 10 PHP SVGs and all 10 JS PNGs decoded with OpenCV back to the exact input. RS block and alignment tables compared programmatically with the JS source: identical. Still to do on dev: scan with a real phone and run `php -l` / CI.

## 2026-10-27 - Core plugin 0.4.0: branded sign-in, two-factor authentication, admin branding
Every new module is switchable with `SD_SECURITY_DISABLE` / the `sd_core_security_feature` filter (keys below). Payments, Stripe, Dream Partner and thank-you links are untouched; products stay Draft.
- New `inc/login.php` (key `login_brand`): split-screen sign-in page in the brand colours. Left (960px and wider): photo (`assets/login-photo.webp`, compressed copy of the theme's mission photo, 115 KB) with dark overlay, "Sylvia's Dream Ministry", "Author & ministry team" pill, "Sharing hope, one page at a time.", one sentence, three check points, "Bringing Heaven Closer to Earth". Right: custom logo (or site name in serif), "Welcome back", white card, show/hide password, Remember me, full-width "Log in", Lost your password?, Back to Sylvia's Dream, Privacy Policy (when set), and a lock note whose minutes come from the real login limit. Lost password, reset password, check email and the verification-code screen use the same design. Title "Sign in | Sylvia's Dream". All text filterable (`sd_core_login_texts`). Uses the theme's existing font stylesheet only; no new third-party requests.
- New `inc/two-factor.php` (key `two_factor`; emergency `define( 'SD_2FA_DISABLE', true );`): optional TOTP two-factor authentication (RFC 6238, pure PHP) from Users > Profile; setup key and otpauth link (no QR image), code required to switch on, 8 hashed one-time recovery codes shown once, password and code required to switch off; branded code screen after the password (no auth cookie until the code is right; 5-minute token, 5 attempts, per-IP limit; Remember me kept); administrator reminder notice; administrators can reset another user's 2FA.
- New `inc/auth-hardening.php`: one generic message for wrong username/email/password, and the normal "check your email" result for unknown accounts on lost password, on wp-login.php and WooCommerce My account (key `login_errors`); Application Passwords for administrators only (`app_passwords`); other sessions ended when a password changes (`session_cleanup`).
- New `inc/admin-brand.php` (key `admin_brand`): "Sylvia's Dream" admin colour scheme (default for new users only), footer "Sylvia's Dream Ministry website · Built and supported by Pimofy Digital" (WordPress version kept), Welcome dashboard widget with quick links, WordPress logo menu hidden for non-administrators.
- Docs: `SECURITY-AUDIT.md` (S12-S14, controls, handler review), `ARCHITECTURE.md` sections 7.1-7.4, README (2FA how-to), `docs/wp-config-sample-additions.php` (`SD_2FA_DISABLE`), plugin readme. PHP syntax checked with php-parser (PHP 8 mode): 0 errors.

## 2026-10-27 - Child theme 1.18.0: book pages matched to the approved prototype
Shop, topic (product category) and single book pages now follow the approved prototype (`prototype/index.html`, also `SylviasDream_Ready_Prototype.html` in the Town library). Everything shown comes from WooCommerce product data or Customizer fields; no book text or price is written into the theme. Payments, Stripe, Dream Partner and thank-you/download links are untouched; products stay Draft.
- Books grid (prototype `.books` / `.book`): 2 columns on phones, 3 from 720px, 4 from 1001px; cover max 290px with the lift-on-hover; title, optional subtitle (2 lines), format pills, then a topic / price row (stacks under 420px); add-to-cart button kept. Books that cannot be bought yet show "View the book".
- Search and topic chips (prototype `.searchbar` / `.filters`): book search (searches products only) and "All + topics" chips from the product categories (Uncategorized hidden; current topic marked with `aria-current`); one swipeable row on phones; 48px targets. Shown on the shop, topic and book-search pages, and on the Books page through the new `[sd_book_filters]` shortcode (added to `content/blocks/books.html` above `[products]`; paste the page again on dev to see it).
- Single book (prototype `.product` / `.buybox`): topic pill linking to the topic page, title, italic subtitle, short description, then a buy box with "eBook price" (downloadable books) or "Price", the price, a currency note (shop currency code), formats, add to cart, Amazon buttons and a download note; Editions pills; description lists shown as gold check lists; new "Book details" table (the product's visible attributes, plus weight/size for print); "You may also like" heading left-aligned. Cover 280px on tablets, 240px on phones.
- Product edit screen, General tab: new fields Subtitle, Amazon paperback, Amazon hardcover and Kindle links (only amazon.* / amzn.* addresses are saved; blank hides the button). Subtitle also read from an attribute named Subtitle; editions from an attribute named Edition(s) or Language(s).
- Customizer > Sylvia's Dream > Shop and book pages: book search placeholder, note under the grid (blank by default), price note (`%s` = currency) and download note (blank hides each).
- Not reproduced (needs Sylvia's content): reader reviews block ("What readers are saying"), the "This book is for you if" list as a separate field (it is styled when written as a list in the description), the translations note under the grid (Customizer field left blank), and the per-format radio choice (each format would need its own product or variation and price).

## 2026-10-27 - Child theme 1.17.0, core plugin 0.3.0: security, preview mode, speed, sharing image
Core plugin 0.3.0
- New `inc/security.php` (hardening, every feature switchable with `SD_SECURITY_DISABLE` or the `sd_core_security_feature` filter): security headers (nosniff, Referrer-Policy, X-Frame-Options SAMEORIGIN, Permissions-Policy, HSTS only over HTTPS), a Content-Security-Policy in Report-Only mode (blocks nothing, so YouTube, Jetpack, Stripe, PayPal and GoFundMe keep working), login limit (5 failed attempts per 15 minutes per IP), password-reset and comment rate limits, session length 8 hours (7 days with Remember me), XML-RPC and pingbacks off, `/wp/v2/users` hidden from visitors, `?author=N` and author archives redirected for visitors, no user sitemap or oEmbed author URL, WordPress version removed, REST CORS header sent for the site's own origin only, HTTP to HTTPS front-end redirect (proxy-aware, no loops), minimum password length 12 (profile, reset, WooCommerce account), upload types limited to images, PDF, EPUB, DOCX, MP3/M4A, MP4, and SVG refused.
- New `inc/preview.php`: reviewer preview mode, only when wp-config sets `define( 'SD_PREVIEW_MODE', true );` (dev only). Signed-in users with `edit_posts` or `sd_reviewer` see Draft books in the shop, categories, search, product blocks and shortcodes, home page and Books mega menu, and can open Draft product pages. Visitors never see drafts; Draft products can never be bought (purchasable off and add-to-cart refused); preview pages are noindex. Product statuses are never changed.
- Sharing: default 1200 x 630 image `assets/og-default.jpg` (wordmark and the theme's default tagline "Author · Speaker · Missionary") for every page without its own image, replaceable in Settings > Sylvia's Dream > Default sharing image; `og:image:width`, `og:image:height`, `og:image:alt`, Twitter title/description/image tags, `summary_large_image`; tagline as the last description fallback; archive, category and posts-page tags. Book pages still share their cover (filter `sd_core_og_use_content_image`).
- Fix: JSON-LD is printed with `JSON_HEX_TAG` so "</script>" written (or entity-encoded) in a title, excerpt or product description can no longer break out of the script tag.
- Redirects: request path sanitised with `esc_url_raw`; redirect targets must be site-relative paths.

Child theme 1.17.0
- New `inc/performance.php` (filter `sdc_perf_feature` switches each part): minified CSS/JS used when built and up to date (`scripts/build-assets.js`, run by `build-theme-zip.sh`; falls back to the source; `SCRIPT_DEBUG` loads the source), emoji script removed, brand font stylesheet and the LCP image (home hero cover or page header photo) preloaded with `fetchpriority="high"`, `decoding="async"` on content images, click-to-play YouTube facade (original iframe kept in `<noscript>`, `assets/js/yt-facade.js`), `comment-reply` async and WooCommerce order-attribution scripts deferred, WooCommerce and WC Blocks stylesheets skipped on pages with no shop content (cart, checkout, account, product, shop, Books page, home and search always keep them).
- Page header photo now has width, height and srcset (no layout shift, smaller file on phones).
- Home and Books mega menu product lists read their statuses from `sdc_product_statuses()` (always "publish" unless preview mode is on).
- Fixes: language boot script JSON is hex-escaped; alt text containing "$1" or a backslash is no longer read as a regex back-reference (`inc/cover-alt.php`); submenu chevron printed through `wp_kses()`.
- Clean-up: Google Fonts URL defined once (`sdc_google_fonts_url()`), formatting aligned, stale `functions.php` version constant (1.16.3 vs style.css 1.16.5) corrected to 1.17.0.

Repo
- `scripts/build-assets.js` (no dependencies), `.min.*` files git-ignored and shipped in zips only. `docs/wp-config-sample-additions.php` lists optional constants. `SECURITY-AUDIT.md` updated.

## 2026-10-26 - Child theme 1.16.3: slideshow alt text
- Jetpack slideshow and gallery images with an empty alt now show the alt text saved in the Media Library (Heaven on Earth slideshows kept `alt=""` in their saved block data).

## 2026-10-26 - Child theme 1.16.2, core plugin 0.2.1
- 23 images on Heaven on Earth, Give, Grace for the Journey and the latest posts now have saved alt text in the Media Library (factual, no names; listed in `docs/ALT-TEXT-AND-SEO-AUDIT.md`).
- Theme: an image block with an empty alt now shows the alt text saved in the Media Library (WordPress does not copy it into blocks already on a page).
- Core plugin: share image addresses are always absolute (My Story's og:image was a relative path).

## 2026-10-26 - Core plugin 0.2.0: social sharing tags and page structured data
- `inc/social.php`: Open Graph and Twitter card tags (title, description, image, URL) on the home page, pages and posts, so shared links show a picture and text. Skipped automatically when Jetpack, Yoast SEO, Rank Math or SEOPress already print them (likely on live with Jetpack).
- Structured data: BlogPosting on posts, WebPage plus BreadcrumbList on pages (added to the existing graph; Home keeps Person, WebSite, Organization).
- Descriptions are never invented: the page excerpt, else the first sentence(s) of the page's own text (160 characters). Images: featured image, else the first image on the page, else the site icon.

## 2026-10-26 - Child theme 1.16.1: automatic alt text for book covers
- `inc/cover-alt.php`: a book-cover image block (`sd-book-cover`) or a WooCommerce product image with no alt text now reads "Book cover of <title> by Sylvia Suhr", using the page or product name already on the site. Alt text set in the Media Library or the block always wins.

## 2026-10-26 - Child theme 1.16.0: redesign handoff, first pass
- Colours aligned with the visual system in `docs/REDESIGN-HANDOFF.docx`: ink #231B14, gold action #775014 (darker, better contrast for buttons and links), gold accent #C18B2D, cream #FCFAF5, cream tint #F4EDDF. Updated in `style.css`, `theme.json` (editor palette), `blocks.css`, `editor.css` and the drawer icons.
- Home hero photo now loads first (`fetchpriority="high"`, never lazy), for a faster first screen (LCP).
- Repo: `prototype/index.html` replaced with the latest prototype (honest "preview only" messages instead of fake success); handoff document saved as `docs/REDESIGN-HANDOFF.docx`; checklist as `docs/LAUNCH-QA-CHECKLIST.md`.
- Not changed on purpose: the handoff suggests a "More" item in the phone bar; it stays out because the header menu button already opens the menu (Moses, 26 Oct).

## 2026-10-26 - Child theme 1.15.0: home hero right side
- The empty right side of the home hero now shows three of Sylvia's book covers (From Fear to Freedom, One Life, SENT), fanned with soft shadows and a warm glow, each linking to its book page. Covers are read from the book pages' own cover images (`inc/hero-books.php`, filter `sdc_hero_book_pages`), so it works on live without media IDs. Wide screens (1100px and up) only; phones and tablets keep the text-only hero. No wording added (links carry the book titles as their accessible names).

## 2026-10-26 - Child theme 1.14.1: phone hero tighter
- Phones: hero spacing and type scale with screen height so the eyebrow, title, intro, quote and both buttons fit above the bottom bar, short phones included; the dev-only shop preview notice is a slim line on phones. No wording changed.

## 2026-10-26 - Child theme 1.14.0: header balanced; hero fits one screen
- Header (desktop): three equal zones (logo | menu | actions) so the menu sits on the true centre line; quieter menu items with a short gold underline for hover and the current page instead of the filled pill; search, cart and Give all 44px tall, Give as a pill with a soft shadow.
- Home hero, laptops and up (13" 1366x768 included): height = screen minus utility bar and header, so the whole hero, buttons included, fits without scrolling; spacing and type scale with screen height so short screens stay balanced.
- Home hero, phones: fills the space between the header and the bottom bar; tighter spacing, smaller title, the two buttons side by side. No wording changed.
- Fix: earlier hero type sizes were overridden by blocks.css (loaded later); the new rules carry enough weight to apply.

## 2026-10-26 - Child theme 1.13.1: hero corner
- Home hero: a rounded corner still showed at the top right. The hero photo and shade layers (and the containers around the hero on Home) now have square corners and fill the hero edge to edge.

## 2026-10-26 - Child theme 1.13.0: home hero balanced
- Home hero (the top "slider"): full bleed with square edges; height fits the first screen so the buttons are in view on laptops (560-760px); stronger left shade fading to the photo on the right; eyebrow with a gold rule; title, intro and quote on balanced line lengths; 54px pill buttons, the outline one on frosted glass; soft fade at the foot. Phones unchanged. No wording changed.

## 2026-10-26 - Child theme 1.12.0: Browse by topic as the prototype; docked phone bar
- Books > Browse by topic: product-category topics now show even while they have no published books, as in prototype v4 (Christian Living, Memoir, Healing & Faith, Missions, Fiction, Children, Devotionals, For Writers, Coming Soon, then All books). Only "Uncategorized" stays hidden. This reverses the 1.8.4 "hide empty categories" rule at Moses's request (26 Oct); "Coming soon" is back because the prototype shows it.
- Dev menu (Appearance > Menus, Primary): Browse by topic reordered to the prototype; From Fear to Freedom and SENT taken out of that group (still under All books and the Books page).
- Phone bottom bar: docked to the bottom edge instead of floating. Full width, hairline top border, light shadow, sentence-case labels, a short gold bar marks the current page, Give shown as a gold icon disc.

## 2026-10-26 - Child theme 1.11.1: button text contrast
- Bold text inside buttons (for example "Download 7 Days to Trusting God with Your Journey" on Your Free Gift) took the general dark-ink colour for bold text and was hard to read on the gold button. Bold, italic and span text inside buttons and file buttons now keeps the button's own text colour.

## 2026-10-26 - Child theme 1.11.0: polish on every page
- `about.css` now loads on every ordinary page and on the Journal (posts page), instead of a list of slugs. Left out on purpose: Home (its own design) and the WooCommerce Shop, Cart, Checkout and My account pages. Opt a page out with the `sdc_polish_page` filter. Styling only; no wording changed.
- Newly covered (published on dev, 26 Oct): Shop For Books; book pages (One Life, SENT, From Fear to Freedom x2, From Chance to Choice x2, The Hidden Key, How to Find the Real You x2, The King's Way, Guard Your Heart, Learning to Recognise God's Voice, When the Word Becomes Life, Wisdom for Soul-Winning, Fast Forward to Success, Manifesting Miracles Now, Angelique, Angelique Finds Her Light, All Shall Be Well); free and download pages (Free Downloads, Free ebook, Free Resource for Mission Training, Free Sample How to Find the Real You, Your Free Gift, When the Word Becomes Life Download); the 14 thank-you pages; Privacy Policy, Terms of Sale, Refund Policy; You are deeply loved by God; TO ENSURE YOU END UP GOING TO HEAVEN; the older Sylvia's Dream page (5022); Journal.
- New rules for older and long-text pages: 760px reading width, heading spacing, quotes and tables as cards, rounded images (book covers keep their shape), file-download blocks as a card with a pill button, calmer book-hero spacing. Journal cards share the Grace for the Journey card style.

## 2026-10-26 - Child theme 1.10.1: fixes from the 1.10.0 check
- Heaven on Earth: the third video ("Jesse's Mission") was invisible on desktop. In a three-video grid the last player kept `justify-self: center` from the odd-video rule and shrank to zero width; it now stretches to fill its column (`style.css`, affects any three-video grid).
- Give: the buttons in the four monthly amount tiles now sit at the foot of each tile, so they line up whatever the text length.

## 2026-10-26 - Child theme 1.10.0: Give, Heaven on Earth, Kenya manual, Contact, Grace for the Journey
- The About-pages stylesheet (`assets/css/about.css`) now also loads on Give, Heaven on Earth, Kenya Ministry Training Manual, Contact and Grace for the Journey, so they share the same banner, section rhythm, cards and forms. Each of these pages also gets a `sd-p-<slug>` body class for page-level styling. Styling only; no wording changed.
- Give: raised giving box, amount tiles with hover state, goal card with gold top edge, rounded impact tiles. FAQ accordions on Give, Heaven on Earth and Contact as clean white rows, highlighted when open.
- Kenya manual: book cover kept to a sensible size with a soft shadow; download card centred with a gold top edge.
- Heaven on Earth: callout with a gold left edge; monthly amount cards centred; GoFundMe widget no longer sits in a full-width white slab; rounded slideshow.
- Grace for the Journey: devotional cards with even image crops, tidier titles and excerpts trimmed to four lines; the sign-up text and image below keep a readable width.
- Contact: rounded form card. Card headings stay left-aligned except the four monthly amount cards, which stay centred.

## 2026-10-26 - Child theme 1.9.2: More videos layout
- The 1.9.1 "three, then a centred two" layout did not apply on dev, where the five players sit inside a nested `.sd-video-grid` (display: contents) and kept the older `grid-column: auto` rule, so all five fell into one row. The layout now targets those players too.

## 2026-10-26 - Child theme 1.9.1: About pages fixes from the page printouts
- Card headings on Speaking, Author Support and My Story all left-aligned (short ones had looked centred, wrapped ones left).
- Space between paragraphs inside cards (Author Support service cards ran together).
- Podcasts "About the interview": the band nested inside More videos is now a white card with normal padding, text top-aligned, no empty slab.
- More videos: on wide screens the last row of two players is centred under the three above.

## 2026-10-26 - Child theme 1.9.0: About pages polish
- New `assets/css/about.css`, loaded only on My Story, Invite Sylvia to speak, Podcasts & Videos and Author Support (body class `sd-about`, slugs filterable with `sdc_about_page_slugs`). Layout and styling only; no wording added or changed.
- Banner: deeper shade, gold rule before the eyebrow, balanced title, larger intro. Sections: steadier spacing, alternating cream / white / warm bands, larger balanced headings with a short gold rule under centred titles, 66-character reading width in split sections, 52px buttons (full width on phones).
- Cards: rounded, softer shadow, gold top accent and lift on hover (off on phones and with reduced motion); numbered story cards get ink circle badges; "Ideal for" lines become a gold-edged callout.
- My Story: framed monogram card, milestone timeline as connected cards, stats centred with a gold top border, large decorative quote marks on quote bands. `my-story.html`: monogram line "Sylvia's portrait here" (placeholder) now shows "Sylvia Suhr".
- Podcasts: video cards framed in white with rounded players; More videos grid as framed tiles; YouTube note as a quiet info line. Speaking: formats checklist as a card. Forms: larger fields, clear focus ring, pill send button; Author Support enquiry sits in a raised card.

## 2026-10-26 - Child theme 1.8.6: phone QA fix
- PHONE-QA-v4-NAV run on dev (Customizer Mobile preview, theme 1.8.5): 12 pass, 1 fail, 6 need a real phone. Fail #9: Books drawer links sat two per row. Phones (760px and below) now stack them in one full-width column with 48px tap height; tablets keep two columns.

## 2026-10-26 - Child theme 1.8.5: phone bottom bar
- Bottom bar on phones is now Home, Books, Ministry, Give. "More" removed (the header menu button already opens the menu); its script removed from `nav.js` and its button from `inc/navigation.php`.
- New look (Moses's request, reference: cisternofhopefoundation.org): floating rounded bar with even side gaps and a soft shadow, four equal columns, icon and label centred on one axis, uppercase labels, active page tinted, Give as a filled gold-dark pill. Very small phones (360px and below) get tighter spacing. Customizer label and docs updated.

## 2026-10-26 - Block validation fixes (content files only, theme stays 1.8.4)
- `content/blocks/author-support.html` and `content/blocks/heaven-on-earth.html`: the `<!-- KEEP: paste the existing Jetpack ... block here -->` placeholders sat loose inside a group or column, so the editor flagged those blocks as invalid. Each placeholder is now wrapped in its own Custom HTML block; the wording of the pages is unchanged. `docs/hoe-segments/README.md` updated to match.
- Gutenberg validator (@wordpress/blocks + block-library under jsdom): all 16 files in `content/blocks/` pass.

## 2026-10-26 - Child theme 1.8.4: Priority 1 clean-up
- Book covers: `book-one-life.html` uses dev media 6604 (`2026/10/one-life-683x1024.png`, was a guessed URL); `book-sent.html` uses media 6605 (`2026/10/sent-683x1024.png`); `book-from-fear-to-freedom.html` uses media 6603 (`2026/10/from-fear-to-freedom.png`). Each image block now carries its media ID.
- Menus: product-category links are hidden on the front end when the category is WooCommerce's default "Uncategorized" or has no published products (`sdc_hide_empty_product_cat_items` in `inc/woocommerce.php`). On dev this hides "Coming soon" (item 6492) and "Uncategorized" (item 6499) under Books > Browse by topic; "Coming soon" returns by itself once a published book is filed under it. Appearance > Menus is unchanged.
- 404 page: "Browse the books" always links to the same Books page as the menu (Customizer "Books page link", default `/shop-for-books/`), with or without WooCommerce.
- Fix (f859bfe): the cover image blocks in the three book files had `width`/`height` on the `<img>` with no matching block attributes, so the editor flagged them as invalid. Removed; all three pages re-applied on dev with no warning.
- Footer widgets: both theme widget areas were already empty on dev; the leftover default widgets (Search, Recent Posts, Recent Comments) sat in Inactive widgets.

## 2026-10-26 - One Life book layout
- `content/blocks/book-one-life.html`: One Life in the same book-page layout as SENT and From Fear to Freedom, using Sylvia's wording only. Not yet applied on dev.

## 2026-10-26 - Child theme 1.8.3: menu fixes
- Language links in menus: a custom link whose URL ends in `#lang-fr`, `#lang-es`, `#lang-id` (or `#lang-en`) switches the whole site to that language, the same as the language selector. Labels stay in their own language; the current language is marked with a tick. A shared address ending in `#lang-fr` opens the page in French.
- Dev menu: Books > Other languages now lists Français, Español and Bahasa Indonesia (language links). From Fear to Freedom and SENT, which had been sitting under Other languages, moved to their proper place with the other books. Ministry now includes Kenya Ministry Training Manual (Agnes, 26 Oct).

## 2026-10-26 - Child theme 1.7.0: About pages to the approved prototype

- Podcasts and Videos rebuilt (content/blocks/podcasts.html): banner "Listen and watch", two featured video cards with captions, note, grid of more videos, Invite Sylvia speaking promo.
- Invite Sylvia to speak (speaking.html): banner, "Messages Sylvia shares" cards with line icons, "In person or online" checklist beside a captioned photo.
- Author Support (author-support.html): banner "Helping Authors Take Their Manuscripts from Draft to Market", intro, three service cards with icons (wording unchanged), "Your Book Matters" split, enquiry form card.
- Theme: line-icon set for cards, video cards/grid, note style; My Story milestone titles gold; stat cards three across from tablets.

## 2026-10-26 - Child theme 1.6.3: My Story to the approved prototype

- content/blocks/my-story.html rebuilt to the client-approved prototype: banner, story with portrait card, Milestones timeline beside a captioned photo, stats and Invite Sylvia to speak. New timeline, photo-caption and stat styles.

## 2026-10-26 - Child theme 1.6.1-1.6.2, parent theme 1.0.1

- 1.6.1: "Explore Sylvia's Books" card on Home and Books: link to books.sylviasdream.com (new tab), wording from the showcase site, crisp SVG QR code in a frame ("Scan with your phone camera"), QR hidden on phones.
- 1.6.2: showcase button "Visit the book showcase" on one line; Books page banner restored ("The bookshop" / "Books by Sylvia Suhr").
- Both themes ship a screenshot.png, so Appearance > Themes shows the design. Parent theme 1.0.1 (screenshot only).

## 2026-10-26 - Child theme 1.6.0, core plugin 0.1.2

- Theme 1.6.0: header menu button back on phones; drawer submenus redesigned (white panel, icon tiles with title and description, Books groups as two-column chips with an "All books" button); footer centred on phones with Books and Ministry side by side.
- Core 0.1.2: version constant matches the plugin header (was 0.1.0 vs 0.1.1); published books with a price carry an Offer (price, currency, availability), plus `inLanguage` and a stable `@id` in the Book structured data.
- Docs: Kenya manual stays published (DECISIONS); host cPanel licence guide (`docs/HOST-CPANEL-LICENCE.md`).

## 2026-10-26 - Child theme 1.5.6-1.5.7 and repository standards

- 1.5.6: responsive fixes from the cross-check (Books CTA strip readable, Podcasts odd video centred with equal 16:9 frames, Author Support artwork not cropped, odd last card spans the row at tablet), phone polish (bottom bar More replaces the header menu button, compact utility bar).
- 1.5.7: tablets (601-760px) keep the header menu button.
- Repository brought to the Pimofy standard used on cisternofhopefoundation, dckabeten.org and smilecommunitycentre.org: ARCHITECTURE-ESSENTIALS, ARCHITECTURE, PRD, SECURITY, SECURITY-AUDIT, CONTRIBUTING, docs/KNOWN-LIMITATIONS, .gitignore, Dependabot, scripts/build-theme-zip.sh; README status, badges, quality gate and docs index updated.

## 2026-10-25 - Child theme 1.5.3-1.5.5: Home sections and Books "Start here"

- 1.5.3: Home gains Latest from Sylvia (3 newest devotionals), Have a book on your heart? (Author Support), Meet Sylvia photo note and "One Life: the memoir" button, "Become a Dream Partner" in the Heaven on Earth band, gold newsletter card (Contact fallback while no sign-up form renders). Dream Partners kept.
- 1.5.4: Books mega "Start here" shows the prototype's three CSS covers (The Report of the Lord, One Life, From Fear to Freedom) until featured books are published; Latest from Sylvia cards side by side with a photo fallback.
- 1.5.5: Start here covers in three equal columns.

## 2026-10-24 (evening) - Child theme 1.3.2-1.5.2: hero, inner pages, Ministry/About layouts, Contact

- 1.3.2: Home hero content on one left edge (eyebrow, heading, intro, quote, buttons) with the prototype shade and sizes.
- 1.4.0-1.4.1: inner-page header band (photo, breadcrumb, eyebrow, title); older content tidied on display (oversized and all-bold paragraphs, 22px HTML block, empty paragraphs, duplicate first H1); video grid; form cards; "Keep exploring" links; breadcrumbs on pattern banners; Podcasts layout fix.
- 1.5.0: prototype layouts built from blocks with Sylvia's current words for Heaven on Earth, Give (gift box with the test giving links), Kenya Ministry Training Manual, My Story, Author Support (content/blocks/*.html). Band, split, card, gift box, FAQ, quote band, callout, checks and monogram styles.
- 1.5.1: page bands light (older dark .sd-band rule no longer applies); Give tiles even; Contact tidy (compact info card, FAQ accordions, social icons) and Topic dropdown; short page-header intros; eyebrow for pages in no menu.
- 1.5.2: grid items no longer offset by WordPress flow-layout margins.

## 2026-10-24 - Child theme 1.3.0-1.3.1: header, mega menu and footer to prototype v4

### Changed
- Desktop header 80px; logo, menu and actions on one centre line; smaller chevrons.
- Books mega panel as the prototype: Browse by topic | Other languages + Readers | Start here; anchored under Books with its arrow, kept on screen by nav.js; "All books ->" bold. Until featured books are published, Start here shows a welcome card (Browse all books / Read Sylvia's story).
- About/Ministry dropdowns: icon tile, bold title, description.
- Footer: even columns; the newsletter widget areas show only sign-up widgets on the front end (WordPress default Archives/Categories widgets can no longer leak in).

### Fixed
- 1.3.1: menu sat 32px low when the WordPress toolbar showed (drawer admin-bar offset applied on desktop).
- 1.3.1: About and Ministry could show together under mixed keyboard and mouse use.

## 2026-10-23 - Child theme 1.2.0-1.2.2: navigation from prototype v4

### Added
- Books **mega menu** (top-level menu item with CSS class `sd-mega`): `#` children become column headings with their own links; published featured books appear as a "Start here" column.
- **Submenu descriptions**: each submenu item's menu Description shows under its title.
- **Phone bottom bar** (up to 760px): Home, Books, Ministry, Give, More (opens the menu drawer); active state, safe-area spacing, 48px+ targets. Header Give button hidden on phones. Customizer: show bar, ministry label/link, mega heading.
- `prototype/index.html` replaced with the updated standalone prototype (v4); review document `project/PROTOTYPE-REVIEW-v4.docx`.

### Fixed
- 1.2.1: links inside a closed mega panel were reachable by Tab.
- 1.2.2: nested mega links stayed hidden when the panel was open (now `visibility: inherit`).

## 2026-10-23 - Child theme 1.1.0: fully editable pages

Built from `docs/NEXT-BUILD-1.1.0.md` in four commits (A to D) so a stopped build cannot lose work.

### Added
- `scripts/check-theme.js`: JSON, block-markup nesting, no `ad-` classes and version checks (`node scripts/check-theme.js`). A GitHub Actions workflow (PHP lint 8.0-8.3 + this script) is written but not yet committed: the GitHub connection lacks the `workflow` permission.
- Prototype photos `assets/images/{hero,bible,mission,give,desk}.jpg` (extracted from prototype v3).
- `theme.json` v2: brand palette only, fonts, fluid sizes, spacing, 760/1200 layout, 48px pill buttons; editor styles so the editor looks like the site.
- 14 block patterns in `patterns/` (category "Sylvia's Dream"): hero, trust strip, featured books, book showcase, About Sylvia, Heaven on Earth, Dream Partners, testimonials, newsletter and contact, page header, FAQ, text and image, call to action strip, contact layout.
- `content/blocks/{home,books,contact}.html` ready to paste into the page Code editor.
- Block styles: Card (group), Book cover shadow (image); core Outline button styled.
- Page templates "Full width (page builder)" and "Canvas (no header or footer)"; Elementor theme-location compatibility (Elementor not required).
- `docs/EDITING-GUIDE.md` for Sylvia.

### Changed
- Footer rebuilt to match the prototype: newsletter line, credit, payment badges row (Customizer: Footer).
- `front-page.php`: shows the Home page's own blocks full width when it has them; otherwise the 1.0.0 sections.
- `page.php`: wide and full-width blocks break out of the 760px column; pages that start with a banner skip the theme page header (one h1).
- Version 1.1.0.

### Not verified
- `php -l` not run (no PHP in the build environment); visual check on dev still to do.

## 2026-10-23 - Handoff update (docs only)

### Added
- `project/PROGRESS-LOG.md`: day-by-day log of 21-23 Oct and where work stopped.
- `docs/NEXT-BUILD-1.1.0.md`: approved brief for child theme 1.1.0 (editable block patterns, theme.json, prototype images, prototype footer, 6-item menu, Elementor-ready templates). Not started.

### Changed
- `HANDOFF.md`: current status, dev site and deployment method (cPanel Git + File Manager, no shell), next steps.
- `project/OPEN-ITEMS.md`, `project/DECISIONS.md`: 23 Oct items and decisions.

## 2026-10-23 - Child theme 1.0.0: full design build

### Added
- `sylviasdream-child` 1.0.0, built from prototype v3:
  - Sticky header: optional utility bar, wordmark and tagline, primary menu with accessible desktop dropdowns (hover, keyboard, Escape), search panel, WooCommerce cart count, Give button. Shrinks and gains a shadow on scroll without layout shift.
  - Mobile and tablet drawer (below 1024px): slide-in, 48px tap targets, accordion submenus, focus trap, Escape and overlay close, scroll lock.
  - Home page: hero with featured covers, trust strip, featured books from published WooCommerce products (hidden when none), about Sylvia, Heaven on Earth band, Dream Partner band with `[sd_partner_progress]`, latest devotionals, reader reviews (only when entered), newsletter and contact band.
  - Templates: `page.php` (page header and 760px column), `single.php`, `index.php`, `archive.php`, `search.php`, `404.php`, `searchform.php`, `template-parts/content-none.php`.
  - Shop: `assets/css/woocommerce.css` and `inc/woocommerce.php` (2/3/4-column cover-first grid, 2:3 covers, single book layout with subtitle from short description, format badges, description in a reading column, four related books, cart, checkout, my account and downloads styling; 48px buttons).
  - Multi-column footer: about text, social links, Books, Ministry and Policies menus, country-only location, copyright with current year.
  - Customizer panel "Sylvia's Dream" with sections for header, every home section, devotionals, shop header and footer. Defaults come only from prototype wording; items awaiting Sylvia's approval default to blank.
  - Google Fonts (Cormorant Garamond, Source Sans 3) with preconnect and `display=swap`, switchable to system fonts. Assets versioned by file modified time.
  - Print styles and `prefers-reduced-motion` support.

### Changed
- Hero defaults now use the prototype wording; second hero button goes to About ("Meet Sylvia").
- All four existing menu locations kept (`primary`, `footer_books`, `footer_about`, `footer_legal`).

### Not verified
- `php -l` could not be run (no PHP in the build environment). Run it on every child-theme PHP file before uploading.

## 2026-10-22 - Core 0.1.1: redirect map corrected

### Fixed
- `sylviasdream-core` 0.1.1: redirects now work when WordPress is installed in a subfolder (the request path is read relative to the site home), so they can be tested on the private preview at `/sylviasdream/`.
- Redirect map: `/my-autobiography/` now goes to `/one-life/` (it is the One Life hardcover page, not a biography); `/how-to-find-the-real-you-2/` removed from redirects because it is a buyer download page; `/manifesting-miracles-now/` removed until Sylvia decides.

### Added
- `how-to-find-the-real-you-2` added to the default protected download pages.

### Changed
- `docs/REDIRECT-MAP.md` revised with the reasons for each redirect and the merges.

## 2026-10-17 - Handover pack

### Added
- `HANDOFF.md`: start-here guide for a person or AI agent taking over (status, ordered next steps, must-not-do list, where off-repo files are, repo map, known gaps).
- `project/CORRESPONDENCE.md`: message history with Sylvia, latest drafted reply, writing style.
- `project/QUOTATION.md` and `project/WEBSITE-AUDIT.md`: full text of quotation v2 and the 15 Oct website review, private details removed.

### Changed
- README, AGENTS.md and `project/README.md` point to `HANDOFF.md`.

## 2026-10-17 - Personal plan, project folder, child theme 0.1.1

### Added
- `project/`: README map, project brief, decision log (15-17 Oct), book catalogue with the 17 Oct Amazon.com.au check and questions, minimum Stripe data request, support and restoration terms with the pre-handover restoration test, 11-phase workflow, call guide, open items.

### Changed
- `docs/DEPLOYMENT.md` and `docs/BACKUP-AND-LAUNCH.md` rewritten for the WordPress.com Personal plan: manual backups (including WooCommerce orders and download access), local build instead of staging, WP Admin uploads instead of GitHub Deployments, manual rollback, no Stripe access for Pimofy, restoration test.
- `docs/CLIENT-CHECKLIST.md`: 25 titles, Stripe exports by Sylvia instead of team access, domain, covers, payment-link approval.
- `docs/QA-CHECKLIST.md`: WordPress.com fee check on a real purchase, past-buyer links, backup and restoration section.
- `scripts/backup-inventory.sh`: marked for the local copy only (no WP-CLI on Personal).
- README and AGENTS.md: Personal plan, project folder, data and free-download rules.

### Fixed
- `sylviasdream-child` 0.1.1: the default second hero button no longer says "Get a Free eBook"; it now links to Heaven on Earth (nothing is free without Sylvia's approval).
- `prototype/verify.js`: reads `index.html` next to the script (or a path argument) instead of a hard-coded sandbox path.

## 2026-10-15 - Prototype v3: Sylvia's feedback

### Changed
- `prototype/index.html` (v3), based on Sylvia's feedback of 15 October and a full read of the live site:
  - Complete catalogue of 25 titles. Titles, subtitles, descriptions, formats and prices taken from the live pages; unknown details are marked "to confirm".
  - Angelique: A Journey of Becoming and Angelique Finds Her Light listed as separate books.
  - Location shown as "Australia" only (removed Peake and South Australia).
  - Dream Partner levels $5, $15, $30 and $50 with the impact wording from the live Heaven on Earth page, plus "Or choose another amount"; 100 Dream Partners goal and the 90% to programs note.
  - No free downloads: Resources items marked "Awaiting Sylvia's approval"; free links and labels removed from the menu, footer, books and newsletter.
  - Author Support page uses Sylvia's live wording verbatim.
  - Heaven on Earth purchase wording flagged for approval; biography and ministry details marked as drafts.
  - Larger body text: 20px desktop, 19px phones.
  - New "For Sylvia's review" page (`#/for-sylvia`) listing changes, pending approvals and book questions.
- README: location, agreed feedback and prototype notes. Client and QA checklists: catalogue questions and approvals.

## 2026-10-14 - Backup and launch runbook

### Added
- `docs/BACKUP-AND-LAUNCH.md`: checklist for backup (Jetpack, XML export, subscribers, Stripe exports and payment-link map), day-1 protection, staging, live-site drafts, switch-over, rollback and post-launch Stripe checks.
- `scripts/backup-inventory.sh`: read-only WP-CLI inventory (pages, media, menus, theme settings, plugins, Author Support content) and database export.

## 2026-10-14 - Scaffold

### Added
- `sylviasdream` parent theme 1.0.0: document shell, menus (primary + three footer menus), image sizes, fallback templates.
- `sylviasdream-child` 0.1.0: warm gold design tokens, large body text, sticky header with accessible mobile menu, Customizer-driven home hero, full footer (books, about, contact, social, newsletter, policies), WooCommerce wrapper.
- `sylviasdream-core` 0.1.0: hides 18 thank-you / download pages from search, sitemap and site search (pages keep working for past buyers); draft 301 redirect map (off by default); JSON-LD for Sylvia, the website, Heaven on Earth and each book; `[sd_partner_progress]` and `[sd_charity_reg]` shortcodes; Settings > Sylvia's Dream.
- Docs: deployment, client checklist, redirect map, QA checklist.
