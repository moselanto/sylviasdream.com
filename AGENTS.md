# AGENTS.md - rules for developers and AI agents

These rules apply to every person and AI agent working on this repository. They are not optional. When a rule here conflicts with an older document, this file and [`project/DECISIONS.md`](project/DECISIONS.md) win.

Project in one line: Pimofy Digital (Moses Munyi) is rebuilding sylviasdream.com for Sylvia Suhr. Dev site https://topnotchmall.co.ke/sylviasdream/; live site sylviasdream.com on the WordPress.com Personal plan (no staging, SFTP/SSH, GitHub Deployments or one-click restore).

## 1. Read in this order before changing anything

1. [`HANDOFF.md`](HANDOFF.md): current status, priorities, things that must not happen.
2. This file.
3. [`project/DECISIONS.md`](project/DECISIONS.md) and [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md).
4. [`ARCHITECTURE-ESSENTIALS.md`](ARCHITECTURE-ESSENTIALS.md), then [`ARCHITECTURE.md`](ARCHITECTURE.md) for the area you will touch.
5. [`docs/MIGRATION-PLAN.md`](docs/MIGRATION-PLAN.md) and [`docs/BACKUP-AND-LAUNCH.md`](docs/BACKUP-AND-LAUNCH.md) before any work that touches the live site.
6. [`SECURITY-AUDIT.md`](SECURITY-AUDIT.md) before changing anything in `sylviasdream-core/inc/security.php`, `preview.php`, `redirects.php` or any output of user content.
7. [`content/live/README.md`](content/live/README.md) before preparing or pasting launch content.

## 2. Content and facts

1. **Never invent facts.** Book details, titles, prices, charity details, figures, reviews, addresses and biography wording come only from Sylvia, the live site, or Amazon (for book titles, subtitles, formats and descriptions where Sylvia has no site text; decision of 26 October). If a fact is missing, leave a visible gap and add it to `project/OPEN-ITEMS.md`.
2. Use **Sylvia's own wording**. Draft wording (biography, home intro, "Every purchase helps Heaven on Earth") needs her written approval before launch.
3. **Nothing is offered free** without Sylvia's written approval.
4. Location is **"Australia" only**. No street, town or postcode anywhere.
5. **Content stays editable in WordPress**: pages and blocks, Customizer settings (`sdc_get()`), menus, WooCommerce products, or Settings > Sylvia's Dream. Never hard-code content in PHP.

## 3. Payments and links

6. **Never touch Stripe.** No Stripe edits, ever, from code or by hand. Pimofy has no Stripe access; Sylvia exports only the data in [`project/STRIPE-DATA-REQUEST.md`](project/STRIPE-DATA-REQUEST.md).
7. **Keep every existing Stripe, Dream Partner and thank-you link working.** Every live book purchase button, Dream Partner / contribution link, after-payment thank-you or download page and subscription keeps working exactly as now, before, during and after migration. No such link is replaced, switched off, redirected or deleted. WooCommerce is an addition, never a replacement. Only exception (decision 31 Oct, Option A): the old publicly accessible paid download files and their thank-you/download pages may be retired, and only after past buyers have new secure links and Sylvia has given her written OK. Stripe links themselves are never changed.
8. **`#KEEP-LIVE-*` markers.** The repo never stores payment-link addresses. In `content/live/` the buttons are marked `#KEEP-LIVE-STRIPE-BUTTON` (book purchases) and `#KEEP-LIVE-DREAM-PARTNER-LINK-5`, `-15`, `-30`, `-50`, `-other`, `-once` (Give page). Before pasting a page on live, copy the existing link **and label** from the same live page into each marked button. After pasting, search the page for `#KEEP-LIVE`: none may remain. After switch-over, click-test every payment and contribution button.
9. Never remove a slug from the protected download list without a decision recorded in `project/DECISIONS.md`. On live the protected thank-you page is `all-shall-be-well`; `all-shall-be-well-2` is the book page.

## 4. Live site and products

