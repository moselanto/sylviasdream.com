# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Saturday 14 November 2026, 04:25 EAT (UTC+3). Section 0 is the current state; older sections are background (valid unless section 0 says otherwise).

## 0. Latest status (14 Nov 2026, 04:25 EAT) - read this first
The new site is LIVE on sylviasdream.com (WordPress.com, launched 12 Nov). Test site https://topnotchmall.co.ke/sylviasdream/ (password-gated) is the reference build and always gets changes first. Detail: `project/PROGRESS-LOG.md` (newest first), `project/OPEN-ITEMS.md` (top section "Current list"), `project/DECISIONS.md`, `CHANGELOG.md`.

### Versions now
- Child theme **1.23.34** on test and live. Parent Base 1.0.1. Core plugin 0.4.6 (test switches on, live switches off).
- Release zips: library `Sylvias Dream/releases/` (1.23.29-1.23.34). Build: `bash scripts/build-theme-zip.sh` (bump version in `style.css` AND `functions.php` SYLVIASDREAM_CHILD_VERSION or the build fails).
- Live plugins added 13-14 Nov: **Rank Math SEO free 1.0.282** - Redirections (4 x 301) and Titles & Meta (titles, descriptions, share image). Moses to confirm keeping it.

### What changed 13-14 Nov (all on test first, then live with Nuracrown's approval)
- 1.23.29 Linktree footer icon; 1.23.30 Home "Meet Sylvia" photo framed like My Story.
- Site audit (88 URLs all 200): Shop for Books single H1 + title; search descriptions; When the Word Becomes Life in Christian Living; Fast Forward to Success product 6465 + page 636 Draft; old pages drafted with 301s (live: 4141 /from-chance-to-choice-2/ -> product, 5860 /free-ebook/ -> /free-7-day-devotional/, 5022 /sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ/ -> /, 636 /fast-forward-to-success/ -> /shop-for-books/).
- Speed: 1.23.31 no Elementor Google Fonts (theme loads same fonts); 1.23.33 dashicons removed for signed-out visitors (link tag dropped); 1.23.34 Stripe express checkout (Apple/Google Pay) scripts only on the two cart books (When the Word Becomes Life, Guard Your Heart Renew Your Mind) - Moses option 2, no Stripe setting changed. Book pages without cart went from ~777 KB to ~300 KB of JS/CSS.
- Audiobooks: 1.23.32/1.23.33 product field "Audiobook on Spotify" + "Listen on Spotify" button. Live: One Life 6447 -> https://open.spotify.com/show/68AvP080lXfLZtSsQ48fRH ; Angelique 6459 -> https://open.spotify.com/show/244Ycpny4KDV6bvEGxxFdd . New Angelique cover (Legacy Edition) live media 7038 (old 6469 kept).
- SEO: 11 page titles/descriptions (Home "Sylvia Suhr: Christian Books & Devotionals | Sylvia's Dream"), separator "|", social share banner 1200x630 (repo `content/social/sylvias-dream-social-share-1200x630.jpg`, live media 7046) as default + Home share image. Descriptions use only Sylvia's existing sentences.
- Handover guide docx (library `Sylvias Dream/Website handover guide and training plan for Sylvia.docx`) - draft for Moses.
- Sylvia asked (WhatsApp) to check the live site and send changes.

### KEEP - never draft, redirect or change
- /all-shall-be-well-2/ (All Shall Be Well thank-you/download page) and live /sent-3/ page 4717 (ebook + mission-guide downloads).
- Stripe settings, Buy ebook (buy.stripe.com) links, Dream Partner links, GoFundMe, thank-you pages.

