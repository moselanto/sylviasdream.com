# Decision log

Newest first. Each entry: what was decided, who decided, and why.

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
