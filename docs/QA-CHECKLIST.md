# QA and acceptance checklist

Run on staging before preview approval, and again on production at launch.

## Shop and downloads
- [ ] Test purchase completes with Stripe (card) and PayPal
- [ ] Buyer receives an email with a private download link that works
- [ ] Download link stops working after the limit / expiry
- [ ] Old public ebook file URLs no longer open the paid files (after buyer transition)
- [ ] Prices and titles match Sylvia's confirmed list
- [ ] Real low-value purchase on production shows **no WordPress.com fee** (only Stripe/PayPal fees), then refunded
- [ ] Past buyers' new secure links work

## Past buyers and protected downloads (`BUYER-TRANSITION-PLAN.md`)
Before launch (test mode, private copy, then live):
- [ ] Ebook files are uploaded to each product (stored in `woocommerce_uploads`), not linked from the public Media Library
- [ ] Download settings: Force downloads; access granted after payment; download limit and expiry set to the numbers Sylvia approved
- [ ] **Signed-out direct file address refused:** opening the file's direct `woocommerce_uploads` address in a private (signed-out) window does not download the file. Required on WordPress.com hosting, not assumed
- [ ] **Expired link refused:** after the download limit is used up or the expiry date passes, the emailed link no longer downloads the file

Launch day:
- [ ] **Legacy test order email link works:** a zero-price manual order (note "Legacy Stripe purchase", status Completed) for a test email address sends the Completed order email, and its private link downloads the right book
- [ ] Each past buyer's legacy order is created and their email sent (or sent by Sylvia); count matches the buyer list
- [ ] **Old thank-you / download pages still work** during the 30 days: each protected page opens, shows `noindex`, is absent from `/sitemap.xml` and site search

30 days after launch:
- [ ] Sylvia confirms no old book payment link is still active (giving links untouched)
- [ ] **Old thank-you / download pages redirect** to My account > Downloads (book pages only; giving thank-you pages unchanged)
- [ ] Old public `.docx` / ebook file addresses return 404 after the files are retired, and copies are in the private backup
- [ ] A past buyer's new secure link still works after the old files are removed

## Giving
- [ ] Existing Stripe subscriptions still active (check Stripe dashboard)
- [ ] Test monthly Dream Partner gift and one-off gift both process and send a receipt
- [ ] `[sd_partner_progress]` shows the correct count
- [ ] Giving levels are $5, $15, $30 and $50 monthly, plus a "choose another amount" option
- [ ] Heaven on Earth purchase wording matches the version Sylvia approved

## Client approvals
- [ ] Only books and resources Sylvia approved are offered free (check the book-writing guide)
- [ ] Biography and ministry information match Sylvia's reviewed version
- [ ] Location shows "Australia" only, with no town or street address

## Structure and SEO
- [ ] Main menu has 6-7 items; Home opens the home page
- [ ] Every old duplicate page 301-redirects to the right page
- [ ] Thank-you pages show `noindex` and are absent from `/sitemap.xml`
- [ ] Every page has its own title and description
- [ ] JSON-LD validates (Rich Results Test) on home and one book page
- [ ] Author Support page content unchanged

## Accessibility and design
- [ ] Warm gold style; body text at least 19px
- [ ] One h1 per page; visible focus; 48px buttons
- [ ] Alt text on all images on key pages
- [ ] Mobile menu works by touch and keyboard (Escape closes)
- [ ] Phone, tablet and desktop in Chrome, Safari, Firefox and Edge

## Backups and restoration (before handover)
- [ ] Launch-day backup set complete in the private folder (content, media, subscribers, settings, products, orders)
- [ ] Restoration test passed on a private copy: site, shop, settings, orders and download access (see `../project/SUPPORT-AND-RESTORATION.md`)
- [ ] Result shared with Sylvia; restore steps and time recorded in the handover guide
- [ ] No Stripe exports, subscriber lists or backups committed to this repo

## Legal
- [ ] Privacy Policy, Terms of Sale and Refund Policy live and linked in footer and checkout
- [ ] Contact page shows no home street address