### Pending (in order)
1. PageSpeed Insights (API daily limit hit 13-14 Nov): run scheduled ~14:20 EAT 14 Nov for Home, Shop, One Life page, Journal, One Life book, When the Word Becomes Life (mobile + desktop); log vs 1-3 s target.
2. Sylvia's reply to the site check; apply changes test first.
3. A1: Sylvia to confirm the test purchase refund.
4. Kenya Ministry Training Manual PDF 404 (Download button on /kenya-ministry-training-manual-free-download/ links /wp-content/uploads/2026/03/Kenya_Training_Manual.pdf) - needs the file from Sylvia.
5. Moses: approve close-out brief v2 and handover guide before they go to Sylvia; confirm keeping Rank Math.
6. Update the handover guide for new items (Listen on Spotify field, Rank Math titles/descriptions, Fast Forward removed).
7. Training session with Sylvia (~60 min, plan in handover guide Part G); USD 168 balance invoice.
8. Security: change test `moses` password, enable 2FA; live admins use WordPress.com two-step sign-in.
9. GoFundMe embed.js text link on Heaven on Earth: needs a render-side theme fix; content edits fail, do not retry.
10. Speed ideas not done: Gutenberg block-library CSS (140 KB) on Elementor pages; Jetpack/WooCommerce analytics scripts (platform).

### How to work
- Test first, then live only after Nuracrown/Moses approve; before/after screenshots; signed-out checks (plain fetch is a good second check); clear cache via WordPress.com Caching > Clear all.
- Never Publish/re-publish posts on live (Jetpack emails subscribers); anything free needs Sylvia's written OK; draft, never delete; styling in child-theme CSS; never invent wording; never mention the repo to Sylvia or contact her without Moses's approval.
- Commit code with CHANGELOG, HANDOFF, OPEN-ITEMS and PROGRESS-LOG in the same commit. Install: Appearance > Themes > Upload > Replace current.
- The hosted browser cannot resize to phone width or open some new tabs; ask Nuracrown to check phones by eye.

