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
- [ ] Stripe set-up/access for real payments (Pimofy never edits Stripe)
- [ ] Old Manifesting Miracles Now page: keep or redirect
- [ ] Earlier items still open: signed acceptance and USD 112 deposit, WordPress.com Administrator invite, Stripe data per `STRIPE-DATA-REQUEST.md`, domain registrar, ebook files, logo, social links, written approval of wording

## Pimofy (Moses / next agent)
- [x] Build child theme 1.1.0 per `docs/NEXT-BUILD-1.1.0.md` (done 23 Oct)
- [x] `.github/workflows/checks.yml` added by Moses; PHP lint 8.0-8.3 and check-theme.js pass
- [ ] Trust strip item 4 and "Every purchase helps" line: add to the pattern only after Sylvia approves
- [x] Deploy 1.1.0 to dev; approved 6-item menu; Jetpack (Forms) on dev; Home "Meet Sylvia"/"Read Sylvia's story" links fixed to /my-story/
- [x] Rebuild Home, Books and Contact on dev from `content/blocks/*.html` (23 Oct; old content kept as revisions)
- [x] Author Support on dev: Jetpack enquiry form restored, two intro paragraphs, same-page `#book-enquiry` button
- [ ] Dev Home blocks: change "Meet Sylvia" and "Read Sylvia's story" button links from /about/ to /my-story/ (the expanded blocks kept the old pattern link; patterns are fixed in the repo now)
- [ ] Books and Contact page headers still show placeholder text ("Small heading", "Page title", "A short introduction to this page."): replace with the prototype wording (Books: "The bookshop" / "Books by Sylvia Suhr" / "eBooks delivered instantly by secure download. Paperbacks and Kindle editions through Amazon."; Contact: "Contact" / "Let's talk" / "Questions about a book, a download, speaking or partnering, Sylvia would love to hear from you.")
- [ ] Home testimonials show placeholders ("Reader review goes here"): fill only with real reviews Sylvia approves, or remove the section
- [ ] Shop journey: Home book buttons and the Books CTA strip go to /shop/ (WooCommerce shop page) while the menu uses /shop-for-books/; pick one target
- [ ] Mobile 320px: header wordmark clipped behind the action icons; store notice overlaps the header at the top of the page (theme CSS fix)
- [ ] Newsletter sign-up on the Home newsletter section does not render on dev (no WordPress.com connection, by decision); check on live at launch
- [ ] Author Support on dev: restore the enquiry form (live likely uses a CoBlocks or Crowdsignal form; those plugins are on live, not dev), split the two intro lines back into two paragraphs, fix the top button to the same-page `#book-enquiry` anchor. Wording itself must stay exactly as live.
- [ ] Mobile: store notice overlaps the header in narrow view; check the menu toggle by touch on a real phone, fix the stacking if confirmed
- [ ] Dev clean-up from QA: footer Archives/Categories widgets (remove), "Browse the books" on 404 goes to /shop/ while the menu uses /shop-for-books/ (pick one), My Story run-together sentence and repeated intro, Ministry repeated intro and "dav" captions (fix only with Sylvia's wording)
- [ ] Legacy catalogue on dev links to buy.stripe.com and exposes direct .docx downloads (7 Days to Trusting God, The King's Way): review before any preview is shared; never edit Stripe
- [ ] Launch: live Jetpack Newsletter emails new posts by default; publish no posts during switch-over without checking, and confirm the email byline shows Sylvia, not "mosemqute"
- [ ] Launch: remove the street address and town/postcode from the live Contact page (Australia only), with Sylvia's OK
- [ ] Launch: decide whether MailPoet (2 unconfirmed records) stays; Jetpack Newsletter is the real list
- [ ] Do not connect the dev site to Sylvia's WordPress.com account and never import her subscriber export anywhere
- [ ] Visual QA at 390px, tablet, desktop; full `docs/QA-CHECKLIST.md`
- [ ] SEO pass: meta descriptions for every page (her wording), book JSON-LD on book pages
- [ ] Confirm the restoration-help policy for the 3-month period (`SUPPORT-AND-RESTORATION.md`)
- [ ] Create the private Google Drive folder "Sylvia's Dream - Backups (private)"
