# Moving the new site to sylviasdream.com (migration plan)

Written 26 Oct 2026 after studying all repo documentation and a read-only audit of the live WordPress admin (same day). Nothing on the live site was changed.

## 1. The aim (agreed)
- Build and finish the new site on the dev copy (topnotchmall.co.ke/sylviasdream/). The live site keeps running untouched.
- Sylvia reviews and approves the dev site in writing.
- Only then is the new site moved into her live WordPress.com site in one planned switch-over window.
- Her plan is **WordPress.com Personal**: no staging, no SFTP/SSH, no one-click restore. So every live step is either invisible preparation or part of the one switch-over window, and a full manual backup comes first.

## 1a. Payments rule (26 Oct, overrides older docs)
All existing Stripe payments stay untouched and working: every book purchase button, every Dream Partner / contribution link, every after-payment thank-you or download page, every subscription. Nothing in this plan edits Stripe, switches a link off, or redirects a thank-you page. In the launch pack these buttons are marked `#KEEP-LIVE-STRIPE-BUTTON` (book purchases) and `#KEEP-LIVE-DREAM-PARTNER-LINK-*` (Give page amounts); before pasting a page, copy the existing link and label from the same live page into that button. After switch-over, click every payment and contribution button on live and confirm it opens the same Stripe page as before.

## 2. What the live site is today (audit, 26 Oct)
| Item | Live now | What it means for the move |
|---|---|---|
| Plan / WordPress | Personal, WordPress 7.1.3 | Theme and plugin uploads allowed; no staging |
| Theme | Twenty Twenty | Rollback = reactivate it |
| Plugins (10 active) | Akismet, CoBlocks, Crowdsignal Dashboard, Crowdsignal Forms, Gravatar Enhanced, Gutenberg, Jetpack, Layout Grid, MailPoet, Page Optimize; Classic Editor inactive | Keep all active until each old form is checked; WooCommerce not installed |
| Front page | "Sylvia's Dream" (slug `sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ`) | Swapped for the new Home at switch-over |
| Posts page | "Free Resource for Mission Training." (`sent-3`) | Looks like a mistake; set the new Journal page as posts page at switch-over |
| Menus | "Top Menu" (assigned to all locations); "Primary" (not assigned) | Build a new menu, assign it at switch-over |
| Pages / posts | 59 pages (55 published), 17 posts (15 published) | Matches the content imported to dev on 21 Oct |
| Newsletter | Jetpack Newsletter emails new **posts** to subscribers | Do not publish or re-publish posts during switch-over |

Pages that exist on dev but **not on live** (must be created on live): Home (new), Give, Invite Sylvia to speak, Journal, Privacy Policy, Terms of Sale, Refund Policy, plus WooCommerce Shop, Cart, Checkout, My account (created by WooCommerce).

Slug differences to respect: on live `/all-shall-be-well/` is the **thank-you page** (protected) and `/all-shall-be-well-2/` is the book page; dev uses `-2` and `-2-3`. The core plugin's protected list already uses the live slugs.

## 3. Blockers found while studying (fix on dev before launch)
1. **Dev addresses inside page content.** 14 files in `sylviasdream-child/content/blocks/` (and the pages pasted from them on dev) contain dev URLs: `https://topnotchmall.co.ke/sylviasdream/...` and `/sylviasdream/...` (theme images, book covers, buttons such as `/sylviasdream/shop-for-books/`). Pasted on live as they are, images and buttons would break or point at the dev site. Action: produce a **launch content pack** with every dev address rewritten for live (root paths, live media URLs).
2. **Media.** Book covers uploaded on dev (e.g. One Life, SENT, From Fear to Freedom, Oct 2026) are not in the live Media Library. Action: list every image the new pages use; upload the missing ones to live Media in the preparation phase (invisible).
3. **Final page content lives on dev, not only in Git.** Some pages were edited directly on dev (restored live wording, covers, menus). Action: before the content pack is built, copy each dev page's final Code-editor content into the repo so Git is the single source.
4. **Published live pages cannot hold a hidden draft** in core WordPress. Options: (a) paste the new content into each existing page during the switch-over window (about 30 pages, 2-3 hours, URLs unchanged); or (b) a "staged revision" plugin, only if it installs and works on WordPress.com and is removed afterwards. Recommended: (a), with the content pack ready so it is copy and paste only.
5. **Dev-only things that must not reach live:** the password gate plugin, the WooCommerce store notice "Private preview ... test mode", test giving links (replaced by the existing Dream Partner links at paste time), test Stripe keys, "Draft for Sylvia's review" notes on the policy pages.
6. **Items still waiting on Sylvia** (from OPEN-ITEMS): approval of the dev site and wording, ebook files, prices, download limit/expiry, Give amounts and Stripe set-up, logo originals, photo, reviews, policies, the free-download decisions, the buyer list or her own emails to past buyers.

