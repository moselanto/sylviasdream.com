# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Monday 26 October 2026, about 13:50 Kenya time (EAT, UTC+3).

## 1. What this project is
Pimofy Digital (Moses Munyi) is rebuilding **sylviasdream.com** for **Sylvia Suhr** (Christian author and missionary, Australia; ministry Heaven on Earth in Kenya). Custom theme, WooCommerce book shop with secure ebook downloads, Dream Partner giving page, content clean-up and SEO. Fixed price **USD 280** (quotation PMF-QT-2026-0106). The live site stays on the **WordPress.com Personal plan** (plugins and theme uploads are allowed on paid plans since April 2026, see `project/DECISIONS.md`).

**Moses's quality bar (23 Oct):** the new site must be international-standard, very professional, mobile-ready, with a beautiful fixed (sticky) menu, and "one of the best book websites in the world". It must match or beat `prototype/index.html` v3, **including its images and footer**, and every page must be **fully editable by Sylvia, like a page builder**, so handover is easy.

## 2. Read in this order
1. This file.
2. [`project/PROGRESS-LOG.md`](project/PROGRESS-LOG.md): what was done, day by day, 21-23 Oct, and exactly where work stopped.
3. [`docs/NEXT-BUILD-1.1.0.md`](docs/NEXT-BUILD-1.1.0.md): the brief for theme 1.1.0 (built 23 Oct; see CHANGELOG).
4. [`AGENTS.md`](AGENTS.md): rules for working in this repo.
5. [`project/README.md`](project/README.md), [`project/DECISIONS.md`](project/DECISIONS.md), [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md).
6. Runbooks in `docs/` (`DEPLOYMENT.md`, `BACKUP-AND-LAUNCH.md`, `QA-CHECKLIST.md`, `REDIRECT-MAP.md`).

## 3. Current status (Monday 26 October 2026, 13:50 EAT)
| Area | Status |
|---|---|
| Dev site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, hidden from search. All work happens here. |
| Live site | **Untouched.** Read-only comparisons only (public REST API). |
| Child theme on dev | **1.8.3** (main `708dda1`). Parent "Sylvia's Dream Base" 1.0.1. Core plugin 0.1.2 (`dist/sylviasdream-core-0.1.2.zip`). |
| Deploy method | Build with `bash scripts/build-theme-zip.sh` (runs `check-theme.js`; last run "OK: 88 files checked, 32 block files"), then WP Admin > Appearance > Themes > Upload > **Replace active with uploaded**. cPanel Git also works again (host fixed the licence 26 Oct). |
| Page content | Pasted into each page's Code editor from `sylviasdream-child/content/blocks/*.html`. Old content stays in page revisions. |
| Products | 25 WooCommerce products, **all Draft** (Books grid empty until launch). |

### Done (newest first)
- **26 Oct, theme 1.8.3: menu corrections** (Agnes). Books > Other languages = Français (`#lang-fr`), Español (`#lang-es`), Bahasa Indonesia (`#lang-id`): each translates the whole site, same as the language selector. From Fear to Freedom (`/from-fear-to-freedom-3/`) and SENT (`/sent/`) moved out of Other languages into Books > Browse by topic. Ministry now has Kenya Ministry Training Manual (`/kenya-ministry-training-manual-free-download/`). Verified desktop and phone drawer (Customizer Mobile). How to add language links: `docs/EDITING-GUIDE.md`.
- **26 Oct, content restored from live** (`818921b`, audit `docs/CONTENT-PARITY-AUDIT.md`): My Story (Sylvia's own sections, Proverbs 3:5, Let's Walk Together video, Partner button, contact form), Podcasts (Lagos captions, About the interview), Hidden Key 6171 (fuller description from duplicate 5319), One Life 2766 (hardcover text from My Autobiography 1239), all 15 posts added to the Grace For the Journey category (dev only). All verified desktop and mobile.
- 1.8.2 book pages SENT (4686) and From Fear to Freedom (4231); 1.8.0-1.8.1 language selector (English, Français, Español, Bahasa Indonesia) with browser-language suggestion card; 1.7.x About pages (My Story, Podcasts, Speaking 6511, Author Support 6235 with its Jetpack form kept), devotionals section on Grace for the Journey (5593); earlier: prototype v4 design on all main pages, mega menu, phone bottom bar, redirects, Give and policy pages. Details: `CHANGELOG.md`, `project/PROGRESS-LOG.md`.

### Dev page IDs
Home 6365 (front page) · Journal 6368 (posts page) · Books 3001 · Contact 20 · My Story 2665 · Invite Sylvia to speak 6511 · Podcasts 1366 · Author Support 6235 · Heaven on Earth 1932 · Grace for the Journey 5593 · SENT 4686 · From Fear to Freedom 4231 · One Life 2766 · Hidden Key 6171 (kept) · drafts: Hidden Key duplicate 5319, My Autobiography 1239, From Fear to Freedom duplicate 4580, New Home 5863.

## 4. What is pending (in priority order)
**Build work (Pimofy / next agent)**
1. **Heaven on Earth (1932) is still on its old layout.** Three automated paste attempts failed (the browser tool blocks the slideshow/GoFundMe markup; keyboard selection displaced protected blocks; page restored and verified). Do NOT retry the Code-editor segment method. Either Moses/Agnes pastes it by hand using `docs/hoe-segments/README.md` (parts 1-4), or replace block by block in the block editor's List View.
2. **Visual checks not yet done** (automated screenshots time out): SENT and From Fear to Freedom pages; real-phone QA (iPhone and Android, 320-430px) per `docs/PHONE-QA-v4-NAV.md`.
3. Language suggestion card (1.8.1) not verified: needs a browser set to French/Spanish/Indonesian or a matching time zone.
4. Books > Browse by topic shows "Uncategorized" and "Coming soon" product categories: hide or rename before launch.
5. Mark 3 books as Featured (after products are published) so "Start here" shows covers.
6. Home gaps against prototype v4, SEO pass (meta descriptions, book JSON-LD), full `docs/QA-CHECKLIST.md`, footer widget clean-up: see `project/OPEN-ITEMS.md`.

**Waiting on Sylvia (send only after Moses approves; emails open "Dear Sylvia," with no sign-off, no em dashes, no technical words)**
- Two drafted emails, still unsent: "Three things we need before your new website launches" (reviews, free guide, OK to replace old Buy buttons) and "Download links for your ebooks: two numbers to choose" (download count and link expiry).
- One Life hardcover Amazon link (page 1239's link opens the Kindle edition).
- OK to repeat the Grace For the Journey category change on live.
- Give page amounts and goal (dev buttons are test placeholders), policies, biography wording, covers, Stripe access, Invite Sylvia to speak wording. Full list: `project/OPEN-ITEMS.md`.

**Launch (live site, only with Sylvia's written OK)**
- Products to Published; newsletter checked on live (Jetpack); legacy link replacements per `docs/LEGACY-LINK-MAP.md`; redirects per `docs/REDIRECT-MAP.md`; protect downloads per `docs/BUYER-TRANSITION-PLAN.md` (on live the protected slug is `all-shall-be-well`, never `all-shall-be-well-2`, which is the book page 4216); repeat the content and menu changes above; `docs/BACKUP-AND-LAUNCH.md` first.

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
- Never connect the dev site to Sylvia's WordPress.com account, and never import her subscriber export anywhere. Her subscribers stay on the live WordPress.com site (Jetpack Newsletter).
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
