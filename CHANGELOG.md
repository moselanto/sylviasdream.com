# Changelog

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
