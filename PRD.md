# Product requirements: Sylvia's Dream website

Owner: Pimofy Digital (Moses Munyi) for Sylvia Suhr. Quotation PMF-QT-2026-0106. Source of truth for scope: `project/PROJECT-BRIEF.md`; decisions: `project/DECISIONS.md`.

## 1. Purpose
Give Sylvia Suhr, Christian author, speaker and missionary, one professional home for her books, story and ministry, where readers buy ebooks safely, past buyers keep access, and supporters give to Heaven on Earth, Kenya.

## 2. Goals
- Sell all 25 current titles as instant, protected ebook downloads (AUD), with paperbacks linked to Amazon.
- Grow monthly Dream Partners toward 100, without touching existing Stripe subscriptions.
- Present Sylvia's story, speaking, author support and the Kenya ministry clearly.
- Make the site easy for Sylvia to edit, fast, accessible and found in search.

## 3. Audiences
Readers seeking faith-based help with fear and anxiety; past buyers needing their downloads; churches and event organisers; aspiring Christian authors; supporters of Heaven on Earth; Sylvia as editor.

## 4. Functional requirements
1. Home: hero, highlights, featured books, Meet Sylvia, Heaven on Earth, Dream Partners, latest devotionals, author support, newsletter.
2. Books: catalogue by topic and language, product pages, cart, checkout with Stripe and PayPal, secure expiring downloads, My downloads.
3. Inner pages: My Story, Podcasts and Videos, Invite Sylvia to speak, Author Support (wording kept exactly), Heaven on Earth, Give, Devotionals, Contact, policies.
4. Navigation: desktop menu with Books mega menu and described dropdowns; phone bottom bar and drawer.
5. Giving: monthly amounts $5 / $15 / $30 / $50 plus another amount, one-off gift, progress toward 100 Dream Partners.
6. Newsletter sign-up connected to the WordPress.com subscriber list.
7. Forms with spam protection; 301 redirects for merged pages; legacy download pages protected until buyers move.
8. SEO: titles, descriptions, alt text, schema, clean sitemap, Search Console.

## 5. Non-functional requirements
- Accessibility: WCAG 2.2 AA intent; one h1 per page, visible focus, 48px targets, 19px+ body text, keyboard-operable menus.
- Responsive from 320px phones to 1920px desktops.
- Performance: no page builders, no front-end frameworks; images sized and lazy-loaded.
- Security: see SECURITY-AUDIT.md. Privacy: Australian Privacy Principles; no private data in Git.
- Editability: all words, images and links editable in WordPress.

## 6. Success measures
Successful test purchase with download and test gift before launch; zero lost access for past buyers; Dream Partner count rising; Lighthouse accessibility 95+; Sylvia edits pages without help after training.

## 7. Out of scope
Hosting migration or plan upgrade; physical book shipping; changes to existing Stripe subscriptions; new content writing beyond agreed drafts.

## 8. Open items
Kept current in `project/OPEN-ITEMS.md` (book prices and files, covers, approvals, Stripe set-up, newsletter connection, Kenya manual status).
