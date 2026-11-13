# Progress log

Newest first. Dev site = `https://topnotchmall.co.ke/sylviasdream/` (private build copy). The live site sylviasdream.com has not been changed.

## Saturday 14 November 2026 (00:00-01:00): child 1.23.31 on LIVE
- Nuracrown approved 1.23.31 for live. Installed via Replace current (was 1.23.30); Theme Details shows 1.23.31; parent Base 1.0.1 kept. WordPress.com object + edge caches cleared. Signed out of live and WordPress.com afterwards.
- Signed-out checks of Home, Shop for Books, One Life, Contact: no errors, fonts look the same. `elementor-gf-` links: Home 2 -> 0, Contact 2 -> 0, Shop 0, One Life 0. PASSED.
- `dashicons-css` is STILL printed for visitors on all four pages (confirmed again by a plain signed-out fetch). Likely cause: WordPress.com's `wp-calypso-bridge-masterbar` stylesheet (also printed for visitors) lists dashicons as a dependency, so the dequeue is overridden. Fix needs a follow-up release (remove the dependency for signed-out visitors), test first.
- Screenshots (signed out): Home before qx8b1hddg3nd9cs2dr8d9zty9d8j5cbk / after qx85e11vy2kh3ecak2qkfn5gn58j5bqd; Shop before qx807hwgpxtq1yvvybk486eqj18j52qe / after qx84w8beb2ynm7gh1r8rr2q9z58j551w; One Life before qx87zmrrfcnjeyz6b9pmpxt3jn8j4ywy / after qx873vthcpbzhqyh57661kf3nd8j51wq; Contact before qx85f1dkrydfzm3z4sh50nqwch8j4kqe / after qx86w7ttt75g6ma3ey3ms51chn8j4ctj.
- PageSpeed Insights: API still at its daily limit at 00:55 EAT; run scheduled for after the reset (Saturday 14 Nov about 11:30 EAT) for Home, Shop for Books, One Life, Journal, mobile and desktop.

