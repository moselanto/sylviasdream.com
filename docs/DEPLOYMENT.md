# Deployment

The live site must keep selling and taking gifts throughout. These steps make that possible.

## 0. Before anything

1. Sylvia invites Pimofy Digital as an **Administrator** (Users > Invite). No password sharing.
2. Confirm the plan in **Upgrades > Plans** (must be Business or Commerce).
3. Take a full backup (Jetpack backup / Activity log restore point).
4. Export the page list and every Stripe payment link with its redirect URL.

## 1. Day 1 on production (no visible change)

1. Deploy **only** `sylviasdream-core/` to production and activate it.
2. Settings > Sylvia's Dream: check the protected slugs list. Leave **Redirects off**.
3. Verify: thank-you pages still open, show `noindex`, and are gone from `/sitemap.xml`.
4. Request removal of the thank-you URLs in Google Search Console if already indexed.

## 2. Build on staging

1. Hosting > Staging site: create a staging copy.
2. Connect this repo with **GitHub Deployments** to the staging site:
   - `sylviasdream/` -> `wp-content/themes/sylviasdream`
   - `sylviasdream-child/` -> `wp-content/themes/sylviasdream-child`
   - `sylviasdream-core/` -> `wp-content/plugins/sylviasdream-core`
   (SFTP upload of the same folders works too.)
3. Install WooCommerce + WooCommerce Stripe Gateway + WooCommerce PayPal Payments (free). Use **test mode** only on staging.
4. Build the design, shop and Give page; run `docs/QA-CHECKLIST.md`.

## 3. Prepare production content (as drafts)

- Create merged book pages, About Sylvia, Give, policies and the new menu on **production as drafts**, so live orders, subscribers and the Author Support edits are never overwritten.
- Products: create books in WooCommerce on production as drafts, upload final PDF/EPUB files as downloadable files.
- WooCommerce > Settings > Products > Downloadable: **Force downloads**, require login off, grant access after payment, set a download limit and expiry. Confirm that the file URL cannot be opened directly.

> **Never use "sync staging to production"** for the database. Code only.

## 4. Launch (afternoon Kenya time = night in Adelaide)

1. Deploy themes to production (GitHub Deployments or SFTP).
2. Appearance > Themes: **Live Preview** the child theme, then **Activate**.
3. Publish drafts, set the new menus, set the Home page.
4. Settings > Sylvia's Dream: turn **Redirects on**.
5. Switch payments to live keys; run one real low-value purchase and refund it.
6. Run the full QA checklist.

## 5. After launch

- Email past buyers their new secure links (Stripe customer list), then retire old public files.
- Keep the thank-you pages hidden (not deleted) for the agreed transition period.

## Rollback

- Activate Twenty Twenty again (one click), turn redirects off, or restore the backup from step 0.
