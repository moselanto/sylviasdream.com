# Launch readiness check (27 Oct 2026)

Read-only cross-check of live sylviasdream.com (public crawl of all 115 sitemap URLs, plus wp-admin) and the dev site. **Nothing on live was changed.** Read this with `MIGRATION-PLAN.md` (the method) and `LIVE-LINK-INVENTORY.md` (every Stripe link and download file on live today).

## Verdict
The method in `MIGRATION-PLAN.md` is safe: we **never copy the dev database over live**. Live keeps its users, subscribers, form responses, Jetpack connection, payments set-up, posts and URLs. We add the new theme and plugin, then update the existing pages in place. **We are not ready to switch over yet**: the 11 items below must be closed first.

## What live is today (checked 27 Oct)
- WordPress.com Personal, WordPress 7.1.3, theme Twenty Twenty (rollback = reactivate it). Upload Theme, Upload Plugin and Tools > Export are all available.
- **Purchases screen still shows the Personal plan renewing 29 Oct 2026 (A$60).** Sylvia says she renewed: confirm it now shows 2027 (Upgrades > Purchases). Domain paid to 25 Oct 2027; Professional Email renews 24 Apr 2027.
- 2 users, both Administrators: mosemqute, sylviasuhr2013 (Jetpack master user).
- 11 plugins (10 active): Akismet, CoBlocks, Crowdsignal Dashboard, Crowdsignal Forms, Gravatar Enhanced, Gutenberg, Jetpack 16.4-a.3, Layout Grid, MailPoet, Page Optimize; Classic Editor inactive. Updates managed by WordPress.com.
- Pages 61 (57 published, 4 draft); posts 17 (15 published, 2 draft); media 248 items (300 MB of 6.2 GB).
- Reading: front page "Sylvia's Dream"; posts page "Free Resource for Mission Training." (a mistake; Journal replaces it); search engines allowed. Jetpack diagnostics say "Site Public: Private": recheck after launch.
- Menus: "Top Menu" assigned to all three locations; "Primary" unassigned. Auto-add new pages is **off** on both (good).
- Jetpack: Newsletter, Forms, Stats, Brute Force Protection, **Secure Sign On** active. Connection tests pass.
- Newsletter: 51 records = **17 subscribed**, 26 not confirmed, 5 bounced, 2 not subscribed, 1 paused. **"Email new posts to subscribers by default" is ON.**
- Forms: 2 contact forms, 131 responses. Comments: 5 pending.
- WordPress.com Payments: Stripe connected ("Bu...th"), plan "Monthly Subscription" $5/month, 0 paid subscribers on this screen, fee shown **8% plus Stripe fees**. The 20 direct buy.stripe.com links on pages are separate from this.
- Public crawl: all 115 URLs return 200; 20 Stripe links and 25 download files listed in `LIVE-LINK-INVENTORY.md`.

## Differences found between live and dev
1. **Live has content dev does not have:** page "Guard Your Heart Renew Your Mind" (`/guard-your-heart-renew-your-mind/`, Stripe link ending `9Ev7g40k`, in the Top Menu), its thank-you page `/thank-you-for-your-purchase-3/` (file `Guard_Your_Heart_Renew_Your_Mind_ebook2.docx`), and the 22 Oct post "Grace for the Journey: You Don't Have to Carry It All". Because live pages are updated in place, these stay; but the new menu and Books section must include this book, and its thank-you page must be protected.
2. **Protected slugs:** the core plugin default list (used on a fresh live install) is correct for live (`all-shall-be-well` = thank-you page there). Dev's saved settings use `all-shall-be-well-2`, which on live is the **book** page: never copy dev settings to live. Add `thank-you-for-your-purchase-3` in Settings > Sylvia's Dream on live.
3. **Redirects are ON on dev.** On live they must stay OFF until the switch-over window (the plugin default is OFF).
4. **Products:** 25, all Draft, on dev. WooCommerce Stripe is **not connected** on dev; on live Sylvia connects her own Stripe (we never touch Stripe). Until then ebooks keep selling through the existing Stripe links.
5. **Dev-only items that must never reach live:** Password Protected plugin, Imunify, WordPress Importer, the store notice text, Rank Math "noindex", `SD_PREVIEW_MODE`.
6. Rank Math and WooCommerce are on dev only. Install on live only in the preparation phase, check they are allowed on Personal, and repeat the Home SEO settings by hand.
7. Jetpack Secure Sign On is on: WordPress.com sign-ins skip the plugin's two-factor. Admins use WordPress.com two-step sign-in.
8. Dev Site Health: slow server (2.7 s) and older PHP. Dev only; live is WordPress.com hosting. Not a launch blocker.

## Before switch-over (all must be ticked)
- [ ] 1. Sylvia's written approval of the dev site and the answers in her 27 Oct email.
- [ ] 2. Confirm the plan renewal shows 2027.
- [ ] 3. Add "Guard Your Heart Renew Your Mind" to dev (page or product, menu, Books) from live.
- [ ] 4. Copy every page edited directly on dev back into `content/blocks/`, then regenerate `content/live/` (no dev address may remain: `grep -r "topnotchmall\|/sylviasdream/" content/live`).
- [ ] 5. Upload the 5 "Upload to live first" images (content/live/README.md) during preparation and fix their addresses.
- [ ] 6. Fill every `#KEEP-LIVE-*` button from the same live page (see inventory).
- [ ] 7. Full backup set (BACKUP-AND-LAUNCH section 1): XML export, media export, subscriber CSV (17 / 51), forms responses export, screenshots of menus, widgets, Reading, plugins, Customizer; Author Support text.
- [ ] 8. Install core plugin on live with Redirects OFF; add `thank-you-for-your-purchase-3` to protected slugs; check one thank-you page shows `noindex` and its download still works.
- [ ] 9. Upload child + parent theme; **do not activate**; Live Preview check.
- [ ] 10. New pages (Home, Give, Invite Sylvia to speak, Journal, policies) created as Drafts; new menus built but unassigned.
- [ ] 11. Agree the switch-over time (Kenya afternoon = Melbourne evening), about 3 hours, both admins reachable.

## Switch-over day (order matters)
1. Fresh backup set. Note the time.
2. Activate child theme. Assign Primary and footer menus.
3. Paste `content/live/` into each existing page (URLs unchanged); fill `#KEEP-LIVE-*`; publish new drafts. **Never publish or update a post** (newsletter emails 17+ people).
4. Settings > Reading: Home = new Home; posts page = Journal.
5. Settings > Sylvia's Dream: Redirects ON.
6. Click-test **every row in `LIVE-LINK-INVENTORY.md`**: each Stripe link opens the same Stripe page; each file downloads; the Author Support and Contact forms send a test message.
7. Run `QA-CHECKLIST.md` and `LAUNCH-QA-CHECKLIST.md` on phone and desktop.
8. Shop: only if Sylvia has connected Stripe in WooCommerce: publish products, one small real purchase, check the download email and fees, refund.

## Rollback (any time, about 10 minutes for the look, hours for content)
Reactivate Twenty Twenty; Redirects OFF; reassign "Top Menu"; Reading back to "Sylvia's Dream" and old posts page. Page content: each page's previous version is in its Revisions (restore per page); the XML backup is the last resort. Never touch Stripe during a rollback.
