# Architecture

How the Sylvia's Dream website is put together. Short version: [ARCHITECTURE-ESSENTIALS.md](ARCHITECTURE-ESSENTIALS.md).

## 1. System context

```
Visitor (phone / desktop)
   |  HTTPS
WordPress.com Personal (sylviasdream.com)          Dev copy: topnotchmall.co.ke/sylviasdream (cPanel)
   |-- Child theme  sylviasdream-child  (design)
   |-- Parent theme sylviasdream        (shell)
   |-- Plugin       sylviasdream-core   (protection, redirects, schema, giving progress)
   |-- WooCommerce  (books, cart, secure downloads)
   |-- Jetpack      (forms, newsletter subscribers)
   |-- Stripe / PayPal (payments; existing Dream Partner subscriptions untouched)
   `-- YouTube, GoFundMe (embeds)
```

The repo never deploys automatically: the Personal plan has no SFTP, SSH, staging or GitHub Deployments. Releases are theme and plugin zips uploaded in WP Admin (see section 8).

## 2. Layers

| Layer | Owns | Changes when |
|---|---|---|
| Content (WordPress) | Pages, blocks, posts, products, menus, Customizer values | Sylvia or Pimofy edits in WP Admin |
| Child theme | All presentation: templates, CSS, patterns, navigation, inner-page header, Keep exploring | Design work |
| Parent theme | Document structure and fallbacks | Rarely |
| Core plugin | Behaviour that must survive a theme switch | Protection, redirects, schema |

## 3. Child theme modules (`sylviasdream-child/inc/`)

| File | Responsibility |
|---|---|
| `customizer.php` | All editable theme texts and links (`sdc_get()`), with defaults |
| `template-tags.php` | Helpers (`sdc_link()`, `sdc_shop_url()`, icons) and the page header band (`sdc_page_header()`) |
| `navigation.php` | Submenu descriptions, Books mega menu "Start here", phone bottom bar, active states |
| `class-sdc-walker-primary.php` | Menu markup: submenu toggles, mega-menu column headings |
| `inner-pages.php` | Section eyebrow, header photo, display-only tidy-up of older content, video grid, Keep exploring |
| `blocks.php` | Pattern category, block styles, theme photos, banner detection |
| `widgets.php` | Newsletter widget areas (sign-up widgets only on the front end) |
| `woocommerce.php` | Shop wrappers, "Return to shop" targets, product display |

Templates: `header.php`, `footer.php`, `front-page.php` (Home blocks, full width), `page.php` (header band + reading column + Keep exploring), `template-full-width.php` (builder pages), `single.php`, `archive.php`, `search.php`, `404.php`, `woocommerce.php`, `template-canvas.php`.

Front end: `style.css` (design tokens in `:root`, numbered sections, release blocks at the end), `assets/css/blocks.css` (also loaded in the editor), `assets/css/woocommerce.css`, `assets/js/nav.js` (drawer, dropdowns, mega-menu placement, search, sticky header; no dependencies).

## 4. Core plugin (`sylviasdream-core/inc/`)

| File | Responsibility |
|---|---|
| `protect-downloads.php` | Keeps legacy download pages and files away from search and casual sharing until buyers move to WooCommerce downloads |
| `redirects.php` | 301 redirects for merged book pages (map in `docs/REDIRECT-MAP.md`) |
| `schema.php` | Structured data (Person, Organization, Book) |
| `dream-partners.php` | `[sd_partner_progress]` shortcode for the 100 Dream Partners goal |
| `settings.php` | Admin settings page (capability and nonce checked) |

## 5. Request flows

### 5.1 Page view
WordPress resolves the page; `page.php` prints `sdc_page_header()` (photo, breadcrumb, eyebrow from the main menu, title) unless the content opens with its own banner, then the content in a 760px column (bands and wide blocks break out), then `sdc_related_links()`. `render_block` and `the_content` filters in `inner-pages.php` adjust display only; saved content is never changed.

### 5.2 Navigation
Desktop: dropdowns open on hover, focus or toggle; Escape closes and hides at once; the Books panel is positioned by `nav.js` to stay on screen. Phones (760px and below): bottom bar Home / Books / Ministry / Give / More; More opens the drawer with accordions.

### 5.3 Buying a book (after launch)
Books page or product > cart > WooCommerce checkout (Stripe / PayPal) > order email with expiring download links (limit and expiry chosen by Sylvia) > My downloads.

### 5.4 Giving
Give page links to the existing Stripe Dream Partner payment links (test placeholders on dev). Giving never goes through the shop cart.

### 5.5 Contact and enquiries
Jetpack Forms on Contact, My Story, Author Support and Heaven on Earth; messages go to Sylvia's email and Jetpack Feedback.

## 6. Data

No custom tables. Content in posts/pages/products, settings in theme mods and plugin options, orders and downloads in WooCommerce, subscribers in Jetpack/WordPress.com. Private data (Stripe exports, buyer and subscriber lists, backups) is never in Git; it lives in the private Google Drive folder.

## 7. Design system

Tokens in `:root`: gold `#b8862b`, text-safe gold `#8a5a14`, cream `#fbf7ef`, ink `#2b2118`; Cormorant Garamond headings, Source Sans 3 body; 1200px site width, 760px reading width, 48px tap targets. Components: page header band, bands (`sd-band`, `--alt`), split, cards, gift box, FAQ, quote band, video grid, Keep exploring, mega menu, bottom bar.

## 8. Build and release

1. `node scripts/check-theme.js` (theme.json, block markup nesting, rules) and PHP lint in GitHub Actions.
2. `bash scripts/build-theme-zip.sh` writes `dist/sylviasdream-child-<version>.zip` (and the core plugin zip).
3. Dev: WP Admin > Themes > Upload > Replace active (cPanel Git deploy is used when the host licence works).
4. Page layouts changed in `content/blocks/` are pasted into the Code editor of the matching page.
5. QA: `docs/QA-CHECKLIST.md`, `docs/PHONE-QA-v4-NAV.md`.
6. Live: only at launch, following `docs/BACKUP-AND-LAUNCH.md`.

## 9. Security architecture

See [SECURITY-AUDIT.md](SECURITY-AUDIT.md) and [SECURITY.md](SECURITY.md).
