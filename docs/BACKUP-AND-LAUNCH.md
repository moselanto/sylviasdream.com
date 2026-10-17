# Backup and launch runbook (Personal plan)

Tick-box steps for sylviasdream.com once Pimofy has **Administrator** access on WordPress.com. Pimofy has **no Stripe access**; Sylvia provides the items in [`../project/STRIPE-DATA-REQUEST.md`](../project/STRIPE-DATA-REQUEST.md).
Read with [`DEPLOYMENT.md`](DEPLOYMENT.md), [`QA-CHECKLIST.md`](QA-CHECKLIST.md) and [`../project/SUPPORT-AND-RESTORATION.md`](../project/SUPPORT-AND-RESTORATION.md).

> **Where backups live:** the private Google Drive folder "Sylvia's Dream - Backups (private)", shared only between Sylvia and Moses. **Never commit backups, subscriber lists or Stripe exports to this repo, and never send them by WhatsApp.**

> **Personal plan:** no Jetpack one-click restore, no Activity Log restore, no SSH/WP-CLI, no staging. Every backup below is taken by hand, and a restore is a manual reinstall that takes hours.

---

## 1. Back up (before touching anything, about 1-2 hours)

Note the date and time: `__________`

### 1.1 Content and media
- [ ] Tools > Export > **All content** (XML).
- [ ] Tools > Export > **Export media library** (zip).

### 1.2 Subscribers
- [ ] **Subscribers** > download CSV. Record the count: `____`

### 1.3 Settings and appearance
- [ ] Screenshots: Pages list, Posts list, Appearance > Menus (each menu), Widgets, Customizer panels, footer, Settings > General/Reading/Discussion.
- [ ] If a Customizer export plugin runs on the plan, export Customizer settings (`.dat`).
- [ ] Copy the **Author Support** page content into a document (must stay exactly as written).
- [ ] Plugins screen screenshot (names and versions).

### 1.4 Full database (if available)
- [ ] Check whether a full-site backup plugin (for example UpdraftPlus) installs and runs on the plan (some backup plugins, such as BackWPup, are not supported on WordPress.com). If yes, back up database + uploads to Sylvia's Google Drive. If not, rely on 1.1-1.3 plus the WooCommerce exports in 1.5 once the shop exists.

### 1.5 Shop (after WooCommerce is installed, and on launch day)
- [ ] WooCommerce > Products > **Export** (CSV).
- [ ] WooCommerce **orders** export in a re-importable format (orders are not reliably in the XML export).
- [ ] Screenshots of WooCommerce settings tabs (General, Products > Downloadable, Payments, Emails).
- [ ] Settings > Sylvia's Dream screenshot.

### 1.6 Stripe (Sylvia exports; Pimofy never edits Stripe)
- [ ] Number of **active subscriptions** (number only): `____`
- [ ] Ebook buyers: name, email, book (or Sylvia sends the launch email herself).
- [ ] Product catalogue (names, prices).
- [ ] Payment links:

| Link name | Price | Product | After-payment page | Active? |
|---|---|---|---|---|
| | | | | |

The after-payment page column feeds the protected slugs (`sylviasdream-core/inc/protect-downloads.php`).

### 1.7 Inventory
[`../scripts/backup-inventory.sh`](../scripts/backup-inventory.sh) needs WP-CLI, which Personal does not offer. Run it only on the **local copy** after importing the backup, to record what the backup contains.

---

## 2. Protect (day 1, live site, no visible change)
- [ ] Upload and activate `sylviasdream-core` (Plugins > Add New > Upload Plugin).
- [ ] Settings > Sylvia's Dream: protected slugs match the payment-link table. **Redirects OFF.**
- [ ] One thank-you page still opens and its source contains `noindex`.
- [ ] `/sitemap.xml` no longer lists thank-you pages.
- [ ] One test download from a thank-you page still works.
- [ ] Google Search Console: request removal of any indexed thank-you URLs.

## 3. Local build
- [ ] Local WordPress (LocalWP or wp-env) with the backup imported.
- [ ] Themes and plugin from this repo; WooCommerce + Stripe Gateway + PayPal Payments; **Stripe test keys only**.
- [ ] Build design, shop, Give page; run `QA-CHECKLIST.md`.

## 4. Live-site preparation (no visible change)
- [ ] Back up again (section 1).
- [ ] Upload both themes; **do not activate**.
- [ ] Create drafts: merged book pages, About Sylvia, Give, Privacy / Terms / Refund, WooCommerce products with final ebook files, new menus.

## 5. Preview and approval
- [ ] Walk Sylvia through Live Preview and draft previews on a call.
- [ ] Test purchase, secure download and test gift pass (test mode).
- [ ] Written approval received; USD 168 balance received.

## 6. Switch-over (Kenya afternoon = evening in Melbourne)
- [ ] Fresh backup set (section 1, including 1.5). Time: `__________`
- [ ] Activate the child theme. Publish drafts; assign menus and Home page.
- [ ] Settings > Sylvia's Dream: **Redirects ON**.
- [ ] WooCommerce payments to **live** keys.
- [ ] One small real purchase; confirm the download email and that no WordPress.com fee was taken; refund it.
- [ ] Run `QA-CHECKLIST.md` on production.

## 7. Rollback
1. Appearance > Themes: reactivate **Twenty Twenty**.
2. Settings > Sylvia's Dream: **Redirects OFF**.
3. If content is affected: restore by hand from the section 6 backup set (XML, media, settings, products, orders). Allow several hours.

## 8. After launch
- [ ] Sylvia confirms the active subscription count equals 1.6.
- [ ] Two old payment links still reach their thank-you pages.
- [ ] Past buyers receive their new secure links (Pimofy or Sylvia sends).
- [ ] After 30 days, and only with Sylvia's written OK: retire old public ebook files; re-point or switch off old payment links.

## 9. Restoration test (before handover)
- [ ] Restore the latest backup set onto a fresh private local copy.
- [ ] Complete the checklist in [`../project/SUPPORT-AND-RESTORATION.md`](../project/SUPPORT-AND-RESTORATION.md) (site, shop, settings, orders, download access).
- [ ] Share the result with Sylvia before handover; record the steps and time taken in the handover guide.
