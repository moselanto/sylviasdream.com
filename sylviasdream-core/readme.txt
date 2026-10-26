=== Sylvia's Dream Core ===
Contributors: pimofydigital
Requires at least: 6.4
Requires PHP: 8.0
Stable tag: 0.3.0
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

Settings: Settings > Sylvia's Dream.
