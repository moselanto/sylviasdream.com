# Changelog

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
