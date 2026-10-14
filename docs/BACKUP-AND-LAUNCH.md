# Backup and launch runbook

Step-by-step for sylviasdream.com once Pimofy Digital has **Administrator** access on WordPress.com and **team member** access in Stripe.
Read together with [`DEPLOYMENT.md`](DEPLOYMENT.md) and [`QA-CHECKLIST.md`](QA-CHECKLIST.md).

> **Where backups live:** a private folder (Sylvia's or Pimofy's Google Drive). **Never commit backups, subscriber lists or Stripe exports to this repo.** They contain readers' and donors' personal data. Only the page inventory and redirect map belong in Git.

---

## 1. Back up (before touching anything, about 1 hour)

### 1.1 Website restore point
- [ ] wordpress.com > the site > **Jetpack > Backup**: confirm today's backup completed.
- [ ] Click **Download backup** and save the zip (files + database) to the private folder.
- [ ] Note the backup date/time here: `__________`. Any later point can also be restored from the **Activity Log**.

### 1.2 Independent content export
- [ ] WP Admin > **Tools > Export > All content** (XML).
- [ ] WP Admin > **Tools > Export > Export media library** (zip).

### 1.3 Subscribers
- [ ] **Subscribers** screen > download CSV (email list; not reliably covered by the site backup).

### 1.4 Inventory (SSH / WP-CLI)
Enable SSH in the site's hosting settings, connect, then run [`scripts/backup-inventory.sh`](../scripts/backup-inventory.sh), or the commands individually:

```bash
wp post list --post_type=page,post --post_status=any \
  --fields=ID,post_title,post_name,post_status,post_modified --format=csv > inventory.csv
wp post list --post_type=attachment --fields=ID,post_title,guid --format=csv > media.csv
wp menu list --format=csv > menus.csv
wp menu item list primary --fields=db_id,title,link --format=csv > menu-primary.csv
wp option get theme_mods_twentytwenty --format=json > theme-mods.json
wp widget list sidebar-1 --format=csv > widgets.csv
wp db export "backup-$(date +%F).sql"
```

No SSH? Screenshot **Pages**, **Posts**, **Appearance > Menus** and **Widgets** instead.

### 1.5 Pages to protect by hand
- [ ] Copy the **Author Support** page content into a document (recently updated by Sylvia; must be kept as is).
- [ ] Screenshot the current menu, Customizer settings and footer so the old look can be rebuilt exactly.

### 1.6 Stripe (read only)
- [ ] **Subscriptions > Export** (CSV). Write down the number of **active** subscriptions: `____`
- [ ] **Customers > Export** (CSV), used later to email past buyers their new links.
- [ ] **Product catalogue > Export** (products and prices).
- [ ] **Payment links**: record every link in a spreadsheet:

| Link name | Price | Product | After-payment redirect page | Active? |
|---|---|---|---|---|
| | | | | |

The redirect column maps each book to its thank-you page (see `sylviasdream-core/inc/protect-downloads.php`).

- [ ] **Do not edit, archive or deactivate anything in Stripe.**

---

## 2. Protect (day 1, live site, no visible change)

- [ ] Zip `sylviasdream-core/` and upload: **Plugins > Add New > Upload Plugin** (or SFTP to `wp-content/plugins/`). Activate.
- [ ] **Settings > Sylvia's Dream**: confirm the protected slugs list matches the payment-link spreadsheet. Leave **Redirects OFF**.
- [ ] Check one thank-you page still opens and its page source contains `noindex`.
- [ ] Check `/sitemap.xml` (and its sub-sitemaps) no longer lists the thank-you pages.
- [ ] Do one test download from a thank-you page to confirm it still works.
- [ ] Google Search Console: request removal of any thank-you URLs already indexed.

## 3. Staging

- [ ] Hosting > **Staging site**: create it.
- [ ] Connect this repo with **GitHub Deployments** to staging:
  - `sylviasdream/` -> `wp-content/themes/sylviasdream`
  - `sylviasdream-child/` -> `wp-content/themes/sylviasdream-child`
  - `sylviasdream-core/` -> `wp-content/plugins/sylviasdream-core`
- [ ] Install WooCommerce, WooCommerce Stripe Gateway and WooCommerce PayPal Payments (free).
- [ ] Use **Stripe test mode keys only** on staging.
- [ ] Build design, shop and Give page; run `QA-CHECKLIST.md`.

## 4. Live-site preparation (no visible change)

- [ ] Deploy theme and plugin **code** to production. **Do not activate** the new theme yet.
- [ ] Create on production **as drafts**: merged book pages, About Sylvia, Give, Privacy / Terms / Refund pages, WooCommerce books with final ebook files, new menus.
- [ ] **Never use "sync staging to production".**

## 5. Switch-over (afternoon Kenya time = night in Adelaide)

- [ ] Fresh Jetpack backup; note the time: `__________`
- [ ] **Appearance > Themes**: Live Preview the child theme, then **Activate**.
- [ ] Publish the drafts; assign the menus and the Home page.
- [ ] **Settings > Sylvia's Dream**: turn **Redirects ON**.
- [ ] Switch WooCommerce payments to **live** Stripe/PayPal keys.
- [ ] One small real purchase, confirm the download email, then refund it.
- [ ] Run `QA-CHECKLIST.md` on production.

## 6. Rollback (if anything breaks)

1. **Appearance > Themes**: reactivate **Twenty Twenty**.
2. **Settings > Sylvia's Dream**: turn **Redirects OFF**.
3. If content is affected: restore the step-5 backup point from the **Activity Log**.

The plugin's protection can stay active during a rollback; it changes nothing visible.

## 7. Stripe check after launch

- [ ] Active subscriptions count equals the number recorded in 1.6.
- [ ] Open two old payment links and confirm they still reach their thank-you pages.
- [ ] Email past buyers (from the Customers export) their new secure download links.
- [ ] After the agreed transition period, retire the old public ebook files and confirm their URLs no longer open.
