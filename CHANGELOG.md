# Changelog

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
