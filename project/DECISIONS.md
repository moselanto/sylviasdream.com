# Decision log

Newest first. Each entry: what was decided, who decided, and why.

## 12 Nov 2026: cart beside Buy ebook (Option A) and SENT free
- **Option A** (Lightsof for Moses, 12 Nov): books with a protected download file show Add to cart beside Sylvia's existing Buy ebook button (child 1.23.15). Her Buy ebook buttons and links are unchanged. Option B (removing buy links) not taken.
- **SENT is free** (Sylvia on the call, confirmed by Lightsof 12 Nov). The SENT product is set free on live; the old SENT page's paid Stripe button is not removed or changed (AGENTS rule 7) until a separate decision.

## 12 Nov 2026: Sylvia's price answers on the call
- All Shall Be Well ebook price is A$5.99 (matches her Stripe payment page); website updated.
- The Report of the Lord stays A$8.35.
- The book's title is From Fear to Freedom; the Stripe product name is left for Sylvia to change herself.
- SENT is to be free (verbal); applied only after her written OK (AGENTS rule 3).

## 12 November 2026: launch before the call
- **Footer shows the Contact Sylvia link, not the sign-up form** (Lightsof, 12 Nov, to match the test site). Customizer `footer_news_form` off.
- **Home hero keeps the cover block with the background photo and the fixed three books** From Fear to Freedom, One Life, SENT (Lightsof, 12 Nov), not the random-book banner widget.
- **Launched early** (Lightsof, 12 Nov ~03:30 EAT, "Launch now; Sylvia connects Stripe for the cart on the call"), on Sylvia's written go-ahead (item 25). Cart and Checkout stay Draft and no ebook files are attached on live, so Add to cart cannot show; books sell through Sylvia's existing Stripe "Buy ebook" buttons until she connects Stripe herself.
- **My account published** (agent, 12 Nov) so "My downloads" links work (sign-in only; no payments involved).
- **Policy "Draft for Sylvia's review" notices removed** (Sylvia approved the policy pages, item 14, 11 Nov); Privacy Policy "Last updated: 11 November 2026".
- **"Meet Jesus" button** on the Going to Heaven page, linking to her Meet Jesus post (Sylvia's yes to item 13, 11 Nov).

## 12 November 2026: PHP syntax check is mandatory in the build
- **`scripts/check-theme.js` fails when any PHP file does not parse, and also fails when it cannot check PHP at all** (Lightsof, 12 Nov; child 1.23.11). Uses `php -l` if PHP is installed, else the `php-parser` npm package. Why: child 1.23.9 shipped a PHP parse error that took the test site down on 11 Nov; a check that silently skips is not a check.
- Registration line kept in the repo block files (`content/blocks/heaven-on-earth.html`, `give.html`) so the repo matches the approved site wording (Sylvia's yes, 11 Nov).

## 12 November 2026: /home/ slug and WooCommerce timing on live
- **Old page 2 "Sylvia's Dream" keeps `/home/`; the new Home draft (6404) uses slug `welcome`** (recommended by the agent, recorded 12 Nov; Lightsof asked for a recommendation). Findings: the live front page is page **5022** "Sylvia's Dream" (Settings > Reading, static), not page 2; posts page is "Free Resource for Mission Training". Page 2 (`/home/`) is linked from the **Primary** menu "Home" item and is the source page of past contact-form entries. The Top Menu "Home" item points to `/sylvias-dream/`. Why: renaming page 2 would break a live, linked address; the new Home becomes the static front page at launch and is served at `/`, so its slug is never seen. At launch: Settings > Reading front page = 6404; leave pages 2 and 5022 published until Sylvia decides, then redirect `/home/` and `/sylvias-dream/` to `/` (add to REDIRECT-MAP) rather than delete.
- **WooCommerce stays installed but inactive until the launch window.** On 12 Nov activation published Shop/Cart/Checkout/My account (set to Draft at once: 6436-6439, policy 6440 Draft) but the product archive at `/shop/` stayed public with the Shop page in Draft, so WooCommerce was deactivated. Site visibility was already "Live". Why: no visible change before launch (runbook Phase B 6 says "close to launch").
- **Product import on live via WordPress XML, not CSV:** WordPress.com rejected the CSV upload ("not allowed to upload this file type"). Plan: at launch, export products from the test site with Tools > Export > Products (WXR), set every `wp:status` to `draft`, import with Tools > Import > WordPress after activating WooCommerce. Download files attached afterwards on live (BUYER-TRANSITION-PLAN).

## 12 November 2026: core 0.4.6 security switches
- **XML-RPC blocking, two-step sign-in and the login limit are switchable in Settings > Sylvia's Dream** (Lightsof, 12 Nov), **off by default on WordPress.com**, on elsewhere. Why: on WordPress.com Personal wp-config.php cannot be edited, and Jetpack (her 24 newsletter subscribers, the Meet Jesus subscribe post, WordPress.com sign-in) may rely on XML-RPC. Live admins use WordPress.com two-step sign-in instead (HANDOFF 27 Oct note). Only core 0.4.6 or later goes on live.

## 12 November 2026: go-ahead to move the new site to sylviasdream.com
- **Sylvia approved the move in writing** (WhatsApp, relayed by Lightsof 12 Nov): "Please move the new website to sylviasdream.com". She also confirmed the payment account is hers (item 8). This satisfies AGENTS rule 11 (migration only after her written approval). Launch still follows `docs/MIGRATION-PLAN.md` and `docs/BACKUP-AND-LAUNCH.md`: backups first, live untouched until the agreed window.

## 11 November 2026: products Published on the test site
- **AGENTS.md rule 12 changed** (Lightsof, 11 Nov): products may be Published on the password-gated, noindex test site so Sylvia can review the shop; they stay Draft on live until launch day. Why: the test site already had them Published for review, which conflicted with the old wording (Draft on dev and live). The password gate and noindex must stay on while products are Published there.

## 11 November 2026: Meet Jesus, bio wording, registration
- **Meet Jesus is delivered through Sylvia's own subscribe post** (Sylvia, 11 Nov): sylviasdream.com/2026/11/10/meet-jesus/ ; subscribers are taken to the download. Not a shop product. The Going to Heaven page links to that post at launch (her yes to item 13). Why: it grows her subscriber list and she built it herself.
- **Bio wording** (Sylvia, 11 Nov, item 9): age 82 and her sentence about returning to Kenya, used as she wrote it (only the apostrophe in "weeks' time" added). Child 1.23.9.
- **Registration:** Heaven on Earth Ministries, Reg. No. 35364, registered in Kenya 2011 (Sylvia via Joshua, 11 Nov). Supersedes "she has none" (5 Nov). Wording approved 11 Nov (yes relayed by Lightsof): "Heaven on Earth Ministries is registered in Kenya (Reg. No. 35364, 2011)." on the Heaven on Earth and Give pages.

## 3 November 2026: 1.23.2, A$ prices, shop checkout plan, no dashboard code edits
- **Prices show "A$"** (Agnes, 3 Nov): AUD symbol filter in the child theme (`sdc_aud_currency_symbol()`, filter `sdc_currency_symbol`). Currency and prices unchanged. Why: international readers read "$" as US dollars.
- **No code edits in the dashboard** (AGENTS.md rule 33). Why: the 31 Oct Additional CSS rule and `inc/cover-alt.php` edit existed only on the test site and the next theme upload would have removed them; both now live in 1.23.2.
- **Shop checkout on live: proposal, not yet agreed with Sylvia.** Use the free WooCommerce Stripe Payment Gateway. On launch day, after backups, install WooCommerce and the gateway (inactive), then Sylvia clicks "Connect with Stripe" and signs in to her own Stripe account herself (Pimofy never sees keys or enters Stripe). Cart payments go to the same Stripe account as her buy buttons. Test site keeps "Test payment (no money)". Checks before launch: WordPress.com help says all paid plans (Personal included) can install plugins, but one forum reply disagrees, so confirm on her site; check whether WordPress.com adds a commerce fee on Personal (the 27 Oct audit showed 8% on WordPress.com Payments, which is separate). Then one real Add to cart purchase and one Stripe-button purchase with Sylvia, both refunded in Stripe.
- **Paperback Amazon links:** the 31 Oct amazon.com.au decision covers Kindle links only; paperbacks (SENT, From Fear to Freedom, French edition) wait for Sylvia (CLIENT-CHECKLIST item 21).
- **Original covers** for Guard Your Heart and Angelique Finds Her Light: Agnes has them (3 Nov), so they are no longer asked of Sylvia.

## 31 October 2026: old download files (Option A), Kindle links, Stripe links on the test site
- **Old public download files are retired, in three steps (Agnes, 31 Oct, "Option A"; Sylvia's email of 31 Oct asked for this, per the quotation).** 1) All new ebook sales are delivered through secure WooCommerce downloads. 2) Past buyers receive new secure links (Sylvia sends that email herself, so her buyer list never leaves her, or Pimofy prepares it for her). 3) Only after that, and with Sylvia's **written OK**, the old publicly accessible paid files are removed and the old thank-you/download pages point to the shop. **This partly supersedes the 26 Oct (night) rule** for the old download files and thank-you/download pages only: nothing is removed or redirected before step 3. Unchanged: every existing Stripe payment link, Dream Partner link and subscription keeps working, and Pimofy never touches Stripe. Free resources (SENT, How to Write and Publish, 7 Days to Trusting God, Kenya Training Manual) are not paid files and are not part of this.
- **Recommended purchase route** (proposed to Sylvia, 31 Oct): Add to cart becomes the main way to buy ebooks (secure download and \"My ebooks\" re-download); the Stripe buttons stay for anyone who already has them. Both routes are part of the purchase tests (Stripe only with a real purchase that Sylvia refunds herself).
- **All Kindle/Amazon links go to amazon.com.au** (Agnes, 31 Oct; option (a) of the 29 Oct question). Format `https://www.amazon.com.au/dp/<same ASIN>`. Done on the test site 31 Oct (14 amazon.com links switched; links already on amazon.com.au unchanged). Repeat on live at launch.
- **Stripe \"Buy ebook\" links copied from live pages** (Agnes, 31 Oct): only from `docs/LIVE-LINK-INVENTORY.md`, after checking each button label on the live page; no link changed. Books still without a link are listed in `project/OPEN-ITEMS.md` (31 Oct); ask Sylvia, never invent.
- **Shop tidy from Sylvia's 31 Oct email** (Agnes): Manifesting Miracles Now unpublished (Draft); the duplicate When Fear Has the Final Word listing (6390) moved to Trash, because it is From Fear to Freedom; Fast Forward to Success has its new cover. Be a World Changer cover saved, not used until Sylvia says whether it is a separate ebook.

## 30 October 2026: sign-in and My account polish (child theme 1.23.0)
- **Requested by Agnes (30 Oct).** The My account area gets a professional design using the existing brand tokens, through WooCommerce template overrides in the child theme (presentation only).
- **Menu labels** "My ebooks" (Downloads) and "Sign out" (Log out); endpoints and their addresses unchanged. Reversible with the `sdc_account_menu_labels` filter.
- **No duplication of core security.** Generic sign-in errors and the branded wp-login.php stay in sylviasdream-core 0.4.x (27 Oct decision); the child theme only styles.
- **Trust line** "Secure sign-in" is printed only on HTTPS so it is always true; no other claims on the sign-in screens.

## 29 October 2026: whole site editable in Elementor (child theme 1.21.0)
- **Elementor editing requested by Agnes (29 Oct 2026).** Every page, the header and the footer can be edited in Elementor (free) by Sylvia, with "Sylvia's Dream" widgets whose defaults are the current wording, and Sylvia's Dream Theme Options using the free Redux Framework (basic built-in screen without Redux). Replaces the 23 Oct note "Elementor-compatible page templates in case she installs Elementor later": Elementor is now the recommended editor; the block editor and patterns keep working.
- **Optional and reversible.** Without Elementor the site looks and works exactly as 1.20.x. Pages are built in Elementor only when an administrator clicks "Build this page in Elementor" (Appearance > Set up page editor); a revision is forced first and "Use old version" switches back. Header/footer fall back to the built-in ones if a template is missing or broken.
- **Payments untouched.** Widgets only display links. The Buy ebook widget reads the product's "Buy ebook link (Stripe)" field; Give page buttons are kept inside "Section from your page" with their links and labels locked. Stripe is never touched.
- **Live site: WordPress.com Personal.** Plugin uploads are allowed on paid plans (see 27 Oct WordPress.com check), so Elementor and Redux Framework (both free, wordpress.org) can be installed there. **Verify at launch** that both install and activate on the Personal plan, that WordPress.com does not block the set-up screen's installer (otherwise install from Plugins > Add New), and that Elementor's generated CSS files are written.
- **Sylvia's role.** Elementor needs a role that can edit published pages (Editor). On the test site she is Contributor; change to Editor when she starts editing (Moses/Lightof to confirm).

## 27 October 2026: sign-in security and admin branding (core plugin 0.4.0)
Recorded with the 0.4.0 release (Lightof, 27 Oct). Code: `sylviasdream-core/inc/two-factor.php`, `auth-hardening.php`, `login.php`, `admin-brand.php`; details in `ARCHITECTURE.md` 7.1-7.4 and `SECURITY-AUDIT.md` S12-S14.
- **Two-factor sign-in is opt-in per user, but required for every administrator before launch.** Each user switches it on in Users > Profile (authenticator app code, 8 one-time recovery codes stored hashed). The plugin does not force it on anyone; instead administrators without it see a reminder notice, and launch waits until every administrator has it on and has stored their recovery codes (`OPEN-ITEMS.md`). Why: protects the site even if a password leaks (audit A07 launch action, quotation scope H) without locking out a user before they have set up an app.
- **Generic sign-in errors.** A wrong username, email or password all show "The username/email or password is incorrect.", and lost password for an unknown account behaves like a known one ("check your email"), on wp-login.php and WooCommerce My account. Why: the old messages told anyone which accounts exist (SECURITY-AUDIT S12). The lockout message and empty-field messages are kept.
- **Branding kept light.** Sign-in screens get the brand design (split screen, photo panel hidden on phones); in the dashboard only an optional "Sylvia's Dream" colour scheme (default for new users only, existing users keep their choice), footer text, a Welcome widget with links to existing screens, and the WordPress logo menu removed for non-administrators. No admin menus are moved or hidden. Why: Sylvia and editors keep the standard WordPress screens that guides and support describe; every part can be switched off (`login_brand`, `admin_brand`).
- **Emergency path is `define( 'SD_2FA_DISABLE', true );` in wp-config.php.** It stops asking for codes site-wide while keeping every user's 2FA settings; remove it again once the locked-out person is back in. For a single user, an administrator can turn 2FA off on that user's profile. Other switches: `SD_SECURITY_DISABLE` keys `login_brand`, `admin_brand`, `two_factor`, `login_errors`, `app_passwords`, `session_cleanup` (or `'all'`).

## 26 October 2026 (late): Sylvia's answers from the call, recorded from Moses's call script notes
Source: "Call with Sylvia spoken script v2" (Moses's notes typed beside each question). Verbal answers: **confirm in writing** before anything is published, per the quotation.
- Angelique: A Journey of Becoming and Angelique Finds Her Light are **separate books** (yes). The **Angelique Legacy Edition** should appear (yes).
- Guard Your Heart Renew Your Mind is **separate** from Guard Your Heart (yes).
- Titles and subtitles: as on Amazon ("one on Amazon"); matches the 26 Oct Amazon rename.
- Spanish, French, Bahasa Indonesia and Large Print editions **should show** (yes).
- Manifesting Miracles Now: "leave it". Read as **leave it out of the shop**; confirm with Sylvia (could also mean leave as is).
- **Free resources she named:** SENT for missionaries; How to Write and Publish a Spirit-Led Christian Book in 7 Days; 7 Days to Trusting God with Your Journey. Everything else stays paid / not free. Still needs her written OK before launch.
- Dream Partners give today through **Stripe** payment links (not the WordPress.com donation block), so no 8% WordPress.com fee issue.
- Open from the call: The King's Way. God's Plan For Salvation (older edition?), co-author credits, ebook prices (AUD).

Reconciled with the project documents (quotation v2, cPanel preview guide v1.1, process checklist, call script):
- Quotation section D said the shop would "replace about 10 separate Stripe payment links" and that old download files would be retired after a transition. **Superseded by the 26 Oct (night) payments rule**: existing Stripe links, contribution links and thank-you pages stay working permanently; any WooCommerce shop is added alongside. Tell Sylvia this is a change in her favour (nothing she already uses stops working).
- The call script and preview guide promised "old download pages keep working for 30 days" and "old links point to the new shop or are switched off with your written OK". Also superseded: they keep working, nothing is switched off.
- Process checklist asked Sylvia for Stripe Analyst access: superseded by 17 Oct (she declined; she exports what is needed).
- The preview guide named a pimofydigital.com subdomain; the actual preview is topnotchmall.co.ke/sylviasdream/.

## 26 October 2026 (night)

- **All existing Stripe payments and contribution links are kept, permanently** (Lightof, 26 Oct). Every live Stripe book-purchase button, every Dream Partner / contribution link, their after-payment thank-you and download pages, and every existing subscription keep working exactly as now, before, during and after the move. Consequences: the `LEGACY-LINK-MAP.md` replacements are **not** carried to live; `BUYER-TRANSITION-PLAN.md` Phase 2 (switching off links, redirecting thank-you pages, deleting files) is **cancelled**; the Give page uses the existing Dream Partner links; WooCommerce, if used, is an addition, never a replacement. The repo never stores payment-link addresses: the launch pack marks those buttons `#KEEP-LIVE-STRIPE-BUTTON` / `#KEEP-LIVE-DREAM-PARTNER-LINK-*`, and the person pasting copies the existing link from the same live page.
- **Amazon is the source for book titles, subtitles, formats and descriptions** (Lightof, 26 Oct), except where Sylvia's own site text exists. See `project/BOOK-CATALOGUE.md` and `project/AMAZON-CATALOGUE.md`.

## 26 October 2026

- **Migration approach confirmed** (Lightof, 26 Oct evening): build on dev without interrupting the live site; move to sylviasdream.com only after Sylvia approves, because the Personal plan has no staging. Plan: `docs/MIGRATION-PLAN.md` (content pasted into existing live pages in one switch-over window; new pages, themes, plugin, media and products prepared invisibly first).

- **Migration only after Sylvia approves the development site** (Agnes). The new site moves from topnotchmall.co.ke/sylviasdream to sylviasdream.com only after Sylvia has reviewed and approved the dev site.
- **Live access already in hand** (Agnes): Agnes's login (account mosemqute) is Administrator on sylviasdream.com (wp-admin and my.wordpress.com); used for the backups. No admin invite needed from Sylvia. Live stays read-only until migration.
- **One consolidated request to Sylvia**: email draft "Everything we need to finish your new website" plus Town document "Everything we need from Sylvia (26 Oct 2026)"; replaces the two 23 Oct drafts. Send only after Moses approves.

- **My Story follows the client-approved prototype** (Agnes, 26 Oct): banner "One life, led by God", story section, Milestones timeline, stats (81 / 20+ / 4), Invite Sylvia to speak. Replaces the earlier rebuild with the old page wording (kept in revisions). Portrait placeholder stays until Sylvia sends a photo.
- **Kenya Ministry Training Manual page stays published** (Agnes, 26 Oct). It is already public on the live site; the dev page (4303) keeps its Published status.
- **Header menu button shows on phones** alongside the bottom bar (Agnes, 26 Oct; reverses the 1.5.6 phone-only hide).

## 26 October 2026

- **Menu corrections** (Agnes): Kenya Ministry Training Manual goes under Ministry now (replaces the 23 Oct rule of keeping it out until Sylvia approves). Books > Other languages opens Français, Español and Bahasa Indonesia, which translate the whole site; From Fear to Freedom and SENT belong with the other books, not under Other languages.

## 23 October 2026

- **Navigation follows prototype v4** (Agnes, 23 Oct evening; replaces the earlier 6-item menu): Home · Books (mega: Browse by topic, Readers, Other languages, Start here) · About (Sylvia's story, Podcasts & Videos, Invite Sylvia to speak when the page exists, Author Support) · Ministry (Heaven on Earth Kenya, Become a Dream Partner) · Devotionals · Contact; plus a phone bottom bar Home / Books / Ministry / Give / More. "Kenya Ministry Training Manual" stays out of the menu until Sylvia approves it as a free resource.
- **Dev site is never connected to Sylvia's WordPress.com account** (Agnes, 23 Oct afternoon). Her subscribers stay on the live WordPress.com site (Jetpack Newsletter, built in on the Personal plan); the subscriber export is never imported into dev or Git. On dev the Subscribe block may stay empty; the sign-up is checked on live at launch.
- **Approved 6-item menu applied on dev** (Moses's approved menu; About Sylvia points to /my-story/, Give stays a header button only).
- **Quality bar raised** (Moses). The site must be international-standard, very professional, mobile-ready, with a beautiful fixed menu, and match or beat prototype v3 including its images and footer.
- **Fully editable by Sylvia** (Moses). Every page must be editable like a page builder. Approach: WordPress block editor with brand `theme.json` and "Sylvia's Dream" block patterns (no extra plugin, works on Personal), plus Elementor-compatible page templates in case she installs Elementor later. Brief: `docs/NEXT-BUILD-1.1.0.md`.
- **Main menu** (Moses): Home · Books (All Books, Devotionals) · About Sylvia · Ministry · Podcasts & Videos · Contact. Give is the gold header button, not a menu item.
- **Covers chosen** (Moses): How to Find the Real You uses the blue "True Identity in Christ" cover; The Hidden Key uses the dark bordered cover with the key (as on Amazon).
- **Book titles and subtitles follow Amazon** (Moses), from his PDF of her Amazon author page, normal title case. Amazon book descriptions supplied by Moses are used as product descriptions.
- **Things still needed from Sylvia** are collected in one list (Town document "Things needed from Sylvia"), sent to her when the build is done.
- **Contact page shows "Australia" only** (street and town removed on dev, per the existing rule).

## 22 October 2026

- **Redirect map corrected and merges approved** (Pimofy). After a content check of each duplicate page: `/how-to-find-the-real-you-2/` is a buyer download page, so it is protected, not redirected; `/my-autobiography/` (One Life hardcover page) goes to `/one-life/`; the fuller text from `/sylvias-dream/` and the original Hidden Key page is merged into the kept `-3` pages; `/manifesting-miracles-now/` waits for Sylvia. No current URL is lost: old pages are retired only when redirects are on. Core plugin 0.1.1; see `docs/REDIRECT-MAP.md`.
- **Preview site** is at topnotchmall.co.ke/sylviasdream/ (password protected, hidden from search). Redirects are switched on there for testing only.

## 18 October 2026

- **Build and client review happen on a private preview site on Pimofy's cPanel hosting** (Moses's decision), so Sylvia can view and test it at any time while her live site stays untouched. Password protected, hidden from search, Stripe test mode on Pimofy's own test account, no real customer data. Approved work then moves into her live WordPress.com site as hidden drafts; the preview is deleted after handover. Full guide: `docs/CPANEL-PREVIEW.md` (Word version in Pimofy's Town library).

## 17 October 2026

- **Stripe data is minimised.** Sylvia asked to share only what is needed. Agreed: no subscriber details at all, only the count of active subscriptions; ebook buyers only (name, email, book bought) for new download links, or Sylvia sends that email herself so the list never leaves her; products and payment links (no personal data). Files go only in the private Drive folder. See `STRIPE-DATA-REQUEST.md`.
- **Restoration test before handover** (Sylvia's request, agreed). Backups are restored onto a private copy and checked for site, shop, settings, orders and customers' download access; the result is shared with Sylvia before handover. See `SUPPORT-AND-RESTORATION.md`.
- **Restoration help.** Included at no charge during the 3-month support period. Afterwards included in the optional USD 20/month plan, or USD 12/hour without it (a typical restore takes a few hours). *Moses to confirm this policy before it is final; the quotation only promises free fixes for technical defects plus 1 hour/month of minor updates.*
- **No Stripe team access.** Sylvia declined the Stripe Analyst role because it can refund payments and pay out funds. Pimofy apologised; Sylvia exports the records herself. (Stripe also has a View only role; not requested.)
- **WordPress access:** Sylvia will invite mosemqute@gmail.com as Administrator.
- **Backups (as told to Sylvia):** content, media, theme and plugins, settings, shop data and subscribers. On Personal there is no one-click restore; a restore means reinstalling these parts and takes hours, not minutes. Internal note: WooCommerce orders need a separate export, and Customizer/WooCommerce settings need an export plugin plus screenshots.
- **Stay on the Personal plan.** Sylvia accepted. Quotation revised to v2 (dated 17 Oct, valid to 31 Oct): Personal plan, no upgrade or migration, 25 titles, custom-designed theme, backup and restoration points in section I.
- **Amazon check** of sylviasdream titles on Amazon.com.au: 24 of 25 found. Findings and questions in `BOOK-CATALOGUE.md`. Book list v3 and a prototype update are pending Sylvia's answers.
- **Call times offered** (Kenya / Melbourne): Sat 17 Oct 9:00 AM-2:00 PM Kenya; Mon 19 Oct 9:00 AM / 5:00 PM; Tue 20 Oct 10:00 AM / 6:00 PM; Wed 21 Oct 11:00 AM / 7:00 PM. Sylvia will confirm.

## 16 October 2026

- **Hosting question.** Two options were prepared: WordPress.com Business (about USD 252/year more) or hosting.com Starter cPanel (about USD 95 for 4 years, renewing at about USD 14/month). Moses: any move would be included in the fee, Jetpack carries the subscribers, ask Sylvia about the domain, do not mention the repo to her.
- **Sylvia pointed out that plugins now work on Personal.** Verified: WordPress.com announced plugins on all paid plans on 2 April 2026; WooCommerce installs on any paid plan. Older text claiming "no WooCommerce on Personal" is outdated. Moses chose not to contact WordPress.com support; the reply said pricing lists no transaction fee, confirmed by a test purchase before launch.
- **Migration fee waived** if a move had been needed. No longer applicable.

## 15 October 2026 (prototype feedback, also in the root README)

- Full catalogue of 25 titles, price stays USD 280.
- Angelique novel and children's book are separate books.
- Location "Australia" only.
- Giving levels $5, $15, $30, $50 monthly plus another amount.
- Nothing free without approval, especially the book-writing guide.
- Approvals before publishing: Heaven on Earth purchase wording, biography, ministry information.
- Author Support wording kept exactly.
- Large, readable text, especially on phones.

## 12 Nov 2026: book pages (Moses)
- One Life: Kobo link dropped. Reviews sections removed from One Life and From Fear to Freedom. SENT: option A, the paid Stripe button stays next to the free downloads (rule 7). Button labels kept exactly as on the old live pages.
