# Past buyers and protected downloads: transition plan

Status: proposed 23 October 2026, not started. Owner: Pimofy (Moses). Nothing here touches Stripe: every Stripe change is made by Sylvia herself.

## The problem
- Past ebook buyers paid through Stripe payment links. After payment, Stripe sends them to a thank-you / download page (the protected slugs in Settings > Sylvia's Dream). Those pages link straight to files in the Media Library (`/wp-content/uploads/...docx`).
- Anyone with a file's direct address can download it. Removing a link does not protect the file.
- While an old payment link is still active, new buyers can still arrive on these pages. So the pages cannot be removed until Sylvia has switched off or re-pointed the links.

## Goal
Every past buyer gets a working, private download link through WooCommerce. New buyers only use the WooCommerce shop. The old public files stop working once the transition period ends.

## Phase 0: before launch (preview period)
1. **Inventory** (no personal data in Git): for each thank-you / download page, record the book and the file it serves. Record each file's address and whether it is linked anywhere else (`docs/LEGACY-LINK-MAP.md`). Keep copies of every file in the private backup folder.
2. **Final ebook files from Sylvia**: one file per book in the format she wants to sell (EPUB/PDF preferred over .docx). Never reuse the old public Media Library URL as the product file.
3. **WooCommerce products** (still Draft): tick Virtual + Downloadable and upload the file from the product screen, so it is stored in `wp-content/uploads/woocommerce_uploads/`, not the public Media Library.
4. **WooCommerce > Settings > Products > Downloads**:
   - File download method: **Force downloads** (the file address is never shown).
   - Access restriction: **Downloads require login** OFF (buyers without an account must still be able to download from the order email). Grant access after payment: ON.
   - Each product: Download limit **5**, Download expiry **30 days** (buyers can always ask for a reset; Sylvia approves the numbers).
5. **Protection test** on a private copy and again on live before launch:
   - Complete a test-mode purchase. The email link works; the file opens.
   - Copy the link and open it in a private window after the limit or expiry is reached: it must be refused.
   - Try to open the file's direct `woocommerce_uploads` address signed out: it must be refused. WordPress.com hosting may not apply the usual Apache `.htaccess` protection, so this test is required, not assumed. If it fails, store files outside `woocommerce_uploads` is not possible on Personal; use Force downloads plus randomised file names and report the result to Moses.
6. **Buyer list from Sylvia** (per `project/STRIPE-DATA-REQUEST.md`): name, email, book. It is kept only in the private Drive folder, never in Git or on the dev site.

## Phase 1: launch day
1. Publish the products. Run the test purchase on live (QA checklist).
2. **Give each past buyer access through WooCommerce**. For each buyer, create a manual order in WooCommerce > Orders: customer email, the book, price 0, note "Legacy Stripe purchase", status Completed. WooCommerce grants download access and sends the "Completed order" email with the private link. Without creating accounts, buyers simply use the email link.
   - Alternative, if Sylvia prefers that the list never leaves her: Pimofy prepares one email per book with a short explanation, and Sylvia sends it from her own email after the orders are created with her.
3. **Old pages stay working**: keep every thank-you / download page live, noindex and out of search (core plugin, already active), so nobody loses access on the day.
4. **Old book buttons**: replace them on the public pages per `docs/LEGACY-LINK-MAP.md` (with Sylvia's written OK).
5. **Stripe** (Sylvia, with Moses on a call): for each book payment link, Sylvia either deactivates it or changes its after-payment page to the Books page. Dream Partner giving links are not touched.

## Phase 2: 30 days after launch
1. Confirm with Sylvia that no old book payment link is still active (her screenshot is enough).
2. Redirect each old thank-you / download page to My account > Downloads, with a short note page if she prefers. Remove them from the protected list. Giving thank-you pages stay as they are.
3. **Retire the public files**: delete the old `.docx` (and any other ebook) files from the Media Library, after confirming copies are in the private backup. WordPress.com Personal has no SFTP, so this is done from Media > Library.
4. Re-test: each old file address returns 404; each past buyer's new link still works.
5. Delete the buyer list from the shared folder unless Sylvia asks otherwise.

## Things that need Sylvia
- Final ebook files and formats.
- Download limit and expiry numbers.
- The buyer list (or sending the emails herself).
- Written OK to replace the old book buttons, and to deactivate or re-point each book payment link in Stripe.
- Whether the book-writing guide on Free Downloads stays free.

## Checks (add to `QA-CHECKLIST.md` results)
- Past-buyer test order: the email arrives and the link downloads.
- Signed-out direct file address: refused.
- Expired or used-up link: refused.
- Old thank-you page still works during the 30 days; afterwards it redirects.
