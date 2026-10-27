# Security audit: sylviasdream.com

| | |
|---|---|
| Scope | Parent theme `sylviasdream/` 1.0.1, child theme `sylviasdream-child/` 1.17.0, core plugin `sylviasdream-core/` 0.4.0, repo scripts and CI |
| Out of scope | WordPress core, WooCommerce, Jetpack, Stripe, PayPal, WordPress.com platform and the dev cPanel host (third-party; kept updated, not reviewed line by line) |
| Environments | Dev https://topnotchmall.co.ke/sylviasdream/ (cPanel); live https://sylviasdream.com (WordPress.com Personal) |
| Reviews | Core 0.4.0 authentication review 27 October 2026 (login, two-factor, auth hardening, admin branding); full re-review 27 October 2026 (child 1.17.0, core 0.3.0); earlier review covered child 1.5.7 / core 0.1.x |
| Reporting policy | [SECURITY.md](SECURITY.md) |

## 1. Method

1. Manual code review of every PHP and JS file in the three packages: superglobal use, output escaping, saves and capability checks, SQL, remote calls, JSON in script tags, regular-expression replacements, redirects.
2. Grep checks for dangerous functions (`eval`, `unserialize`, `base64_decode`, remote file reads, cURL), `$_GET` / `$_POST` / `$_SERVER`, and `$wpdb`.
3. Behaviour checks on the dev site.
4. PHP syntax: verified with php-parser for this release (no PHP binary on the dev cPanel account or in the Town sandbox); the GitHub Actions workflow `.github/workflows/checks.yml` runs `php -l` on every PHP file under PHP 8.0, 8.1, 8.2 and 8.3 on each push and pull request, plus `node scripts/check-theme.js`.

Severity scale used below: High (exploitable by an outside visitor or leads to loss of buyer access or payments), Medium (needs an authenticated author or leaks information useful to attackers), Low (hygiene or limited impact).

## 2. Attack surface

| Surface | Exposure | Notes |
|---|---|---|
| Public pages and templates | Read only | All dynamic output escaped |
| Request URI | `navigation.php` (active state), `redirects.php` (lookup), `security.php` (HTTPS redirect) | `wp_unslash` + `esc_url_raw`; used for comparison or same-host redirect only |
| `$_SERVER['REMOTE_ADDR']`, `HTTP_X_FORWARDED_PROTO`, `HTTP_CF_VISITOR` | `security.php` | Sanitised; IP validated with `FILTER_VALIDATE_IP` |
| Settings > Sylvia's Dream | Administrators | Settings API nonce + `manage_options` |
| 2FA code screen `wp-login.php?action=sd_2fa` (0.4.0) | Visitors holding a pending-login token | Reviewed: HttpOnly token cookie (256-bit, transient keyed by HMAC), nonce bound to token, 5-minute expiry, 5 attempts per token, 10 per IP per 15 min, failures count toward the login lockout; no auth cookie before a valid code |
| 2FA profile buttons (0.4.0) | Signed-in user, own profile | Part of core's profile form: `update-user_{id}` nonce + `edit_user`; enable needs a valid code, disable needs password + code; failures rate limited |
| 2FA admin reset (0.4.0) | `edit_users` on another user's profile | Core profile nonce + `edit_users` + `edit_user` |
| 2FA reminder "Remind me in 30 days" link (0.4.0) | Administrators | `check_admin_referer( 'sd_2fa_later' )` + `manage_options`; writes one user-meta timestamp |
| Password fields | `security.php` length check only | Read after core/WooCommerce verified the nonce; never stored or logged |
| Forms | Jetpack Forms | Jetpack handles nonce, sanitising and Akismet |
| Checkout and payments | WooCommerce + Stripe/PayPal; existing buy.stripe.com links | Card data never touches the theme or plugin |
| Downloads | WooCommerce expiring links; legacy thank-you pages protected by noindex | Limit and expiry set by Sylvia |
| REST API | WordPress core and plugins | User routes hidden from visitors; same-origin CORS |
| Uploads | Media Library | Allow-list; SVG refused |
| Third-party embeds | YouTube (facade), GoFundMe (Heaven on Earth), Google Fonts | Loaded from providers; no secrets |

## 3. Findings

