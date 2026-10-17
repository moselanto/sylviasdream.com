# Support and restoration

## Support terms (quotation section 09, term 7)
- **3 months after launch, free:** fixes for technical defects within the agreed scope, and minor updates (text, prices, images, adding a new book) up to 1 hour per month.
- **Restoration from backup during the 3 months:** included at no charge (told to Sylvia on 17 Oct 2026; Moses to confirm whether this covers every cause or only defects from our work).
- **After 3 months:** optional maintenance plan USD 20/month, which includes restoration help; without the plan, USD 12/hour. A typical restore takes a few hours (roughly USD 24-48).

## What the backups contain
| Part | How it is backed up on the Personal plan |
|---|---|
| Pages, posts, comments, menus | Tools > Export > All content (XML) |
| Media | Tools > Export > Export media library (zip) |
| Subscribers | Subscribers screen > download CSV |
| Theme and plugins | This repo (code) plus a zip of each installed plugin version list (screenshot of Plugins screen) |
| Settings (Customizer, WooCommerce, plugin settings) | Customizer export plugin + WooCommerce settings screenshots + Settings > Sylvia's Dream screenshot |
| Shop products | WooCommerce > Products > Export (CSV) |
| **Orders and download permissions** | WooCommerce orders export to a re-importable format. WordPress's XML export does not reliably include them. Preferred: a full-site backup plugin that includes the database (for example UpdraftPlus, saved to Sylvia's Google Drive) **after confirming it runs on her plan**. If no full-database tool works, orders are re-imported and download access is regenerated per order (Order > Order actions > Regenerate download permissions) |
| Ebook files | Originals kept in the private Drive folder |

Backups are stored privately in Sylvia's name. Taken before each major step and on launch day.

## Restoration test (before handover) - Sylvia's request, agreed 17 Oct 2026
Restore the latest backup onto a **private copy** (local WordPress, e.g. LocalWP) and tick each item. Share the completed table with Sylvia before handover.

| Check | Result |
|---|---|
| Home, menus, pages and posts look and link correctly | |
| Media library complete, images show | |
| Theme active with Customizer settings (hero, contact, social) | |
| Plugins installed and Settings > Sylvia's Dream values restored | |
| WooCommerce products, prices, downloadable files present | |
| WooCommerce settings (payments in test mode, tax, emails, download settings) | |
| Orders present with correct status and totals | |
| A test customer's **download access** works from My Account and the order email | |
| Subscribers list count matches | |
| Time taken for the full restore (for the handover guide) | |

If any check fails: fix the backup method, back up again, and repeat the test. Record the steps that worked in the handover guide.