## 4. Order of work
### Phase A: dev only (now)
- Finish the build and the Heaven on Earth layout; fix the open QA items.
- Build the launch content pack (blockers 1-3) in `content/live/`, with a check that no dev address remains.
- Export products from dev (CSV, still Draft) for import on live.
- Sylvia's review of the dev site; collect her answers; written approval.

### Phase B: live, invisible preparation (after approval)
1. Full backup set (`BACKUP-AND-LAUNCH.md` section 1): XML, media, subscriber CSV and count, screenshots of menus, widgets, settings, plugins; Author Support text copied.
2. Upload and activate `sylviasdream-core` with **Redirects OFF** (adds noindex to thank-you pages; no visible change). Check one thank-you page and one download still work.
3. Upload both themes; **do not activate**.
4. Upload missing media.
5. Create the new pages as **Drafts** (Home, Give, Invite Sylvia to speak, Journal, policies).
5a. Before any of this: in the live **Top Menu** untick "Automatically add new top-level pages to this menu" (cPanel preview guide 9.1), so new draft pages never appear in the menu.
5b. Design settings: move them with the Customizer Export/Import plugin, imported while the child theme is in Live Preview and saved as a Customizer **draft** (guide 9.2).
6. Install WooCommerce close to launch; set its Shop, Cart, Checkout and My account pages to **Draft immediately** (guide 9.2) (it publishes Shop/Cart/Checkout/My account pages); import products as **Draft**; downloads set up per `BUYER-TRANSITION-PLAN.md` Phase 0; payments in test mode.
7. Build the new menu (not assigned).
8. Private preview for Sylvia with Appearance > Themes > **Live Preview** and draft previews on a call; test purchase, download and gift in test mode.

### Phase C: switch-over window (Kenya afternoon = Melbourne evening, about 3 hours)
1. Fresh backup set (including products and settings).
2. Activate the child theme. Assign the new menu and footer menus.
3. Paste the content pack into each existing page (URLs unchanged); publish the new drafts.
4. Settings > Reading: Home = new Home page; posts page = Journal.
5. Settings > Sylvia's Dream: **Redirects ON** (`REDIRECT-MAP.md`).
6. Publish products; switch payments to live keys; one small real purchase, check the download email and that no WordPress.com fee was taken; refund.
7. Do **not** replace any old book button: every existing Stripe button keeps its live link (section 1a). Click-test each payment and contribution link.
8. Run `QA-CHECKLIST.md` and `LAUNCH-QA-CHECKLIST.md` on live.
9. Do not publish or re-publish any post (newsletter emails). Author display name = Sylvia.

### Phase D: after launch
- Past buyers get new secure links (`BUYER-TRANSITION-PLAN.md` Phase 1); Sylvia confirms the subscription count is unchanged.
- Thank-you / download pages and payment links stay as they are (section 1a); no Phase 2 retirement.
- Restoration test before handover; delete the dev copy after handover.

## 4a. Source documents reconciled (26 Oct)
Quotation v2 (PMF-QT-2026-0106), cPanel preview guide v1.1 (18 Oct), the process checklist and the call script were checked against this plan. Differences and what wins are in `project/DECISIONS.md` (26 Oct, late). In short: payments rule section 1a wins over the quotation's "replace Stripe links" and the 30-day retirement; new pages may be brought in by Tools > Import (as the guide says) but **existing** pages are updated in place so their URLs never change; the guide's "about one hour" launch assumed import of drafts, while pasting into ~30 existing pages takes 2-3 hours.

