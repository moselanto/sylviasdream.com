# Sylvia's Dream (child theme) - v1.2.2

The actual site design. **Activate this theme** (the parent `sylviasdream` must also be installed). Built from the approved prototype v3 (`prototype/index.html`): warm gold and cream, Cormorant Garamond headings, Source Sans 3 body text at 19-20px.

## Elementor (1.21.0, fixes in 1.21.1, 1.21.2, 1.21.3 and 1.22.0)

Optional. Appearance > Set up page editor installs Elementor and Redux, prepares Elementor's colours and fonts, creates the starter header/footer and builds each main page in Elementor. Widgets: Elementor panel > Sylvia's Dream (26, including **Video** since 1.22.0: paste a YouTube or YouTube Shorts address; it shows the same click-to-play thumbnail as the block pages, 9:16 for Shorts). 1.22.0: "Section from your page" renders its blocks like a normal page (auto-embeds, then blocks, then shortcodes), so YouTube videos, reusable blocks, Jetpack forms and `[sd_partner_progress]` work as on the old page; the click-to-play script loads on Elementor pages and in the Elementor preview; the GoFundMe widget script is loaded on Elementor-built pages. Options: Appearance > Sylvia's Dream Theme Options. Guide for Sylvia: `docs/ELEMENTOR-EDITING-GUIDE.md`; developer notes: ARCHITECTURE.md section 14.

## Page builder (1.1.0)

Every page can be edited visually in the block editor. Add sections from **+ > Patterns > Sylvia's Dream** (14 patterns in `patterns/`). Brand colours, fonts and sizes come from `theme.json`. Templates: Default, **Full width (page builder)** (`template-full-width.php`) and **Canvas** (`template-canvas.php`). When the Home page has block content, `front-page.php` shows only that; otherwise the Customizer sections below. Ready-to-paste pages: `content/blocks/*.html`. Guide for Sylvia: `docs/EDITING-GUIDE.md`.

## Navigation (1.2.x, prototype v4)
- Main menu: Appearance > Menus > Primary Navigation. Turn on Screen Options > **CSS Classes** and **Description**.
- **Mega menu:** give a top-level item the CSS class `sd-mega`. Under it, add Custom Links with URL `#` as column headings and put links under each heading. Published featured books appear automatically as "Start here" (heading in Customizer > Header and menu).
- **Descriptions:** the Description of a submenu item shows under its title.
- **Phone bottom bar:** Customizer > Sylvia's Dream > Header and menu (on/off, ministry label and link). Books uses the Books page link, Give uses the Give button settings; More opens the menu.

## Editing map

| What | Where in WordPress |
|---|---|
| All home page text (hero, trust strip, books heading, about band, Heaven on Earth, Dream Partners, devotionals, reader reviews, newsletter) | Appearance > Customise > Sylvia's Dream |
| Hero cover image, photo of Sylvia, Heaven on Earth background | Appearance > Customise > Sylvia's Dream (image fields) |
| Header tagline, Give button, utility bar, Google Fonts on/off | Customise > Sylvia's Dream > Header and menu |
| Footer text, location (country only), copyright, contact email, social links | Customise > Sylvia's Dream > Footer, contact and social |
| Main menu (6-7 items, one level of dropdowns) | Appearance > Menus > Primary Navigation |
| Footer columns | Appearance > Menus > Footer: Books & Resources, Footer: About & Ministry, Footer: Policies |
| Newsletter sign-up | Appearance > Widgets > Home: Newsletter sign-up / Footer: Newsletter sign-up |
| Books, prices, covers, ebook files | Products (WooCommerce). Short description = subtitle. A "Format" attribute gives the format badges |
| Featured books on the home page | Products > star a product as Featured (featured first, then newest). Only published products are shown; with none, the section is hidden |
| Dream Partner count and goal | Settings > Sylvia's Dream (core plugin, `[sd_partner_progress]`) |

Blank Customizer fields hide their item. Reader reviews appear only when a review is filled in (with the reader's permission).

**Wording awaiting Sylvia's approval** (prototype drafts used as defaults): hero intro, about-Sylvia paragraphs. The "Every purchase helps Heaven on Earth" trust item is blank until approved.

## Header and menu behaviour

- Sticky header (translucent cream, backdrop blur). After scrolling it shrinks (84px to 68px desktop) and gains a shadow. The outer box keeps its height, so nothing below moves (no layout shift).
- Desktop (1024px and up): dropdowns open on hover and on keyboard focus; the chevron button beside each parent toggles it (aria-expanded/aria-controls); Escape closes and returns focus; click outside closes.
- Phone and tablet (below 1024px): the hamburger opens a full-height slide-in drawer with 48px+ tap targets, accordion submenus, focus trap, Escape and overlay click to close, body scroll lock and a "Become a Dream Partner" button.
- Search button opens a search panel; cart icon shows the WooCommerce item count (updated after AJAX add to cart).

## File map

| File | Purpose |
|---|---|
| `style.css` | Theme header and design system (tokens, header, home, cards, posts, footer, print) |
| `assets/css/woocommerce.css` | Shop grid, single book, cart, checkout, my account and downloads (loaded only with WooCommerce) |
| `assets/js/nav.js` | Sticky header, dropdowns, mobile drawer, search panel (vanilla, deferred) |
| `theme.json` | Palette, fonts, sizes, spacing, layout widths, button and block styles |
| `patterns/*.php` | Block patterns (category "Sylvia's Dream") |
| `content/blocks/*.html` | Home, Books and Contact pages to paste into the Code editor |
| `inc/blocks.php` | Pattern category, block styles, editor fonts, image helper, Elementor support |
| `assets/css/blocks.css`, `assets/css/editor.css` | Pattern and block-style CSS (site and editor) |
| `assets/images/*.jpg` | Prototype photos: hero, bible, mission, give, desk |
| `template-full-width.php`, `template-canvas.php` | Page-builder templates |
| `functions.php` | Version, setup, menus, assets (filemtime versioning), fonts and preconnect, includes |
| `inc/customizer.php` | All editable text and image settings |
| `inc/template-tags.php` | Icons, links, cart link, page header, post cards, pagination, social links |
| `inc/class-sdc-walker-primary.php` | Primary menu walker with submenu toggle buttons |
| `inc/woocommerce.php` | Shop hooks: 2:3 covers, columns, single book layout, format badges, cart fragment, home products |
| `inc/widgets.php` | Newsletter widget areas |
| `header.php` / `footer.php` | Header (utility bar, brand, menu, actions) and multi-column footer |
| `front-page.php` | Home page sections |
| `page.php` | Pages: page header + 760px reading column (wider for cart, checkout, account) |
| `single.php`, `index.php`, `archive.php`, `search.php`, `404.php`, `searchform.php`, `template-parts/content-none.php` | Devotionals, listings, search, not found |
| `woocommerce.php` | Shop wrapper |
