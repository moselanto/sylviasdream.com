# Progress log

Newest first. Dev site = `https://topnotchmall.co.ke/sylviasdream/` (private build copy). The live site sylviasdream.com has not been changed.

## Friday 23 October 2026

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