10. **No changes to the live site before backups.** Take the full backup set in `docs/BACKUP-AND-LAUNCH.md` section 1 before every live step, and a fresh set on switch-over day.
11. **Migration only after Sylvia's written approval** of the dev site. Until then the live site is read-only.
12. **Products stay Draft until launch.** Never publish a product on dev or live before launch day. The core plugin's preview mode shows drafts to signed-in reviewers without changing their status.
13. `SD_PREVIEW_MODE` is for the **dev site only**. Never define it on live.
14. Redirects stay **OFF** (Settings > Sylvia's Dream) until the switch-over window on launch day.
15. Never sync a local or dev database over production. Deploy code only.
16. Never connect the dev site to Sylvia's WordPress.com account, and never import her subscriber export anywhere.
17. Do not publish or re-publish posts on live during switch-over: Jetpack Newsletter emails new posts to subscribers.

## 5. Communication

18. **Do not mention this repository to Sylvia.**
19. Do not contact Sylvia or send anything to her without Moses's approval. Emails to her open "Dear Sylvia," with no sign-off, no em dashes and no technical words.

## 6. Code rules

20. **No hard-coded dev URLs.** Never write `topnotchmall.co.ke` or a `/sylviasdream/` path in theme or plugin code. Use `home_url()`, `get_theme_file_uri()`, `sdc_shop_url()`, `sdc_link()` or site-relative paths. The redirect map holds site-relative paths only. Dev addresses are allowed only in `sylviasdream-child/content/blocks/` (dev page content); `content/live/` must contain none.
21. Presentation belongs in the child theme; behaviour that must survive a theme change belongs in the core plugin.
22. **Escape on output, sanitise on input.** `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` on output; `sanitize_*`, `absint`, `esc_url_raw` and `wp_unslash` on input. Any SQL uses `$wpdb->prepare()`. JSON printed into a `<script>` uses `JSON_HEX_TAG`.
23. Every save uses a nonce and a `current_user_can()` check (the Settings API does both for `sd_core`). Do not add custom AJAX, REST or admin-post handlers without a security review recorded in `SECURITY-AUDIT.md`.
24. **No secrets in Git.** No API keys, passwords, tokens, Stripe exports, buyer or subscriber lists, backups, phone numbers or bank details. Any future secret is read from a `wp-config.php` constant or `getenv()` (pattern in `docs/wp-config-sample-additions.php`). Private files live in the private Google Drive folder only.
25. CSS class names never start with `ad-` (ad blockers hide them). Use the `sd-` prefix.
26. Accessibility: one `h1` per page, visible focus, 48px touch targets, alt text on every image, 19px+ body text, keyboard-operable menus, reduced motion respected.
27. New hardening or performance features must be switchable (`sd_core_security_feature` / `SD_SECURITY_DISABLE` keys in the core plugin; `sdc_perf_feature` keys in the theme).

## 7. Before shipping (every release)

28. **Bump versions**: child theme `Version:` in `style.css` and `SYLVIASDREAM_CHILD_VERSION` in `functions.php`; core plugin header `Version:`, `SD_CORE_VERSION` and `Stable tag` in `readme.txt`.
29. **Update `CHANGELOG.md`**, the README badges and every affected doc in the same commit.
30. Run, in this order:
    1. `node scripts/check-theme.js` (must print `OK`).
    2. PHP syntax check on every changed PHP file (`php -l` where PHP is available; CI runs `php -l` on PHP 8.0-8.3 for every PHP file and must pass).
    3. `node scripts/build-assets.js` after any CSS or JS change (also run automatically by `build-theme-zip.sh`).
    4. `bash scripts/build-theme-zip.sh` to produce the zips.
31. After deploying to dev, smoke-test Home, the Books page, Contact and a 404 URL, then the relevant QA checklist items. Record results in `project/PROGRESS-LOG.md`.
32. Keep `HANDOFF.md` section 3, `project/OPEN-ITEMS.md`, `project/DECISIONS.md` and `project/PROGRESS-LOG.md` current in the same commit as the work.
