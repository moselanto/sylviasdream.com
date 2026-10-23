# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Friday 23 October 2026, about 13:00 Kenya time (EAT, UTC+3).

## 1. What this project is
Pimofy Digital (Moses Munyi) is rebuilding **sylviasdream.com** for **Sylvia Suhr** (Christian author and missionary, Australia; ministry Heaven on Earth in Kenya). Custom theme, WooCommerce book shop with secure ebook downloads, Dream Partner giving page, content clean-up and SEO. Fixed price **USD 280** (quotation PMF-QT-2026-0106). The live site stays on the **WordPress.com Personal plan** (plugins and theme uploads are allowed on paid plans since April 2026, see `project/DECISIONS.md`).

**Moses's quality bar (23 Oct):** the new site must be international-standard, very professional, mobile-ready, with a beautiful fixed (sticky) menu, and "one of the best book websites in the world". It must match or beat `prototype/index.html` v3, **including its images and footer**, and every page must be **fully editable by Sylvia, like a page builder**, so handover is easy.

## 2. Read in this order
1. This file.
2. [`project/PROGRESS-LOG.md`](project/PROGRESS-LOG.md): what was done, day by day, 21-23 Oct, and exactly where work stopped.
3. [`docs/NEXT-BUILD-1.1.0.md`](docs/NEXT-BUILD-1.1.0.md): the full brief for the next theme build (approved by Moses, not started).
4. [`AGENTS.md`](AGENTS.md): rules for working in this repo.
5. [`project/README.md`](project/README.md), [`project/DECISIONS.md`](project/DECISIONS.md), [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md).
6. Runbooks in `docs/` (`DEPLOYMENT.md`, `BACKUP-AND-LAUNCH.md`, `QA-CHECKLIST.md`, `REDIRECT-MAP.md`).