| ID | Area | Finding | Severity | Status | Fix |
|---|---|---|---|---|---|
| S1 | Content | Public `.docx` and Stripe book-payment links on ordinary pages | Medium | Fixed on dev; **not repeated on live by decision** (26 Oct payments rule: all existing Stripe links stay) | Dev changes in `docs/LEGACY-LINK-MAP.md`; on live the links remain, accepted risk |
| S2 | Downloads | Old download pages and files reachable by direct URL | Medium | Mitigated; accepted | Core plugin noindex + sitemap and search exclusion (`protect-downloads.php`); retirement of pages and files cancelled by the 26 Oct payments rule |
| S3 | Theme | Default WordPress widgets (Archives, Categories) leaking into the footer | Low | Fixed (child 1.3.0) | Front-end widget filter |
| S4 | Plugin | Version constant (0.1.0) differed from header (0.1.1) | Low | Fixed (core 0.1.2) | Constants aligned |
| S5 | Content | Street address on the live Contact page | Medium (privacy) | Open | Remove at launch with Sylvia's OK ("Australia" only) |
| S6 | XSS | JSON-LD printed without `JSON_HEX_TAG`: entity-encoded `</script>` in a title, excerpt or product text (decoded by `sd_core_page_description`) could break out of the script tag (stored XSS by any author or contributor) | High | Fixed (core 0.3.0) | `wp_json_encode` with `JSON_HEX_TAG` and `JSON_HEX_AMP` in `inc/schema.php` |
| S7 | XSS | Language boot script printed filterable JSON without hex escaping | Medium | Fixed (child 1.17.0) | Hex-escaped JSON in `inc/languages.php` |
| S8 | Output integrity | Alt text containing `$1` or `\` used as a `preg_replace` replacement corrupted output | Low | Fixed (child 1.17.0) | `sdc_preg_quote_replacement()` in `inc/cover-alt.php` |
| S9 | Information exposure | REST user list, `?author=N`, user sitemap, XML-RPC and generator tag exposed usernames and version | Medium | Fixed (core 0.3.0) | `inc/security.php` keys `rest_users`, `author_enum`, `xmlrpc`, `version` |
| S10 | Authentication / uploads | No login rate limit, no security headers, any upload type WordPress allows (including SVG via other plugins) | Medium | Fixed (core 0.3.0) | `inc/security.php` keys `login_limit`, `form_limit`, `headers`, `upload_mimes` |
| S12 | Authentication | Login and lost-password messages revealed whether a username or email existed (account discovery) | Medium | Fixed (core 0.4.0) | Generic messages, `inc/auth-hardening.php` key `login_errors` (wp-login.php and WooCommerce My account login and lost password) |
| S13 | Authentication | Password-only sign-in for administrators (audit A07 launch action) | Medium | Fixed (core 0.4.0), adoption pending | Optional TOTP 2FA, `inc/two-factor.php`; reminder notice for administrators; each administrator must switch it on |
| S14 | Authentication | Application Passwords available to every user; stored sessions survived a password change | Low | Fixed (core 0.4.0) | Keys `app_passwords`, `session_cleanup` |
| S11 | Open redirect | A filtered redirect map could point off-site | Low | Hardened (core 0.3.0) | Targets must start with `/` (not `//`); `wp_safe_redirect()`; request path via `esc_url_raw` |

## 4. Hardening controls and how to disable each

All controls are in `sylviasdream-core/inc/security.php` and are on by default. Disable in `wp-config.php`:

```php
define( 'SD_SECURITY_DISABLE', array( 'xmlrpc', 'csp' ) ); // listed keys off
define( 'SD_SECURITY_DISABLE', 'all' );                    // every control off
```

or in code: `add_filter( 'sd_core_security_feature', function ( $on, $key ) { return 'csp' === $key ? false : $on; }, 10, 2 );`

| Key | Control | Extra tuning |
|---|---|---|
| `headers` | nosniff, `Referrer-Policy: strict-origin-when-cross-origin`, `X-Frame-Options: SAMEORIGIN`, `Permissions-Policy` (front end only) | `sd_core_security_headers` (return empty value to drop a header) |
| `hsts` | `Strict-Transport-Security: max-age=31536000` over HTTPS only; no includeSubDomains or preload | |
| `csp` | Content-Security-Policy **Report-Only** (never blocks YouTube, Jetpack, Stripe, PayPal, GoFundMe, Google Translate) | |
| `login_limit` | 5 failed logins per 15 minutes per IP | `sd_core_login_limit` (array: max, seconds) |
| `form_limit` | Password reset 5 per hour per IP; comments 10 per 10 minutes per IP (moderators exempt) | `sd_core_lostpassword_limit`, `sd_core_comment_limit` |
| `session_expiry` | Login cookie 8 hours; 7 days with Remember me | |
| `xmlrpc` | XML-RPC and pingbacks off, `X-Pingback` removed | Switch off if a self-hosted Jetpack needs XML-RPC |
| `rest_users` | `/wp/v2/users` hidden from signed-out visitors | |
| `author_enum` | `?author=N` and author archives redirect home for visitors; no users sitemap; no oEmbed author URL | |
| `version` | WordPress generator tag removed | |
| `cors` | REST CORS headers only for the site's own origin | `sd_core_cors_origins` |
| `https` | Front-end HTTP to HTTPS 301 when the home URL is https; skips AJAX, cron, REST, CLI and proxy-reported HTTPS (no loops) | |
| `password_length` | Minimum 12 characters (profile, reset, WooCommerce account); hashing left to WordPress core | `sd_core_min_password_length` (floor 8) |
| `upload_mimes` | Allow-list: JPG, PNG, GIF, WebP, AVIF, ICO, PDF, EPUB, DOCX, MP3/M4A/M4B, MP4/M4V; SVG/SVGZ refused | `sd_core_upload_mimes`, `sd_core_allow_svg` |

