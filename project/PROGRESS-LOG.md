# Progress log

Newest first. Dev site = `https://topnotchmall.co.ke/sylviasdream/` (private build copy). The live site sylviasdream.com has not been changed.

## Monday 26 October 2026
- Content parity with live restored on dev (`818921b`): My Story, Podcasts, Hidden Key 6171, One Life 2766, Grace For the Journey category on all 15 posts. Verified desktop and mobile. Amazon link from page 1239 is the Kindle edition; hardcover link requested from Sylvia.
- Theme 1.8.3 (`708dda1`) deployed: menu language links (`#lang-fr/es/id/en`). Primary menu fixed: Books > Other languages = Français, Español, Bahasa Indonesia; From Fear to Freedom and SENT moved to Browse by topic; Kenya Ministry Training Manual under Ministry. All links and language switches verified on desktop and phone drawer.
- Afternoon: live facts checked read-only (Agnes's admin login). Consolidated email draft to Sylvia and checklist document. New covers for From Fear to Freedom, One Life and SENT applied on dev (products and book pages). One Life Amazon buttons now point to the AU paperback. `book-one-life.html` built and committed (f04b704) but **not yet applied on dev** because Town credits ran out.
- Stopped here. Next: apply book-one-life.html to 2766; Heaven on Earth by hand (`docs/hoe-segments/README.md`), visual checks of SENT and From Fear to Freedom, real-phone QA, hide "Uncategorized"/"Coming soon" from Browse by topic. See `HANDOFF.md` section 4.

## Friday 23 October 2026

### Phone menu, footer, Phase 0, schema, captions (26 Oct, 07:45-08:30 EAT)
- Theme 1.6.0 and core plugin 0.1.2 deployed to dev (WP Admin upload). Mobile: header menu button back, drawer submenus as white panels with icon tiles, Books groups as two-column chips, footer centred. All pass in the Customizer Mobile/Tablet presets and desktop.
- Phase 0: core plugin active; the 19 old thank-you/download pages are noindex and excluded from site search (sitemap exclusion could not be inspected: dev has no sitemap). One listed slug was wrong (`all-shall-be-well`; the page is `all-shall-be-well-2`, ID 3431): fixed in the plugin default list and the dev setting. Pages and files stay reachable by direct link on purpose until past buyers have new WooCommerce links (AGENTS rule 5; file blocking is Phase 1/launch).
- Book schema: draft sample emits valid Book JSON-LD; Offer (price, currency, availability) appears once products are published with prices. Check `bookFormat` (EBook vs Paperback) per product at publishing.
- Heaven on Earth: all seven "dav" slideshow captions removed.
- Kenya Ministry Training Manual stays published (Agnes).

### Home sections and Books "Start here" (24 Oct 23:45 - 25 Oct 00:15 EAT)
- Theme 1.5.3-1.5.5 deployed to dev (theme-zip upload); Home page 6365 content replaced from content/blocks/home.html.
- Dev QA (desktop + Customizer Mobile): all new Home sections pass; Start here covers equal and contained; Escape/Tab still correct. Open: Featured books empty until products are published; newsletter shows the Contact fallback on dev; "One Life: the memoir" goes to the Books page until the product is published.

### Inner pages, Ministry/About layouts and Contact (24 Oct, 20:20-23:45 EAT)
- Theme 1.3.2 to 1.5.2 deployed to dev by WordPress theme-zip upload (cPanel licence still broken).
- Agnes chose: prototype layouts with Sylvia's current words; Give gift box with the existing test links; Ministry pages Heaven on Earth, Give, Kenya Ministry Training Manual.
- Page content replaced on dev from content/blocks: Heaven on Earth 1932, Give 6428, Kenya manual 4303, My Story 2665, Author Support 6235 (existing Jetpack forms and slideshows kept). Contact (20): Topic dropdown added to the form.
- Kenya manual page 4303 is Published on dev (it was already published, copied from live); Agnes's note said it stays a draft until Sylvia approves it as free: decide.
- Dev QA on 1.5.2 (desktop + Customizer Mobile 320px): all rebuilt pages, Contact and grids pass. Open: Devotionals sign-up form does not render on dev (no WordPress.com/Jetpack connection, by decision); My downloads (account page) has no eyebrow or Keep exploring by design.

### Phone QA and speaking page (24 Oct, afternoon EAT)
- Phone checklist run in the Customizer Mobile preview (320 x 480; exact iPhone/Android sizes not available in the browser tool): 12 pass, 3 partial, 3 not testable (safe area, rotate, zoom), 1 fail (Invite Sylvia to speak 404, page was a draft).
- Agnes chose to publish the speaking page on dev (ID 6511). Verified: page loads, About link opens it on desktop and from the phone drawer, "Send an invitation" opens /contact/.

### Header, Books mega menu and footer to prototype v4 (24 Oct, 11:15-14:00 EAT)
- Agnes: home header/menu and footer did not look good; Books menu must match the prototype exactly.
- Theme 1.3.0 (2124c93) and 1.3.1 (3f51f47). Checked first in a local headless Chromium harness against the prototype at 1440/1280/1024/390/320.
- cPanel showed "License is not for this host", so Git update and File Manager were unavailable. Deployed instead with WordPress Appearance > Themes > Upload > "Replace active with uploaded" (zip of sylviasdream-child from main).
- Dev menu: Books columns reordered (Browse by topic, Other languages, Readers). Footer menus set to the prototype: Books (All books, One Life, From Fear to Freedom, SENT, My downloads), Ministry (About Sylvia, Heaven on Earth Kenya, Become a Dream Partner, Podcasts & Videos, Author Support), Help (Contact Sylvia, Privacy Policy, Terms of Sale, Refunds & Downloads). Archives and Categories widgets removed from Footer: Newsletter sign-up.
- Dev QA on 1.3.1, logged in and in the Customizer: all pass (header on one line, Books 3 columns with arrow and on screen, tiles, mixed keyboard/mouse, Escape then Tab, footer, mobile bar and drawer).

### Speaking page and phone QA checklist (24 Oct, morning EAT)
- "Invite Sylvia to speak" page built from the v4 prototype speaking route (710750c, content/blocks/speaking.html) and created on dev as a Draft, ID 6511; invitation button goes to /contact/. Added to Primary > About after Sylvia's story with the description "Churches and events, online or in person" (Custom Link, as drafts cannot be picked).
- docs/PHONE-QA-v4-NAV.md: iPhone and Android checklist at 320-430px.
- Book publishing, Featured books, Stripe test mode and test purchase wait for Sylvia's confirmed prices and ebook files (the two emails to her are still unsent, pending Moses).

### Prototype v4 navigation (about 16:40-17:30 EAT)
- Agnes supplied the updated prototype (v4) and review document; navigation rebuilt to match: theme 1.2.0 (ef803f6) mega menu, submenu descriptions, phone bottom bar; 1.2.1 (37228c2) and 1.2.2 (037ceed) keyboard/visibility fixes. All deployed to dev.
- Dev Primary menu: Home · Books (sd-mega: Browse by topic = 10 WooCommerce categories + All books; Readers = My downloads, Download help; Other languages = French -> From Fear to Freedom page, Bahasa Indonesia -> SENT page) · About (Sylvia's story, Podcasts & Videos, Author Support) · Ministry (Heaven on Earth Kenya, Become a Dream Partner) · Devotionals · Contact. Spanish and "Invite Sylvia to speak" omitted (no page); Kenya Ministry Training Manual omitted (not approved).
- Desktop front end: Books panel opens on hover and toggle with all 15 links; Tab order correct; Escape closes and hides at once; About/Ministry dropdowns with descriptions pass.
- Customizer Mobile preset on Home, Books, Contact, Give, My Story: bottom bar labels and active state pass, More opens/closes the drawer, accordions pass, Contact form button and footer reachable. Not yet verified: exact 320/390px geometry, sideways scroll and full footer clearance (browser tool could not resize or screenshot reliably). Needs a real-phone check.

### Buyer transition plan and email to Sylvia (about 16:20 EAT)
- `docs/BUYER-TRANSITION-PLAN.md` written: inventory and WooCommerce protected downloads before launch, zero-price legacy orders for past buyers on launch day, old pages kept 30 days, then redirect and retire the public files.
- Email to Sylvia drafted (reader reviews with permission, free book-writing guide decision, OK to replace old book Stripe links on live). Not sent.

### Testimonials removed; legacy Stripe/.docx links replaced on dev (about 15:30-16:00 EAT)
- Dev Home: placeholder testimonials section removed (it only had "Reader review goes here" / "Reader name, country" x3). Repo: testimonials removed from `content/blocks/home.html`; the pattern stays for later, used only with real, permitted reviews.
- Dev: 31 book-purchase buy.stripe.com links on 18 pages (incl. 3 drafts) now go to /shop-for-books/; two public .docx links removed (draft 6304 "Download My Story"; /to-ensure-you-end-up-going-to-heaven/ "Download free ebook"). Giving links, thank-you/download pages, media files and Stripe untouched. Sample click-tests passed; no posts had matches. Full map: `docs/LEGACY-LINK-MAP.md`. Live still has all of these links.

### Books target set to /shop-for-books/ (about 15:15-15:25 EAT)
- Agnes chose /shop-for-books/ as the single Books target. Theme commit da977d5: Customizer "Books page link" (default /shop-for-books/) drives sdc_shop_url(), so every theme/pattern book button, the 404 "Browse the books" and WooCommerce "Return to shop"/"Continue shopping" go there. WooCommerce Shop page stays "Shop" (/shop/).
- Deployed to dev (cPanel Git update to da977d5; inc/customizer.php, inc/template-tags.php, inc/woocommerce.php copied). Saved dev block links updated: Home "Explore the Books" and "See all books", Books CTA strip "Explore the Books".
- Click-tested all three buttons and the 404 "Browse the books": all open /shop-for-books/. Home, Books and a 404 render at desktop and 320px (Customizer preview); 320px header/notice fix still intact.

### Dev link/header fixes and 320px CSS fix (about 15:00-15:10 EAT)
- Dev Home (6365): block buttons "Meet Sylvia" and "Read Sylvia's story" now go to /my-story/ (click-tested).
- Dev Books (3001) header: "The bookshop" / "Books by Sylvia Suhr" / "eBooks delivered instantly by secure download. Paperbacks and Kindle editions through Amazon." Dev Contact (20) header: "Contact" / "Let's talk" / "Questions about a book, a download, speaking or partnering, Sylvia would love to hear from you." Both render at desktop and 320px (Customizer preview); Contact form present; no message sent.
- CSS fix committed (f0ab2bc): store notice in page flow above the header; up to 400px brand mark hidden, smaller wordmark, tighter gaps (48px tap targets kept). Deployed to dev (cPanel Git update to f0ab2bc, style.css copied). At 320px the full "Sylvia's Dream" wordmark fits beside Search, Cart and Menu; the notice no longer covers the header; pointer taps on Open menu, the Books submenu toggle and All Books now work. Desktop header unaffected.
- Shop URLs on dev: /shop/ is the WooCommerce product archive (WooCommerce > Settings > Products > Shop page = "Shop"), currently "No products were found" (all products Draft). /shop-for-books/ is the rebuilt block-based Books page (menu target). Home book buttons and the Books CTA strip still link to /shop/; target decision pending.

### Author Support fixed and Home/Books/Contact rebuilt on dev (about 14:40-14:55 EAT)
- Author Support (dev page 6235): the live enquiry form is a **Jetpack** form (reusable form 6255), not CoBlocks or Crowdsignal. Restored inline on dev: Name (required), Email (required), Message, "Contact us"; live thank-you wording kept; recipient sylviasuhr@gmail.com. Intro split back into the two live paragraphs; top button now `#book-enquiry` on the same page (duplicate anchor id removed, click-tested). Template: Full width (page builder). Wording matches live. No test message sent.
- Home (page 6365), Books (/shop-for-books/, page 3001) and Contact (page 20) rebuilt from `content/blocks/*.html`: pasted into the Code editor, expanded in the visual editor, saved; template Full width (page builder). Previous content kept as revisions (Home's body was empty before; it used the theme fallback).
- Render checks, desktop browser and 320px Customizer mobile preview: all three render without errors. Home shows hero, trust strip, featured books, About, ministry, Dream Partners, testimonials, newsletter CTA. Books shows page header, products area (empty: products are Draft) and CTA strip. Contact shows info, FAQ and the Jetpack form (Name, Email, Message required; "Send message"); fields fit at 320px.
- Repo: hero and About Sylvia patterns and the Customizer defaults now link to /my-story/ instead of /about/ (404).

### Dev deploy, dev fixes and live read-only check (about 13:45-14:40 EAT)
- CI: Moses added `.github/workflows/checks.yml` (c4f2692). PHP lint 8.0-8.3 and `check-theme.js` all pass (first ever PHP lint of the repo).
- 1.1.0 deployed to dev (cPanel Git update to c4f2692 + File Manager copy). Home, Shop For Books, Contact and 404 render without errors.
- Dev changes: Jetpack 16.3.1 installed, Forms active; Contact (page 20) now renders its existing form (recipient, subject and fields unchanged). Subscribe block still empty: Jetpack Newsletter needs a WordPress.com connection. Decision: do NOT connect dev to Sylvia's WordPress.com account.
- Dev: Hero "Meet Sylvia" and About band "Read Sylvia's story" now link to /my-story/ (was /about/, 404).
- Dev: approved menu applied: Home, Books (All Books, Devotionals), About Sylvia (/my-story/), Ministry, Podcasts & Videos, Contact. Give removed from the menu; gold header Give button kept. Desktop dropdown and mobile drawer (keyboard) work; pointer tap on the mobile toggle timed out in the Customizer preview (store notice overlaps header), needs a real-phone check.
- Live sylviasdream.com read-only check (nothing changed): WordPress.com Personal (bought by another WordPress.com account); theme Twenty Twenty; active plugins Akismet, CoBlocks, Crowdsignal Dashboard, Crowdsignal Forms, Gravatar Enhanced, Gutenberg, Jetpack (managed), Layout Grid, MailPoet, Page Optimize; WooCommerce not installed. Jetpack Newsletter: 21 total subscribers (51 records incl. not confirmed/bounced), 0 paid; "email new posts to subscribers by default" is ON; email byline shows "mosemqute". MailPoet holds 2 unconfirmed records (separate list).
- Live Contact still shows a street address and town/postcode (against the Australia-only rule). Not changed.
- Author Support on dev: wording matches Sylvia's live text, but the enquiry form (Name, Email, Message, "Contact us") does not render on dev, the two intro lines are merged into one paragraph, and the top "Let's Talk About Your Book" button points to /sylviasdream/#book-enquiry (home page) instead of the same-page #book-enquiry anchor.

### Child theme 1.1.0 built (about 13:15-13:40 EAT)
- `scripts/check-theme.js` added (commit 1b84a23). The GitHub Actions workflow (PHP lint 8.0-8.3) could not be committed: the GitHub connection lacks the `workflow` permission.
- 1.1.0 committed in four parts: A images + theme.json + block support (9d986dd); B prototype footer + payment badges (ee8a338); C 14 patterns + `content/blocks` pages (34c45dc); D templates, editing guide and docs.
- Not yet deployed to dev; `php -l` still not run. Next: deploy, menu, Jetpack, paste pages, QA.


### Where work stopped (about 13:00 EAT)
- Moses approved the **1.1.0 build** (fully editable pages, prototype images, prototype footer, 6-item menu, Elementor-ready). Brief: `docs/NEXT-BUILD-1.1.0.md`.
- Two build attempts failed immediately: the Town account ran out of credits. **Nothing from 1.1.0 exists yet.** Start there.
- The five prototype photos were extracted from `prototype/index.html` and saved to the Town library ("Sylvias Dream/Theme builds/prototype-images/": hero.jpg 1376x768, bible.jpg, mission.jpg, give.jpg, desk.jpg each 1000x746).

### Theme 1.0.0 built and deployed to dev
- Commit `fd1aaa1`: full design build from prototype v3 (sticky shrinking header, mobile drawer with focus trap, front page sections, page/single/archive/search/404 templates, WooCommerce styling, multi-column footer, 11 Customizer sections).
- Deployed via cPanel Git pull + File Manager copy. No PHP lint possible (no shell). Home, Shop For Books, Contact and 404 render without errors.
- Visual QA findings: menu has 8 flat items (crowded, no dropdowns); Books page is still the legacy catalogue; Contact prints a raw `[jetpack_subscription_form]` shortcode and has no form; home book grid empty because products are drafts; newsletter has no field; mobile only checked at 320px in WP preview.
- Moses approved the new menu: Home · Books (All Books, Devotionals) · About Sylvia · Ministry · Podcasts & Videos · Contact, with Give as the header button. **Not yet applied.**
- Moses asked for every page to be editable by Sylvia like a page builder. Chosen approach: WordPress block editor with brand theme.json and "Sylvia's Dream" block patterns (no extra plugin, works on Personal), plus Elementor-compatible page templates in case she ever installs Elementor.

### QA fixes on dev
- Shop For Books: extra H1 ("When Fear Has the Final Word") changed to H2.
- Alt text added to 8 images on Shop For Books and 2 on One Life.
- Contact: street address and town removed; shows "Australia" only.
- QA results still open: no meta descriptions on any page; no book JSON-LD on legacy book pages; sitemap 404 on dev; checkout policy links not verifiable (all products draft).

### Products (all 25 remain Draft)
- Descriptions: 17 from Sylvia's own site pages (21-22 Oct), 7 from Amazon text Moses supplied in a Word file, Guard Your Heart Renew Your Mind from text Moses pasted. All 25 now have descriptions.
- Titles/subtitles checked against Amazon (Moses's PDF of her Amazon.com.au "All Books" page; Amazon.com.au itself returns 500 errors, Amazon.com works). Changes: How to Find the Real You subtitle -> "Finding Peace, Purpose and Your True Identity in Christ"; The King's Way -> "Walking the Narrow Path to Peace, Purpose, and Freedom in Christ"; When the Word Becomes Life -> "How God's Word Becomes Living Reality"; Fast Forward to Success -> "Break Through Your Limitations to Change Your Finances, Family, Fame, Freedom & Future".
- Not on Amazon (Sylvia to confirm titles): When Fear Has the Final Word, Manifesting Miracles Now.
- Covers set: How to Find the Real You (blue "True Identity in Christ" version), The Hidden Key (dark bordered version with key, as on Amazon), plus 6 Moses supplied (The Report of the Lord, From Chance to Choice, SENT, 7 Days from Fear to Freedom, Guard Your Heart Renew Your Mind, When the Word Becomes Life).
- Still no cover: From Fear to Freedom, When Fear Has the Final Word, Manifesting Miracles Now, Fast Forward to Success (pending from Sylvia).

## Thursday 22 October 2026
- Duplicate-page merge and redirects (core plugin 0.1.1, commit `c22f1af`): see `DECISIONS.md` and `docs/REDIRECT-MAP.md`. All 9 URL tests passed signed in. Signed out, the dev password gate shows first (some gate pages return HTTP 404 but render the form), so redirects need re-checking on live.
- Give page created at `/give/` (Heaven on Earth wording, progress "0 of 100", $5/$15/$30/$50 monthly + other amount + give once, all **test placeholder links**, GoFundMe link, FAQ). Last menu item for now.
- Privacy Policy, Terms of Sale, Refund Policy published on dev, each marked "Draft for Sylvia's review. Not yet approved."; set as WP privacy page and WooCommerce terms page; "Footer Policies" menu in "Footer: Policies".
- WooCommerce store notice on: "Private preview of the new Sylvia's Dream website. Payments are in test mode and no real money is taken." Hero "Explore the Books" links to `/sylviasdream/shop-for-books/`.

## Wednesday 21 October 2026
- Dev site set up on topnotchmall.co.ke; live content imported; core plugin installed; 25 draft products created from book list v2.
