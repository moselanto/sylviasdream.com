# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Thursday 29 October 2026, about 12:00 Kenya time (EAT, UTC+3). Section 0 is the current state; sections 1 onwards are older background (still valid unless section 0 says otherwise).

## 0. Latest status (29 Oct 2026) - read this first
**New 29 Oct (14:00): child theme 1.21.1 on branch `feature/elementor-builder` (bug-fix on top of 1.21.0; not on the test site yet).** 1.21.0 was tried on the test site and rolled back by hand because of two bugs: (A) all dark gold buttons went blank (the Kit seeding wrote a site-wide dark gold link colour, body/heading fonts and content width into the Elementor Kit), (B) pages built in Elementor were squeezed into the 760px reading column. 1.21.1: the Kit gets only colour/font picker choices; a one-time admin clean-up removes the 1.21.0 keys where they still have the seeded value and clears Elementor's CSS; built pages use the new template `templates/sd-elementor-full-width.php` (theme header/footer, no content column; old template kept for "Use old version"); new `assets/css/button-safety.css` keeps every theme button in its designed colours whatever the Kit says (filter `sdc_perf_feature` key `button_safety`). Before uploading: run `bash scripts/build-theme-zip.sh`; after uploading, open the Dashboard once as administrator (runs the clean-up), then Elementor > Tools > Regenerate CSS & Data. Test list: `project/OPEN-ITEMS.md` "29 Oct (Elementor 1.21.1)".

**New 29 Oct (12:00): child theme 1.21.0 on branch `feature/elementor-builder` (pull request open, not merged, not on the test site yet).** The whole site can now be edited in Elementor (free) by Sylvia: Appearance > Sylvia's Dream Theme Options (Redux, or a basic screen without it), Elementor header/footer with per-page choice and safe fallback to the normal header/footer, 25 "Sylvia's Dream" widgets, Appearance > Set up page editor (install Elementor and Redux, create the Kit, starter header/footer, build each main page from its current content), a Dashboard guide box and a simpler editor screen for non-administrators. Optional: without Elementor nothing changes. Stripe and payment links untouched. Core plugin unchanged (0.4.4). Guide for Sylvia: `docs/ELEMENTOR-EDITING-GUIDE.md`; developer notes: `ARCHITECTURE.md` section 14; test list: `project/OPEN-ITEMS.md` "29 Oct (Elementor)". Sylvia needs the Editor role to use Elementor.

**Where things stand:** the new site is built on the test site and Sylvia has reviewed it once (her email of 27 Oct; reply drafted 27 Oct). Her live site sylviasdream.com is **untouched** and must stay that way until she approves launch in writing. Full lists: `project/OPEN-ITEMS.md` (top sections dated 29 Oct and 28 Oct) and `docs/CLIENT-CHECKLIST.md` (what Sylvia must send or approve).

**Versions on the test site (topnotchmall.co.ke/sylviasdream):** child theme **1.20.2** (repo branch: 1.21.1; 1.21.0 was tried and rolled back), parent Sylvia's Dream Base 1.0.1, core plugin **0.4.4**, WooCommerce 11.2.1, Rank Math, Jetpack, Password Protected (test site only).

**Build rule (important):** before zipping the theme always run `node scripts/build-assets.js`. WordPress resets file dates when it unzips, so a stale `style.min.css` wins over `style.css`; the header fix in 1.19.2-1.19.4 never showed until 1.19.5 rebuilt the .min files. `.min` files are gitignored and only live in the release zip.