0.4.0 keys (in other files, same switches; `'all'` turns them off too):

| Key | Control | Extra tuning |
|---|---|---|
| `login_errors` | One message for wrong username, email or password; unknown accounts on lost password get the normal "check your email" result (wp-login.php and WooCommerce) | `sd_core_generic_login_message` |
| `two_factor` | TOTP two-factor authentication (RFC 6238, SHA1, 30 s, 6 digits, +/-1 step, no reuse of a step; `random_bytes` secret; `hash_equals`; recovery codes hashed with `wp_hash_password`). Emergency: `define( 'SD_2FA_DISABLE', true );` | `sd_core_2fa_limits` (per-token, per-IP, window), `sd_core_2fa_issuer` |
| `app_passwords` | Application Passwords only for `manage_options` users | |
| `session_cleanup` | Other sessions destroyed on password change; all on email reset | |
| `login_brand` | Branded sign-in screens (presentation only) | `sd_core_login_texts` |
| `admin_brand` | Admin colour scheme, footer, Welcome widget, WP logo menu hidden for non-administrators (presentation only) | `sd_core_admin_*` |

Rate-limit counters are transients keyed by an MD5 of bucket and IP (no raw IPs stored). The IP is `REMOTE_ADDR`; behind a proxy correct it with `sd_core_client_ip`.

Related controls outside `security.php`: reviewer preview (`inc/preview.php`) only with `SD_PREVIEW_MODE` and only for `edit_posts` / `sd_reviewer` users, drafts never purchasable, noindex; protected download pages (`inc/protect-downloads.php`); safe redirects (`inc/redirects.php`).

## 5. OWASP Top 10 checklist

| Risk (OWASP Top 10 2021 mapping) | Status | Evidence |
|---|---|---|
| SQL injection (A03 Injection) | Pass | No custom tables or queries except preview mode's search clause, built with `$wpdb->prepare()` and `$wpdb->esc_like()`. All other data through WordPress / WooCommerce APIs. |
| Cross-site scripting (A03 Injection) | Pass | All template output through `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` / `wp_kses`; JSON-LD and language JSON hex-escaped (S6, S7); `nav.js` builds no HTML from user input; Report-Only CSP for monitoring. |
| CSRF (A01 Broken Access Control) | Pass | Saves: Settings > Sylvia's Dream through the Settings API (nonce); 2FA profile buttons inside core's profile form (core nonce); 2FA code screen (token-bound nonce) and reminder link (nonce), reviewed in section 2; no custom AJAX, REST or admin-post handlers; forms are Jetpack Forms; WooCommerce and core handle their own nonces. |
| Broken authentication (A07) | Pass, with launch action | Login rate limit, password-reset limit, minimum password length 12, session expiry, generic login errors, optional TOTP 2FA (0.4.0), Application Passwords for administrators only, sessions ended on password change. Action: every administrator switches 2FA on before launch. |
| Insecure file uploads (A04 Insecure Design / A05) | Pass | Upload allow-list; SVG refused even if another plugin allows it. DOCX allowed for existing resources. |
| Access control (A01) | Pass | Settings page and save require `manage_options`; preview needs a signed-in `edit_posts` or `sd_reviewer` user; Draft products refused at purchasable and add-to-cart checks for everyone; REST user list hidden. |
| Session hijacking (A07) | Pass, with recommendation | HTTPS redirect, HSTS, WordPress core auth cookies, 8-hour session. Recommend `FORCE_SSL_ADMIN` on every environment. |
| Password security (A02 Cryptographic Failures) | Pass | WordPress core hashing (bcrypt since WP 6.8); passwords never sanitised, stored, logged or committed by this code. |
| API / REST exposure (A01 / A05) | Pass | `/wp/v2/users` hidden from visitors; CORS same-origin only; XML-RPC off; no custom endpoints. |
| Sensitive data exposure (A02 / A05) | Pass, one open item | No secrets or private data in Git (`.gitignore` excludes `*.csv`, `backups/`, zips); version and usernames hidden; protected download pages noindexed. Open: street address on live Contact page (S5). |
| Security misconfiguration (A05) | Pass, with recommendations | Security headers on; recommend `DISALLOW_FILE_EDIT` on self-managed hosts. |
| Vulnerable components (A06) | Process in place | Dependabot weekly for GitHub Actions; monthly WordPress, WooCommerce and Jetpack updates during support. |