## 4b. Elementor-built pages (added 10 Nov 2026)

`content/live/` was written for the block editor (26 Oct). Since then several test-site pages were built or rebuilt in Elementor, so their final layout and wording now live in Elementor data (`_elementor_data` post meta), not in `post_content` and not in `content/live/`. Pasting the block files for these pages on live would bring back the old layout and lose the 5 Nov changes. This section replaces the 29 Oct note "`content/live/` stays the block-editor launch pack" for these pages only.

### Pages built in Elementor (checked in wp-admin on the test site, 10 Nov, read only)
All nine are Published on the test site and use the page template "Elementor full width (Sylvia's Dream)". The Pages list marks each as Elementor and Appearance > Set up page editor lists each as "Built in Elementor".

| Page (test-site ID) | On live today? | Changes not in `content/live/` (known) |
|---|---|---|
| Home (6365, front page) | New page on live (live front page is the old "Sylvia's Dream" page) | Elementor layout of the home sections |
| Shop For Books / Books (3001) | Exists (`/shop-for-books/`) | Page top banner widget settings |
| Contact (20) | Exists | Elementor layout around the Jetpack form |
| My Story (2665) | Exists | Portrait Media 6774, Kenya photo 6779, garden photo 6789, Milestones styling, "Let's Walk Together" layout (5 Nov) |
| Invite Sylvia to speak (6511) | New page on live | Elementor layout |
| Podcasts and Videos (1366) | Exists | Elementor layout, video cards |
| Author Support & Publishing Services (6235) | Exists | Elementor layout (wording kept exactly) |
| Heaven on Earth - Christian Charity and Church in Kenya (1932) | Exists | Ministry gallery 6786-6789, Kenya photo 6779, YouTube channel button, "Registered" wording removed |
| Give (6428) | New page on live | Four Dream Partner tiers with Sylvia's 5 Nov wording and her A$5/15/30/50 Stripe links, GoFundMe button, "Registered" wording removed, unapproved preview/billing notes (OPEN-ITEMS 5 Nov) |

Not built in Elementor (Default template, move with `content/live/` or by hand as before): Grace for the Journey 5593, Journal 6368 (posts page), SENT 4686, From Fear to Freedom 4231, One Life 2766, The Hidden Key 6171, Kenya Ministry Training Manual 4303, Privacy Policy 3, Terms of Sale 6432, Refund Policy 6434 (Refund and Returns Policy 6375 is a Draft), WooCommerce Shop/Cart/Checkout/My account, other book, download and thank-you pages. 72 pages in total (62 published, 10 drafts); 63 are not Elementor. Note: the block editor shows an "Edit with Elementor" button on every page; that alone does not mean a page is built in Elementor.

Headers and footers: the site-wide header and footer are the **built-in Sylvia's Dream header/footer** (Appearance > Sylvia's Dream Theme Options), not Elementor. The Elementor library holds "Sylvia's Dream header (starter)" 6701 and "Sylvia's Dream footer (starter)" 6702 (Published, type Section) and "Default Kit" 6699. The starters are not chosen site-wide and not on any of the 20 main pages checked (all set to "Use the website setting"); the other 52 pages' overrides were not opened. So no header or footer template needs to move at launch; the theme's built-in header and footer come with the child theme and Customizer settings.

Versions on the test site: Elementor 4.3.5, Redux Framework 4.5.16 (both active). Live needs the same or newer.

### Step 1: inventory (test site, before launch) - done 10 Nov (table above); repeat just before launch in case more pages are built
1. In WP Admin > Pages, list every page whose row shows "Elementor" (or open each main page and check for "Edit with Elementor" / "Back to WordPress Editor"). Record the final list here and in OPEN-ITEMS.
2. Elementor > Templates > Theme Builder / Saved Templates: note any header or footer template in use and the page-level header/footer overrides (`_sdc_header` / `_sdc_footer`).
3. For each page, list every image it uses (Media IDs) and every outgoing link (Stripe, GoFundMe, YouTube, Amazon, internal).