## 3. Current status (23 Oct 2026)
| Area | Status |
|---|---|
| Dev site | Private build copy at `https://topnotchmall.co.ke/sylviasdream/` (WP admin `/sylviasdream/wp-admin/`), password-gated and hidden from search. All work happens here. |
| Live site | **Untouched.** Never edit sylviasdream.com until launch. |
| Code on dev | Child theme **1.0.0** (commit `fd1aaa1`) running; parent "Sylvia's Dream Base" 1.0.0; core plugin **0.1.1** active, redirects ON (dev only). |
| Next build | Child theme **1.1.0** (fully editable block patterns, theme.json, prototype images, prototype footer, 6-item menu, Elementor-ready templates). **Approved, not started**: the build agent stopped because the Town account ran out of credits. Brief: `docs/NEXT-BUILD-1.1.0.md`. |
| Products | 25 WooCommerce products, **all Draft**. All 25 have main descriptions (Sylvia's own wording from her pages or Amazon). Titles/subtitles checked against Amazon. 21 have covers. |
| Pages on dev | Give (test placeholders), Privacy Policy, Terms of Sale, Refund Policy (all marked "Draft for Sylvia's review"), store notice banner on, merged duplicate pages, 5 old pages set to Draft. |
| Things needed from Sylvia | Town document "Things needed from Sylvia" (Moses's Town library). Summary in `project/OPEN-ITEMS.md`. |
| Quotation / deposit | Check with Moses; last known: v2 sent, signature and USD 112 deposit pending. |

## 4. What to do next (in order)
1. **Build child theme 1.1.0** exactly per `docs/NEXT-BUILD-1.1.0.md`. One single commit to `main`.
2. **Deploy to dev** (see section 6), then set the approved menu: Home · Books (dropdown: All Books, Devotionals) · About Sylvia · Ministry · Podcasts & Videos · Contact. Give is the gold header button, not a menu item.
3. **Rebuild Home, Books and Contact** pages on dev from the 1.1.0 patterns (`content/blocks/*.html`, paste into the page Code editor). Install Jetpack on dev so the contact form and newsletter work (the Contact page currently prints a raw `[jetpack_subscription_form]` shortcode and has no form).
4. **Visual QA** at 390px phone, tablet and desktop; fix; run `docs/QA-CHECKLIST.md`.
5. Keep adding to "Things needed from Sylvia"; when the build is done Moses sends it to her.
6. Then the workflow phases 7-11 in `project/WORKFLOW.md` (live prep as drafts, preview with Sylvia, launch, handover).

## 5. Things that must not happen
- No changes to the live site before backups and launch day.
- No Stripe edits, ever. Existing subscriptions and payment links stay; old links change only with Sylvia's written OK.
- Products stay **Draft** until launch.
- Use **Sylvia's own wording only**; never invent facts, reviews, numbers or addresses. Draft wording (biography, home intro) needs her approval.
- No free downloads without her written approval.
- "Australia" only: no street address or town anywhere.
- No passwords requested, stored or committed. No private data (Stripe exports, buyer lists, backups, phone numbers, bank details) in Git.
- Do not mention this repo to Sylvia.
- Never sync a dev or local database over production.
- CSS class names never start with `ad-` (ad blockers hide them); use `sd-`.

## 6. How code reaches the dev site
- cPanel: `https://cpanel.topnotchmall.co.ke/` > Git Version Control. Repo clone: `/home/topnotch/repositories/sylviasdream.com`. "Update from Remote" pulls `main`.
- Then copy folders with **File Manager** (there is **no Terminal/shell** on this account) into `/home/topnotch/public_html/sylviasdream/wp-content/themes/` (`sylviasdream`, `sylviasdream-child`) and `/wp-content/plugins/sylviasdream-core`.
- The active dev theme already uses the `sylviasdream-child` folder, so replacing files updates the site immediately. If a page shows a critical error, re-upload the previous version from Git.
- `php -l` **cannot be run** on cPanel or in the Town sandbox. Review PHP carefully; after deploy, load Home, Shop For Books, Contact and a 404 URL to smoke-test.
- The live site (WordPress.com) gets code by zip upload through WP Admin (`docs/DEPLOYMENT.md`).

## 7. Where things are that are NOT in this repo
| Item | Where |
|---|---|
| Quotation v2, book list v2, website audit (.docx) | Moses's Town library, collection "Sylvias Dream" |
| Book covers (final set) | Town library: "Sylvias Dream/Book covers/Final clean set" (+ covers Moses uploaded to WP Media on 23 Oct) |
| Prototype photos (hero, bible, mission, give, desk) | Town library: "Sylvias Dream/Theme builds/prototype-images/". Also embedded as base64 in `prototype/index.html` (the `IMG` object: hero, bible, mission, give, desk) |
| "Things needed from Sylvia" list | Town document of that name |
| Live-site content export | Private backups (not in Git) |
| Payment details, phone numbers | Quotation .docx only |

## 8. Repo map
| Path | What |
|---|---|
| `sylviasdream/` | Parent theme shell |
| `sylviasdream-child/` | The design (1.0.0 now; 1.1.0 next) |
| `sylviasdream-core/` | Plugin 0.1.1: download-page protection, redirects (subfolder-safe), schema, `[sd_partner_progress]`, Settings > Sylvia's Dream |
| `prototype/` | Design prototype v3 (the visual target) |
| `docs/` | Deployment, backup and launch, QA checklist, redirect map, client checklist, next-build brief |
| `project/` | Brief, decisions, catalogue, progress log, open items, workflow, correspondence |

## 9. Known gaps
- `php -l` never run on any theme version (no PHP available). Dev pages render without errors on 1.0.0.
- Books page on dev is still the legacy long catalogue page; the product grid stays empty until products are published at launch.
- Book JSON-LD missing on legacy book pages; meta descriptions missing on all pages (Rank Math/SEO pass still to do, using her wording).
- `sitemap.xml` returns 404 on dev (search engines are discouraged there); check on live.
- Mobile tested only in the 320px WordPress preview; needs a real phone check.

## 10. Keeping this file current
Whoever finishes a step updates section 3, `project/PROGRESS-LOG.md`, `project/OPEN-ITEMS.md`, `project/DECISIONS.md` and `CHANGELOG.md` in the same commit.