## Friday 13 November 2026 (20:00-23:40): speed pass, child 1.23.31 on TEST
- Nuracrown asked to improve speed in code only, target 1-3 s load. PageSpeed Insights API returned quota exceeded, so a manual inventory was taken of live Home, Shop, a book page and Journal.
- Baseline (live, from the sandbox, mobile UA): HTML is already edge-cached by WordPress.com (x-ac HIT, brotli); time to first byte about 1.8 s from the sandbox's location (network distance, not code). Home 18 stylesheets + 21 scripts, book page 15 + 45 scripts.
- Causes found: (1) Elementor printed two extra Google Fonts stylesheets with every weight of both families (duplicate of the theme's fonts); (2) dashicons.css (57 KB) loaded for all visitors; (3) book pages load Stripe express checkout: stripe.js ~1.1 MB, upe-classic 131 KB, React/ReactDOM 213 KB, lodash, wp-data, checkout scripts (Stripe plugin setting, not changed); (4) Gutenberg block-library CSS 140 KB and Jetpack/WooCommerce analytics (platform, left).
- Already in place from earlier releases: emoji off, LCP preload, YouTube facade, deferred theme scripts, WooCommerce styles trimmed on non-shop pages, logo with a 480px phone source.
- Child **1.23.31**: Elementor Google Fonts off when theme fonts are on; theme font URL adds Cormorant 400/400i and Source Sans 400i; dashicons skipped for signed-out visitors. Zip in library `Sylvias Dream/releases/sylviasdream-child-1.23.31.zip` (file sh72ey4s59txastd8v8qx0xxb98j470c).
- Installed on TEST only (Theme Details 1.23.31). Checked signed in: Home, Shop for Books, One Life, Contact render, no errors, fonts look the same; `elementor-gf-` stylesheets 2 -> 0 on Home, Shop and Contact. Signed-out dashicons check blocked by the test password gate.
- Screenshots: Home before qx8es3egxx4njbqse86sgj99618j4f7g / after qx84zgek2njyf28j5ya2adt2558j5tzs; Shop before qx85dt8cjmdkkf6xkdjncd23dh8j5249 / after qx86eppd3jt67smfnwdm37k0g98j4gv5; One Life after qx8bpm80jr3efpbf4zcafdjzh18j576h; Contact before qx86yac3vrhvngezm8bxnnsfwx8j4y5x / after qx89fg5807xerq8n144awnzt5d8j45at.
- Waiting: approval for live; Moses's decision on Stripe express checkout on book pages.

## Friday 13 November 2026 (evening): handover guide and Sylvia check message
- Handover guide and training plan built as a Word document in the close-out brief v2 style (Parts A-G: what the site does, signing in, step-by-step editing, orders/refunds, do-not-change, quick reference, 60-minute training plan). Library: `Sylvias Dream/Website handover guide and training plan for Sylvia.docx` (file sh73a9h1e9k3wvqqjmryaqckj58j5c06). Updated vs the Town doc draft: Fast Forward Lulu mention removed, Linktree and Give any amount added. Draft for Moses's approval; not sent.
- WhatsApp message asking Sylvia to look through the live site (Home, Books, book pages, My Story, Give, Heaven on Earth, Contact) and reply "All OK" or send changes; Nuracrown confirms it has been sent.
- Repo handover refreshed for the next agent: HANDOFF section 0 rewritten (13 Nov 23:20), DECISIONS 13 Nov entry, OPEN-ITEMS current list.

## Friday 13 November 2026 (19:10-19:55): audit fixes on LIVE
- Nuracrown approved the audit fixes for live (keep the All Shall Be Well thank-you page).
- Live changes:
  - Shop for Books page 3001: title "Shop For Books" -> "Shop for Books"; content heading "Books by Sylvia Suhr" H1 -> H2 (same styling); search description added. Signed out: one H1.
  - Journal page 6961 and post 6330 (Dream Again) search descriptions added, same sentences as test (post: Update only, "Post updated", not re-published).
  - Product 6451 When the Word Becomes Life: Christian Living on, Uncategorized off; price A$7.16 and Stripe/Kindle links unchanged.
  - Product 6465 Fast Forward to Success -> Draft (shop shows 22 products, was 23).
  - Drafted (not deleted) with 301 redirects: 4141 /from-chance-to-choice-2/ -> From Chance to Choice product (1); 5860 /free-ebook/ -> /free-7-day-devotional/ (2); 5022 /sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ/ -> / (3); 636 /fast-forward-to-success/ -> /shop-for-books/ (4). All checked signed out.
  - NEW PLUGIN: live had no redirect tool, so Rank Math SEO (free, 1.0.282) was installed and activated with only the Redirections module enabled; no Rank Math account connected, setup wizard skipped.
  - Kept: /all-shall-be-well-2/ (thank-you page) and /sent-3/ page 4717 (on live it holds the English/Bahasa ebook and mission-guide download links), no redirect.
  - Kenya: Primary menu item 6863 already points to /kenya-ministry-training-manual-free-download/; no menu change. Correction to the audit: the missing PDF (/wp-content/uploads/2026/03/Kenya_Training_Manual.pdf, 404) is linked from the download button ON that page, not the menu. Still needs the file from Sylvia.
  - No menus, Stripe, Buy ebook, Dream Partner, GoFundMe or thank-you links changed. Caches cleared (object + edge); signed-out checks passed.
- Screenshots (partial; final public captures timed out): Shop before qx8ak85cszr50y0szhbc6cc3b18j4e3k, H1 before qx854p114gky6d2rhh7rjfw2fs8j5amx, H2 after qx85886v53kwk2kg481badxke98j5p00, Shop description qx81btq9bjbqtya0931xetagh58j5dz6, WWBL before qx86ka04yhs48wpjtnwd1bn81s8j41n0, Journal description qx883pqbx6wa54ss9p6jtw8zjh8j40gt, Dream Again description qx822aj4dvg0kenzrya60hmfbs8j5kvc.
- Signed-out after screenshots retaken 13 Nov ~20:15 (read-only, no changes): /from-chance-to-choice-2/ -> product qx89habn3813fjhw7b9tnt7am58j5ext; /free-ebook/ -> /free-7-day-devotional/ qx8cxe79zxhek2q8m8bmascbp18j4jw6; /sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ/ -> / qx8c37cr94dta1q7zq1qhyz7g18j424g; /fast-forward-to-success/ -> /shop-for-books/ qx8556ctc8bmzeb79yxxxcmkbs8j4yqt; When the Word Becomes Life showing Christian Living qx86748ndbrbdvs7xedx2w0xzs8j54ws; Journal qx872x29s9dkw4mhanrqjq573n8j45dd. Shop catalogue: both pages inspected, 12 + 10 = 22 products, no Fast Forward (full-catalogue screenshot capture timed out; the landing screenshot above shows the Shop page).
- Nuracrown: Sylvia has already been asked to check the website and send any changes.

## Friday 13 November 2026 (18:00-19:10): audit fixes on TEST
- Nuracrown asked for a full cross-check. Crawl of all 88 live sitemap URLs: all 200, no critical errors. Issues: Kenya manual PDF 404 (menu link), Shop for Books two H1s, old duplicate pages public, missing meta descriptions (Shop, Journal, Dream Again post), When the Word Becomes Life in Uncategorized, Fast Forward still live. Empty alt on mega-menu/cart covers is intentional (title text beside the image). Amazon/Instagram/Patreon errors were bot blocking.
- Nuracrown approved all six fixes. TEST changes (no live changes):
  - Kenya menu item 6598 already points to /kenya-ministry-training-manual-free-download/ on test (live still points to the missing PDF).
  - Shop page 3001 title "Shop For Books" -> "Shop for Books". Test already has only one H1 ("Books by Sylvia Suhr"); live has two.
  - Drafted (not deleted) with Rank Math 301 redirects (Redirections module switched on): 4141 /from-chance-to-choice-2/ -> From Chance to Choice product (1); 4717 /sent-3/ -> /sent/ (2); 5860 /free-ebook/ -> /free-7-day-devotional/ (3); 5022 /sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ/ -> home (4); 636 /fast-forward-to-success/ -> /shop-for-books/ (5). All redirects tested.
  - NOT changed: /all-shall-be-well-2/ is page 3431 "All Shall Be Well Thank you" (buyer download page) - must stay. Audit listing was wrong.
  - Unassigned Top Menu 22: items 6328->6940, 6325->6941, 6343->6942 repointed to the redirect targets; 6335 reparented; Author Support 6348 was removed by mistake and recreated as 6939 (same label/link). Menu 22 stays unassigned; Primary 21 and footers 26/27/52 unchanged.
  - Meta descriptions (Sylvia's own sentences): Shop 3001 "Faith-filled books for readers seeking a deeper relationship with God, renewed identity, freedom from fear and courage for the journey ahead."; Journal 6368 "Weekly devotional writing from Sylvia on trust, peace and walking with God. Receive Sylvia's devotional letter, new book announcements and news from Kenya."; post 6744 Dream Again "I am able to do immeasurably more than you could ever imagine or think according to the power working within you. Dream again. Awake! There is so much more!" (Update only, not re-published).
  - Product 6380 When the Word Becomes Life: Christian Living on, Uncategorized off. Price and Stripe/Kindle links unchanged.
  - Fast Forward product 6397 was already Draft.
- Waiting: Nuracrown's approval to repeat on live (live IDs differ; live also needs the Kenya link and Shop H1 fix).

## Friday 13 November 2026 (about 17:45): child 1.23.30 (Home Meet Sylvia photo) on LIVE
- Nuracrown approved 1.23.30 for live. Installed sylviasdream-child-1.23.30.zip on live via Appearance > Themes > Upload > Replace current (was 1.23.29); Theme Details confirms 1.23.30 active. WordPress.com object and edge caches cleared.
- Signed-out desktop check of Home: no errors; Meet Sylvia portrait shows the 4:5 frame, rounded corners, soft shadow and inner white line; note still over the photo; section wording, both buttons and their links unchanged. No page content, settings or links edited. Signed out of live and WordPress.com afterwards.
- Screenshots: before `qx8fnj0t9a0c0ccx9d0p3bfxdn8j5enr`, desktop after `qx84fctxgrgnns50j0zvst65q98j4m5g`.
- Phone-width check: the browser tool cannot resize to phone width, so Nuracrown checked Home on a real phone (13 Nov 17:50): the Meet Sylvia photo looks right. Done.

## Friday 13 November 2026 (15:55-16:05): child 1.23.30 (Home Meet Sylvia photo) on test
- Nuracrown asked for the Home "Meet Sylvia" photo to be styled like the My Story portrait. Child **1.23.30** (style.css only): 4:5 frame, max 440px (340px on phones), 24px corners, soft shadow, fine white inner line; the "Eighty-two and still becoming" note stays over the photo.
- Installed on TEST only; wording, photo and both buttons (Read Sylvia's story, One Life: the memoir) unchanged. Live waits for approval.

## Friday 13 November 2026 (15:45-15:55): child 1.23.29 (Linktree) live
- Approved by Nuracrown. Installed 1.23.29 on LIVE (from 1.23.28). Theme Options Linktree already https://linktr.ee/sylvia_suhr (nothing saved). Object and edge caches cleared.
- Signed out on Home, One Life and Contact: footer link https://linktr.ee/sylvia_suhr, new tab, label "Linktree (opens in a new tab)"; Facebook, Instagram, YouTube, Patreon unchanged; One Life A$12.99, Buy ebook ...g401 and Amazon unchanged.

## Friday 13 November 2026 (15:35-15:45): child 1.23.29 (Linktree) on test
- Nuracrown approved option 1 for Sylvia's Linktree. Child **1.23.29**: "Linktree address" field in Theme Options > Contact and social links (also Customizer footer settings and the fallback settings screen), default https://linktr.ee/sylvia_suhr; footer social icons show a Linktree icon, new tab, screen-reader label "Linktree (opens in a new tab)". Zip in `Sylvias Dream/releases`.
- Installed on TEST only; field saved with https://linktr.ee/sylvia_suhr; Facebook, Instagram, YouTube and Patreon links unchanged; Home, One Life and Contact load without errors. Live waits for approval.

## Friday 13 November 2026 (15:00-15:35): Fast Forward removed on test; Linktree request
- Sylvia (14:58): no file for Fast Forward, remove it. TEST product 6397 set to Draft (not deleted). Still referencing it on TEST: legacy page 636 /fast-forward-to-success/ (Lulu bit.ly links), unassigned "Top Menu" 22 item, Christian Living category (product still assigned), Rank Math link index on Home 6365 (not visible). Live (product 6465 and its legacy page) waits for approval. A2 ebook price question is no longer needed.
- Sylvia asked for Linktree https://linktr.ee/sylvia_suhr . Theme Options > Contact and social links has only Facebook, Instagram, YouTube, Patreon and Amazon fields, so the footer icon needs a small child-theme change (new Linktree field + icon). Contact page 20 has a Social Links block (Instagram, Patreon) but Elementor would not load. Nothing changed.

## Friday 13 November 2026 (11:30-13:50): Give any amount and The King's Way to LIVE
- Approved by Nuracrown. LIVE Give page 6406: "Give any amount" button added directly after the $5/month call to action, below the monthly cards (elementor-button classes, new tab, https://buy.stripe.com/6oU4gz6gf0vfc6og2T7g40o). All 42 existing link labels and destinations unchanged.
- LIVE The King's Way product 6455: Product description replaced with the rearranged test version (test 6384). Price A$8.41, Stripe and Kindle links unchanged; status Published.
- WordPress.com object and edge caches cleared; both pages checked signed out.
- All 18 rearranged book descriptions are now live.

## Friday 13 November 2026 (10:15-11:05): Sylvia's answers and 17 descriptions to LIVE
- TEST Give page 6428: "Give any amount" button added after the monthly buttons (new tab, https://buy.stripe.com/6oU4gz6gf0vfc6og2T7g40o). 11 existing links unchanged. Not on live yet.
- LIVE (approved by Nuracrown): Fast Forward 6465 "Buy paperback on Lulu" (short description); One Life 6447 "What readers are saying" cards (Lynne Duncan, John Parran); Invite Sylvia 6408 image 6757 alt "Sylvia Suhr speaking to a Kenyan congregation".
- LIVE Manifesting Miracles Now: product 6463 set to Draft; old page 4118 was already in Trash, restored as Draft (not deleted). Both URLs 404 signed out; shop shows 23 products; Coming soon category empty.
- LIVE descriptions copied from test (Product description only) for 17 books: 6462, 6466, 6458, 6461, 6457, 6448, 6393, 6456, 6467, 6450, 6453, 6449, 6464, 6460, 6392, 6451, 6454. Live originals backed up in the Town session report. Prices, Stripe and Amazon links untouched.
- The King's Way (live) skipped: live text has "http://www.sylviasdream.com" where test has "www.sylviasdream.com".
- WordPress.com object and edge caches cleared. Checked signed out.

## Friday 13 November 2026 (06:15-10:10): Sylvia's answers to the close-out brief
- A1 test purchase (From Fear to Freedom): Sylvia says success. Refund not yet confirmed.
- A2 Fast Forward: Lulu paperback https://www.lulu.com/shop/sylvia-suhr/fast-forward-be-a-world-changer-sylvia-suhr/paperback/product-wnr5wk.html . TEST product 6397 now has "Buy paperback on Lulu" in the short description. Ebook stays Coming soon (no price given).
- A3 reviews: Lynne Duncan (Goodreads) and John Parran (Amazon paperback 2019) added to TEST One Life product 6376 as "What readers are saying" cards, with two typos fixed. Kerryn (Kindle, AU, 20 Dec 2025): which book is still unconfirmed.
- A4 Kenya: Sylvia asked what was meant. Home page sentence "Sylvia will be returning to Kenya in a few weeks' time ..." Asked her for travel date and wording.
- A5: the chosen picture is already on Invite Sylvia (test 6511, media 6847). Alt text set. Duplicate upload 6926 not used.
- A6 give-any-amount link https://buy.stripe.com/6oU4gz6gf0vfc6og2T7g40o : NOT added yet. Test Give page 6428 Elementor editor stuck on Loading; nothing changed.
- A7 past-buyers email: Sylvia wrote her own; she sends it from her email.
- A8 Manifesting Miracles Now cancelled. Test 6398 is a draft. Still shown on test legacy page 4118 and in the shop / Coming soon category (signed-in view only). Live 6463 to be set to draft after approval.
- A9 Stripe rename: Sylvia says done.

## Friday 13 November 2026 (02:00-02:20): remaining 10 book descriptions on test
- TEST only, existing wording only. Rearranged 9: Learning to Recognise God's Voice (6379), SENT (6382), The Hidden Key to Healing and Breakthrough (6378), The King's Way (6384), The Report of the Lord (6396), Trusting God Deeper (6389), When the River Flows Again (6392), When the Word Becomes Life (6380), Wisdom for Soul-Winning (6383). Repeated top titles removed where they only repeated the product title; list intros as headings; quotes with a reference as blockquotes.
- Manifesting Miracles Now (6398) is a **draft** product on test (not published), which is why it has no book page; description left unchanged (already prose; would need new wording). Live 6463 is published as Coming soon.
- All 22 done on test (18 rearranged, 4 left as is). Original descriptions backed up in the Town session report. Waiting for Nuracrown's approval before copying to live.

## Friday 13 November 2026 (01:00-02:00): shop page fixed (1.23.27, 1.23.28), descriptions 12 of 22 on test
- Shop page cause: page 3001's saved content (old copy of header photo, title, search, topic chips, plus the "Explore Sylvia's Books" showcase with QR) is printed by WooCommerce as the shop description above the books, so header/search/topics showed twice with a large photo.
- **1.23.27** (hide the duplicated header/search/topics; showcase as a card) approved by Nuracrown and installed on live; signed out: one header, one search, one topic list; book grid, prices, links unchanged.
- **1.23.28** (book grid directly under search/topics like the test site; showcase card moved below the books and page numbers) installed on test then live at Nuracrown's request ("need to look better like this"); caches cleared; signed out: wording, links, prices and covers unchanged; sort control tidy. No page content edited.
- Book descriptions on TEST (existing wording only): 9 rearranged (7 Days from Fear to Freedom, 7 Days to Trusting God, All Shall Be Well, Angelique Finds Her Light, From Chance to Choice, From Fear to Freedom, Guard Your Heart Renew Your Mind, How to Find the Real You, How to Write and Publish), 3 left as is (Angelique: A Journey of Becoming, Fast Forward to Success, Fields of Becoming). Original descriptions backed up in the Town session report. 10 still to do.
- Close-out brief for Sylvia (Word, fill-in boxes) and a WhatsApp cover message prepared; to be sent only with Moses's OK.

## Friday 13 November 2026 (00:35-00:55): child 1.23.26 live, two descriptions live, SENT paid button removed
- Nuracrown checked the phone bottom menu on test and approved **1.23.26** (full-width book descriptions on every book page, phone menu polish). Installed on live (from 1.23.25); caches cleared. One Life, Guard Your Heart and From Fear to Freedom checked signed out: wording, prices (A$12.99, A$8.42, A$4.50), Stripe and Amazon/Kindle links unchanged.
- One Life (6447) and Guard Your Heart (6452) descriptions on live replaced with the rearranged test versions (existing wording only; One Life reader quotes removed; Guard Your Heart repeated title/subtitle/byline removed; Also by links to the live product pages). Original descriptions backed up in the Town session report. Prices, Stripe and Amazon/Kindle links unchanged.
- Cross-check of all 24 live book pages (fresh fetch): every page is a book page with the "About this book" section and loads the same 1.23.26 theme files, so the full-width layout applies to all; a screenshot of Guard Your Heart showing the old narrow layout and old text predates the update.
- **SENT is free** (Sylvia's written answer 5.2; Nuracrown confirmed 13 Nov, replacing Moses's option A). Live /sent/ (4686): backup draft **7008**; only the "buy ebook stripe" button (...g40c) removed; Amazon and both free download buttons kept and working; nothing changed in Stripe (link stays in Sylvia's account). Verified signed out; caches cleared.
- Still to do: rearrange the other book descriptions on test (approved), then screenshots.

## Friday 13 November 2026 (00:15-00:25): child 1.23.25 (book page polish) approved and live
- **1.23.25 installed on live** (from 1.23.24); caches cleared. Signed out, before vs after: One Life (A$12.99) and Guard Your Heart (A$8.42) wording and prices unchanged; Stripe ...g401 and ...g40d, Amazon AU paperback and Kindle links unchanged; related-book suggestions vary per load. No content, price or Stripe change.
- Next on test: 1.23.26 (full-width descriptions on every book page, phone bottom bar polish) and rearranged One Life / Guard Your Heart descriptions (existing wording only; One Life reader quotes removed per Nuracrown).

## Friday 13 November 2026 (00:00-00:10): child 1.23.24 (express pay row) approved and live
- Live markup read first (read only): ul.wc-block-components-express-payment__event-buttons > three li#express-payment-method-express_checkout_element_{applePay,googlePay,link} > div > div.StripeElement > div.__PrivateStripeElement > iframe. 1.23.24 replaces the 1.23.23 auto-fit grid with one flex row of three equal-width items (stacked under 480px). Installed on test (no express buttons there), approved by Nuracrown.
- **1.23.24 installed on live** (from 1.23.23); object and edge caches cleared. Signed out with one A$7.10 eBook, before vs after: wording and main-content links identical; total A$7.10; Stripe card fields present; Live mode; Apple Pay, Google Pay and Link present; Place order never clicked; no order; cart emptied. No WooCommerce or Stripe setting opened.
- Inside Stripe's own widget (not theme-controlled): USD quote moved US$5.01 -> US$5.02 with the exchange rate (0.7056 -> 0.7070); the optional "Secure, fast checkout with Link" prompt in the card section did not show after (the express Link button still shows). Watch on Nuracrown's check.
- Express row alignment to be confirmed by eye by Nuracrown (checkout screenshots are blocked).

## Thursday 12 November 2026 (23:45-23:55): child 1.23.23 (checkout polish) approved and live
- 1.23.23 installed on test first (test checkout shows only "Test payment", no express buttons; Town withholds checkout screenshots, so Nuracrown reviewed the test checkout himself and approved).
- **1.23.23 installed on live** (from 1.23.22); object and edge caches cleared. Signed out with one eBook (Guard Your Heart Renew Your Mind) in the cart, before vs after: wording and main-content links identical; total A$7.10; Stripe card fields present, "Live mode", Stripe amount US$5.01 with A$7.10 in its currency selector (unchanged, not touched); Apple Pay, Google Pay and Link present (3 express frames before and after). No settings opened, no payment details entered, no button clicked, no order placed; cart emptied.
- Not verified: whether the express pay buttons sit in one even row (no screenshots allowed on checkout); Nuracrown to check by eye.

## Thursday 12 November 2026 (23:40): child 1.23.22 (Give polish) approved and live
- Nuracrown checked the test Give page (four monthly cards in one even row) and approved. **1.23.22 installed on live** (from 1.23.21); caches cleared. Signed out on /give/: wording identical; main-content links unchanged; Dream Partner and A$5/15/30/50 Stripe links and Go Fund Me https://gofund.me/e6fffb40c unchanged; four cards in one even desktop row with aligned buttons. No payment pages opened.
- Checkout polish started as 1.23.23 on the test site only (waiting for approval).

## Thursday 12 November 2026 (23:00-23:20): design pass 1.23.20/1.23.21 approved and live
- Child **1.23.20** (styling pass for To ensure you end up going to Heaven, Heaven on Earth, Give) and **1.23.21** (prayer card width, book cover not cropped) built and installed on test first; desktop before/after screenshots sent to Nuracrown; approved 12 Nov. Mobile screenshots not possible with the browser tool.
- **1.23.21 installed on live** (from 1.23.19); object and edge caches cleared. Signed out on all three pages: wording identical (prayer page title shown in sentence case by CSS only), all main-content links unchanged, Dream Partner ...g405 and Give $15 ...g40l / $30 ...g40m / $50 ...g40n Stripe links unchanged, Go Fund Me https://gofund.me/e6fffb40c unchanged, Meet Jesus and free eBook buttons kept. No payment pages opened.

## Thursday 12 November 2026 (22:30-22:55): child 1.23.18 regression, fixed in 1.23.19
- Child **1.23.18** (Contact columns keep equal widths; WordPress.com loads no core Columns CSS for the kept section, so opening an FAQ squeezed the form and Send button) installed on test and live about 22:35. **Error:** the build step overwrote `assets/css/about.css` with only the new Contact rules, so all inner pages using it (Contact, Give, Heaven on Earth, My Story, To ensure you end up going to Heaven, book/resource pages) lost their page styling on test and live for about 20 minutes.
- Child **1.23.19** installed on test, then live (restores the full about.css from 1.23.17 plus the 1.23.18 Contact rules); caches cleared. Signed out on live: Contact matches test, round social icons back, opening each question no longer resizes the form or Send button; Give, Heaven on Earth and My Story styling matches test again; To ensure you end up going to Heaven is single-column like test with its live buttons.
- Design notes from that check (no changes made): To ensure you end up going to Heaven: long unbroken text, oversized all-caps title, book and portrait images stacked at the end. Heaven on Earth: photo grids/slideshows dominate, uneven section lengths, needs tighter spacing. Give: text-heavy donation cards, uneven whitespace, mixed button styles.

## Thursday 12 November 2026 (22:20-22:30): free PDF check, favicon, Kenya manual PDF attempt
- Read-only check signed out of every PDF linked on the 188 live sitemap pages, plus every PDF address in the live export (21 files). Only one broken link: **uploads/2026/03/Kenya_Training_Manual.pdf** (404), linked from /kenya-ministry-training-manual-free-download/. The test site has it at the same path (Media Library name Kenya_Training_Manual-1.pdf). All other PDFs return 200 (linked: free-sample-how-to-find-the-real-you, free-7-day-devotional, sent (2), thank-you-for-your-purchase, thank-you-for-your-purchase-2).
- Favicon: Nuracrown's 512x512 gold rounded square with white dove uploaded and set as the live Site Icon (attachment 6999); caches cleared; signed-out site icon tags point to it and the image loads.
- Kenya manual PDF not yet copied: download from the test Media Library failed, and live "Upload from URL" failed with "Memory exceeded". Page 4303 and its download button unchanged. Needs the PDF file supplied directly, then upload to live and repoint the button.

## Thursday 12 November 2026 (21:00-22:20): image descriptions from test, child 1.23.17 (Contact), Kenya manual page
- Image descriptions on live replaced with the test site wording (checked first in the test admin: page content and Media Library alt still matched the morning export). Home portrait "Sylvia Suhr, smiling, in a teal top and pearl necklace" (same image on My Story); Invite Sylvia "AI-generated illustration of Sylvia speaking to a Kenyan congregation"; My Story "Sylvia Suhr with schoolchildren in Kenya" and "Sylvia Suhr with children in a vegetable garden in Kenya". Alt text only; verified signed out.
- Child **1.23.17** installed on test, then live (1.23.16 -> 1.23.17). Contact: the saved Social Icons block renders inside an Elementor kept section where WordPress.com does not load the core Social Icons CSS, so icons showed as a bulleted list; the theme now styles them (round white frame, gold icon, no bullet). The form column no longer stretches, so opening a question (+) does not make the message box taller. Verified signed out on live /contact/ (all three questions opened; no form submitted). Note: the Contact section has Instagram and Patreon icons (Facebook is in the footer only).
- Kenya Ministry Training Manual (page 4303): original saved as unpublished draft **6994**; test layout applied (image and introduction, checklist section, scripture quote band, centred download card) with the existing live image. Title, slug and date (19 Dec 2025) kept; no posts edited. Wording same as test except the added heading "Kenya Ministry Training Manual".
- **Open:** the download link https://sylviasdream.com/wp-content/uploads/2026/03/Kenya_Training_Manual.pdf returns 404 on live (it was already broken on the old page; link kept as it was). The file exists on the test site at the same path.
- Caches cleared after each change.

## Thursday 12 November 2026 (18:45-19:35): book pages One Life, From Fear to Freedom, SENT replaced on live; image descriptions
- Moses's decisions (12 Nov): Kobo link on One Life dropped; reviews sections removed; SENT option A (paid Stripe button stays next to the free downloads); button labels kept as on the old live pages. Final files (reviews removed from the prepared versions) in the Town library `Sylvias Dream/launch-templates/book-pages-final-12-Nov` (not in Git: payment links).
- One Life (2766): backup draft **6969**; whole content replaced in the Code editor; live cover 2026/10/one-life-683x1024.png already existed. Signed out: no #KEEP-LIVE, no Kobo, no reviews. Stripe: both "Buy Instant Download (Stripe)" -> ...g401 (One Life, A$12.99); "buy chance to choice now (stripe)" -> ...g408 (From Chance to Choice, A$9.99).
- From Fear to Freedom (4231): backup draft **6975**; content replaced; both images existed. No invalid blocks, no #KEEP-LIVE; title, slug and date kept. Stripe: "Buy Instant Download (Stripe)" -> ...g407 (Stripe product still named "Freedom From Fear", A$4.50; renaming is Sylvia's); "direct Stripe download" -> ...g400 (How to Find the Real You, A$9.99).
- SENT (4686): backup draft **6980**; content replaced; both covers existed; title, slug and date kept. Stripe: "buy ebook stripe" -> ...g40c (SENT, A$5.99). Free download Bahasa and Free download for missionaries PDFs open (contents not viewable in the test browser).
- Every Stripe page opened only; no payment details entered, nothing paid. No other pages or posts edited.
- Image descriptions (alt text only) on live: Home portrait sylvia-suhr-portrait-1.jpg "Sylvia Suhr"; Invite Sylvia Speaking-to-a-Kenyan-congregation-1.png "A speaker addressing a congregation"; My Story sylvia-suhr-kenya-children-1.jpg "Children gathered together" and sylvia-suhr-kenya-vegetable-garden-1.jpg "Vegetable garden". Plain descriptions of what each photo shows (test wording not compared). No other content changed.
- WordPress.com object and edge caches cleared after each change; all checks signed out.

## Thursday 12 November 2026 (18:20-18:40): Journal, Shop page, Grace for the Journey on live
- Journal: no Journal page existed on live (not in Trash either), so **Journal page 6961** was created and published at /journal/ and set as the Posts page (Settings > Reading, was 4717). No post edited or republished. Signed out: /journal/ shows the archive (10 per page, original dates, pagination); /sent-3/ shows its own mission-training content and free downloads again.
- WooCommerce Shop page setting changed from draft 6436 to **3001 Shop For Books**; draft 6436 left as it was. Signed out: empty cart Return to shop goes to /shop-for-books/ and opens the catalog.
- Grace for the Journey (5593): original saved first as unpublished draft **6964** "BACKUP - Grace for the Journey - before layout migration - 2026-11-12". Test layout applied: "Reflections and encouragement" banner (live theme Bible image), six latest-article cards in three columns with dates and excerpts, newsletter call to action and existing sign-up section. Card artwork uses existing live images; bottom artwork stays live attachment 5603; three articles without featured images stay text-only. Page publish date kept.
- All 18 published posts compared before and after: dates, statuses, modified times and featured images unchanged. No newsletter sign-up submitted. No Stripe, Buy ebook, Dream Partner, GoFundMe or thank-you link changed.
- WordPress.com object and edge caches cleared after each change; all checks signed out.

## Thursday 12 November 2026 (17:00-18:20): child 1.23.16 live, live vs test audit finished
- Child **1.23.16** installed on test, then live (1.23.15 -> 1.23.16); WordPress.com object and edge caches cleared. Signed out on live Heaven on Earth: no embed.js text or link; GoFundMe widget loads with its fundraiser and Donate now; Go Fund Me button still https://gofund.me/e6fffb40c; Dream Partner Stripe link unchanged; monthly giving still /give/; contact form and sliders present. No page content, payment settings or donation links edited.
- Read-only audit, 66 live pages vs 62 test, 24 live products vs 23 test. Nothing on live changed. Findings (fixes need approval):
  - High: /journal/ is 404 on live; live Posts page is 4717 "Free Resource for Mission Training", so /sent-3/ shows the post archive instead of its own content. Fix: publish Journal and set it as Posts page (no post republished).
  - High: WooCommerce Shop page is draft 6436, so Cart "Return to shop" goes to ?page_id=6436 (redirected to a product). Fix: set Shop to 3001 (/shop-for-books/) or publish the dedicated Shop page.
  - Medium: Manifesting Miracles Now (product 6463) has no product image. Test legacy page uses 2022/06/c4a11af3-5284-42ad-aa0e-b792a08ff6ea.png; assign it if Sylvia confirms it is the cover. Keep Coming soon.
  - Medium: Grace for the Journey (5593) on live keeps the old static layout; test has cards and newsletter section. Needs the page migrated, not just images.
  - Low: stale or empty alt text (Home portrait says open Bible and journal; speaking image; My Story Kenya photos).
  - Earlier export image warnings (author-support, invite-sylvia-to-speak, my-story, Home) are resolved: live images load under *-1 filenames.
  - All 23 shared products have matching covers. Only synced blocks used on live: 6924, 6937, 6939, all published.
  - All Shall Be Well slugs differ between sites (sales 4216, thank-you 3431): never copy by slug or change Stripe thank-you destinations.

## Thursday 12 November 2026 (13:00-13:45): child 1.23.15 live, Heaven on Earth fixed, book pages prepared
- Child **1.23.15** (Option A) installed on test, then live (Replace current). Signed out: Guard Your Heart Renew Your Mind (6393) and When the Word Becomes Life (6451) show Add to cart beside the unchanged Buy ebook; All Shall Be Well (no file) shows Buy ebook only. Guest checkout reached with When the Word Becomes Life (A$7.16): Stripe card fields shown, live mode. Cart emptied, no order placed.
- SENT product on live already free (blank price, Free download sent-ebook-final.pdf); nothing changed. Old /sent/ page paid Stripe button unchanged.
- Heaven on Earth 1932 now references live synced patterns **6939** (six-photo slider, was 6636) and **6937** (updates form, was 6639); 6924 unchanged. Signed out: slider advances, form shows. Elementor data backup saved in the Town session before the change.
- Verified signed out: Home Meet Sylvia uses sylvia-suhr-portrait-1.jpg; From Chance to Choice cover shows in shop and on its product page.
- **Still open:** the `gofundme.com/static/js/embed.js` text link still renders on Heaven on Earth although the anchor was removed from saved content (WordPress.com turns the page's script tag into a link). Needs a render-side fix (theme filter stripping that script/link; the theme already loads the GoFundMe script).
- Book pages One Life, From Fear to Freedom, SENT prepared from `content/live/` with every `#KEEP-LIVE` button filled from the same live page (same link and label); One Life keeps its From Chance to Choice Stripe button. Files in the Town library `Sylvias Dream/launch-templates/book-pages-prepared-12-Nov` (not in Git: payment links). Review document for Moses; nothing published.
- Caches cleared after the changes.

## Thursday 12 November 2026 (12:30-13:15): call with Sylvia - Stripe connected, cart live, price answers
- Before the call: Stripe add-on installed on live by Lightsof (WooCommerce > Settings > Payments). Sylvia then connected her own existing Stripe account herself; checked: Stripe enabled, test mode off. No Stripe setting edited by Pimofy.
- Published Cart 6437 and Checkout 6438. Attached the protected ebook files through WooCommerce's protected upload (`woocommerce_uploads/2026/11/`): Guard Your Heart Renew Your Mind (6393, A$7.10) and When the Word Becomes Life (6451, A$7.16). WordPress.com caches cleared.
- Signed out: both books still show **Buy ebook** and no Add to cart. Cause: by design, `sdc_buy_link_not_purchasable` (child `inc/woocommerce.php`) hides the cart button on any product with a Stripe buy link (`_sdc_buy_link`), and both have one. Empty-cart checkout redirect to Cart is normal WooCommerce. Decision needed (Moses): theme option to show Add to cart beside Buy ebook when a download file is attached, or (with Sylvia's written OK) remove the buy link from those products.
- Sylvia's answers (call brief 5.1-5.4): 5.1 All Shall Be Well = **A$5.99** (live product 6458 changed A$7.56 -> A$5.99, verified signed out, Buy ebook link unchanged); 5.2 SENT should be **free** (not changed: needs her written OK, and the old SENT page's paid A$5.99 Stripe button stays until she approves); 5.3 correct title **From Fear to Freedom** (website already uses it; the Stripe name "Freedom From Fear" is hers to change); 5.4 The Report of the Lord **A$8.35 confirmed**.
- Earlier today (paused fix task, partial): live synced patterns 6937 (HoE contact form) and 6939 (HoE slideshow 1) created but page 1932 not yet pointed at them; stray GoFundMe embed.js link still on 1932; Home 6404 Meet Sylvia portrait set; From Chance to Choice (6457) product image set; featured images set on posts 6351 and 6297 (dates unchanged).

## Thursday 12 November 2026 (12:10): launch call script
- Spoken script v3 for the call with Sylvia written (`project/call-scripts/2026-11-12-sylvia-launch-call-v3.md`, also a Town document): show the live site, she connects her own Stripe (WooCommerce > Settings > Payments), one real ebook purchase + download + refund, optional test gift, price questions (All Shall Be Well, SENT paid button, "Freedom From Fear" name), Manifesting Miracles Now, hiding old home pages 2 and 5022, old payment links after 30 days, past buyers list, approval, USD 168 balance, training date. Based on v2 (pre-build call).

## Thursday 12 November 2026 (09:30-10:45): more post-launch fixes on live
- **Phone: floating Subscribe button** (Jetpack) sat on top of the theme's bottom bar and covered Give. Turned off Jetpack > Newsletter > Settings > Subscriptions > "Floating button on bottom corner" (overlay and in-post pop-up were already off). Newsletter, subscribers and the homepage newsletter section unchanged. Signed-out check OK.
- **Heaven on Earth "Life at Heaven on Earth" slider** (11 photos, arrows, pause, 1/11 counter) was missing on live: the kept section calls a synced pattern (`wp:block ref 6637`) that existed only on the test site. Created live synced pattern **"HoE slideshow 2", ID 6924**, same markup, using the 11 photos already in the live Media Library; page 1932 now references 6924. Signed-out check: all 11 slides, arrows and pause work. Lesson: kept sections that use `wp:block` refs need the pattern recreated on the target site (test IDs do not exist on live).
- **Logo:** `SDM_Header_Logo_960w_ForLightBG.png` uploaded to live and set in Customize > Site Identity > Logo (no separate retina/mobile/sticky logo fields on test or live). Signed-out desktop loads the new file; phone preview in the Customizer also OK.
- **Site icon (favicon):** the test icon file could not be fetched, so a 512x512 icon was made from the dove in the new logo (repo `project/assets/sylviasdream-site-icon-512.png`) and set in Site Identity > Site Icon. Live serves `cropped-sylviasdream-site-icon-512.png` for the favicon and Apple touch icon. Swap for the test-site icon later if Moses or Sylvia prefer it.
- Caches cleared after each change.
- **Not fixed:** Heaven on Earth "From the field" video cards on live have a tall dark area above each thumbnail (test shows compact thumbnail cards). The kept widget has no size setting; needs a theme fix (likely WordPress.com's own YouTube embed markup vs the theme's yt-facade CSS). Not yet investigated in code.

## Thursday 12 November 2026 (06:00-09:30): post-launch fixes on live
- **Footer (child 1.23.12):** live showed the Jetpack sign-up form under "Weekly encouragement in your inbox" because Jetpack exists on WordPress.com; test shows the "Contact Sylvia" link. New Customizer checkbox `footer_news_form` (default off) now decides; off shows the link. Uploaded to live (Replace current), "Footer: Newsletter sign-up" widget area confirmed empty, caches cleared, signed-out check OK (link to /contact/, no form). The homepage newsletter section and Jetpack's floating Subscribe button are unchanged.
- **My account 6439 published** so "My downloads" (/my-account/downloads/) shows the sign-in page instead of 404. Cart 6437 and Checkout 6438 still Draft.
- **Logo:** Lightsof replaced the header logo on live with `SDM_Header_Logo_960w_ForLightBG.png` (Sylvia's Dream Ministry). Site icon not confirmed.
- **Books mega menu:** the "Start here" covers were missing because the Books item in the live Primary menu lacked the `sd-mega` CSS class (Appearance > Menus > Books > CSS Classes). Added. Featured stars were briefly set on 3 books (long titles overlapped), then removed, so the theme's fallback shows the short names in test order: The Report of the Lord, One Life, From Fear to Freedom. Lesson: menus rebuilt by hand must copy the `sd-mega` class from the test menu.
- **Home hero (child 1.23.13):** the hero is the cover block (class `sd-hero-cover`, background photo, ink dim 60) inside the preserved "Section from your page" widget. Its book covers did not show on live because the older live book pages (`from-fear-to-freedom-3`, `one-life`, `sent`) have no "sd-book-cover" image. 1.23.13 falls back to the matching product image. Home 6404 restored from Elementor revision 6793 (an interim swap to the "Home: big welcome banner" widget was undone; that widget picks random books and has no background photo). Verified signed out over 3 reloads: background photo behind From Fear to Freedom, One Life and SENT, as on test.
- Zips in library `Sylvias Dream/releases`: child 1.23.12, 1.23.13. Live now runs child **1.23.13**, core 0.4.6.

## Thursday 12 November 2026 (03:40-04:50): NEW SITE LIVE on sylviasdream.com
- Products imported by Lightsof (Tools > Import > WordPress): 24 products as Draft; only the 2 protected ebook PDFs failed (expected, they are not public).
- Child theme **1.23.11 activated** on live (no errors). First template insert gave empty sections because the templates had been imported while the theme was inactive (Elementor drops unknown widgets): old templates trashed, re-imported (new IDs 6744 Home, 6747 Shop For Books, 6750 Contact, 6754 My Story, 6758 Invite, 6761 Podcasts, 6765 Author Support, 6768 Heaven on Earth, 6771 Give). Lesson: import Elementor templates only after the child theme is active.
- Published with templates (Elementor full width): Home 6404 (front page in Settings > Reading; posts page unchanged, 4717), Give 6406 (test-only "Private preview" note removed), Invite Sylvia to speak 6408. Existing pages updated in place (addresses unchanged): Shop For Books 3001, Contact 20, My Story 2665, Podcasts and Videos 1366, Author Support 6235, Heaven on Earth 1932. All 24 products Published.
- Replica of test settings: new menus Primary, Footer Books, Footer About, Footer Policies assigned to the four theme locations (old live "Primary" renamed "Primary (old)", Top Menu unassigned); Customizer Sylvia's Dream values and theme options (colours, width 1200, sticky header, built-in header/footer) set as on test with live addresses; WooCommerce AUD, sell worldwide, shipping off, coupons, AJAX add to cart, reviews. Policy pages copied from test and published: Privacy Policy 6826, Terms of Sale 6830, Refund Policy 6833; "Draft for Sylvia's review" notices removed (approved item 14), Privacy "Last updated: 11 November 2026".
- Redirects ON (core map: /home/, /new-home/ -> /; /sylvias-dream/ and /from-fear-to-freedom/ -> /from-fear-to-freedom-3/; hidden key -> -3; /my-autobiography/ -> /one-life/). /shop/ and /one-life/ are not in the map, so they correctly do not redirect. /all-shall-be-well/ thank-you still works.
- "Meet Jesus" button added on /to-ensure-you-end-up-going-to-heaven/ -> /2026/11/10/meet-jesus/.
- My account 6439 published (sign-in and My ebooks); /my-account/downloads/ no longer 404. Cart 6437 and Checkout 6438 stay Draft until Sylvia connects Stripe.
- Click-tests (no payment): Give A$5/15/30/50 Dream Partner links open the right Stripe products; Heaven on Earth buttons OK; GoFundMe opens "Heaven on Earth Ministries cares for orphans" (A$1,576 of A$5,000); Buy ebook on One Life (A$12.99), From Fear to Freedom (Stripe name "Freedom From Fear", A$4.50), All Shall Be Well (Stripe $5.99 vs site A$7.56), SENT page button (A$5.99) while the catalogue lists SENT as Free. Search, language selector, cart drawer and all 14 footer links OK. Elementor files and WordPress.com caches cleared.
- Not done: logo and site icon still the old live ones (agent could not download the test-site PNGs); phone-width checks (browser tool has no phone view).

## Thursday 12 November 2026 (03:30): launch started (part 1), paused at product import
- Lightsof chose to launch now (Sylvia's written go-ahead 12 Nov; cart payments to be connected by Sylvia on the call).
- Live: fresh All content export taken; child theme replaced with **1.23.11** (still inactive, Twenty Twenty active); **WooCommerce 11.3.0 activated**, pages 6436-6439 confirmed Draft, site visibility Live, no payments set up. Signed out: home unchanged, /cart/ 404.
- **Blocked:** the agent's browser upload tool refuses XML files (even renamed .txt), so the products file could not be imported. Lightsof to import `Products live draft 12 Nov 2026.xml` by hand: Tools > Import > WordPress > choose file > assign to Sylvia's admin user > tick "Download and import file attachments" > Submit. Then the agent continues with part 2 (activate theme, insert templates, publish).
- Note: while WooCommerce is active the empty `/shop/` archive may be public again.

## Thursday 12 November 2026 (03:10): child 1.23.11 built
- Registration line added to `content/blocks/heaven-on-earth.html` (after the mission paragraph) and `give.html` (below Go Fund Me), and the matching `content/live/` files.
- `scripts/check-theme.js` check 5: PHP parse (`php -l` or php-parser; fails if neither). Tested: without a parser it fails; with php-parser 92 PHP files pass; a deliberate syntax error is reported as an ERROR.
- Built `sylviasdream-child-1.23.11.zip` (check-theme OK, assets minified) and saved it to the Town library `Sylvias Dream/releases`. Not installed anywhere yet.

## Thursday 12 November 2026 (02:55): /shop/ re-check, products XML, HANDOFF rewrite
- Live, signed out: `/shop/` first still showed the empty archive (cached); WooCommerce confirmed inactive; WordPress.com Hosting > Caching > Clear all; then `/shop/` and `/shop/?nocache=...` both redirect to the home page.
- Test site: Tools > Export > Products (WXR). Prepared `Products live draft 12 Nov 2026.xml` (24 products set to draft, trashed "When Fear Has the Final Word" removed, `_downloadable_files` removed for the 2 protected ebooks, product links swapped to sylviasdream.com, 26 image attachments left pointing at the test site so the importer can fetch them). Saved to the Town library `Sylvias Dream/launch-templates`. Nothing imported on live.
- HANDOFF section 0 rewritten for the next agent (status, done, remaining in order).

## Thursday 12 November 2026 (02:40): live templates imported, /home/ checked, WooCommerce trial
- Live: the 9 live-safe templates imported into Elementor > Saved Templates (IDs 6410 Home, 6412 Shop For Books, 6414 Contact, 6419 My Story, 6421 Invite Sylvia to speak, 6423 Podcasts and Videos, 6425 Author Support, 6430 Heaven on Earth, 6432 Give); none inserted into a page.
- /home/: front page is page 5022 "Sylvia's Dream"; page 2 (`/home/`) is linked from the Primary menu "Home"; Top Menu "Home" points to `/sylvias-dream/`. New Home draft 6404 slug changed home -> `welcome` (still Draft). Decision in DECISIONS 12 Nov.
- WooCommerce 11.3.0 installed and activated: its Shop 6436, Cart 6437, Checkout 6438, My account 6439 set to Draft at once (Refund policy 6440 already Draft); site visibility already Live. Signed-out `/shop/` still showed an empty product archive, so WooCommerce was **deactivated** (installed, inactive). `/cart/` 404 after that; plain `/shop/` still showed the archive once (likely cache; a cache-busting request redirected home): re-check.
- Product CSV import refused by WordPress.com ("not allowed to upload this file type"); 0 products created. Prepared CSV (all Draft, no IDs, no download files, test-site image URLs) kept in the session; plan is XML import at launch (DECISIONS).
- Menus unchanged (Top Menu 32 items, Primary 17); no WooCommerce page in any menu. Home page unchanged for visitors.

## Thursday 12 November 2026 (02:20): Elementor templates exported; Phase B media and drafts on live
- Test site: Elementor 4.3.5 -> 4.4.0 and Redux 4.5.16 -> 4.5.18 (match live); Elementor DB update done; front end and wp-admin fine. The 9 Elementor pages saved as templates `LAUNCH - <page> - 2026-11-12` and exported to JSON; no page changed.
- JSON made live-safe (MIGRATION-PLAN 4b step 3): every `topnotchmall.co.ke/sylviasdream/` and `/sylviasdream/` address replaced with `sylviasdream.com/` or `/`; none remain in any file. Image URLs and IDs pointed at the live Media Library (map in MIGRATION-PLAN 4b). Stripe/GoFundMe links unchanged. Originals and live-safe copies in the Town library `Sylvias Dream/launch-templates` (private; not in Git, they contain payment links).
- Live: 9 missing media uploaded (IDs 6395-6403, two ChatGPT images got a `-1` suffix); kenya-family.jpg already there (ID 1949). Library now 263 items.
- Live: empty Drafts created (Save draft only): Home 6404 (slug home), Give 6406 (give), Invite Sylvia to speak 6408 (invite-sylvia-to-speak). Not in Top Menu or Primary. Signed-out checks: home page unchanged, /give/ and /invite-sylvia-to-speak/ still 404. Templates not inserted yet: their widgets come from the child theme, which is not active on live (insert in Live Preview or the launch window).

## Thursday 12 November 2026 (01:45): Phase B started on live sylviasdream.com
- Installed: Sylvia's Dream Core 0.4.6 (activated), parent theme Sylvia's Dream Base 1.0.1 and child 1.23.10 (uploaded, **not activated**; active theme still Twenty Twenty), Elementor 4.4.0 and Redux Framework 4.5.18 from the plugin directory (activated; Elementor onboarding skipped, no page edited).
- Settings > Sylvia's Dream on live: XML-RPC, two-step sign-in, login limit and Redirects all unticked (WordPress.com defaults), Dream Partners so far = 1 (goal 100). Saved and re-checked after signing out and in.
- Top Menu: "Automatically add new top-level pages" was already unticked; menu saved unchanged.
- Checks: Home, Meet Jesus and Heaven on Earth (Dream Partner section) look the same before/after; Jetpack Connection Tests all pass; Newsletter overview (24 subscribers) and subscribers (54 records) load; Meet Jesus subscribe form renders (no test subscription sent); /all-shall-be-well/ opens with its Download PDF link and has robots noindex, nofollow; WordPress.com sign-out/sign-in works. /give/ is 404 on live (page does not exist there yet; pre-existing).
- Version note: live has Elementor 4.4.0 / Redux 4.5.18, test has 4.3.5 / 4.5.16. Update the test site to the same versions before exporting the Elementor templates (MIGRATION-PLAN 4b step 2).

## Thursday 12 November 2026 (01:30): core 0.4.6 on test; live media backup stored
- Test site: core 0.4.6 installed ("Replace current with uploaded"), active, no critical error. Settings > Sylvia's Dream shows all three Security switches ticked (XML-RPC off, two-step sign-in, login limit); nothing saved. Two-step sign-in section present on the `moses` profile (status Off: moses not enrolled yet, so a code sign-in was not tested); branded sign-in page loads with the login-limit notice.
- Live media backup: the server downloaded the files itself with a temporary one-off script (only `https://sylviasdream.com/wp-content/uploads/` URLs from `urls.txt`) in `public_html/sd-tmp-a9265136`; folder and script deleted afterwards (address now 404). Result in `/home/topnotch/sylviasdream-live-backup-2026-11-12/media`: **254 files, 306.7 MB**, 0 failed, year folders 2019-2026. Outside public_html, not web-accessible.
- Backup step 1 now complete apart from screenshots (kept in the browser session only).

## Thursday 12 November 2026 (01:15): core 0.4.6; media folder still empty
- Core plugin 0.4.6 built: Settings > Sylvia's Dream "Security switches" for XML-RPC, two-step sign-in and login limit; off by default on WordPress.com, on elsewhere (test site unchanged). PHP parse-checked (no errors); zip in the Town library `Sylvias Dream/releases`. Not installed anywhere yet.
- Media backup: cPanel File Manager shows `/home/topnotch/sylviasdream-live-backup-2026-11-12/media` empty after refresh (no zip parts found; only `urls.txt` in the parent folder). Nothing extracted or deleted. Re-upload the 13 parts into that exact folder, then extract and count (expect 254 files, about 308 MB).

## Thursday 12 November 2026 (01:00): Sylvia's extra answers, media backup storage, Phase B held
- Sylvia: 1 Dream Partner (item 7); Lulu paperback link for "Fast Forward: Be a World Changer" (items 16/17). Recorded.
- Media backup: folder `/home/topnotch/sylviasdream-live-backup-2026-11-12/media` created outside public_html and `urls.txt` uploaded; the 13 zip parts could not be uploaded (browser tool 10 MB limit, no cPanel Terminal). Media still safe on the live site; parts kept in the Town thread.
- Phase B not started: waiting for the media backup, and core 0.4.5 must not be activated on WordPress.com until it has an XML-RPC switch (OPEN-ITEMS 12 Nov).

## Thursday 12 November 2026 (00:40-01:00): live backup, step 1 (read only)
- Live sylviasdream.com, nothing changed. Saved to the Town library `Sylvias Dream/backups`: All content XML (4.4 MB), Jetpack newsletter subscribers CSV (54 records, 24 confirmed subscribers per Newsletter overview), MailPoet CSV (2 unconfirmed). Author Support text copied (session record).
- Media: WordPress.com media export needs Business/Commerce (not upgraded). Instead all 254 attachment files listed in the XML (308 MB) were downloaded from their public URLs into the agent sandbox; library upload failed (file size limit), so a permanent home is still needed.
- Screenshots taken in the browser session (pages 60: 56 published/4 drafts; posts 20; menus Top Menu and Primary; widgets; Customizer panels; Settings; plugins 11; theme Twenty Twenty). They could not be copied to the library.
- Plan: WordPress.com Personal, renews 29 Oct 2027 (A$60). Upload Plugin and Upload Theme screens are available (nothing installed).

## Thursday 12 November 2026: Sylvia's go-ahead
- Sylvia answered 8 (yes) and 25 ("Please move the new website to sylviasdream.com"), and offered to meet today. Recorded in CLIENT-CHECKLIST, OPEN-ITEMS, DECISIONS, CORRESPONDENCE, HANDOFF. Live site still untouched.

## Wednesday 11 November 2026 (after noon): rule 12, final WhatsApp
- AGENTS.md rule 12: products may be Published on the password-gated test site, Draft on live until launch (DECISIONS 11 Nov); HANDOFF conflict note removed. 11 Nov WhatsApp finalised without the registration paragraph (CORRESPONDENCE).

## Wednesday 11 November 2026 (noon): handover check
- Handover pass for the next agent: ARCHITECTURE-ESSENTIALS versions (child 1.23.10, core 0.4.5), README "last updated" points to HANDOFF section 0, HANDOFF adds a handover checklist (CI check before installs, sign-ins, library zips) and flags the AGENTS rule 12 vs test-site Published products conflict, CORRESPONDENCE holds the full 11 Nov WhatsApp text.

## Wednesday 11 November 2026 (late morning): status and launch plan
- HANDOFF sections 0 and 3 rewritten: where things stand, what remains (now / waiting on Sylvia / launch / handover) and how to work. README badges at child 1.23.10.

## Wednesday 11 November 2026 (midday): registration line, Elementor health check, memory limit
- Sylvia's yes confirmed (relayed by Lightsof): "Heaven on Earth Ministries is registered in Kenya (Reg. No. 35364, 2011)." added as a new Text Editor widget on Heaven on Earth 1932 (after the intro section, before the photo gallery) and Give 6428 (after the giving options, below GoFundMe, before Updates). No existing widget, button or payment link changed. Checked desktop and Elementor phone preview.
- Elementor health check (read only): all 9 Elementor pages open in the editor and their text fields (including "Section from your page") are editable. No current errors found: System Info shows no warnings; its log holds only old JS errors (29 Oct, 5 Nov) and two old warnings inside Elementor itself. No error_log files in the site folders. Site Health: 1 critical issue (no page cache, slow server response), plus OPcache off, PHP 8.2, inactive plugins/themes, late cron event.
- wp-config.php: backup `wp-config.php.bak-20261111`, then added `define( 'WP_MEMORY_LIMIT', '256M' );` (was 40M). Site and wp-admin fine; System Info shows 256M.
- Editor still slow to open (about 80-90 seconds on Home, Author Support, Heaven on Earth). Theme queries are small and cached, so the likely cause is the server (no OPcache, slow response). Next: ask the host to enable OPcache for ea-php82 and check server load.

## Wednesday 11 November 2026 (late morning): test site restored on child 1.23.10
- Lightsof signed in to cPanel; in File Manager, `/public_html/sylviasdream/wp-content/themes`: old `sylviasdream-child` (1.23.9) moved to Trash, 1.23.10 zip uploaded and extracted. Nothing else touched.
- Appearance > Themes: Sylvia's Dream 1.23.10 active (child of Sylvia's Dream Base). Elementor > Tools > Clear Files & Data run (no page-cache plugin found).
- Front end checked: Home "Eighty-two and still becoming" and "Today, at eighty-two"; My Story "Today, age 82" and 82 stat; Invite Sylvia to speak "still becoming at eighty-two". No 81 / eighty-one. Books and Contact load. One 504 on My Story, fine on retry. No content, Customizer, payment or Stripe change.

## Wednesday 11 November 2026 (morning): 1.23.9 install broke the test site; 1.23.10 fix ready
- Child 1.23.9 zip built from main and saved to the Town library (Sylvias Dream/releases). Installed on the test site ("Replace installed with uploaded", 1.23.8 -> 1.23.9, "Theme updated successfully"). Straight after, wp-admin and the front end showed "There has been a critical error" (HTTP 500).
- Cause: the apostrophe in "weeks' time" ended the single-quoted PHP string in `inc/customizer.php` (About "Second paragraph" default). Not caught because `php -l` is not available in the build sandbox and check-theme.js does not parse PHP.
- Fix: child 1.23.10 (typographic apostrophe, wording unchanged), PHP-parser check of all child and core PHP files (no errors), zip in the library. Committed 8a3e9f5.
- **Test site still down.** wp-admin cannot load, so 1.23.10 must go in through cPanel File Manager (topnotchmall.co.ke:2083): upload the zip to the /sylviasdream/ install's wp-content/themes, delete only `sylviasdream-child`, extract. The cPanel sign-in was rejected and the retry was cancelled. Then check Home, My Story and Invite Sylvia to speak still show 82, and that Books and Contact load. Live site untouched.

## Wednesday 11 November 2026: Sylvia's answers, child 1.23.9
- Sylvia answered 2, 9, 10, 13, 14 on WhatsApp, published her Meet Jesus subscribe post on live, asked where to find the payment account (8), and sent the Heaven on Earth registration (Reg. No. 35364, Kenya, 2011). Recorded in OPEN-ITEMS, CLIENT-CHECKLIST, DECISIONS, CORRESPONDENCE.
- Child 1.23.9: age 82 and her Kenya-return sentence in the About text, My Story and Invite Sylvia to Speak defaults.

## Tuesday 10 November 2026 (late): Elementor inventory
- Follow-up check, read only: the other 52 pages (43 published, 9 drafts) all have Header and Footer "Use the website setting"; none uses starter 6701/6702. All 72 pages checked. MIGRATION-PLAN 4b and OPEN-ITEMS updated.
- Read-only check in wp-admin: 9 pages built in Elementor (Home, Shop For Books, Contact, My Story, Invite Sylvia to speak, Podcasts and Videos, Author Support, Heaven on Earth, Give 6428); header/footer are the built-in theme ones; starter templates 6701/6702 unused; Elementor 4.3.5, Redux 4.5.16. MIGRATION-PLAN 4b and OPEN-ITEMS updated. Nothing changed on the site.

## Tuesday 10 November 2026 (night): product checks and two launch blockers
- wp-admin, read only: Guard Your Heart Renew Your Mind is product 6393 (A$7.10, 6x9 PDF attached in `woocommerce_uploads`, Stripe link `...40k`); product 6381 is the separate Guard Your Heart (no file, no Stripe link, "Coming soon"); The Report of the Lord 6396 is A$8.35 with Stripe link `...40g`. BOOK-CATALOGUE, CLIENT-CHECKLIST and OPEN-ITEMS corrected.
- Test site: Monday Encouragement post 5688, test-site address in its text changed to sylviasdream.com (as on live); date, author and status unchanged. Going to Heaven page 2458: empty button (no label, no link) removed; no wording changed; Meet Jesus not added. Both checked on the front end. Live site untouched; no code or payment link edited.

## Tuesday 10 November 2026 (evening): child 1.23.8 and core 0.4.5
- Core `inc/sale-guard.php`: paid ebooks with no price or no download file are not purchasable; product-screen warning; setting "Coming soon guard" (on); schema Offer omitted for them. Child: "Coming soon" price and buy-box message (Customizer `shop_coming_soon`), Stripe and Amazon buttons unchanged.
- Follow-up email and WhatsApp reminder to Sylvia recorded (CORRESPONDENCE, CLIENT-CHECKLIST, OPEN-ITEMS); not sent, Moses to approve.
- HANDOFF sections 0 and 3 rewritten; README badges, ARCHITECTURE 4.0a, CHANGELOG updated.
- `check-theme.js` OK (144 files); assets rebuilt; zips built. `php -l` not run locally (no PHP); CI covers it.
- Installed on the test site (Replace active/current with uploaded): Themes shows child 1.23.8 active, Plugins shows core 0.4.5 active, Settings > Sylvia's Dream "Coming soon guard" ticked. Smoke test signed in: Home, Books, Contact and a 404 URL fine. When the River Flows Again (6392): "Coming soon" price and buy-box message, no Add to cart, product-screen warning "...until it has a download file". The Report of the Lord: Stripe button kept, A$8.35. When the Word Becomes Life (6380): A$7.16, Add to cart and Stripe button. No code or link edited in the dashboard; no purchase made.

## Tuesday 10 November 2026: Elementor launch plan, GoFundMe address, protected ebook
- `docs/MIGRATION-PLAN.md` section 4b: how the Elementor-built pages (My Story, Give, Heaven on Earth, Contact, Books, any header/footer templates) move to sylviasdream.com, since `content/live/` holds only the older block-editor versions. Saved-template export, dev-address and image clean-up, import on live, existing pages updated only in the switch-over window, fallback to `content/live/`.
- Old GoFundMe address dc685ae2 replaced with e6fffb40c in `content/blocks/` (give, heaven-on-earth), `content/live/` (same two) and `docs/hoe-segments/part2.txt`. No code change; no version bump (page source files only; the Customizer setting from 1.23.6 already overrides the button on render).
- Moses's latest WhatsApp follow-up to Sylvia (items 2, 3, 6-24 open; 1, 4, 5 treated as answered) recorded in `project/CORRESPONDENCE.md`; `docs/CLIENT-CHECKLIST.md` updated (1, 4, 5 ticked; 2, 3, 6 partly received).
- Test site, When the Word Becomes Life (6380): protection checked and finished. Product already used the protected copy in `woocommerce_uploads/2026/10/`; Media 5646 already gone; removed the leftover disabled `uploads/2026/07/` approved directory. Signed out: old address 404, protected file 403. Order #6686 still downloads from My account. Live site and Stripe untouched.

## Tuesday 3 November 2026: child theme 1.23.2
- Built on `release/1.23.2` from main: mega-menu fallback cover without duplicate title, `bible.jpg` banner alt text, AUD symbol "A$", One Life Kindle link to amazon.com.au. `check-theme.js` OK, assets rebuilt. PR #9; CI `php -l` 8.0-8.3 passed.
- First upload: Books banner alt still empty, because Books uses the theme page header (Elementor Page top banner) rather than a cover block. Fixed in `sdc_page_header()` (`sdc_theme_photo_alt()`), zip rebuilt as the same version and uploaded again.
- Verified on the test site: Books banner alt "An open Bible and journal by a sunny window"; One Life once in "Start here"; A$9.99 on cards, product page, cart and checkout (test cart emptied, no order); One Life page 2766 Kindle link amazon.com.au/dp/B07MLMQ37C. 31 Oct Additional CSS rule deleted. Live site and Stripe untouched.

## Saturday 31 October 2026 (night): cross-check live vs test site
- Compared 72 live public pages/posts with the test site (live sitemap would not load). Fixes and open items in OPEN-ITEMS "31 Oct cross-check". Two fixes were made in the test-site dashboard (Additional CSS, `inc/cover-alt.php`) and are now in child 1.23.2.

## Saturday 31 October 2026: shop tidy, Kindle links, Stripe links (test site only)
- PR #8 (child 1.23.0 + 1.23.1 account layout fix) merged into `main`.
- After Sylvia's 31 Oct email: Manifesting Miracles Now unpublished (Draft); duplicate When Fear Has the Final Word (6390) in Trash (restorable); Fast Forward to Success product image = new cover. Be a World Changer cover saved only (question to Sylvia).
- Kindle: 14 amazon.com links switched to `https://www.amazon.com.au/dp/<same ASIN>`; existing AU links unchanged.
- Stripe "Buy ebook" links added (label checked on the live page first; no link changed): When the Word Becomes Life `aFa7sLcED5Pz2vO03V7g40i`, Learning to Recognise God's Voice `dRm00jcED91L5I03g77g40h`, Trusting God Deeper `aFa3cv6gfcdXc6ocQH7g403` (from /shop-for-books/), Guard Your Heart Renew Your Mind `14AdR90VV6TDc6o9Ev7g40k`, The King's Way `8x2cN5cED1zjeew17Z7g409`, Wisdom for Soul-Winning `3cI8wP5cbfq9das3g77g40b`, Angelique `aFa8wP1ZZ7XHc6o5of7g402`, All Shall Be Well `dRm00jcEDdi10nGbMD7g404`, Angelique Finds Her Light `dRm00j9sr5Pzb2k2c37g40a`, SENT `bJe9AT3436TDc6obMD7g40c`, Guard Your Heart `7sY00jeMLfq97Q8eYP7g40d`, The Report of the Lord `8x27sL8ona5P8Uc7wn7g40g` (from the live home page). The 5 earlier links unchanged. Code suffixes only (full addresses are on the live pages and in `docs/LIVE-LINK-INVENTORY.md`).
- No purchase made; live site and Stripe untouched.

## Friday 30 October 2026: child theme 1.23.0, professional sign-in and My account
- Built on branch `feature/account-login-polish` from main (1.22.0): WooCommerce My account template overrides (sign in, register, lost/reset password, dashboard, navigation, downloads, orders), `inc/account.php`, `account.css`, `account.js`. Core plugin unchanged. `check-theme.js` OK, assets rebuilt, `php -l` left to CI. Not yet on the test site; test list in OPEN-ITEMS "30 Oct (account 1.23.0)".

## Thursday 29 October 2026 (15:50): child theme 1.22.0, YouTube videos on Elementor pages
- With 1.21.2, My Story built in Elementor showed the "Let's Walk Together" YouTube Short as a dark box with the bare address. Cause: the "Section from your page" widget rendered blocks with `do_blocks()` only; the YouTube iframe comes from WordPress's auto-embed on `the_content` (priority 8), before the theme facade swaps it for the thumbnail.
- Fixed on `feature/elementor-builder`: kept sections render like `the_content`; facade also in the Elementor editor and from the address when oEmbed fails; facade script enqueued for Elementor pages and the preview; GoFundMe script for rebuilt Heaven on Earth; new Video widget; top-level embed blocks map to it. Audit of Speaking, Podcasts, Author Support, Heaven on Earth, Give, Contact (and My Story) in CHANGELOG.
- `check-theme.js` OK; `php -l` not run (no PHP in the sandbox; manual review; CI runs it); `build-assets.js` and `build-theme-zip.sh` run; zip `sylviasdream-child-1.22.0.zip` with fresh .min files. Not yet uploaded to the test site; nothing changed on live.

## Thursday 29 October 2026 (15:00): child theme 1.21.3, banner gap
- Agnes: gap between header and banner on My Story (not on Author Support). Removed `.sd-page-body` top padding when a banner page starts with a full-width block, and bottom padding when it ends with a full-width band; plain pages keep spacing. Installed and checked on the test site (see OPEN-ITEMS).

## Thursday 29 October 2026 (afternoon, later): child theme 1.21.2, page banner photo and light text
- With 1.21.1 on the test site, "Build this page in Elementor" on Books gave a banner without its photo (bible.jpg) and with a dark intro line; stopped before the other inner pages. Home was fine.
- Cause found in code: inside Elementor's `.entry-content > .elementor` wrapper, Elementor's `.elementor img { height: auto }` and `.page:not(.home) .entry-content p` (ink-2) beat the theme banner rules; the builder copied only the Cover's `url` attribute (no ID, `<img>`, focal point; old site address kept).
- Fixed on `feature/elementor-builder`: banner conversion carries photo, focal point, shade, light words, heading and intro; Page top banner widget has photo position, shade and text colour; style.css banner safety rules; elementor.css rules for a banner kept as "Section from your page". All inner-page banners audited (Covers on Books, My Story, Speaking, Podcasts, Author Support, Heaven on Earth; Page header pattern on Contact; none on Give).
- `check-theme.js` OK; `php -l` not run (no PHP in the sandbox; balance check and manual review; CI runs it); `build-assets.js` and `build-theme-zip.sh` run; zip `sylviasdream-child-1.21.2.zip` with fresh .min files. Not yet uploaded to the test site; nothing changed on live.

## Thursday 29 October 2026 (afternoon): child theme 1.21.1, Elementor bug fixes
- 1.21.0 on the test site showed blank dark gold buttons site-wide (Kit link colour, fonts and width seeded into the Elementor Kit) and squeezed Elementor pages (760px reading column); it was rolled back by hand.
- Fixed on `feature/elementor-builder`: Kit seeding limited to picker choices; one-time clean-up of the 1.21.0 Kit values (hand-set values kept) and of the template of pages built under 1.21.0; new template `templates/sd-elementor-full-width.php`; full-width rules in `elementor.css`; new `button-safety.css` (switch `button_safety`).
- `check-theme.js` OK; `php -l` not run (no PHP in the sandbox; balance check and manual review; CI runs it); `build-assets.js` and `build-theme-zip.sh` run; zip `sylviasdream-child-1.21.1.zip` with fresh .min files. Not yet uploaded to the test site; nothing changed on live.

## Thursday 29 October 2026 (late morning): child theme 1.21.0, whole site editable in Elementor
- Agnes asked for the whole website to be editable in Elementor by Sylvia, following the Smile Community Centre build (Redux Theme Options + Elementor). Built on branch `feature/elementor-builder` off main: Theme Options (Redux or built-in screen), Elementor header/footer with per-page choice and safe fallback, 25 Sylvia's Dream widgets, set-up screen (plugins, Kit, starters, build each main page), dashboard guide, simple editor screen, plain-English guide `docs/ELEMENTOR-EDITING-GUIDE.md`.
- Lessons from Smile applied: every widget has a Photos panel (with a note where photos come from elsewhere); a missing Elementor Kit shows a notice and a "Create it now" button.
- `check-theme.js` OK; PHP not available in the sandbox (no root, no internet): balance script over all 56 child PHP files clean plus manual review; `php -l` left to CI. `build-assets.js` run; zip `sylviasdream-child-1.21.0.zip` built. Not yet uploaded to the test site; nothing changed on live.


## Thursday 29 October 2026: child theme 1.20.0 (Journal, cart drawer, checkout, buy-box fix)
- Built 1.20.0 from main (2b17bf0): Journal index/archives/single redesign, side cart drawer with AJAX add to cart (single book page too), classic cart/checkout/order-received/Downloads polish with short eBook checkout, and the tall "Buy paperback on Amazon" button fixed. Details in CHANGELOG.
- `check-theme.js` OK; `build-assets.js` run; zip `sylviasdream-child-1.20.0.zip` built. No PHP runtime in the sandbox: run `php -l` / CI and smoke-test Home, Books, a book page, Journal, a post, Cart, Checkout and a 404 after upload.
- Deployer settings: WooCommerce > Settings > Products: tick "Enable AJAX add to cart buttons on archives"; leave "Redirect to the cart page after successful addition" unticked (otherwise the drawer is skipped and the cart page opens).

## Tuesday 27 October 2026 (midday): core 0.4.2 on dev, social image, Home SEO, WordPress.com check
- Core 0.4.2 (0a105be): the Jetpack "Prove your humanity" maths box had an inline 50x25px size that hid the typed number on the branded sign-in page; now 6.5rem wide, 48px tall, larger centred text. Uploaded to dev with 0.4.1 and active; Dashboard fine. The maths question did not appear during the test, so the box itself is not yet seen on screen.
- Core 0.4.1 on dev: two-factor setup shows the QR code next to the key. Setup was opened and cancelled; 2FA stays Off. Phone scan not done.
- Social image designed (1200x630, EB Garamond, logo kit colours, mission photo) and uploaded as media 6669. Rank Math on Home (6365): SEO title, description, Facebook and X image/title/description, five focus keywords; global default OpenGraph image set to the same file; X card summary_large_image. Front end checked: each tag once, matching. Rank Math score 85/100.
- WordPress.com Personal check (documentation only, no live change): plugins and theme uploads allowed; WordPress.com sign-in bypasses the plugin's 2FA and generic errors; no wp-config access, so the plugin's constants cannot be used on live; XML-RPC and login-limit risks. Details in HANDOFF section 4.

## Tuesday 27 October 2026: core 0.4.1, QR code for two-factor setup
- `inc/qr-svg.php` + `inc/lib/qrcode.php` (MIT port of kazuhikoarase/qrcode-generator): setup screen shows a locally drawn SVG QR code of the otpauth:// address; key and link kept.
- Tests in the sandbox (no PHP runtime): php-parser PHP 8 mode 0 errors on every plugin PHP file; PHP port run through a php-parser AST interpreter gave matrices identical to the library's JS version for 10 inputs (v1-v15); all decoded with OpenCV to the exact input. Not yet deployed to dev; phone scan and `php -l` / CI still to do.

## Monday 26 October 2026 (night): payments rule, Amazon titles, launch pack
- Decision: every existing Stripe payment, Dream Partner contribution link, thank-you/download page and subscription stays working unchanged (DECISIONS). Launch pack buttons marked `#KEEP-LIVE-*`; no payment-link addresses stored in Git.
- `content/live/` launch pack built (dev addresses rewritten for sylviasdream.com, image list).
- Amazon check: titles for all listed books (`BOOK-CATALOGUE.md`); verbatim descriptions found for 5 listings only (`AMAZON-CATALOGUE.md`).
- Dev products 6376-6400: 23 renamed to Amazon titles, all still Draft; no description changed.

## Monday 26 October 2026 (afternoon): theme 1.8.4, Priority 1
- Theme 1.8.4 (b316844) uploaded to dev with "Replace active with uploaded"; active.
- One Life 2766, SENT 4686 and From Fear to Freedom 4231 re-pasted from f859bfe (real covers 6604, 6605, 6603). The first paste of 2766 showed an invalid-content warning: the image blocks had `width`/`height` on `<img>` that the block does not save. Fixed in all three files, checked with the Gutenberg validator, re-applied. No warnings in the editor.
- Verified as admin at 1440px: covers load; Books > Browse by topic = All books, From Fear to Freedom, SENT; 404 "Browse the books" goes to /shop-for-books/. Inactive widgets removed.
- Not done: phone-width check; signed-out check (front-end gate password cannot be saved in Town: no username field).
- Validator also flags pre-existing invalid blocks in `author-support.html` and `heaven-on-earth.html` (see OPEN-ITEMS).

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