### Step 2: export (test site)
1. For each page: Edit with Elementor > arrow next to Update > **Save as Template** (name `LAUNCH - <page> - <date>`). Then Elementor > Templates > Saved Templates > **Export** each one to JSON.
2. Export the Kit's Global Colours and Fonts only (screenshot or Site Settings export). Do not import a whole Kit on live: Elementor's Kit import can overwrite site settings (see the 1.21.0 Kit incident).
3. Save the JSON files to the private Google Drive folder (they contain payment links), not to Git (AGENTS.md rule 24 and rule 8).

### Step 3: make the exports live-safe (before launch)
1. In each JSON, replace `https://topnotchmall.co.ke/sylviasdream/` with `https://sylviasdream.com/` and `\\/sylviasdream\\/` paths with `\\/` (same rule as `content/live/`). Search afterwards: no `topnotchmall` and no `/sylviasdream/` may remain.
2. Every Stripe and Dream Partner link must be the link Sylvia uses on live: book buttons keep the live link and label from the same live page (`#KEEP-LIVE` rule); the A$15/30/50 links are new (Sylvia, 5 Nov, OPEN-ITEMS) and must be click-checked on live without paying.
3. GoFundMe: https://gofund.me/e6fffb40c (also set in Customizer `sdc_gofundme_url` on live after installing child 1.23.6+).
4. Remove dev-only wording: the Give preview notice and billing note unless Moses or Sylvia approves them.
5. Elementor JSON stores image IDs and URLs from the test site. Its importer tries to download images from those URLs, which fails while the test site is behind its password. So upload every image from step 1.3 to live Media first (Phase B step 4), then in the JSON point each image `url` to the live address and `id` to the live Media ID.

### Step 4: live preparation (Phase B, invisible)
1. Confirm Elementor (free) and Redux install and activate on WordPress.com Personal (OPEN-ITEMS 29 Oct, still unverified). If they cannot, use the fallback below.
2. Install the child theme (not activated) and set Elementor > Settings so only Pages use Elementor.
3. Import each JSON at Elementor > Templates > Saved Templates > Import. Importing a template changes no page.
4. **New pages (Home, Invite Sylvia to speak, Give):** create as Draft, Edit with Elementor, insert the template, Save as Draft, set template "Sylvia's Dream Elementor full width". Preview with Sylvia.
5. **Existing published pages (Shop For Books, Contact, My Story, Podcasts and Videos, Author Support, Heaven on Earth):** do nothing yet. Updating a published page in Elementor publishes it at once, so these wait for Phase C.

### Step 5: switch-over window (Phase C, after the child theme is active)
1. For each existing page: Edit with Elementor > insert its saved template > check > Update. The page keeps its address. Set its template to the Elementor full-width template.
2. Publish Home (then Settings > Reading), Invite Sylvia to speak and Give.
3. Elementor > Tools > Regenerate CSS & Data; clear WordPress.com cache.
4. On each page: search the rendered page for `#KEEP-LIVE`, `topnotchmall` and `/sylviasdream/` (none may remain); click every Stripe, Dream Partner and GoFundMe button and confirm the same Stripe/GoFundMe page opens (do not pay); phone and desktop check.
5. Rollback for one page: Elementor > "Back to WordPress Editor" restores the previous `post_content`; or Page revisions.

### Fallback if Elementor cannot run on live
Use the block-editor files in `content/live/`, after first copying the 5 Nov content changes (table above) back into `sylviasdream-child/content/blocks/` and regenerating `content/live/`. Note the gaps in OPEN-ITEMS and get Moses's OK before launch.

## 5. Rollback (any time in Phase C)
Reactivate Twenty Twenty; Redirects OFF; reassign "Top Menu"; reset Settings > Reading to the old front page. Content restore from the same-day backup by hand (hours). Never touch Stripe during a rollback.
