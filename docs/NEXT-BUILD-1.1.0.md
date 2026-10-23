# Next build: child theme 1.1.0 (approved by Moses 23 Oct 2026, not started)

> **Status (23 Oct 2026, 13:40 EAT): built and committed to `main` in four parts. Not yet deployed to dev.**

Upgrade `sylviasdream-child` from 1.0.0 (commit `fd1aaa1`) to 1.1.0. One single commit to `main`. Keep the 1.0.0 design (gold/cream palette, Cormorant Garamond + Source Sans 3, 20px body, sticky header, mobile drawer) and do not break current behaviour.

**Goal:** Sylvia (non-technical) can edit EVERY page visually, like a page builder, in the WordPress block editor; the site matches or beats `prototype/index.html` v3, including its images and footer; international-standard, mobile-first, "one of the best book websites in the world".

## 1. Prototype images
Five photos, saved in Moses's Town library at "Sylvias Dream/Theme builds/prototype-images/" and embedded as base64 in `prototype/index.html` (the `IMG` object keys):
| File | Size | Prototype key |
|---|---|---|
| hero.jpg | 1376x768 | IMG.hero (home hero) |
| bible.jpg | 1000x746 | IMG.bible |
| mission.jpg | 1000x746 | IMG.mission |
| give.jpg | 1000x746 | IMG.give |
| desk.jpg | 1000x746 | IMG.desk |

Add them as `sylviasdream-child/assets/images/{hero,bible,mission,give,desk}.jpg` (extract from the prototype base64 if the library copies are not reachable). Use them wherever the prototype uses each key, as Customizer and pattern defaults (`get_theme_file_uri`), with alt text, width/height, `loading="lazy"` except the hero.

## 2. Footer
Rebuild `footer.php` to look exactly like the prototype footer or better: same columns, headings, links, newsletter line, inline-SVG social icons, copyright line, payment badges row (VISA, Mastercard, AMEX, Apple Pay, Google Pay, PayPal), credit "Website by Pimofy Digital", dark ink background with gold accents. Keep menu locations `footer_books`, `footer_about`, `footer_legal` and existing Customizer fields. Location "Australia" only.

## 3. theme.json (v2)
Palette: gold #b8862b, gold-dark #8a5a14, gold-light #f3e3bf, cream #fbf7ef, cream-2 #f5ecdb, ink #2b2118, ink-2 #5a4a3a, line #e7dcc6, white, green #3f6b4a. Font families, fluid sizes, spacing scale, contentSize 760px, wideSize 1200px, pill buttons min-height 48px, heading styles. Palette only (no custom colours or gradients). `add_editor_style` so the editor looks like the site.

## 4. Block patterns (`/patterns/*.php`, category "Sylvia's Dream")
Core blocks only (cover, group, columns, image, heading, paragraph, buttons, quote, separator, details) plus shortcode blocks; editable and responsive, using the prototype images:
Hero; Trust strip (4 items); Featured books (`[products limit="8" columns="4" orderby="menu_order"]`); Book showcase; About Sylvia band; Heaven on Earth band; Dream Partners band (`[sd_partner_progress]` + button to `/give/`); Testimonials (3 cards, placeholder "Reader review goes here"); Newsletter/contact CTA; Page header; FAQ (details); Two-column text + image; CTA strip; Contact layout (left: email, "Australia", social; right: `<!-- wp:jetpack/contact-form -->` with name, email, message).
Wording only from the prototype or current Customizer defaults. Never invent facts, numbers, reviews or addresses.

## 5. Ready-to-paste pages
`content/blocks/home.html` (full home matching the prototype), `content/blocks/books.html` (page header + `[products limit="30" columns="4"]` + CTA), `content/blocks/contact.html`. Paste into each page's Code editor on dev.

## 6. Templates
- `front-page.php`: if the Home page has block content, render only that, full width (alignfull/alignwide); otherwise fall back to the 1.0.0 sections.
- `page.php`: support alignwide/alignfull while keeping a 760px reading column.
- Page templates: "Full width (page builder)" and "Canvas (no header or footer)".
- Elementor compatibility (theme support; Elementor content renders on those templates) without requiring Elementor.

## 7. Header and menu
Keep the gold "Give" button. Style the primary menu for 6 top-level items with one dropdown. Approved menu: **Home · Books (All Books, Devotionals) · About Sylvia · Ministry · Podcasts & Videos · Contact**. Desktop dropdowns (hover + keyboard), accordion in the mobile drawer.

## 8. Other
- Block styles: "Card" (group), "Book cover shadow" (image), "Outline" (button). Theme supports: align-wide, responsive-embeds, editor-styles, wp-block-styles.
- `docs/EDITING-GUIDE.md`: plain-English guide for Sylvia (edit text and images, add sections from Patterns > Sylvia's Dream, drag to reorder, add a book, change the menu, what not to touch).
- Escape all output; `wp_enqueue` with filemtime; text domain `sylviasdream-child`; no CSS classes starting with `ad-`; no external libraries.
- Version 1.1.0; update `CHANGELOG.md`, `sylviasdream-child/README.md`, `HANDOFF.md`, `project/PROGRESS-LOG.md`.
- Validate theme.json as JSON and every pattern's block-comment nesting; `php -l` if PHP is available (it is not on cPanel or in the Town sandbox).
- Do not touch Stripe, payment links, the live site or `sylviasdream-core`.

## After the build (on dev)
1. cPanel Git "Update from Remote", copy `sylviasdream-child` with File Manager (see `HANDOFF.md` section 6). Smoke-test Home, Shop For Books, Contact, a 404.
2. Set the approved menu; Give removed from the menu.
3. Install Jetpack on dev (contact form + newsletter; Contact currently prints a raw `[jetpack_subscription_form]`).
4. Paste `content/blocks/*.html` into Home, Books and Contact.
5. QA at 390px, tablet, desktop; run `docs/QA-CHECKLIST.md`.
