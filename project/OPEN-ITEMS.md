# Open items (23 October 2026)

## Waiting on Sylvia
(Kept in full in Moses's Town document "Things needed from Sylvia"; send it to her when the build is done.)
- [ ] Covers: From Fear to Freedom, When Fear Has the Final Word, Manifesting Miracles Now, Fast Forward to Success
- [ ] Confirm titles/subtitles (not on Amazon): When Fear Has the Final Word ("A Devotional on Peace"), Manifesting Miracles Now ("Everyday God Encounters of Selected Authors")
- [ ] Fast Forward to Success: add a line about her own chapter? (Amazon text describes the whole collection)
- [ ] Other Amazon editions in the shop or not: French (De la Peur à la Liberté), Spanish (UNA VIDA), Angelique Legacy Edition, The King's Way: God's Plan For Salvation paperback, large-print editions
- [ ] Approve Privacy Policy, Terms of Sale, Refund Policy
- [ ] Approve Give page amounts ($5/$15/$30/$50 + other) and the 100 Dream Partners goal
- [ ] Approve home hero intro and About Sylvia paragraphs (draft biography from the prototype); "Every purchase helps Heaven on Earth" line yes/no
- [ ] Photo of Sylvia; newsletter sign-up preference
- [ ] Reader reviews for the Home page: up to 3 real quotes, verbatim, with the book, the reader's approved display name (and country if they want it) and their permission to quote on the website
- [ ] Free Downloads page (`/free-7-day-devotional/`) offers the book-writing guide as a free .docx: approve or remove
- [ ] Stripe set-up/access for real payments (Pimofy never edits Stripe)
- [ ] Old Manifesting Miracles Now page: keep or redirect
- [ ] Earlier items still open: signed acceptance and USD 112 deposit, WordPress.com Administrator invite, Stripe data per `STRIPE-DATA-REQUEST.md`, domain registrar, ebook files, logo, social links, written approval of wording

## Pimofy (Moses / next agent)
- [x] Kenya Ministry Training Manual page stays published (Agnes, 26 Oct)
- [ ] Host: fix the cPanel licence on topnotchmall.co.ke (`docs/HOST-CPANEL-LICENCE.md`)
- [ ] Home page gaps against prototype v4: see PROGRESS-LOG 24 Oct (Latest from Sylvia, Have a book on your heart?, newsletter form, featured covers, Meet Sylvia quote card, 4th trust item)
- [ ] cPanel licence error on topnotchmall.co.ke ("License is not for this host"): ask the host to fix it. Until then deploy the child theme by WordPress theme-zip upload (Replace active)
- [ ] Start here welcome card wording ("New to Sylvia's books? Begin with her story, or browse every title by topic.") is ours: include in Sylvia's review; it is replaced by 3 featured covers once books are published
- [ ] Real-phone check of the v4 navigation (iPhone and Android, 320-430px): bottom bar clearance, drawer, no sideways scroll, wordmark
- [ ] "Invite Sylvia to speak" page (dev ID 6511) **published on dev 24 Oct at Agnes's choice** so the About menu link stops returning 404. Wording still needs Sylvia's approval before launch; change or unpublish it if she asks
- [ ] Add Spanish to Other languages once that page exists; Kenya Ministry Training Manual only after Sylvia approves it
- [ ] Mark 3 confirmed books as Featured so the Books mega menu shows "Start here" (after products are published)
- [x] Build child theme 1.1.0 per `docs/NEXT-BUILD-1.1.0.md` (done 23 Oct)
- [x] `.github/workflows/checks.yml` added by Moses; PHP lint 8.0-8.3 and check-theme.js pass
- [ ] Trust strip item 4 and "Every purchase helps" line: add to the pattern only after Sylvia approves
- [x] Deploy 1.1.0 to dev; approved 6-item menu; Jetpack (Forms) on dev; Home "Meet Sylvia"/"Read Sylvia's story" links fixed to /my-story/
- [x] Rebuild Home, Books and Contact on dev from `content/blocks/*.html` (23 Oct; old content kept as revisions)
- [x] Author Support on dev: Jetpack enquiry form restored, two intro paragraphs, same-page `#book-enquiry` button
- [x] Dev Home blocks: change "Meet Sylvia" and "Read Sylvia's story" button links from /about/ to /my-story/ (the expanded blocks kept the old pattern link; patterns are fixed in the repo now)
- [x] (23 Oct) Books and Contact page headers still show placeholder text ("Small heading", "Page title", "A short introduction to this page."): replace with the prototype wording (Books: "The bookshop" / "Books by Sylvia Suhr" / "eBooks delivered instantly by secure download. Paperbacks and Kindle editions through Amazon."; Contact: "Contact" / "Let's talk" / "Questions about a book, a download, speaking or partnering, Sylvia would love to hear from you.")
- [x] Home testimonials placeholders removed from dev and from `content/blocks/home.html` (23 Oct)
- [x] (da977d5, deployed; Agnes chose /shop-for-books/) Shop journey: Home book buttons and the Books CTA strip go to /shop/ (WooCommerce product archive, empty while products are Draft) while the menu uses /shop-for-books/ (block-based Books page). Recommended: /shop-for-books/. Waiting on Agnes's choice; then update patterns (sdc_shop_url) and dev pages
- [x] (f0ab2bc, deployed) Mobile 320px: header wordmark clipped behind the action icons; store notice overlaps the header at the top of the page (theme CSS fix)
- [ ] Newsletter sign-up on the Home newsletter section does not render on dev (no WordPress.com connection, by decision); check on live at launch
- [ ] Author Support on dev: restore the enquiry form (live likely uses a CoBlocks or Crowdsignal form; those plugins are on live, not dev), split the two intro lines back into two paragraphs, fix the top button to the same-page `#book-enquiry` anchor. Wording itself must stay exactly as live.
- [x] Mobile: store notice overlap fixed (f0ab2bc); pointer taps work in the 320px preview. Still confirm on a real phone
- [ ] Dev clean-up from QA: footer Archives/Categories widgets (remove), "Browse the books" on 404 goes to /shop/ while the menu uses /shop-for-books/ (pick one), My Story run-together sentence and repeated intro, Ministry repeated intro and "dav" captions (fix only with Sylvia's wording)
- [x] Dev: book buy.stripe.com links replaced with /shop-for-books/ and public .docx links removed from ordinary pages (`docs/LEGACY-LINK-MAP.md`)
- [ ] Launch: repeat the `docs/LEGACY-LINK-MAP.md` replacements on live, with Sylvia's written OK; point each button at its product page once products are published
- [ ] Old download pages and media files are still publicly reachable by direct URL: follow `docs/BUYER-TRANSITION-PLAN.md` (plan written 23 Oct; Phase 0 before launch)
- [ ] Email to Sylvia drafted 23 Oct (reviews, free guide, OK for old book Stripe links); send only after Moses approves
- [ ] Launch: live Jetpack Newsletter emails new posts by default; publish no posts during switch-over without checking, and confirm the email byline shows Sylvia, not "mosemqute"
- [ ] Launch: remove the street address and town/postcode from the live Contact page (Australia only), with Sylvia's OK
- [ ] Launch: decide whether MailPoet (2 unconfirmed records) stays; Jetpack Newsletter is the real list
- [ ] Do not connect the dev site to Sylvia's WordPress.com account and never import her subscriber export anywhere
- [ ] Visual QA at 390px, tablet, desktop; full `docs/QA-CHECKLIST.md`
- [ ] SEO pass: meta descriptions for every page (her wording), book JSON-LD on book pages
- [ ] Confirm the restoration-help policy for the 3-month period (`SUPPORT-AND-RESTORATION.md`)
- [ ] Create the private Google Drive folder "Sylvia's Dream - Backups (private)"