**Test-site set-up you must know:**
- All **25 products are Published** on the test site (decision Lightof 28 Oct) so Sylvia can see the shop; the site stays behind its password and noindex. WooCommerce site visibility = Live. On live nothing is published before approval.
- **Reviewer preview** and **Test payments** switches in Settings > Sylvia's Dream (core 0.4.3/0.4.4). Both refuse to turn on at sylviasdream.com (`sd_core_is_live_site()`); the test gateway "Test payment (no money)" is never registered there.
- Checkout page uses the **classic** `[woocommerce_checkout]` shortcode (the block checkout did not list the test gateway); Cart is classic too. Recheck on live.
- WooCommerce > Products: "Enable AJAX add to cart buttons on archives" ON, "Redirect to cart" OFF (cart drawer, 1.20.0).
- Store notice: "Private preview of the new Sylvia's Dream website. Buy ebook buttons use Sylvia's real Stripe links and take real payments."
- Users: `moses` (Administrator), `sylvia` (sylviasuhr@gmail.com, **Contributor**, display name Sylvia Suhr; Lightof sends her password). All 17 posts are credited to Sylvia Suhr on the test site. **On live the posts are still by mosemqute:** change the author/display name there only at launch, and never publish or re-publish a post (the Jetpack newsletter emails subscribers).
- Redirects are still ON on the test site. On live they stay OFF until switch-over day.

