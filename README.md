# Sylvia's Dream - sylviasdream.com

![Parent theme](https://img.shields.io/badge/Parent%20theme-v1.0.1-8A6216)
![Child theme](https://img.shields.io/badge/Child%20theme-v1.6.2-B8862B)
![Core plugin](https://img.shields.io/badge/Core%20plugin-v0.1.2-2B2620)

Website for **Sylvia Suhr**, Christian author, speaker and missionary (Australia), and her Kenya ministry **Heaven on Earth**. Built and maintained by **Pimofy Digital** (quotation PMF-QT-2026-0106).

> Status: **dev build, pre-launch.** Child theme 1.6.2 runs on the private dev copy (topnotchmall.co.ke/sylviasdream). Nothing is live yet; the live site still runs Twenty Twenty. Launch waits on Sylvia's book prices and files, Stripe set-up and approvals ([docs/KNOWN-LIMITATIONS.md](docs/KNOWN-LIMITATIONS.md)).

## Taking over this project?

Start with [`HANDOFF.md`](HANDOFF.md): current status, next steps in order, and what must not happen. Then [`project/README.md`](project/README.md) for everything agreed with the client.

## Packages

| Package | Folder | Version | Role |
|---|---|---|---|
| Sylvia's Dream Base (parent theme) | [`sylviasdream/`](sylviasdream) | 1.0.1 | Minimal shell. Never activated on its own. |
| Sylvia's Dream (child theme) | [`sylviasdream-child/`](sylviasdream-child) | 1.6.2 | **Activate this one.** Prototype v4 design: Books mega menu, phone bottom bar, page header band, Home sections, Ministry/About/Contact layouts, WooCommerce styling. |
| Sylvia's Dream Core (plugin) | [`sylviasdream-core/`](sylviasdream-core) | 0.1.2 | Download-page protection, redirects, structured data, Dream Partner progress. |

## Quality gate

```bash
node scripts/check-theme.js       # theme.json, block markup, repo rules
bash scripts/build-theme-zip.sh   # gate + dist/ zips for theme and plugin
```

GitHub Actions lints every PHP file on PHP 8.0-8.3 and runs the theme check on each push; Dependabot keeps the workflow actions current.

## Hosting

WordPress.com **Personal** plan (agreed with Sylvia, 17 October 2026). No upgrade, no migration. Plugins, including WooCommerce, run on all paid plans since April 2026.
Personal has **no staging site, no SFTP/SSH, no GitHub Deployments and no one-click restore**. The WordPress.com 8% fee applies only to its own Payments/Donations blocks, which this build does not use; a real test purchase confirms fees before launch.

## How work reaches the site

See [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md). In short: build and test on a private **local copy**, upload theme and plugin zips through WP Admin, prepare content as **drafts**, then switch over in one short window. Backups are taken by hand before each major step.

## Project folder

[`project/`](project/README.md) holds everything agreed with Sylvia: brief, decision log, quotation and audit text, book catalogue and Amazon check, the minimum Stripe data, support and restoration terms, the 11-phase workflow, call guide, correspondence and open items.

## Docs

| File | Purpose |
|---|---|
| [`HANDOFF.md`](HANDOFF.md) | Start here: status, next steps, must-not-do list |
| [`ARCHITECTURE-ESSENTIALS.md`](ARCHITECTURE-ESSENTIALS.md) | One-page architecture: stack, where things are, release, never-do |
| [`ARCHITECTURE.md`](ARCHITECTURE.md) | Full architecture: layers, modules, request flows, design system, build and release |
| [`PRD.md`](PRD.md) | Product requirements: purpose, audiences, functional and non-functional requirements |
| [`SECURITY.md`](SECURITY.md) / [`SECURITY-AUDIT.md`](SECURITY-AUDIT.md) | Reporting policy; attack surface, findings, controls, residual risks |
| [`CONTRIBUTING.md`](CONTRIBUTING.md) | Workflow, build and check, coding standards, releasing |
| [`docs/KNOWN-LIMITATIONS.md`](docs/KNOWN-LIMITATIONS.md) | What does not work yet on dev, why, and when it resolves |
| [`docs/PHONE-QA-v4-NAV.md`](docs/PHONE-QA-v4-NAV.md) | Real-phone checklist for navigation |
| [`docs/BACKUP-AND-LAUNCH.md`](docs/BACKUP-AND-LAUNCH.md) | Step-by-step checklist (Personal plan): manual backups, day-1 protection, local build, preview, switch-over, rollback, post-launch, restoration test |
| [`scripts/backup-inventory.sh`](scripts/backup-inventory.sh) | Read-only WP-CLI inventory, for the local copy only (no WP-CLI on Personal) |
| [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) | Local build, WP Admin uploads, drafts, launch day and rollback |
| [`docs/CLIENT-CHECKLIST.md`](docs/CLIENT-CHECKLIST.md) | Access and content needed from Sylvia |
| [`docs/REDIRECT-MAP.md`](docs/REDIRECT-MAP.md) | Content audit: keep, merge, redirect, protect |
| [`docs/BUYER-TRANSITION-PLAN.md`](docs/BUYER-TRANSITION-PLAN.md) | Moving past buyers to WooCommerce protected downloads; retiring public files |
| [`docs/LEGACY-LINK-MAP.md`](docs/LEGACY-LINK-MAP.md) | Old Stripe and .docx links: what was replaced, kept and why |
| [`docs/EDITING-GUIDE.md`](docs/EDITING-GUIDE.md) | Plain-English editing guide for Sylvia |
| [`docs/QA-CHECKLIST.md`](docs/QA-CHECKLIST.md) | Acceptance checks before the balance is due |
| [`CHANGELOG.md`](CHANGELOG.md) | Release history |

## Agreed with the client

- Keep the domain, WordPress.com Personal plan, blog posts, subscribers and Stripe account. USD 280 fixed (deposit USD 112, balance USD 168).
- No Stripe team access: Sylvia exports only the minimum data (see `project/STRIPE-DATA-REQUEST.md`).
- Existing buyers keep access to their ebooks; existing Stripe giving subscriptions are not touched.
- Keep the recently updated **Author Support** page content as it is.
- Keep the warm gold style, with large, readable text and straightforward navigation.
- Balance is due after Sylvia approves the preview, including a successful test purchase, secure download and test gift.
- Restoration test before handover (see `project/SUPPORT-AND-RESTORATION.md`).

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

[`prototype/index.html`](prototype/index.html) is a single-file design preview (v3). Open it in a browser; the **For Sylvia's review** page (`#/for-sylvia`) lists every change and open question. Run `node prototype/verify.js` (from any folder) to render every route and check for broken internal links.
