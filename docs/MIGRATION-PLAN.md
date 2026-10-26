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

## 5. Rollback (any time in Phase C)
Reactivate Twenty Twenty; Redirects OFF; reassign "Top Menu"; reset Settings > Reading to the old front page. Content restore from the same-day backup by hand (hours). Never touch Stripe during a rollback.