## 6. Controls confirmed sound

- No `eval`, `unserialize`, `base64_decode`, remote file reads or cURL in the themes or plugin.
- Superglobals used only in `navigation.php`, `redirects.php` and `security.php`, unslashed and sanitised.
- Display filters (`render_block`, `the_content`, `wp_content_img_tag`) never write to the database.
- Social tags and schema use only text already on the site; escaped with `esc_attr`.
- Payment logic is not in this code: WooCommerce and Stripe/PayPal handle checkout; existing Stripe links are left untouched.

## 7. Environment and secrets

No API keys, passwords or tokens in the repo. Any future secret must come from a `wp-config.php` constant or `getenv()` (`docs/wp-config-sample-additions.php`). Stripe, PayPal and WordPress.com credentials stay with Sylvia; Pimofy never edits Stripe. Private exports (Stripe data, buyer and subscriber lists, backups) live only in the private Google Drive folder.

## 8. Residual risks

| Risk | Note |
|---|---|
| CSP is Report-Only and has no report endpoint | Violations appear only in browser consoles; it does not block injected scripts. Tighten only after reviewing real traffic. |
| Proxy-shared IPs | Behind a proxy or CDN without `sd_core_client_ip`, all visitors share one rate-limit bucket. |
| Forwarded-protocol headers | `X-Forwarded-Proto` / `CF-Visitor` are trusted only to skip the HTTPS redirect (loop prevention); a forged header just skips the redirect. HSTS covers returning browsers. |
| Legacy download pages and Stripe links stay public by decision | Mitigated by noindex and sitemap/search exclusion only (S1, S2). |
| Personal plan | No staging, SFTP or one-click restore; mitigated by manual backups and draft-first changes. |
| Third-party embeds | YouTube and GoFundMe can change behaviour; keep embeds to those providers. |
| 2FA secrets at rest | TOTP secrets are stored unencrypted in user meta (they must be readable to check codes); a database leak exposes them. Recovery codes are hashed. |
| 2FA lock-out | A user who loses their phone and recovery codes needs an administrator reset, or `SD_2FA_DISABLE` in wp-config.php. |
| Generic lost-password result | A mistyped username shows "check your email" and no email arrives (accepted trade-off for account privacy). |
| WordPress.com live | WordPress.com may offer its own account 2FA for WordPress.com logins; this plugin's 2FA covers wp-login.php / WooCommerce sign-ins of site users. Test on live before relying on it. |
| Dev host | No page cache, slow server response; dev must stay password-gated and noindexed. |

## 9. Recommendations

1. `define( 'FORCE_SSL_ADMIN', true );` on every environment (WordPress.com already forces HTTPS).
2. `define( 'DISALLOW_FILE_EDIT', true );` on any self-managed host.
3. Two-factor authentication for every administrator at launch (quotation scope H; built in core 0.4.0, each administrator switches it on in Profile); least privilege: Moses as Administrator only while contracted.
4. Page cache and a web application firewall on the final host (needed for both the 1-3 s target and resilience); WordPress.com provides caching on live.
5. Backups before every major step and on launch day ([docs/BACKUP-AND-LAUNCH.md](docs/BACKUP-AND-LAUNCH.md)); restoration test before handover.
6. Never define `SD_PREVIEW_MODE` on live.
7. Monthly during support: update WordPress, WooCommerce and Jetpack; merge Dependabot PRs; re-run the grep checks in section 1 on each release.

## 10. Automated checks

- `.github/workflows/checks.yml`: `php -l` on every PHP file in `sylviasdream`, `sylviasdream-child` and `sylviasdream-core` under PHP 8.0-8.3; `node scripts/check-theme.js` on Node 20.
- `.github/dependabot.yml`: weekly GitHub Actions updates.
- Release gate: `bash scripts/build-theme-zip.sh` refuses to build if `check-theme.js` fails.
