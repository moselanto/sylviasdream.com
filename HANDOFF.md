# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Tuesday 27 October 2026, about 11:30 Kenya time (EAT, UTC+3).

## 1. What this project is
Pimofy Digital (Moses Munyi) is rebuilding **sylviasdream.com** for **Sylvia Suhr** (Christian author and missionary, Australia; ministry Heaven on Earth in Kenya). Custom theme, WooCommerce book shop with secure ebook downloads, Dream Partner giving page, content clean-up and SEO. Fixed price **USD 280** (quotation PMF-QT-2026-0106). The live site stays on the **WordPress.com Personal plan** (plugins and theme uploads are allowed on paid plans since April 2026, see `project/DECISIONS.md`).

**Moses's quality bar (23 Oct):** the new site must be international-standard, very professional, mobile-ready, with a beautiful fixed (sticky) menu, and "one of the best book websites in the world". It must match or beat `prototype/index.html` v3, **including its images and footer**, and every page must be **fully editable by Sylvia, like a page builder**, so handover is easy.

## 2. Read in this order
0a. `docs/MIGRATION-PLAN.md`: how the finished dev site moves to sylviasdream.com (live audit 26 Oct, blockers, phases, rollback).
0. `docs/REDESIGN-HANDOFF.docx` (design direction, acceptance criteria, integration matrix) and `docs/LAUNCH-QA-CHECKLIST.md` (26 Oct).
1. This file.
2. [`project/PROGRESS-LOG.md`](project/PROGRESS-LOG.md): what was done, day by day, 21-27 Oct, and exactly where work stopped.
3. [`docs/NEXT-BUILD-1.1.0.md`](docs/NEXT-BUILD-1.1.0.md): the brief for theme 1.1.0 (built 23 Oct; see CHANGELOG).
4. [`AGENTS.md`](AGENTS.md): rules for working in this repo.
5. [`project/README.md`](project/README.md), [`project/DECISIONS.md`](project/DECISIONS.md), [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md).
6. Runbooks in `docs/` (`DEPLOYMENT.md`, `BACKUP-AND-LAUNCH.md`, `QA-CHECKLIST.md`, `REDIRECT-MAP.md`).

