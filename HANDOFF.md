# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Wednesday 11 November 2026 (EAT, UTC+3). Sections 0 and 3 are the current state; sections 4 onwards are older background (still valid unless section 0 says otherwise).

## 0. Latest status (12 Nov 2026, 03:00 EAT) - read this first
Rewritten Thursday 12 November 2026. Sections 0 and 3 are the current state. Detail: `project/PROGRESS-LOG.md` (newest first), `project/OPEN-ITEMS.md` (top sections current), `project/DECISIONS.md`, `docs/CLIENT-CHECKLIST.md` (Sylvia's answers by number), `docs/MIGRATION-PLAN.md` (launch steps, live media map).

### Where we are
- **Sylvia gave written go-ahead to move (12 Nov, item 25)** and confirmed the payment account (item 8). We are in **Phase B** (invisible live preparation). **Visitors still see the old site** (Twenty Twenty theme).
- **Test site** (https://topnotchmall.co.ke/sylviasdream/, password-gated, noindex): build finished. Child theme **1.23.10** installed (**1.23.11** built 12 Nov: registration line in block files + PHP parse check in the build; content-only, install when convenient), core **0.4.6**, Elementor 4.4.0, Redux 4.5.18. 9 Elementor pages, all editable. Never install child 1.23.9 (PHP parse error).
- **Live sylviasdream.com (WordPress.com Personal)** - done so far:
  - Backup: content XML, newsletter CSV (54 records / 24 subscribers), MailPoet CSV in the Town library `Sylvias Dream/backups`; all **254 media files (306.7 MB)** on the topnotchmall server in `/home/topnotch/sylviasdream-live-backup-2026-11-12/media` (outside public_html).
  - Core 0.4.6 **active** with XML-RPC, two-step sign-in, login limit and Redirects **off**; Dream Partners = 1. Jetpack connection, newsletter, Meet Jesus subscribe form and thank-you noindex all checked OK.
  - Parent theme Sylvia's Dream Base 1.0.1 and child 1.23.10 uploaded, **not active**. Elementor 4.4.0 and Redux 4.5.18 active (no page built).
  - 9 missing media uploaded (IDs 6395-6403; map in MIGRATION-PLAN 4b).
  - Empty **Drafts**: Home 6404 (slug `welcome`; old page 2 keeps `/home/`, DECISIONS 12 Nov), Give 6406, Invite Sylvia to speak 6408. Not in any menu.
  - 9 live-safe launch templates in Elementor > Saved Templates (6410 Home, 6412 Shop For Books, 6414 Contact, 6419 My Story, 6421 Invite, 6423 Podcasts, 6425 Author Support, 6430 Heaven on Earth, 6432 Give). Not inserted anywhere.
  - WooCommerce 11.3.0 **installed but inactive** (activation exposed `/shop/`); its pages 6436-6439 Draft. After cache clear `/shop/` redirects home (12 Nov).
  - Products **not imported**: CSV upload blocked on WordPress.com. Ready instead: `Products live draft 12 Nov 2026.xml` in the Town library `Sylvias Dream/launch-templates` (24 products all `draft`, trashed duplicate removed, product links swapped to sylviasdream.com, 26 image attachments still point to the test site so the importer can fetch them, protected download files removed).
- **Sylvia's answers:** done 1, 2 (6x9 Guard Your Heart), 4, 5 (Reg. No. 35364 line on Heaven on Earth/Give), 7 (1 Dream Partner), 8, 9, 10, 13, 14, 25. Fast Forward Lulu paperback link received (items 16/17 still to confirm). Launch-critical open: **26** (dates; she offered to meet 12 Nov at 7 or 8 pm Melbourne = 11 am / 12 noon Kenya, WhatsApp prepared in CORRESPONDENCE).

### What is remaining (in order)
**A. Before the launch window**
1. Moses: agree the call/launch window with Sylvia (item 26); private preview on that call (Live Preview of child theme + draft previews).
2. Moses security: change `moses` password on test, "Log Out Everywhere Else", set up two-factor; live admins use WordPress.com two-step sign-in.
3. Test site: ask host to enable OPcache (editor opens in 80-90 s).
4. ~~Child 1.23.11~~ built 12 Nov (registration line in block files, PHP parse check). Install 1.23.11 on test, then use 1.23.11 (not 1.23.10) on live at launch.
5. Confirm with Sylvia: Fast Forward / Be a World Changer one book? (16), price (17); Top Menu "Home" item points to `/sylvias-dream/`.
6. Give template contains the test-only "Private preview: ..." note: remove at insertion unless approved.

**B. Launch window (about 3 h, Kenya afternoon = Melbourne evening; MIGRATION-PLAN Phase C)**
1. Fresh backup (content XML, subscribers).
2. Activate WooCommerce; set its pages Draft/assign; check Site visibility Live; Tools > Import > WordPress the products XML (all draft); attach the two protected ebook files; check images, Stripe `_sdc_buy_link` and free-download links.
3. Activate child theme 1.23.10; insert saved templates into Home 6404, Give 6406, Invite 6408 and the 6 existing pages (MIGRATION-PLAN 4b step 5); Settings > Reading front page = 6404.
4. Publish products; menus; Kindle links amazon.com.au; Going to Heaven links to `/2026/11/10/meet-jesus/`; Redirects ON per REDIRECT-MAP (add `/home/` and `/sylvias-dream/` -> `/` only with Sylvia's OK).
5. Click-test every Stripe, Dream Partner and GoFundMe button (do not pay); Sylvia connects her own Stripe for the cart; one real purchase she refunds; QA checklists; clear WordPress.com cache.

**C. After launch / handover**
- Past buyers' new download links (item 24, Option A), then old public files removed with her written OK.
- Restore test, training (she signs in as `sylvia`), balance USD 168 due. Delete test copy after handover.
- After her Kenya trip: reword "in a few weeks' time" in the About paragraph.
- Remaining Sylvia items after launch: 3, 6, 11, 12, 15, 16-24, ebook files (2).

### How to work
- **Handover checklist for the next agent:** (1) read AGENTS.md, this section 0, DECISIONS (newest first), OPEN-ITEMS (top sections are current); (2) check the latest GitHub Actions "Checks" run on `main` is green (it runs `php -l` on PHP 8.0-8.3) before installing any zip; (3) the test site is reached by the Town browser; wp-admin and cPanel sign-ins are entered by Lightsof when asked; (4) release zips live in the Town library `Sylvias Dream/releases` (child 1.23.10, core 0.4.6 current); launch files (templates, products XML) in `Sylvias Dream/launch-templates`; live backups in `Sylvias Dream/backups` and on the topnotchmall server; (5) the live site is reached through WordPress.com (my.wordpress.com/sites/sylviasdream.com), session already signed in or Lightsof signs in.
- Read AGENTS.md, this section, `project/DECISIONS.md`, `project/OPEN-ITEMS.md` first.
- Code: edit in the repo, bump versions, `bash scripts/build-theme-zip.sh` (it now fails on any PHP syntax error; needs PHP or `npm install --no-save php-parser@3` / NODE_PATH), commit to `main` with CHANGELOG, HANDOFF, OPEN-ITEMS, DECISIONS, PROGRESS-LOG in the same commit; save zips to the Town library; install via Appearance > Themes > Upload > Replace. If wp-admin dies, replace the theme folder via cPanel File Manager (`/public_html/sylviasdream/wp-content/themes`).
- Wording: only Sylvia's own words or her written yes; record each answer by number.
- Content edits on Elementor pages: add new widgets rather than restructuring the "Section from your page" widgets.

**Never** (full list in section 5 and AGENTS.md): touch Stripe or break an existing payment, Dream Partner or thank-you link; change the live site before backups and Sylvia's written OK; invent facts or wording; edit code in the WordPress dashboard; mention this repo to Sylvia or contact her without Moses's approval.

## 1. What this project is
Pimofy Digital (Moses Munyi) is rebuilding **sylviasdream.com** for **Sylvia Suhr** (Christian author and missionary, Australia; ministry Heaven on Earth in Kenya). Custom theme, WooCommerce book shop with secure ebook downloads, Dream Partner giving page, content clean-up and SEO. Fixed price **USD 280** (quotation PMF-QT-2026-0106). The live site stays on the **WordPress.com Personal plan** (plugins and theme uploads are allowed on paid plans since April 2026, see `project/DECISIONS.md`).

**Moses's quality bar (23 Oct):** the new site must be international-standard, very professional, mobile-ready, with a beautiful fixed (sticky) menu, and "one of the best book websites in the world". It must match or beat `prototype/index.html` v3, **including its images and footer**, and every page must be **fully editable by Sylvia, like a page builder**, so handover is easy.

## 2. Read in this order
0a. `docs/MIGRATION-PLAN.md`: how the finished dev site moves to sylviasdream.com (live audit 26 Oct, blockers, phases, rollback).
0. `docs/REDESIGN-HANDOFF.docx` (design direction, acceptance criteria, integration matrix) and `docs/LAUNCH-QA-CHECKLIST.md` (26 Oct).
1. This file.
2. [`project/PROGRESS-LOG.md`](project/PROGRESS-LOG.md): what was done, day by day, 21-23 Oct, and exactly where work stopped.
3. [`docs/NEXT-BUILD-1.1.0.md`](docs/NEXT-BUILD-1.1.0.md): the brief for theme 1.1.0 (built 23 Oct; see CHANGELOG).
4. [`AGENTS.md`](AGENTS.md): rules for working in this repo.
5. [`project/README.md`](project/README.md), [`project/DECISIONS.md`](project/DECISIONS.md), [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md).
6. Runbooks in `docs/` (`DEPLOYMENT.md`, `BACKUP-AND-LAUNCH.md`, `QA-CHECKLIST.md`, `REDIRECT-MAP.md`).

## 3. Current status (11 November 2026)
| Area | Status |
|---|---|
| Dev / test site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, noindex. Users: `moses` (Administrator, 2FA off, password must change), `sylvia` (Editor). cPanel: topnotchmall.co.ke:2083 (Lightsof signs in). |
| Live site | sylviasdream.com on WordPress.com Personal, Twenty Twenty theme still active. **Phase B (12 Nov):** core 0.4.6 active (switches off), themes uploaded not active, Elementor 4.4.0 and Redux 4.5.18 active, 9 missing media uploaded, empty Drafts Home 6404 (slug welcome), Give 6406, Invite 6408, 9 launch templates in Saved Templates; WooCommerce 11.3.0 installed but **inactive** (exposed /shop/); products not imported (CSV blocked, use XML at launch). No visible change. Sylvia published "Meet Jesus" there 10 Nov. Domain paid to 25 Nov 2027. |
| Migration | Only after item 25 (written go-ahead). `docs/MIGRATION-PLAN.md` (4b for Elementor pages). |
| Child theme | **1.23.11** in the repo and library (12 Nov); test site and live (uploaded, inactive) have 1.23.10. 1.23.9 is broken, never install. |
| Core plugin | **0.4.6** in the repo, on the test site (switches on) and active on live (switches off), 12 Nov. Never use 0.4.5 on live. |
| Other plugins (test) | WooCommerce, Elementor 4.3.5 (free), Redux 4.5.16, Rank Math, Jetpack, Password Protected (test only). |
| Server (test) | PHP 8.2, memory 512M, WP_MEMORY_LIMIT 256M, OPcache off (ask host), no page cache. |
| Products | 25 (Published on test; Draft on live). 17 with Stripe link; protected files for When the Word Becomes Life and Guard Your Heart Renew Your Mind (6x9); others "Coming soon". |
| Giving | Give page (Elementor): A$5/15/30/50 monthly Dream Partner links; registration line added 11 Nov; "give any amount" link pending; GoFundMe https://gofund.me/e6fffb40c. |
| Sylvia's answers | Done: 1, 2 (partly), 4, 5, 8, 9, 10, 13, 14, **25 (written go-ahead to move, 12 Nov)**. Launch-critical open: 26 (dates; she offered to meet 12 Nov). |

## 4. What is pending (older background, superseded by section 0 "What is remaining")
**Build work (Pimofy / next agent)**
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

**WordPress.com Personal compatibility (27 Oct, from WordPress.com documentation, not tested on live)**
- Plugin and theme uploads are allowed on Personal (since April 2026), so the child theme and core plugin can be installed.
- WordPress.com sign-in (Jetpack SSO) is on by default. People who sign in with a WordPress.com account skip the core plugin's two-factor check and generic errors, and may see WordPress.com's own sign-in screen instead of the branded page. Plan for live: every admin turns on WordPress.com two-step sign-in and the site requires it.
- No SFTP or file access on Personal, so `wp-config.php` constants (`SD_2FA_DISABLE`, `SD_SECURITY_DISABLE`, `SD_PREVIEW_MODE`) cannot be set on live; the only fallback is deactivating the plugin in WP Admin.
- Risks: the plugin switches XML-RPC off, which Jetpack features may need; the login limit counts by visitor address, which could be one shared address behind WordPress.com's servers. Proposed before launch: Settings > Sylvia's Dream switches for XML-RPC, two-factor and the login limit, then a sign-in test straight after installing on live.

## 5. Things that must not happen
- **Never break an existing Stripe payment or contribution link** (26 Oct): every live book purchase button, Dream Partner link, thank-you/download page and subscription keeps working exactly as now. No link is replaced, switched off or redirected (`project/DECISIONS.md`). Only exception (31 Oct, Option A): the old public paid download files and their thank-you/download pages, and only after past buyers have new secure links and Sylvia has given her written OK.
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
