# Changelog

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
