# Decision log

Newest first. Each entry: what was decided, who decided, and why.

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
