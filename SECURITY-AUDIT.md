# Security audit

Scope: `sylviasdream/` 1.0.0, `sylviasdream-child/` 1.17.0, `sylviasdream-core/` 0.3.0 (full re-review 27 October 2026; earlier review covered child 1.5.7 / core 0.1.x). Method: code review of every PHP and JS file in the repo (grep for superglobals, output, saves, remote calls), plus behaviour checks on the dev site. Last reviewed 26 October 2026.

## 1. Attack surface
| Surface | Exposure | Notes |
|---|---|---|
| Public pages and templates | Read only | All dynamic output escaped |
| Request URI | `navigation.php` (active state), `redirects.php` (redirect lookup) | `wp_unslash` + `esc_url_raw` / path parse; used only for comparison |
| Core plugin settings page | Administrators | Nonce + `current_user_can()` |
| Forms | Jetpack Forms | Jetpack handles nonce, sanitising and spam (Akismet) |
| Checkout and payments | WooCommerce + Stripe/PayPal | Card data never touches the theme or plugin |
| Downloads | WooCommerce expiring links; legacy pages protected by core plugin | Limit and expiry set by Sylvia |
| Third-party embeds | YouTube, GoFundMe script (Heaven on Earth only) | Loaded from the provider; no secrets |

## 2. Findings and fixes
| ID | Finding | Status |
|---|---|---|
| S1 | Public `.docx` and Stripe payment links on ordinary pages | Fixed on dev (`docs/LEGACY-LINK-MAP.md`); repeat on live at launch |
| S2 | Old download pages and files reachable by direct URL | Planned: `docs/BUYER-TRANSITION-PLAN.md` Phase 0 before launch |
| S3 | Default WordPress widgets (Archives, Categories) leaking into the footer | Fixed (1.3.0, front-end filter) |
| S4 | Core plugin version constant (0.1.0) differs from header (0.1.1) | Fixed in 0.1.2 |
| S5 | Street address on live Contact page | Open: remove at launch with Sylvia's OK |
| S6 | JSON-LD printed without `JSON_HEX_TAG`: entity-encoded `</script>` in a title/excerpt/product text (decoded by `sd_core_page_description`) could break out of the script tag (stored XSS by any author/contributor) | Fixed in core 0.3.0 (`inc/schema.php`) |
| S7 | Language boot script printed filterable JSON without hex escaping | Fixed in child 1.17.0 (`inc/languages.php`) |
| S8 | Alt text with `$1` or `\` used as a `preg_replace` replacement (output corruption) | Fixed in child 1.17.0 (`inc/cover-alt.php`) |
| S9 | REST user list, `?author=N`, user sitemap, XML-RPC and generator tag exposed usernames / version | Fixed in core 0.3.0 (`inc/security.php`) |
| S10 | No login rate limit, no security headers, any upload type WordPress allows (incl. SVG via other plugins) | Fixed in core 0.3.0 (`inc/security.php`) |
| S11 | Filtered redirect map could point off-site | Hardened in core 0.3.0 (site-relative targets only + `wp_safe_redirect`) |

## 2a. Hardening module (core 0.3.0, `inc/security.php`)
Switch any feature off with `define( 'SD_SECURITY_DISABLE', array( '<key>' ) );` or the `sd_core_security_feature` filter. Keys: `headers`, `csp`, `hsts`, `login_limit`, `form_limit`, `session_expiry`, `xmlrpc`, `rest_users`, `author_enum`, `version`, `cors`, `https`, `password_length`, `upload_mimes`.
- CSP is **Report-Only** on purpose: it never blocks YouTube, Jetpack, Stripe, PayPal, GoFundMe or Google Translate. Tighten only after reviewing reports.
- CORS: the REST API sends `Access-Control-Allow-Origin` only for the site's own origin (`sd_core_cors_origins` filter); no permissive CORS anywhere.
- Rate limits use transients keyed by a hash of the IP (`REMOTE_ADDR`; behind a proxy correct it with `sd_core_client_ip`).
- Passwords: WordPress core hashing (bcrypt from WP 6.8); only a minimum length (12, `sd_core_min_password_length`) is added.
- Admin HTTPS: set `FORCE_SSL_ADMIN` (see `docs/wp-config-sample-additions.php`).

## 3. Controls confirmed sound
- No `eval`, `unserialize`, `base64_decode`, remote file reads or cURL in theme or plugin. The only SQL is in preview mode's search clause and uses `$wpdb->prepare()` + `esc_like()`.
- No custom form, AJAX, REST or admin-post handlers: the only save is Settings > Sylvia's Dream (Settings API nonce + `manage_options`); forms are Jetpack Forms.
- Superglobals used only in the two places above, unslashed and sanitised.
- All template output uses `esc_html`, `esc_attr`, `esc_url` or `wp_kses_post`; menu and Customizer values sanitised on save.
- Display filters (`render_block`, `the_content`) never write to the database.
- JS (`nav.js`) builds no HTML from user input.

## 4. Requested controls checklist
| Control | State |
|---|---|
| HTTPS everywhere | WordPress.com default |
| 2FA for administrators | To enable at launch (scope H) |
| Spam protection on forms | Jetpack / Akismet |
| Least-privilege accounts | Moses as Administrator only while contracted |
| Backups before each major step | `docs/BACKUP-AND-LAUNCH.md` |
| Restoration test before handover | `project/SUPPORT-AND-RESTORATION.md` |

## 5. Environment and secrets
No API keys, passwords or tokens in the repo. Stripe, PayPal and WordPress.com credentials stay with Sylvia; Pimofy never edits Stripe. Private exports live in the private Google Drive folder only.

## 6. Residual risks
- Personal plan: no staging, SFTP or one-click restore; mitigated by manual backups and draft-first changes.
- Dev host cPanel licence error: deploys go through WP Admin theme upload until fixed.
- Third-party embeds can change behaviour; keep to YouTube and the GoFundMe widget.

## 7. Maintenance routine
Monthly during support: update WordPress, WooCommerce and Jetpack; check the Dependabot PRs for GitHub Actions; re-run this audit's grep checks on each release.

## 8. Automated checks
`.github/workflows/checks.yml`: `php -l` on PHP 8.0-8.3 for every PHP file; `node scripts/check-theme.js` for theme.json, block-markup nesting and repo rules. Dependabot keeps the workflow actions current.
