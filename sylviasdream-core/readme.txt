=== Sylvia's Dream Core ===
Contributors: pimofydigital
Requires at least: 6.4
Requires PHP: 8.0
Stable tag: 0.4.6
License: GPLv2 or later

Site functions for sylviasdream.com that must survive a theme change.

== Description ==

* Hides post-purchase download / thank-you pages from search engines, the Jetpack sitemap and site search, while keeping them working for past buyers.
* 301 redirects from retired and duplicate pages (off until launch day).
* Structured data: Sylvia as author, the website, Heaven on Earth, and each book.
* [sd_partner_progress] Dream Partner progress bar, and [sd_charity_reg] registration line.
* Open Graph / Twitter sharing tags with a default 1200 x 630 sharing image (assets/og-default.jpg, replaceable in Settings).
* Security hardening (inc/security.php): security headers, report-only CSP, login and form rate limits, session expiry, XML-RPC off, REST user list and author enumeration hidden, same-origin CORS only, HTTPS redirect, minimum password length, upload type allow-list (no SVG). Switch features off with the SD_SECURITY_DISABLE constant or the sd_core_security_feature filter.
* Reviewer preview mode (inc/preview.php): define( 'SD_PREVIEW_MODE', true ) on the dev site only, so signed-in reviewers see Draft books as they will look at launch. Drafts stay unbuyable and hidden from visitors.
* Branded sign-in screens (inc/login.php): split-screen sign in, lost password, reset password and verification-code screens with the site logo, brand colours and fonts the theme already loads. Text filterable with sd_core_login_texts.
* Sign-in hardening (inc/auth-hardening.php): one generic message for wrong username, email or password; unknown accounts on lost password get the normal "check your email" result; Application Passwords for administrators only; other sessions ended when a password changes.
* Two-factor authentication (inc/two-factor.php): optional per user, from Users > Profile > Two-factor authentication. Setup shows a locally drawn QR code (inc/qr-svg.php) plus the key. Time-based 6-digit codes from Google Authenticator, Microsoft Authenticator, Authy or similar; 8 one-time recovery codes. Emergency switch: define( 'SD_2FA_DISABLE', true ); in wp-config.php.
* Admin branding (inc/admin-brand.php): "Sylvia's Dream" admin colour scheme (default for new users only), footer credit, Welcome dashboard widget with quick links, WordPress logo menu hidden for non-administrators.

* Coming soon guard (inc/sale-guard.php): a paid ebook with no price or no download file cannot be added to the cart; the theme shows "Coming soon". Free books, Stripe buttons and Amazon buttons are not affected. Switch: Settings > Sylvia's Dream > Coming soon guard, or the sd_core_sale_guard_enabled filter.

Settings: Settings > Sylvia's Dream.

Third-party code: inc/lib/qrcode.php is a namespaced port of the MIT-licensed QR Code Generator by Kazuhiko Arase (github.com/kazuhikoarase/qrcode-generator); licence in inc/lib/qrcode-LICENSE.txt.

== Changelog ==

= 0.4.6 =
* Settings > Sylvia's Dream: on/off switches for XML-RPC blocking, two-step sign-in and the failed sign-in limit. Off by default on WordPress.com (Jetpack, newsletter and WordPress.com sign-in), on by default elsewhere. Existing sites keep their current behaviour.

= 0.4.5 =
Coming soon guard: paid ebooks with no price or no download file are not purchasable (woocommerce_is_purchasable, priority 40); warning on the product edit screen; setting on by default. Helpers sd_core_ebook_missing() and sd_core_ebook_coming_soon() for the theme.

= 0.4.4 =
Test payment (no money) gateway for the test site only.

= 0.4.3 =
Reviewer preview switch; thank-you-for-your-purchase-3 protected.

= 0.4.2 =
Readable "Prove your humanity" box on the branded sign-in page.

= 0.4.1 =
Two-factor setup now shows a QR code of the otpauth:// address, drawn on this server as inline SVG (no external service). The key and the link stay as a fallback.

= 0.4.0 =
Branded sign-in screens, two-factor authentication, generic login errors, Application Passwords for administrators only, session clean-up on password change, admin colour scheme and Welcome widget.