**Done 27-29 Oct (newest first; details in OPEN-ITEMS and CHANGELOG):**
- 1.20.2: homepage "25 titles" now counts published books automatically ("25+ titles"; 27 books still shows 25+, 30 shows 30+). 1.20.1: Author Support page restyled (wording unchanged). Posts reassigned to Sylvia.
- 1.20.0: Journal index and single post redesign, side cart drawer, cart/checkout to shop standard (ebook-only checkout asks name, email, country), order-received downloads box, buy-box button heights. Smoke test passed, test order #6692.
- 1.19.x: random Featured Books on each refresh, "Buy ebook (Stripe)" field per product, header logo no longer overlaps the menu, product page tidy (no repeated subtitle, no quantity box for ebooks, one payment note).
- Core 0.4.3-0.4.4: reviewer preview switch, test payment gateway, `thank-you-for-your-purchase-3` protected.
- Prices set from Amazon AU Kindle prices (books with a Stripe link use the Stripe checkout price). Brand "Sylvia Suhr" on all products (Rank Math schema). Facebook https://www.facebook.com/sylviasdream in footer and Rank Math.
- Full test order with download passed (#6686, When the Word Becomes Life).
- 27 Oct: read-only live audit and launch runbook `docs/LAUNCH-READINESS.md`, live link inventory `docs/LIVE-LINK-INVENTORY.md` (20 Stripe links, 25 download files).

**Pending for Pimofy / next agent (short list):**
1. **Kindle/Amazon links:** mixed amazon.com and amazon.com.au. Waiting for Lightof's choice: (a) everyone to amazon.com.au, or (b) visitor's own country with AU fallback. Then add a small Amazon/Kindle icon to the button.
2. Stripe "Buy ebook" links still missing for ~20 books (only One Life, From Fear to Freedom, Hidden Key, From Chance to Choice, How to Find the Real You have them). Source: `docs/LIVE-LINK-INVENTORY.md` (check each label on the live page) or Sylvia.
3. Ebook files: only When the Word Becomes Life has a downloadable file. Attach protected copies (not the old public uploads URLs) to every paid product.
4. When the Word Becomes Life is "Uncategorized"; Rank Math focus keywords not set on the 25 products.
5. Mobile: Author Support page is long on phones; real-phone checks of nav, drawer and checkout still open.
6. Two-factor for every administrator before launch; WordPress.com sign-in decision (see section 4 and SECURITY-AUDIT).
7. Launch prep per `docs/LAUNCH-READINESS.md`: confirm the Personal plan renewal now shows 2027, add "Guard Your Heart Renew Your Mind" (live only) to the new site, regenerate `content/live/`, backups, install with Redirects OFF.

**Waiting on Sylvia (send or approve):** see `docs/CLIENT-CHECKLIST.md`, updated 29 Oct.

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

## 3. Current status (Monday 26 October 2026, 13:50 EAT)
| Area | Status |
|---|---|
| Dev site | `https://topnotchmall.co.ke/sylviasdream/` (admin `/sylviasdream/wp-admin/`), password-gated, hidden from search. All work happens here. |
| Live site | **Untouched.** Agnes's login (mosemqute) is Administrator on wp-admin and my.wordpress.com (used for backups); read-only until migration. Domain with WordPress.com (paid to 25 Nov 2027, auto-renew); Personal plan renews 29 Oct 2026; Stripe connected via WordPress.com Payments; WooCommerce not installed on live. |
| Migration | **Only after Sylvia reviews and approves the dev site** (Agnes, 26 Oct). |
| Child theme in repo | **1.20.0** (29 Oct: Journal redesign, side cart drawer, checkout polish, buy-box button height fix; zip `sylviasdream-child-1.20.0.zip`; not yet uploaded to dev; tick WooCommerce > Settings > Products "Enable AJAX add to cart buttons on archives"). Earlier: **1.18.0** (27 Oct: book pages, shop grid, topic chips and buy box matched to the prototype; zip `sylviasdream-child-1.18.0.zip` in Town library `projects/sylviasdream`; not yet uploaded to dev; re-paste `content/blocks/books.html` on the Books page for the new `[sd_book_filters]` search and topic chips). Before that **1.17.0** (27 Oct: security, preview mode, speed, sharing image; zips in Town library `projects/sylviasdream`; not yet uploaded to dev). Previously on dev: **1.16.3** (repo main; see CHANGELOG 26 Oct "Priority 1 clean-up"). Parent "Sylvia's Dream Base" 1.0.1. Core plugin 0.2.1 (`dist/sylviasdream-core-0.2.1.zip`). |
| Core plugin in repo | **0.4.2**, uploaded to dev and active (27 Oct). 0.4.2 (0a105be): readable "Prove your humanity" answer box on the branded sign-in page (Jetpack maths check). 0.4.1 (404f4ac): QR code on the two-factor setup screen, drawn on the site as inline SVG, key kept as fallback; QR seen on dev in a cancelled setup (2FA still Off for every account). 0.4.0 (65f208d): branded sign-in screens, generic login errors, opt-in two-factor sign-in, admin branding. Still to check on dev: scan the QR with a real phone; see the humanity box when Jetpack shows it (it did not appear during the 27 Oct test). Child theme on dev: **1.18.3**. **Release zip for the live install:** Town library `projects/sylviasdream/sylviasdream-core-0.4.2.zip` (built 27 Oct from main at 0a105be; 203,618 bytes; 26 files under `sylviasdream-core/`; SHA-256 `9abf91dcbf0af7c8d3eac51935410f1a158a31844c74c9875625aaced8744d2b`). Read the WordPress.com notes in section 4 before installing on live. |
| Deploy method | Build with `bash scripts/build-theme-zip.sh` (runs `check-theme.js`; last run "OK: 88 files checked, 32 block files"), then WP Admin > Appearance > Themes > Upload > **Replace active with uploaded**. cPanel Git also works again (host fixed the licence 26 Oct). |
| Page content | Pasted into each page's Code editor from `sylviasdream-child/content/blocks/*.html`. Old content stays in page revisions. |
| Products | 25 WooCommerce products, **all Draft** (Books grid empty until launch). |

### Done (newest first)
- **27 Oct, core 0.4.1-0.4.2 on dev:** QR code for two-factor setup (inline SVG, no outside service) and a readable "Prove your humanity" box. See CHANGELOG.
- **27 Oct, social image and Home SEO (dev, Rank Math):** new 1200x630 social image (logo, "From fear to lasting peace in Christ", "Faith-based books and devotionals by Sylvia Suhr", mission photo), media 6669 `sylvias-dream-social-1200x630-1.jpg`. Set as the Home (6365) Facebook/X image and as Rank Math's global default OpenGraph image; X card summary_large_image. Home SEO title "Sylvia Suhr: Christian Books & Devotionals | Sylvia's Dream"; description "Faith-based books and devotionals by Sylvia Suhr, Christian author, speaker and missionary. Move from fear to lasting peace in Christ. Explore her books today."; social title "From fear to lasting peace in Christ | Sylvia's Dream"; focus keywords Sylvia Suhr, Christian author, Christian devotionals, Christian books, faith-based books. Rank Math score 85/100. Rank Math prints the sharing tags, so the core plugin's own tags stay off. Dev No Index unchanged.
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
