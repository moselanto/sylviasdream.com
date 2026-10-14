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
| [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) | Staging, GitHub deployment, launch day and rollback |
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