## 3. Current status (Tuesday 27 October 2026, 11:30 EAT)
| Area | Status |
|---|---|
| Dev site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, hidden from search. All work happens here. |
| Live site | **Untouched.** Agnes's login (mosemqute) is Administrator on wp-admin and my.wordpress.com (used for backups); read-only until migration. Domain with WordPress.com (paid to 25 Nov 2027, auto-renew); Personal plan renews 29 Oct 2026; Stripe connected via WordPress.com Payments; WooCommerce not installed on live. |
| Migration | **Only after Sylvia reviews and approves the dev site** (Agnes, 26 Oct). |
| Versions | Parent "Sylvia's Dream Base" **1.0.1**. Child theme **1.18.3** (27 Oct dev work: 1.18.1 real covers in Books menu Start here and Free download field/button; 1.18.2 price note shows the currency, header logo alt from the Media Library, taller full logo on desktop, compact logo on phones; 1.18.3 narrow-phone logo fix; builds on 1.18.0 book pages and 1.17.0 security/preview/speed). Core plugin **0.4.0** (main, commit 65f208d; uploaded to dev and active 27 Oct). |
| Sign-in and admin (core 0.4.0) | Branded split-screen sign-in (wp-login.php, lost/reset password, check email and the 2FA code screen; photo panel hidden below 960px; lock note reads the real login-limit window). Generic login and lost-password errors. Opt-in TOTP two-factor sign-in per user from Users > Profile (8 hashed recovery codes, 5-minute code token, 5 codes per token and 10 per IP per 15 minutes; reminder notice for administrators without it). Admin branding: "Sylvia's Dream" colour scheme (default for new users only), footer text, Welcome dashboard widget, WordPress logo menu removed for non-administrators. Emergency: `define( 'SD_2FA_DISABLE', true );` in wp-config.php; switch keys `login_brand`, `admin_brand`, `two_factor`, `login_errors`, `app_passwords`, `session_cleanup` in `SD_SECURITY_DISABLE`. On dev 2FA is still **Off** for every account. Fresh wrong-password / real sign-in test **not yet done** (Lightof to test). Not yet checked on the live WordPress.com plan. |
| Deploy method | Build with `bash scripts/build-theme-zip.sh` (runs `check-theme.js`; last run "OK: 88 files checked, 32 block files"), then WP Admin > Appearance > Themes > Upload > **Replace active with uploaded**. cPanel Git also works again (host fixed the licence 26 Oct). |
| Page content | Pasted into each page's Code editor from `sylviasdream-child/content/blocks/*.html`. Old content stays in page revisions. |
| Products | 25 WooCommerce products, **all Draft** (Books grid empty until launch). Amazon Kindle links on 22 products, paperback links on 2 (6400, 6376 One Life). Free download buttons set on SENT 6382, How to Write and Publish 6400 and 7 Days to Trusting God 6399 from existing site files (Sylvia's written OK still needed before launch). No cover: 6390, 6397, 6398; soft covers: 6381, 6394. The Report of the Lord (6396) has no price. |

### Done (newest first)
- **27 Oct, core plugin 0.4.0** (65f208d) uploaded to dev and active: Dashboard Welcome widget, footer text, colour scheme option, 2FA profile section (left Off), sign-in page on desktop and phone and the Lost password screen checked visually. Fresh sign-in test still open (OPEN-ITEMS).
- **27 Oct, child theme 1.18.1-1.18.3 on dev**: real covers in Books > Start here; Free download field and button (SENT 6382, How to Write and Publish 6400, 7 Days to Trusting God 6399, existing site files; products still Draft); price note with currency; header logo alt from the Media Library; new header logo and site icon from the Sylvia's Dream Ministry logo kit; narrow-phone logo fix. Amazon Kindle links on 22 products and paperback links on 2.
- **26 Oct, theme 1.9.0: About pages polish** (My Story, Speaking, Podcasts, Author Support): design-only stylesheet `assets/css/about.css`; My Story placeholder "Sylvia's portrait here" replaced by "Sylvia Suhr". See CHANGELOG.
- **26 Oct, theme 1.8.5: phone bottom bar** with four items (More removed; header menu button opens the menu), floating rounded bar, centred icons and labels.
- **26 Oct, theme 1.8.4: Priority 1 clean-up.** Real cover URLs for One Life (media 6604), SENT (6605) and From Fear to Freedom (6603) in `content/blocks/`; empty or "Uncategorized" product categories hidden from menus (Books > Browse by topic); 404 "Browse the books" goes to the Books page. Applied on dev and verified signed in as admin (desktop): covers load, no editor warnings, menu and 404 correct, inactive widgets removed. Phone-width check still open.
- **26 Oct, theme 1.8.3: menu corrections** (Agnes). Books > Other languages = Français (`#lang-fr`), Español (`#lang-es`), Bahasa Indonesia (`#lang-id`): each translates the whole site, same as the language selector. From Fear to Freedom (`/from-fear-to-freedom-3/`) and SENT (`/sent/`) moved out of Other languages into Books > Browse by topic. Ministry now has Kenya Ministry Training Manual (`/kenya-ministry-training-manual-free-download/`). Verified desktop and phone drawer (Customizer Mobile). How to add language links: `docs/EDITING-GUIDE.md`.
- **26 Oct, content restored from live** (`818921b`, audit `docs/CONTENT-PARITY-AUDIT.md`): My Story (Sylvia's own sections, Proverbs 3:5, Let's Walk Together video, Partner button, contact form), Podcasts (Lagos captions, About the interview), Hidden Key 6171 (fuller description from duplicate 5319), One Life 2766 (hardcover text from My Autobiography 1239), all 15 posts added to the Grace For the Journey category (dev only). All verified desktop and mobile.
- 1.8.2 book pages SENT (4686) and From Fear to Freedom (4231); 1.8.0-1.8.1 language selector (English, Français, Español, Bahasa Indonesia) with browser-language suggestion card; 1.7.x About pages (My Story, Podcasts, Speaking 6511, Author Support 6235 with its Jetpack form kept), devotionals section on Grace for the Journey (5593); earlier: prototype v4 design on all main pages, mega menu, phone bottom bar, redirects, Give and policy pages. Details: `CHANGELOG.md`, `project/PROGRESS-LOG.md`.

### Dev page IDs
Home 6365 (front page) · Journal 6368 (posts page) · Books 3001 · Contact 20 · My Story 2665 · Invite Sylvia to speak 6511 · Podcasts 1366 · Author Support 6235 · Heaven on Earth 1932 · Grace for the Journey 5593 · SENT 4686 · From Fear to Freedom 4231 · One Life 2766 · Hidden Key 6171 (kept) · drafts: Hidden Key duplicate 5319, My Autobiography 1239, From Fear to Freedom duplicate 4580, New Home 5863.

## 4. What is pending (in priority order)
**Build work (Pimofy / next agent)**
00. **Sign-in security (core 0.4.0), before launch:** fresh sign-in test on dev (wrong password shows the generic error; correct sign-in reaches the Dashboard); every administrator switches on two-factor sign-in in Users > Profile and stores their recovery codes (AGENTS.md rule 32 / DECISIONS 27 Oct); confirm the plugin, 2FA and branding work on the live WordPress.com plan (SECURITY-AUDIT.md, known limitation "WordPress.com live"). Covers still needed for 6390, 6397, 6398 and better covers for 6381, 6394; price for 6396.
0. ~~Done in 1.8.4 (repo); dev application tracked in OPEN-ITEMS.~~ Was: apply `book-one-life.html` (commit f04b704) to dev page 2766, after swapping its guessed cover URL for the real URL of media 6604. Then check SENT and From Fear to Freedom with their new covers, and check all three pages on desktop and mobile. Details: `project/OPEN-ITEMS.md`. The email draft to Sylvia is up to date (covers received, AU paperback link, payment account, plan renewal).
1. **Heaven on Earth (1932) is still on its old layout.** Three automated paste attempts failed (the browser tool blocks the slideshow/GoFundMe markup; keyboard selection displaced protected blocks; page restored and verified). Do NOT retry the Code-editor segment method. Either Moses/Agnes pastes it by hand using `docs/hoe-segments/README.md` (parts 1-4), or replace block by block in the block editor's List View.
2. **Visual checks not yet done** (automated screenshots time out): SENT and From Fear to Freedom pages; real-phone QA (iPhone and Android, 320-430px) per `docs/PHONE-QA-v4-NAV.md`.
3. Language suggestion card (1.8.1) not verified: needs a browser set to French/Spanish/Indonesian or a matching time zone.
4. ~~Books > Browse by topic "Uncategorized" and "Coming soon"~~: hidden by theme 1.8.4 while empty.
5. Mark 3 books as Featured (after products are published) so "Start here" shows covers.
6. Home gaps against prototype v4, SEO pass (meta descriptions, book JSON-LD), full `docs/QA-CHECKLIST.md`, footer widget clean-up: see `project/OPEN-ITEMS.md`.

**Waiting on Sylvia (send only after Moses approves; emails open "Dear Sylvia," with no sign-off, no em dashes, no technical words)**
- One consolidated email draft, unsent: "Everything we need to finish your new website" (logo originals first, wording, reviews, books, downloads, photo, socials, payment account, plan renewal). Checklist: Town document "Everything we need from Sylvia (26 Oct 2026)". It replaces the two 23 Oct drafts.
- One Life hardcover Amazon link (page 1239's link opens the Kindle edition).
- OK to repeat the Grace For the Journey category change on live.
- Give page amounts and goal (dev buttons are test placeholders), policies, biography wording, covers, Stripe access, Invite Sylvia to speak wording. Full list: `project/OPEN-ITEMS.md`.

**Launch (live site, only with Sylvia's written OK)**
- Products to Published; newsletter checked on live (Jetpack); legacy link replacements per `docs/LEGACY-LINK-MAP.md`; redirects per `docs/REDIRECT-MAP.md`; protect downloads per `docs/BUYER-TRANSITION-PLAN.md` (on live the protected slug is `all-shall-be-well`, never `all-shall-be-well-2`, which is the book page 4216); repeat the content and menu changes above; `docs/BACKUP-AND-LAUNCH.md` first.

## 5. Things that must not happen
- **Never break an existing Stripe payment or contribution link** (26 Oct): every live book purchase button, Dream Partner link, thank-you/download page and subscription keeps working exactly as now. No link is replaced, switched off or redirected (`project/DECISIONS.md`).
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
- No launch while any administrator is without two-factor sign-in. `SD_2FA_DISABLE` is an emergency switch only (locked-out administrator); remove it again straight after.

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
| `sylviasdream/` | Parent theme shell, 1.0.1 |
| `sylviasdream-child/` | The design, child theme 1.18.3 (patterns, templates, WooCommerce book pages, `inc/performance.php`; dev page content in `content/blocks/`) |
| `sylviasdream-core/` | Plugin 0.4.0: download-page protection, redirects (subfolder-safe), schema, social sharing tags, `[sd_partner_progress]`, security hardening (`inc/security.php`), sign-in hardening (`inc/auth-hardening.php`), two-factor sign-in (`inc/two-factor.php`), branded sign-in screens (`inc/login.php`), admin branding (`inc/admin-brand.php`), reviewer preview mode (`inc/preview.php`), Settings > Sylvia's Dream |
| `prototype/` | Design prototype (`prototype/index.html`, the visual target) |
| `content/live/` | Launch content pack for sylviasdream.com (`#KEEP-LIVE-*` markers) |
| `scripts/` | `check-theme.js`, `build-assets.js`, `build-theme-zip.sh` |
| `docs/` | Migration plan, deployment, backup and launch, QA checklists, redirect map, editing guide, client checklist, `wp-config-sample-additions.php` |
| Root docs | `AGENTS.md`, `ARCHITECTURE.md` / `ARCHITECTURE-ESSENTIALS.md`, `SECURITY-AUDIT.md`, `CHANGELOG.md` |
| `project/` | Brief, decisions, catalogue, progress log, open items, workflow, correspondence |

## 9. Known gaps
- `php -l` cannot run on cPanel or in the Town sandbox; CI (`.github/workflows/checks.yml`) lints every PHP file on PHP 8.0-8.3, and core 0.4.0 was also checked with php-parser (0 errors).
- Books page on dev is still the legacy long catalogue page; the product grid stays empty until products are published at launch.
- Book JSON-LD missing on legacy book pages; meta descriptions missing on all pages (Rank Math/SEO pass still to do, using her wording).
- `sitemap.xml` returns 404 on dev (search engines are discouraged there); check on live.
- Mobile tested only in the 320px WordPress preview; needs a real phone check.

## 10. Keeping this file current
Whoever finishes a step updates section 3, `project/PROGRESS-LOG.md`, `project/OPEN-ITEMS.md`, `project/DECISIONS.md` and `CHANGELOG.md` in the same commit.
