# Sylvia's Dream - sylviasdream.com

![Parent theme](https://img.shields.io/badge/Parent%20theme-v1.0.0-8A6216)
![Child theme](https://img.shields.io/badge/Child%20theme-v0.1.0-B8862B)
![Core plugin](https://img.shields.io/badge/Core%20plugin-v0.1.0-2B2620)

Website for **Sylvia Suhr**, Christian author, speaker and missionary (South Australia), and her Kenya ministry **Heaven on Earth**. Built and maintained by **Pimofy Digital** (quotation PMF-QT-2026-0106).

> Status: **scaffold**. Nothing here is live yet. The live site still runs the WordPress.com Twenty Twenty theme.

## Packages

| Package | Folder | Version | Role |
|---|---|---|---|
| Sylvia's Dream Base (parent theme) | [`sylviasdream/`](sylviasdream) | 1.0.0 | Minimal shell. Never activated on its own. |
| Sylvia's Dream (child theme) | [`sylviasdream-child/`](sylviasdream-child) | 0.1.0 | **Activate this one.** Warm gold design, large text, simple menu, home, shop wrapper, footer. |
| Sylvia's Dream Core (plugin) | [`sylviasdream-core/`](sylviasdream-core) | 0.1.0 | Download-page protection, redirects, structured data, Dream Partner progress. |

## Hosting

WordPress.com (plugin-capable plan: Business or Commerce; confirm in Upgrades > Plans). **Not cPanel.**
Custom themes and plugins are allowed, with SFTP/SSH, staging sites and GitHub Deployments.

## How work reaches the site

See [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md). In short: build and test on the WordPress.com **staging** site, deploy only **code** to production, never sync the staging database over production.

## Docs

| File | Purpose |
|---|---|
| [`docs/BACKUP-AND-LAUNCH.md`](docs/BACKUP-AND-LAUNCH.md) | Step-by-step checklist (Personal plan): manual backups, day-1 protection, local build, preview, switch-over, rollback, post-launch, restoration test |
| [`scripts/backup-inventory.sh`](scripts/backup-inventory.sh) | Read-only WP-CLI inventory, for the local copy only (no WP-CLI on Personal) |
| [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) | Local build, WP Admin uploads, drafts, launch day and rollback |
| [`docs/CLIENT-CHECKLIST.md`](docs/CLIENT-CHECKLIST.md) | Access and content needed from Sylvia |
| [`docs/REDIRECT-MAP.md`](docs/REDIRECT-MAP.md) | Content audit: keep, merge, redirect, protect |
| [`docs/QA-CHECKLIST.md`](docs/QA-CHECKLIST.md) | Acceptance checks before the balance is due |
| [`CHANGELOG.md`](CHANGELOG.md) | Release history |

## Agreed with the client

- Keep the domain, WordPress.com plan, blog posts, subscribers and Stripe account.
- Existing buyers keep access to their ebooks; existing Stripe giving subscriptions are not touched.
- Keep the recently updated **Author Support** page content as it is.
- Keep the warm gold style, with large, readable text and straightforward navigation.
- Balance is due after Sylvia approves the preview, including a successful test purchase, secure download and test gift.

## Sylvia's feedback on the prototype (15 October 2026)

- **Complete catalogue.** All current titles appear (25 in prototype v3), including Fields of Becoming, When the River Flows Again and Guard Your Heart Renew Your Mind. The price stays at USD 280.
- **Angelique.** *Angelique: A Journey of Becoming* (novel) and *Angelique Finds Her Light* (children's book) are separate books. Sylvia confirms all titles, subtitles, descriptions, formats and prices.
- **Location.** Show "Australia" only. No town or street address anywhere.
- **Dream Partner giving.** Keep $5, $15, $30 and $50 monthly, plus "choose another amount".
- **Free downloads.** Nothing is listed as free without Sylvia's approval, especially the book-writing guide.
- **Approvals before publishing.** The wording about book purchases supporting Heaven on Earth, the final biography and the ministry information.
- **Author Support.** Keep her recently updated wording exactly as published.
- **Readability.** Large, easy-to-read text, especially on phones.

## Prototype

[`prototype/index.html`](prototype/index.html) is a single-file design preview (v3). Open it in a browser; the **For Sylvia's review** page (`#/for-sylvia`) lists every change and open question. Run `node prototype/verify.js` to render every route and check for broken internal links.
