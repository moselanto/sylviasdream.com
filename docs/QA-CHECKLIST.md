# QA and acceptance checklist

Run on staging before preview approval, and again on production at launch.

## Shop and downloads
- [ ] Test purchase completes with Stripe (card) and PayPal
- [ ] Buyer receives an email with a private download link that works
- [ ] Download link stops working after the limit / expiry
- [ ] Old public ebook file URLs no longer open the paid files (after buyer transition)
- [ ] Prices and titles match Sylvia's confirmed list

## Giving
- [ ] Existing Stripe subscriptions still active (check Stripe dashboard)
- [ ] Test monthly Dream Partner gift and one-off gift both process and send a receipt
- [ ] `[sd_partner_progress]` shows the correct count

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

## Legal
- [ ] Privacy Policy, Terms of Sale and Refund Policy live and linked in footer and checkout
- [ ] Contact page shows no home street address
