# Deployment (WordPress.com Personal plan)

The live site must keep selling and taking gifts throughout. The site stays on the **Personal** plan (agreed 17 Oct 2026), which has **no staging site, no SFTP/SSH, no GitHub Deployments and no one-click restore**. So: build privately on a local copy, upload code through WP Admin, prepare content as drafts, and switch over in one short window.
For the tick-box version, use [`BACKUP-AND-LAUNCH.md`](BACKUP-AND-LAUNCH.md). Project context: [`../project/`](../project/README.md).

## 0. Before anything
1. Sylvia invites mosemqute@gmail.com as **Administrator**. No passwords shared. No Stripe access (see `../project/STRIPE-DATA-REQUEST.md`).
2. First look, change nothing: confirm **Upgrades > Plans** shows Personal, storage used (6 GB limit), how giving works now, that **Appearance > Themes > Add New > Upload** and **Plugins > Add New > Upload** are available, and where the domain is registered.
3. Take the full backup set (`BACKUP-AND-LAUNCH.md` section 1).

## 1. Day 1 on production (no visible change)
1. Zip `sylviasdream-core/` and upload it in **Plugins > Add New > Upload Plugin**. Activate.
2. Settings > Sylvia's Dream: check the protected slugs list against the payment-link table. Leave **Redirects off**.
3. Verify: thank-you pages still open, show `noindex`, and are gone from `/sitemap.xml`.

## 2. Build privately (local copy)
1. Create a local WordPress site (LocalWP or `wp-env`) and import the backup set (XML, media, Customizer export).
2. Install the themes and plugin from this repo, WooCommerce, WooCommerce Stripe Gateway and WooCommerce PayPal Payments. **Stripe test keys only.**
3. Build the design, shop, Give page and policies. Run `QA-CHECKLIST.md`.
4. Run `node prototype/verify.js` after any prototype change.

## 3. Prepare production (no visible change)
1. Zip and upload `sylviasdream/` and `sylviasdream-child/` in **Appearance > Themes > Add New > Upload**. **Do not activate.**
2. Install WooCommerce and the payment plugins; keep payments disabled or in test mode.
3. Create merged book pages, About Sylvia, Give, policies and new menus **as drafts**. Create books as **draft** WooCommerce products with final PDF/EPUB files.
4. WooCommerce > Settings > Products > Downloadable: Force downloads, grant access after payment, download limit and expiry. Confirm the file URL cannot be opened directly.
5. Preview for Sylvia: **Appearance > Themes > Live Preview** of the child theme on a call, plus draft preview links. Her approval plus test purchase, secure download and test gift trigger the USD 168 balance.

## 4. Launch (Kenya afternoon = evening in Melbourne)
1. Fresh backup set (section 1 of the runbook).
2. Activate the child theme. Publish drafts, set menus and Home page.
3. Settings > Sylvia's Dream: **Redirects on**.
4. Live Stripe/PayPal keys. One real low-value purchase, confirm the download email and that **no WordPress.com fee** was taken, then refund.
5. Full QA checklist on production.

## 5. After launch
- Confirm the active Stripe subscription count is unchanged (Sylvia checks and tells us the number).
- Past ebook buyers get new secure links on launch day (sent by Pimofy, or by Sylvia from her own account).
- Old thank-you pages stay working and hidden for 30 days, then are retired only with Sylvia's written OK; old payment links are re-pointed or switched off with her written OK.

## Rollback
1. Reactivate **Twenty Twenty** (Appearance > Themes).
2. Turn **Redirects off**.
3. Content problems: restore from the launch-day backup set by hand. On Personal this takes hours, not minutes (see `../project/SUPPORT-AND-RESTORATION.md`). Never edit Stripe during a rollback.
