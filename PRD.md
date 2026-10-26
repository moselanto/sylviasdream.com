# Product requirements: Sylvia's Dream website

| | |
|---|---|
| Client | Sylvia Suhr (Sylvia's Dream), Australia |
| Supplier | Pimofy Digital (Moses Munyi) |
| Quotation | PMF-QT-2026-0106, revised v2, 17 October 2026, fixed price USD 280 |
| Dev site | https://topnotchmall.co.ke/sylviasdream/ |
| Live site | https://sylviasdream.com (WordPress.com Personal plan) |
| Sources | [`project/PROJECT-BRIEF.md`](project/PROJECT-BRIEF.md) (scope), [`project/QUOTATION.md`](project/QUOTATION.md), [`project/DECISIONS.md`](project/DECISIONS.md) (decisions; newest wins) |
| Last updated | 27 October 2026 |

## 1. Purpose

Give Sylvia Suhr, Christian author, speaker and missionary, one professional home for her books, story and ministry, where readers buy ebooks safely, past buyers keep access, and supporters give to Heaven on Earth in Kenya. The site must be international-standard, mobile-ready, with a fixed (sticky) menu, match or beat the approved prototype including its images and footer, and be fully editable by Sylvia.

## 2. Goals

1. Sell the full catalogue (25 titles at the time of writing) as instant, protected ebook downloads, with paperbacks and Kindle editions linked to Amazon.
2. Grow monthly Dream Partners toward 100 without touching any existing Stripe subscription or payment link.
3. Present Sylvia's story, speaking, author support and the Kenya ministry clearly.
4. Be fast, secure, accessible, found in search, and easy for Sylvia to edit after training.
5. Move to the live site with zero lost access for past buyers and no broken payment link.

## 3. Audiences

| Audience | Needs |
|---|---|
| Readers seeking faith-based help (for example with fear and anxiety) | Find the right book, buy it simply, download it at once |
| Past buyers | Their existing thank-you / download pages keep working |
| Churches and event organisers | Invite Sylvia to speak |
| Aspiring Christian authors | Author Support information and enquiry form |
| Supporters of Heaven on Earth | Understand the ministry; give monthly or once |
| International visitors | Language selector (English, Français, Español, Bahasa Indonesia) |
| Sylvia as editor | Change words, images, links, prices and books without code |

## 4. Scope

In scope (quotation sections A-I): discovery and content audit; redesign on WordPress.com; content consolidation; WooCommerce book shop with secure digital delivery; Dream Partner giving and Heaven on Earth; email list sign-up; SEO; security, analytics and compliance (policies, form protection, two-factor login, analytics); testing, launch, training, written guide and 3 months of support.

## 5. Functional requirements

| ID | Requirement |
|---|---|
| F1 | Home: hero, highlights, featured books, Meet Sylvia, Heaven on Earth, Dream Partners, latest devotionals, author support, newsletter. |
| F2 | Books: catalogue by topic and language, product pages with format badges, cart, checkout with Stripe and PayPal, secure expiring downloads, My account > Downloads. |
| F3 | Inner pages: My Story, Podcasts and Videos, Invite Sylvia to speak, Author Support (wording kept exactly), Heaven on Earth, Give, Devotionals (Grace for the Journey), Journal, Contact, Kenya Ministry Training Manual, Privacy Policy, Terms of Sale, Refund Policy. |
| F4 | Navigation: desktop menu with Books mega menu and described dropdowns, sticky header; phone bottom bar (Home, Books, Ministry, Give) and drawer menu. |
| F5 | Giving: monthly $5 / $15 / $30 / $50 plus another amount, one-off gift, progress toward 100 Dream Partners (`[sd_partner_progress]`), using the existing Stripe Dream Partner links. |
| F6 | Newsletter sign-up connected to the existing WordPress.com (Jetpack) subscriber list on live. |
| F7 | Forms (Jetpack) with spam protection. |
| F8 | Legacy pages: 301 redirects for merged pages, switched on only on launch day; thank-you / download pages kept working, hidden from search engines, sitemaps and site search. |
| F9 | SEO: titles, meta descriptions (Sylvia's wording), alt text, structured data (Person, WebSite, Organization, Book, BlogPosting, WebPage, BreadcrumbList), Open Graph and Twitter tags with a default 1200 x 630 sharing image, clean sitemap, Search Console. |
| F10 | Reviewer preview on dev: signed-in reviewers see Draft products as they will look at launch, without publishing them; drafts never buyable. |
| F11 | Editability: every word, image, link and price editable in WordPress (blocks, patterns, Customizer, products, Settings > Sylvia's Dream). |
| F12 | Existing payments: every existing Stripe book button, Dream Partner link, thank-you/download page and subscription keeps working exactly as now. |

## 6. Non-functional requirements

### 6.1 Performance
- Target page load **1-3 seconds**.
- Implemented in the theme: minified CSS/JS, preloaded LCP image and font stylesheet, lazy images with `decoding="async"`, click-to-play YouTube facade, WooCommerce styles only where shop content appears, emoji script removed, deferred non-critical scripts. No page builders or front-end frameworks.
- Dependency: the dev host measures about 4.2 s server response with no page cache. The target requires **page caching on the final host** (WordPress.com caches on live). Measure Core Web Vitals on mobile after launch.

### 6.2 Security
| Requirement | How it is met |
|---|---|
| Rate limiting | Login 5 failures per 15 min per IP; password reset 5 per hour; comments 10 per 10 min (core `inc/security.php`) |
| Input sanitisation | All input sanitised (`sanitize_*`, `absint`, `esc_url_raw`, `wp_unslash`); all output escaped |
| Password hashing | WordPress core hashing (bcrypt from WP 6.8); minimum length 12 added |
| Secrets | No keys in code or Git; any secret read from `wp-config.php` constants or `getenv()` |
| CORS | REST API CORS headers for the site's own origin only |
| SQL injection | No raw SQL except one preview search clause, built with `$wpdb->prepare()` and `esc_like()` |
| Session expiry | 8 hours, or 7 days with Remember me |
| HTTPS everywhere | Front-end HTTP to HTTPS redirect, HSTS over HTTPS, `FORCE_SSL_ADMIN` recommended |
| Security headers | nosniff, Referrer-Policy, X-Frame-Options, Permissions-Policy, Report-Only CSP |
| Dependency updates | Dependabot weekly updates for GitHub Actions; WordPress, WooCommerce and Jetpack updated monthly during support |
| Also | XML-RPC off, user enumeration blocked, upload allow-list (no SVG), 2FA for administrators at launch. Every control switchable. Details: [SECURITY-AUDIT.md](SECURITY-AUDIT.md) |

### 6.3 Other
- Accessibility: WCAG 2.2 AA intent; one `h1` per page, visible focus, 48px targets, 19px+ body text, keyboard-operable menus, alt text on images, reduced motion respected.
- Responsive from 320px phones to 1920px desktops.
- Privacy: Australian Privacy Principles; no private data in Git; minimum Stripe data only ([`project/STRIPE-DATA-REQUEST.md`](project/STRIPE-DATA-REQUEST.md)).
- Hosting: stays on WordPress.com Personal; no plan upgrade.
- Compatibility: WordPress 6.4+, PHP 8.0+ (CI lints PHP 8.0-8.3).

## 7. Acceptance criteria

The balance (USD 168) is due, and launch proceeds, only when all of these hold:

1. Sylvia has reviewed the dev site and approved it, and the wording, **in writing**.
2. A test purchase, secure ebook download and test gift succeed (test mode before launch).
3. On launch day one small real purchase succeeds, the download email arrives, no WordPress.com fee is taken, and it is refunded.
4. Every existing Stripe payment and Dream Partner link is click-tested on live and opens the same Stripe page as before; no `#KEEP-LIVE` marker remains on any page.
5. Two old thank-you pages still open for past buyers and carry `noindex`; they are absent from `/sitemap.xml`.
6. Sylvia confirms the active subscription count is unchanged after launch.
7. `docs/QA-CHECKLIST.md` and `docs/LAUNCH-QA-CHECKLIST.md` pass on live; real-phone navigation checks in `docs/PHONE-QA-v4-NAV.md` pass.
8. Lighthouse accessibility 95+.
9. Page load within 1-3 s on the live host.
10. Restoration test completed and shared with Sylvia before handover ([`project/SUPPORT-AND-RESTORATION.md`](project/SUPPORT-AND-RESTORATION.md)).
11. Sylvia can edit pages without help after training.

## 8. Out of scope

- Hosting migration or WordPress.com plan upgrade.
- Physical book shipping.
- Any change to Stripe: subscriptions, payment links, settings or existing thank-you pages.
- Replacing or retiring existing payment links or download files (cancelled by the 26 October payments rule).
- Writing new content beyond agreed drafts; inventing reviews, figures or facts.
- Connecting the dev site to Sylvia's WordPress.com account or importing her subscribers.

## 9. Open items

Kept current in [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md). Summary at 26 October 2026:

**Waiting on Sylvia** (one consolidated email, unsent, awaiting Moses's approval)
- Written approval of the dev site (the go-ahead to migrate) and of draft wording (home intro, biography, "Every purchase helps" line).
- Original logo files; photo of Sylvia; up to 3 real reader reviews with permission.
- Covers for When Fear Has the Final Word, Manifesting Miracles Now, Fast Forward to Success; confirmation of titles not on Amazon; which other editions appear in the shop.
- One Life hardcover Amazon link.
- Stripe links for the $15, $30 and $50 Dream Partner levels; approval of Give amounts and the 100 goal.
- Confirmation that the connected payment account is hers; keep the Personal plan renewing (29 October 2026).
- Approval of Privacy Policy, Terms of Sale and Refund Policy.
- Written confirmation of the three free resources she named on the call; decision on Manifesting Miracles Now.
- Ebook files, prices, download limit and expiry; signed acceptance and deposit.

**Pimofy**
- Upload child 1.17.0 and core 0.3.0 to dev; set `SD_PREVIEW_MODE` on dev.
- Real-phone checks (320-430px) and visual checks of SENT and From Fear to Freedom pages.
- Remaining migration blockers: upload missing media to live, copy final dev page content into Git, keep dev-only items off live.
- Meta descriptions (her wording), alt text for remaining images, Search Console and Bing, analytics, 2FA for admins, training, written guide, restoration test.
- Mark 3 books as Featured after products are published.

**Launch**
- Remove the street address from the live Contact page (with Sylvia's OK); set the newsletter byline to Sylvia; decide on MailPoet; publish no posts during switch-over.