## 0b. Earlier status (12 Nov 2026, 03:00 EAT) - superseded by section 0
Rewritten Thursday 12 November 2026. Sections 0 and 3 are the current state. Detail: `project/PROGRESS-LOG.md` (newest first), `project/OPEN-ITEMS.md` (top sections current), `project/DECISIONS.md`, `docs/CLIENT-CHECKLIST.md` (Sylvia's answers by number), `docs/MIGRATION-PLAN.md` (launch steps, live media map).

### Where we are
- **THE NEW SITE IS LIVE on sylviasdream.com (12 Nov 2026, 03:40-04:50 EAT)**, launched early at Lightsof's request after Sylvia's written go-ahead (item 25). Child theme **1.23.13** (footer Contact link 1.23.12, hero covers 1.23.13) and core **0.4.6** active (security switches off, redirects on, Dream Partners 1), Elementor 4.4.0, Redux 4.5.18, WooCommerce 11.3.0 (AUD).
- Live pages from the launch templates: Home 6404 (front page), Give 6406, Invite Sylvia to speak 6408, Shop For Books 3001, Contact 20, My Story 2665, Podcasts and Videos 1366, Author Support 6235, Heaven on Earth 1932. Policy pages 6826/6830/6833. 24 products published. Menus Primary / Footer Books / Footer About / Footer Policies as on test. My account 6439 published; **Cart 6437 and Checkout 6438 Draft** until Sylvia connects Stripe, so books sell through her Stripe "Buy ebook" buttons for now (Add to cart cannot show: no ebook files attached on live yet).
- Backups before launch: content XML (library `Sylvias Dream/backups`, plus a fresh export 12 Nov 03:30 in the session), 254 media files on the topnotchmall server outside public_html. Rollback: Appearance > Themes > activate Twenty Twenty, Settings > Reading front page 5022, menus "Primary (old)" / Top Menu.
- Test site (topnotchmall.co.ke/sylviasdream/, password-gated) stays as the reference build (child 1.23.10 there).

### What is remaining (in order)
1. Child **1.23.16** is installed on test and live (12 Nov 17:30); 1.23.15 added Add to cart beside Buy ebook, 1.23.16 removed the stray GoFundMe script link on Heaven on Earth. Live vs test audit finished 12 Nov 18:20: Journal (6961, Posts page), WooCommerce Shop page (3001) and Grace for the Journey layout done 12 Nov 18:40; Alt text fixed 19:35. Book pages One Life / From Fear to Freedom / SENT replaced on live 19:30 per Moses (backups 6969 / 6975 / 6980). Still open: Manifesting Miracles Now cover (Sylvia to confirm artwork).
2. Call with Sylvia: she connects her own Stripe for the cart; publish Cart/Checkout; attach the 2 protected ebooks; one real purchase she refunds.
3. Confirm prices with Sylvia (All Shall Be Well, SENT free vs paid, "Freedom From Fear" name) - never edit Stripe.
4. Real-phone checks; footer contact vs newsletter decision.
5. Moses security (change `moses` password on test, 2FA); live admins use WordPress.com two-step sign-in.
6. After launch: past buyers' new links (item 24), restore test, training, USD 168 balance, open Sylvia items (3, 6, 11, 12, 15-24), reword "in a few weeks' time" after her trip.
7. Lessons: import Elementor templates only while the child theme is active; menus rebuilt by hand need the `sd-mega` class on Books; do not swap the home hero to the "big welcome banner" widget (random books, no background photo); kept sections with `wp:block` refs need the synced pattern recreated on live (Heaven on Earth slider: test 6637 -> live 6924); keep Jetpack's floating Subscribe button off.

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

## 3. Status table (11 November 2026; see section 0 for 13 Nov changes)
| Area | Status |
|---|---|
| Dev / test site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, noindex. Users: `moses` (Administrator, 2FA off, password must change), `sylvia` (Editor). cPanel: topnotchmall.co.ke:2083 (Lightsof signs in). |
| Live site | sylviasdream.com on WordPress.com Personal, Twenty Twenty theme still active. **Phase B (12 Nov):** core 0.4.6 active (switches off), themes uploaded not active, Elementor 4.4.0 and Redux 4.5.18 active, 9 missing media uploaded, empty Drafts Home 6404 (slug welcome), Give 6406, Invite 6408, 9 launch templates in Saved Templates; WooCommerce 11.3.0 installed but **inactive** (exposed /shop/); products not imported (CSV blocked, use XML at launch). No visible change. Sylvia published "Meet Jesus" there 10 Nov. Domain paid to 25 Nov 2027. |
| Migration | Only after item 25 (written go-ahead). `docs/MIGRATION-PLAN.md` (4b for Elementor pages). |
| Child theme | **1.23.34** on test and live (14 Nov ~03:35); 1.23.33 before (14 Nov ~03:05); 1.23.32 before (14 Nov; dashicons still loads for visitors, follow-up needed); 1.23.30 before (live 13 Nov about 17:45, Home Meet Sylvia photo frame; phone checked). 1.23.29 Linktree live 13 Nov 15:50. 1.23.18 and 1.23.9 are broken, never install. |
| Core plugin | **0.4.6** in the repo, on the test site (switches on) and active on live (switches off), 12 Nov. Never use 0.4.5 on live. |
| Other plugins (test) | WooCommerce, Elementor 4.3.5 (free), Redux 4.5.16, Rank Math, Jetpack, Password Protected (test only). |
| Plugins added on live 13 Nov | Rank Math SEO free 1.0.282 (Redirections module only, 4 redirects). |
| Server (test) | PHP 8.2, memory 512M, WP_MEMORY_LIMIT 256M, OPcache off (ask host), no page cache. |
| Products | 25 (Published on test; Draft on live). 17 with Stripe link; protected files for When the Word Becomes Life and Guard Your Heart Renew Your Mind (6x9); others "Coming soon". |
| Giving | Give page 6406 (live): A$5/15/30/50 monthly Dream Partner links + "Give any amount" (https://buy.stripe.com/6oU4gz6gf0vfc6og2T7g40o, live 13 Nov); GoFundMe https://gofund.me/e6fffb40c. |
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
