# Sylvia's Dream - sylviasdream.com

![Parent theme](https://img.shields.io/badge/Parent%20theme-v1.0.1-8A6216)
![Child theme](https://img.shields.io/badge/Child%20theme-v1.22.0-B8862B)
![Core plugin](https://img.shields.io/badge/Core%20plugin-v0.4.4-2B2620)
![Elementor](https://img.shields.io/badge/Elementor-optional%2C%2026%20widgets-92003B)

Last updated: 29 October 2026. 1.21.1 fixes the 1.21.0 Elementor bugs (blank buttons, narrow pages). 1.21.0 adds optional Elementor editing: see `docs/ELEMENTOR-EDITING-GUIDE.md` (for Sylvia) and `ARCHITECTURE.md` section 14.

## 1. What this project is

A rebuild of **sylviasdream.com**, the website of **Sylvia Suhr**, Christian author, speaker and missionary (Australia), and of her Kenya ministry **Heaven on Earth**. The new site has a custom WordPress theme, a WooCommerce book shop with secure ebook downloads, a Dream Partner giving page, content clean-up and SEO.

| | |
|---|---|
| Client | Sylvia Suhr (Sylvia's Dream) |
| Supplier | Pimofy Digital (Moses Munyi) |
| Quotation | PMF-QT-2026-0106, revised v2, 17 October 2026, fixed price USD 280 (deposit USD 112, balance USD 168 after approval) |
| Dev site | https://topnotchmall.co.ke/sylviasdream/ (admin `/sylviasdream/wp-admin/`), password-gated, hidden from search. All build work happens here. |
| Live site | https://sylviasdream.com on the **WordPress.com Personal plan**. Untouched until Sylvia approves the dev site in writing. Still runs Twenty Twenty. |

Status: **dev build, pre-launch.** Migration to the live site happens only after Sylvia reviews and approves the dev site ([`docs/MIGRATION-PLAN.md`](docs/MIGRATION-PLAN.md)). All 25 WooCommerce products on dev are **Draft** and stay Draft until launch.

**Taking over?** Read [`HANDOFF.md`](HANDOFF.md) first, then [`AGENTS.md`](AGENTS.md). The working rules in AGENTS.md are mandatory.

## 2. Packages and versions

| Package | Folder | Version in repo | Role |
|---|---|---|---|
| Sylvia's Dream Base (parent theme) | [`sylviasdream/`](sylviasdream) | 1.0.1 | Minimal shell: document structure, menus, template fallbacks. Never activated on its own. |
| Sylvia's Dream (child theme) | [`sylviasdream-child/`](sylviasdream-child) | 1.17.0 | **The theme to activate.** All design: templates, CSS, patterns, navigation, WooCommerce styling, performance layer. |
| Sylvia's Dream Core (plugin) | [`sylviasdream-core/`](sylviasdream-core) | 0.4.1 | Behaviour that must survive a theme change: download-page protection, redirects, structured data, social sharing tags, security hardening, branded sign-in screens, two-factor authentication, admin branding, reviewer preview mode, Dream Partner progress. |

Versions are defined in two places each and must match:

- Child theme: `Version:` in `sylviasdream-child/style.css` and `SYLVIASDREAM_CHILD_VERSION` in `sylviasdream-child/functions.php` (checked by `scripts/check-theme.js`).
- Core plugin: `Version:` in the header of `sylviasdream-core/sylviasdream-core.php`, `SD_CORE_VERSION` in the same file, and `Stable tag` in `sylviasdream-core/readme.txt`.

Deployment state (from `HANDOFF.md`): child 1.17.0 and core 0.3.0 zips are in the Town library collection `projects/sylviasdream` and are **not yet uploaded to dev**. Dev was last on child 1.16.3 and core 0.2.1. History: [`CHANGELOG.md`](CHANGELOG.md). Core 0.4.0 (sign-in screens, two-factor authentication, admin branding) is built as `sylviasdream-core-0.4.0.zip` in the same collection and not yet uploaded to dev. Core 0.4.1 (QR code on the two-factor setup screen) is committed to main; zip not built yet.

### Two-factor authentication (core 0.4.0)

1. Each person signs in, opens **Users > Profile**, scrolls to **Two-factor authentication** and presses **Set up two-factor authentication**.
2. In an authenticator app (Google Authenticator, Microsoft Authenticator, Authy or similar) they add an account by scanning the QR code shown (core 0.4.1, drawn on the site itself), or with the key shown (time based, 6 digits), or tap the link on the phone itself.
3. They type the 6-digit code and press **Turn on two-factor authentication**. Eight recovery codes are shown once: save them.
4. From then on, after the password the sign-in asks for the code (or a recovery code).
5. Turning it off needs the password and a code. An administrator can turn it off for another user from that user's profile.
6. Emergency (everyone locked out): add `define( 'SD_2FA_DISABLE', true );` to `wp-config.php`, sign in, fix the account, remove the line.

All new controls can be switched off with `SD_SECURITY_DISABLE` keys `login_brand`, `admin_brand`, `two_factor`, `login_errors`, `app_passwords`, `session_cleanup` (see [ARCHITECTURE.md](ARCHITECTURE.md) section 7.1).

## 3. Repository layout

| Path | Contents |
|---|---|
| `sylviasdream/` | Parent theme shell |
| `sylviasdream-child/` | Child theme: `functions.php`, templates (`header.php`, `footer.php`, `front-page.php`, `page.php`, `single.php`, `archive.php`, `search.php`, `404.php`, `woocommerce.php`, `template-full-width.php`, `template-canvas.php`), `inc/` modules, `assets/css`, `assets/js`, `assets/images`, `patterns/`, `template-parts/`, `theme.json`, `content/blocks/*.html` (page layouts as built on dev) |
| `sylviasdream-core/` | Core plugin: `sylviasdream-core.php`, `inc/*.php`, `assets/` (`og-default.jpg`, `login-photo.webp`, `login.css`, `admin.css`, `admin-colors.css`), `readme.txt` |
| `content/live/` | Launch content pack: copies of `content/blocks/` with every dev address rewritten for sylviasdream.com ([README](content/live/README.md)) |
| `scripts/` | `check-theme.js` (quality gate), `build-assets.js` (minifier), `build-theme-zip.sh` (release zips), `backup-inventory.sh` (read-only WP-CLI inventory, local copy only) |
| `docs/` | Runbooks, audits, checklists, migration plan |
| `project/` | Everything agreed with the client: brief, decisions, quotation, catalogue, open items, progress log |
| `prototype/` | Single-file design prototype (`index.html`) and `verify.js` |
| `.github/` | `workflows/checks.yml` (PHP lint 8.0-8.3, theme check) and `dependabot.yml` (weekly GitHub Actions updates) |

## 4. Requirements for building

- Node.js (CI uses Node 20) for `check-theme.js` and `build-assets.js`. No npm packages are needed.
- Bash and `zip` for `build-theme-zip.sh`.
- PHP 8.0 or later to run `php -l` locally if available. There is no PHP on the dev cPanel account or in the Town sandbox; GitHub Actions lints every PHP file on each push.
- WordPress 6.4+ and PHP 8.0+ on the server (theme and plugin headers).

## 5. Build the release zips

```bash
node scripts/check-theme.js        # quality gate: JSON, block markup, "ad-" class rule, version match
php -l <each changed .php file>    # where PHP is available; CI runs it on 8.0-8.3 anyway
bash scripts/build-theme-zip.sh    # runs check-theme.js and build-assets.js, then writes the zips
```

`build-theme-zip.sh` writes:

- `dist/sylviasdream-child-<version>.zip` (includes the generated `.min.css` / `.min.js` files)
- `dist/sylviasdream-core-<version>.zip`

`dist/`, `*.zip` and the `.min.*` files are git-ignored. The parent theme is not zipped by the script; zip the `sylviasdream/` folder by hand if it needs reinstalling.

## 6. Install and deploy to the dev site

Dev site: https://topnotchmall.co.ke/sylviasdream/ (cPanel at `https://cpanel.topnotchmall.co.ke/`).

**Option A: WP Admin upload (usual method)**

1. Build the zips (section 5).
2. Child theme: Appearance > Themes > Add New > Upload > choose the zip > **Replace active with uploaded**.
3. Core plugin: Plugins > Add New > Upload Plugin > choose the zip > replace the current version.
4. If a page layout in `sylviasdream-child/content/blocks/` changed, paste it into the Code editor of the matching dev page (old content stays in Revisions).

**Option B: cPanel Git**

1. cPanel > Git Version Control > repository `/home/topnotch/repositories/sylviasdream.com` > **Update from Remote** (pulls `main`).
2. Copy folders with File Manager (there is no Terminal on this account) into `/home/topnotch/public_html/sylviasdream/wp-content/themes/` (`sylviasdream`, `sylviasdream-child`) and `/wp-content/plugins/sylviasdream-core`.
3. Git does not contain the `.min.*` files; without them the theme loads the readable source files, which is safe.

**Dev-only configuration.** Add the reviewer preview constant to the dev `wp-config.php` only (see [`docs/wp-config-sample-additions.php`](docs/wp-config-sample-additions.php)):

```php
define( 'SD_PREVIEW_MODE', true ); // dev only, never on live
```

**Smoke test after every deploy:** load Home, the Books page (`/shop-for-books/`), Contact and a 404 URL. If a page shows a critical error, re-upload the previous version. Then run the relevant items in [`docs/QA-CHECKLIST.md`](docs/QA-CHECKLIST.md) and [`docs/LAUNCH-QA-CHECKLIST.md`](docs/LAUNCH-QA-CHECKLIST.md).

## 7. Migration to the live site

The live site moves only after Sylvia's written approval. The Personal plan has no staging, SFTP/SSH, GitHub Deployments or one-click restore, so every live step is either invisible preparation or part of one switch-over window. Full plan: [`docs/MIGRATION-PLAN.md`](docs/MIGRATION-PLAN.md); tick-box runbook: [`docs/BACKUP-AND-LAUNCH.md`](docs/BACKUP-AND-LAUNCH.md).

| Phase | What happens |
|---|---|
| A. Dev only | Finish the build; build the launch content pack in `content/live/`; export products (Draft); Sylvia reviews and approves in writing. |
| B. Live, invisible preparation | Full manual backup set; upload and activate the core plugin with **Redirects OFF**; upload both themes without activating; upload missing media; create new pages as Drafts; WooCommerce close to launch with its pages set to Draft; products imported as Draft; new menu built but not assigned; private Live Preview for Sylvia. |
| C. Switch-over window (about 3 hours) | Fresh backup set; activate child theme; assign menus; paste `content/live/` into each existing page (URLs unchanged), filling every `#KEEP-LIVE-*` button from the same live page; set Home and Journal in Settings > Reading; **Redirects ON**; publish products; one small real purchase then refund; click-test every Stripe and Dream Partner link; run QA. |
| D. After launch | Past buyers get new secure links; Sylvia confirms the subscription count is unchanged; restoration test before handover; delete the dev copy after handover. |

Rollback: reactivate Twenty Twenty, Redirects OFF, reassign "Top Menu", reset Settings > Reading. Never touch Stripe during a rollback.

Rules that apply throughout: every existing Stripe payment link, Dream Partner link, thank-you/download page and subscription keeps working exactly as now ([`project/DECISIONS.md`](project/DECISIONS.md), 26 October). Do not publish or re-publish posts during switch-over (Jetpack Newsletter emails new posts to subscribers).

## 8. Quality gate and CI

- `node scripts/check-theme.js`: every `*.json` in the three packages parses; block comments in `patterns/*.php` and `content/blocks/*.html` are balanced, nested and have valid attribute JSON; no CSS class starts with `ad-`; child `style.css` version equals `SYLVIASDREAM_CHILD_VERSION`.
- `.github/workflows/checks.yml` (push to `main`, pull requests, manual): `php -l` on every PHP file under PHP 8.0, 8.1, 8.2 and 8.3; `node scripts/check-theme.js` on Node 20.
- `.github/dependabot.yml`: weekly update PRs for GitHub Actions.

## 9. Hosting facts

- Live: WordPress.com Personal (agreed with Sylvia on 17 October 2026; no upgrade, no migration of host). Plugins, including WooCommerce, run on all paid WordPress.com plans since April 2026. Domain registered with WordPress.com, paid to 25 November 2027. Personal plan renews 29 October 2026.
- Dev: cPanel hosting on topnotchmall.co.ke. Site Health reports no page cache and about 4.2 s server response time on this host. The 1-3 s page-load target needs page caching on the final host (see [`ARCHITECTURE.md`](ARCHITECTURE.md) section 9).

## 10. Documentation index

### Root

| File | Purpose |
|---|---|
| [`HANDOFF.md`](HANDOFF.md) | Start here: current status, priorities, what must not happen |
| [`AGENTS.md`](AGENTS.md) | Mandatory rules for developers and AI agents |
| [`ARCHITECTURE-ESSENTIALS.md`](ARCHITECTURE-ESSENTIALS.md) | One-page architecture |
| [`ARCHITECTURE.md`](ARCHITECTURE.md) | Full architecture: components, hooks, data flow, settings, filters and constants |
| [`PRD.md`](PRD.md) | Product requirements, acceptance criteria, scope, open items |
| [`SECURITY.md`](SECURITY.md) | Vulnerability reporting policy |
| [`SECURITY-AUDIT.md`](SECURITY-AUDIT.md) | Security audit: findings, controls, OWASP checklist, residual risks |
| [`CONTRIBUTING.md`](CONTRIBUTING.md) | Workflow, build and check, coding standards, releasing |
| [`CHANGELOG.md`](CHANGELOG.md) | Release history |

### docs/

| File | Purpose |
|---|---|
| [`docs/MIGRATION-PLAN.md`](docs/MIGRATION-PLAN.md) | How the finished dev site moves to sylviasdream.com |
| [`docs/BACKUP-AND-LAUNCH.md`](docs/BACKUP-AND-LAUNCH.md) | Tick-box backup, protection, switch-over, rollback and restoration test |
| [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) | Deployment on the Personal plan (older parts superseded by the payments rule) |
| [`docs/LAUNCH-QA-CHECKLIST.md`](docs/LAUNCH-QA-CHECKLIST.md) | Launch QA checklist |
| [`docs/QA-CHECKLIST.md`](docs/QA-CHECKLIST.md) | Acceptance checks before the balance is due |
| [`docs/PHONE-QA-v4-NAV.md`](docs/PHONE-QA-v4-NAV.md) | Real-phone navigation checklist |
| [`docs/REDIRECT-MAP.md`](docs/REDIRECT-MAP.md) | Content audit: keep, merge, redirect, protect |
| [`docs/LEGACY-LINK-MAP.md`](docs/LEGACY-LINK-MAP.md) | Old Stripe and .docx links changed on dev only (not repeated on live) |
| [`docs/BUYER-TRANSITION-PLAN.md`](docs/BUYER-TRANSITION-PLAN.md) | Past buyers and protected downloads (Phase 2 cancelled by the payments rule) |
| [`docs/CONTENT-PARITY-AUDIT.md`](docs/CONTENT-PARITY-AUDIT.md) | Content restored from live |
| [`docs/ALT-TEXT-AND-SEO-AUDIT.md`](docs/ALT-TEXT-AND-SEO-AUDIT.md) | Alt text and SEO audit |
| [`docs/EDITING-GUIDE.md`](docs/EDITING-GUIDE.md) | Plain-English editing guide for Sylvia |
| [`docs/CLIENT-CHECKLIST.md`](docs/CLIENT-CHECKLIST.md) | Access and content needed from Sylvia |
| [`docs/KNOWN-LIMITATIONS.md`](docs/KNOWN-LIMITATIONS.md) | What does not work yet on dev and why |
| [`docs/CPANEL-PREVIEW.md`](docs/CPANEL-PREVIEW.md) | Guide to the private cPanel preview site |
| [`docs/HOST-CPANEL-LICENCE.md`](docs/HOST-CPANEL-LICENCE.md) | Dev host licence issue (fixed 26 October) |
| [`docs/NEXT-BUILD-1.1.0.md`](docs/NEXT-BUILD-1.1.0.md) | Historical brief for theme 1.1.0 |
| [`docs/REDESIGN-HANDOFF.docx`](docs/REDESIGN-HANDOFF.docx) | Design direction, acceptance criteria, integration matrix |
| [`docs/hoe-segments/README.md`](docs/hoe-segments/README.md) | Heaven on Earth page in paste segments |
| [`docs/wp-config-sample-additions.php`](docs/wp-config-sample-additions.php) | Optional `wp-config.php` constants (documentation only, not loaded) |

### project/

| File | Purpose |
|---|---|
| [`project/README.md`](project/README.md) | Index of client agreements |
| [`project/PROJECT-BRIEF.md`](project/PROJECT-BRIEF.md) | Scope source of truth |
| [`project/DECISIONS.md`](project/DECISIONS.md) | Decision log (newest first) |
| [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md) | Open items: waiting on Sylvia, Pimofy, launch |
| [`project/PROGRESS-LOG.md`](project/PROGRESS-LOG.md) | Day-by-day work log |
| [`project/QUOTATION.md`](project/QUOTATION.md) | Quotation PMF-QT-2026-0106 v2 |
| [`project/STRIPE-DATA-REQUEST.md`](project/STRIPE-DATA-REQUEST.md) | Minimum Stripe data Sylvia exports herself |
| [`project/SUPPORT-AND-RESTORATION.md`](project/SUPPORT-AND-RESTORATION.md) | Support terms and restoration test |
| [`project/BOOK-CATALOGUE.md`](project/BOOK-CATALOGUE.md), [`project/AMAZON-CATALOGUE.md`](project/AMAZON-CATALOGUE.md), [`project/AMAZON-DESCRIPTIONS.md`](project/AMAZON-DESCRIPTIONS.md) | Book catalogue and Amazon source data |
| [`project/WEBSITE-AUDIT.md`](project/WEBSITE-AUDIT.md) | Audit of the original site |
| [`project/WORKFLOW.md`](project/WORKFLOW.md), [`project/CALL-GUIDE.md`](project/CALL-GUIDE.md), [`project/CORRESPONDENCE.md`](project/CORRESPONDENCE.md) | Workflow, call guide, correspondence record |
| [`project/PROTOTYPE-REVIEW-v4.docx`](project/PROTOTYPE-REVIEW-v4.docx) | Prototype v4 review |

### Other

| File | Purpose |
|---|---|
| [`content/live/README.md`](content/live/README.md) | Launch content pack: rules, `#KEEP-LIVE-*` markers, images to upload |
| [`sylviasdream-child/README.md`](sylviasdream-child/README.md) | Child theme notes |
| [`sylviasdream-core/readme.txt`](sylviasdream-core/readme.txt) | Core plugin readme |
| [`prototype/index.html`](prototype/index.html) | Design prototype; `node prototype/verify.js` checks every route |
