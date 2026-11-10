# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Tuesday 10 November 2026 (EAT, UTC+3). Sections 0 and 3 are the current state; sections 4 onwards are older background (still valid unless section 0 says otherwise).

## 0. Latest status (10 Nov 2026) - read this first
Rewritten 10 Nov 2026 (Kenya time). This section and section 3 are the current state. Older daily notes (26 Oct to 5 Nov) are in `project/PROGRESS-LOG.md`, `project/OPEN-ITEMS.md` and `CHANGELOG.md`; they are no longer repeated here.

**Where things stand**
- The new site is built on the test site (https://topnotchmall.co.ke/sylviasdream/, password-gated, noindex). Sylvia's live site sylviasdream.com is **untouched** and stays read-only until she approves the test site **in writing**.
- Repo `main`: child theme **1.23.8**, core plugin **0.4.5** (10 Nov: "Coming soon" sale guard, see CHANGELOG). Zips in the Town library `Sylvias Dream/releases`.
- Test site: child **1.23.6** and core **0.4.4** until 1.23.8 / 0.4.5 are installed (check section 3; PROGRESS-LOG records each install). Note: `main` also contains child 1.23.7 (CSS-only My Story polish, `about.css`), which OPEN-ITEMS calls withdrawn because it did not reach the Elementor markup; it is harmless and ships inside 1.23.8.
- Pages built in Elementor on the test site: My Story, Give, Heaven on Earth (gallery), Contact, Books (confirm list per `docs/MIGRATION-PLAN.md` 4b step 1). They move to live by Elementor saved templates (MIGRATION-PLAN 4b), not by pasting `content/live/`.
- Shop: 25 products, Published on the test site only (Draft on live until launch). 17 have Sylvia's Stripe "Buy ebook" link. Only When the Word Becomes Life has a protected download file (verified 10 Nov: old public address 404, protected file 403, order #6686 downloads). With core 0.4.5 every other paid ebook without a Stripe link shows "Coming soon" until its file and price are added.
- Waiting on Sylvia: her 9 Nov reply was the Meet Jesus booklet only. A follow-up email and WhatsApp reminder (items 2, 3, 6-26) are prepared and need Moses's OK before sending (`project/CORRESPONDENCE.md`, `docs/CLIENT-CHECKLIST.md`).

**Next agent, start here (priority order)**
1. Install child 1.23.8 and core 0.4.5 on the test site if PROGRESS-LOG does not show it done; smoke-test (AGENTS rule 31) and check one "Coming soon" book, one Stripe book, When the Word Becomes Life (Add to cart still works) and a free book.
2. Security (Moses only): change the `moses` password (it was emailed to Sylvia on 27 Oct), "Log Out Everywhere Else", turn on two-factor for `moses`.
3. When Sylvia answers: record by number in CLIENT-CHECKLIST and OPEN-ITEMS; attach each ebook file through the product's file uploader (protected `woocommerce_uploads`), never as a public Media link.
4. MIGRATION-PLAN 4b step 1 (Elementor page inventory) and confirm Elementor and Redux run on WordPress.com Personal.
5. Upload the original covers for Guard Your Heart (6381) and Angelique Finds Her Light (6394) when Agnes supplies them.
6. Open launch blockers: OPEN-ITEMS "31 Oct cross-check" (Monday Encouragement test-site address, Going to Heaven button, unapproved Give wording), SEO meta descriptions, Rank Math focus keywords, Amazon icon on Kindle buttons, real-phone checks.

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

## 3. Current status (10 November 2026)
| Area | Status |
|---|---|
| Dev / test site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, noindex. All work happens here. Users: `moses` (Administrator, 2FA off, password must change), `sylvia` (Editor). |
| Live site | sylviasdream.com on WordPress.com Personal (renewed by Sylvia), Twenty Twenty theme, **untouched**. WooCommerce not installed on live. Domain paid to 25 Nov 2027. |
| Migration | Only after Sylvia's written approval (item 25 in CLIENT-CHECKLIST). Plan: `docs/MIGRATION-PLAN.md` (section 4b for Elementor pages). |
| Child theme | Repo **1.23.8** (10 Nov). Test site 1.23.6 until 1.23.8 is installed. |
| Core plugin | Repo **0.4.5** (10 Nov). Test site 0.4.4 until 0.4.5 is installed. |
| Other plugins (test site) | WooCommerce 11.2.1, Elementor (free), Redux, Rank Math, Jetpack, Password Protected (test only). |
| Products | 25 on the test site (Published there; Draft on live until launch). 17 with a Stripe "Buy ebook" link; 1 protected ebook file (When the Word Becomes Life). Others show "Coming soon" from core 0.4.5. |
| Giving | Give page (Elementor): A$5/15/30/50 monthly Dream Partner links from Sylvia (5 Nov); "give any amount" link pending; GoFundMe https://gofund.me/e6fffb40c. |
| Deploy method | `bash scripts/build-theme-zip.sh` (runs `check-theme.js` and `build-assets.js`), then WP Admin > Appearance > Themes > Upload > Replace active with uploaded; Plugins > Add New > Upload > Replace current with uploaded. Never edit code in the dashboard. |

## 4. What is pending (in priority order)
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
