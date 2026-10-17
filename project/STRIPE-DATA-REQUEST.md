# Stripe: minimum data needed

Agreed with Sylvia on 17 October 2026. Pimofy has **no access to her Stripe account**. Sylvia exports only what is listed here, and only into the private Google Drive folder shared between her and Moses. **Never commit any of it to this repo, and never send it by WhatsApp.**

| What | Why it is needed | Personal data? |
|---|---|---|
| Number of **active subscriptions** (a number only) | Confirm after launch that no Dream Partner subscription changed | No |
| **Ebook buyers only:** name, email, book bought | Send past buyers their new secure download links on launch day | Yes. Alternative: Pimofy prepares the email and links, and Sylvia sends it from her own account, so the list never leaves her |
| **Products and prices** (product catalogue) | Match each book to its WooCommerce product and price | No |
| **Payment links:** name, price, after-payment page (screenshots fine) | Keep each old thank-you page working and hidden; plan re-pointing later | No |

**Not needed:** subscriber names or emails, donor or Dream Partner details, payout or bank information, card data.

## Where it is used
- Payment-link table: `../docs/BACKUP-AND-LAUNCH.md` section 1.6 and the protected-slugs list in Settings > Sylvia's Dream (`sylviasdream-core/inc/protect-downloads.php`).
- Active-subscription count: checked again in `../docs/BACKUP-AND-LAUNCH.md` section 8.
- Buyer list: launch-day email only; deleted from the shared folder after the 30-day transition unless Sylvia asks otherwise.

## Connecting Stripe to WooCommerce
Done together with Sylvia on a call when the shop is ready (she signs in and approves the connection herself). Test mode is used for every check before launch.
