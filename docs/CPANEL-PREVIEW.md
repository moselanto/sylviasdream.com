# cPanel preview site: build, client review and launch guide

Version 1.1, 18 October 2026 (includes questions and answers). Word version kept in Pimofy's Town library (Sylvias Dream collection).


## 1. Purpose

This guide explains how Pimofy Digital builds the new sylviasdream.com on a private preview website on Pimofy's cPanel hosting, where Sylvia can view and test it at any time, without touching her current live website until she has approved the new site and launch day arrives.

It covers the full journey: setting up the cPanel preview, importing a copy of her content, building, sharing it with Sylvia, approval, moving the finished work into her live WordPress.com site, launch, rollback and cleanup.


### 1.1 Guiding principles

- Her live site stays exactly as it is until launch day. We only read from it (exports and screenshots) before then.
- The preview site holds no real customer, buyer, donor or subscriber data. Only her public content (pages, posts, images, book details).
- No real money moves on the preview. All payments use Stripe test mode on Pimofy's own Stripe test account.
- The preview is private: password protected and hidden from Google.
- We never copy the preview database over her live site. Finished work moves across in pieces (themes, pages, products, settings) and waits as hidden drafts.
- Her live site stays on the WordPress.com Personal plan. No upgrade, no migration of hosting.
- Every change to the live site is preceded by a backup.


## 2. The three spaces at a glance

| Space | Where | Who sees it | Used for |
|---|---|---|---|
| 1. Preview site | Pimofy cPanel subdomain, for example sylviasdream.pimofydigital.com (final name to be chosen) | Moses and Sylvia only (password) | Building, testing and Sylvia's review and approval |
| 2. Live site, hidden drafts | Sylvia's WordPress.com site (Personal plan) | Logged-in administrators only | Receiving the approved work as inactive themes, draft pages and draft products |
| 3. Live site, launched | sylviasdream.com | Everyone | The new website after the one-hour switch-over |

What the public sees during the project:

| Period | What visitors see |
|---|---|
| Preview build and review | The current website, unchanged |
| Moving work into the live site as drafts | The current website, unchanged (thank-you pages quietly drop out of Google) |
| Launch hour | The new website |
| 30 days after launch | New website; old download pages still work, hidden, for past buyers |


## 3. What we need before starting


### 3.1 Hosting requirements (Pimofy cPanel)

| Item | Requirement |
|---|---|
| Subdomain | One free subdomain on a Pimofy domain |
| SSL | Free AutoSSL / Let's Encrypt certificate (https is required for Stripe and for Sylvia's browser to trust the site) |
| PHP | 8.2 or 8.3 (match the requirement in the theme header: PHP 8.0 or later) |
| PHP limits | memory_limit 256M, upload_max_filesize 128M, post_max_size 128M, max_execution_time 300 |
| Database | One MySQL / MariaDB database and user |
| Disk | About 2-3 GB free (media library plus 25 ebook files) |
| Installer | Softaculous (or manual WordPress install) |
| Backups | cPanel backup or JetBackup available |


### 3.2 Accounts and files

- Administrator access to Sylvia's WordPress.com site (invite to mosemqute@gmail.com), used read-only until the live preparation stage.
- Pimofy's own Stripe account in test mode (for preview payments). Sylvia's real Stripe is connected only on the live site, together with her on a call.
- A PayPal sandbox account (optional, to show the PayPal button on the preview).
- The GitHub repo moselanto/sylviasdream.com (themes and core plugin).
- Sylvia's book list answers, ebook files (PDF or EPUB), covers and photos as they arrive.
- The private Google Drive folder "Sylvia's Dream - Backups (private)".


## 4. Setting up the preview site on cPanel


### 4.1 Create the subdomain

1. cPanel > Domains (or Subdomains) > Create A New Domain.
2. Enter the subdomain, for example sylviasdream.pimofydigital.com. Avoid using "sylviasdream.com" itself or anything that could be mistaken for her real site.
3. Document root: a new folder, for example /home/USER/sylviasdream-preview. Untick "Share document root".
4. Save.


### 4.2 Turn on SSL

1. cPanel > SSL/TLS Status > select the new subdomain > Run AutoSSL.
2. Wait until the padlock shows as valid, then open https://the-subdomain in a browser to confirm.


### 4.3 Set PHP version and limits

1. cPanel > MultiPHP Manager: set the subdomain to PHP 8.2 or 8.3.
2. cPanel > MultiPHP INI Editor (or Select PHP Version > Options): set the limits in section 3.1.
3. Enable the PHP extensions WordPress and WooCommerce need: mysqli, curl, gd or imagick, intl, mbstring, zip, xml, openssl.


### 4.4 Install WordPress

1. cPanel > Softaculous > WordPress > Install.
2. Choose https:// and the subdomain; leave the directory field empty.
3. Site name: Sylvia's Dream (Preview). Admin username: not "admin". Strong password stored in Pimofy's password manager. Admin email: Moses's work address.
4. Untick any bundled extra plugins or themes Softaculous offers.
5. Enable Softaculous auto-backups if available.
6. Install, then log in at /wp-admin.


### 4.5 Make it private and invisible to Google

1. Settings > Reading: tick "Discourage search engines from indexing this site".
2. Add a site-wide password with a privacy plugin (for example the free "Password Protected" plugin). Sylvia then sees a simple, phone-friendly password page instead of the browser pop-up that cPanel Directory Privacy shows. Allow logged-in administrators through.
3. Belt and braces: add this to the subdomain's .htaccess file so search engines are told not to index anything even if the password is ever switched off: Header set X-Robots-Tag "noindex, nofollow".
4. robots.txt in the document root: User-agent: * then Disallow: /
5. Optional: cPanel Directory Privacy as a second lock while building, removed before sharing with Sylvia if the double prompt confuses her.

> **Note:** If Stripe test webhooks are set up later, check that the password plugin lets the WooCommerce Stripe webhook address through (most plugins allow REST and wc-api requests, or have a setting for it). Test card payments in the preview normally complete without webhooks.


### 4.6 Stop real emails going out

- The preview should only ever email Moses and Sylvia.
- Install an email logging plugin (for example WP Mail Logging) to see every message the site sends.
- Do not import any subscriber, customer or user lists. Test orders use Moses's and Sylvia's own email addresses only.
- Leave the Jetpack / subscription features off on the preview so no newsletter can be triggered.


### 4.7 Basic security

- Two-factor login for every administrator (for example the free Two Factor plugin or Wordfence Login Security).
- Sylvia gets her own account with the Shop Manager or Editor role if she wants to try adding a book, never the shared admin login.
- Keep WordPress, plugins and themes updated during the project.
- Turn on a cPanel / JetBackup backup of the subdomain folder and database before each milestone.


## 5. Importing a copy of her current content

Everything in this section is read-only on her live site.

1. Live site (WordPress.com) > Tools > Export > All content: download the XML file.
2. Live site > Tools > Export > Export media library: download the zip.
3. Copy the Author Support page wording into a document (it must stay exactly as written).
4. Screenshot her current menu, footer and Customizer settings for reference.
5. Preview site > Tools > Import > WordPress > Install now > Run importer. Upload the XML file, assign posts to the preview admin, and tick "Download and import file attachments".
6. If some images do not come across, upload the media zip contents with the Add From Server approach or a bulk media upload, then re-link.
7. Check the page count and post count against the live site (55 pages, 14 posts at the time of the audit).
8. Store the XML and media zip in the private Drive folder as part of the first backup set.

> **Note:** The import brings in her public content and post authors only. It does not bring her subscribers, Stripe customers or donors, and that is intended.


## 6. Building on the preview


### 6.1 Themes and plugins

| Item | Source | Notes |
|---|---|---|
| Sylvia's Dream Base (parent theme) | GitHub repo, folder sylviasdream/, zipped | Install, never activate on its own |
| Sylvia's Dream (child theme) | GitHub repo, folder sylviasdream-child/, zipped | Activate on the preview |
| Sylvia's Dream Core (plugin) | GitHub repo, folder sylviasdream-core/, zipped | Activate; redirects can be tested on the preview |
| WooCommerce | WordPress plugin directory | Shop, cart, checkout, downloads |
| WooCommerce Stripe Gateway | WordPress plugin directory | Test keys from Pimofy's Stripe test account |
| WooCommerce PayPal Payments | WordPress plugin directory | Sandbox mode, optional |
| Customizer Export/Import | WordPress plugin directory | Used later to carry design settings to the live site |
| Privacy, email log, 2FA plugins | WordPress plugin directory | Preview only; not copied to the live site |

To make the zips: download the repo from GitHub (Code > Download ZIP), then zip each of the three folders separately so each zip contains the folder at its top level. Upload in Appearance > Themes > Add New > Upload (themes) and Plugins > Add New > Upload (plugin).


### 6.2 WooCommerce settings

- Currency: Australian dollar (AUD). Store address country: Australia only, no street address shown on the site.
- Products are virtual and downloadable ebooks. Print, audiobook and Kindle formats link out to Amazon, Kobo, Barnes & Noble or Spotify.
- WooCommerce > Settings > Products > Downloadable products: Force downloads, grant access after payment, download limit and expiry (for example 5 downloads, 30 days; to be agreed).
- Guest checkout allowed; account created automatically so buyers can re-download from My Account.
- Emails: order confirmation with secure download links, branded in the warm gold style.
- Policies: Privacy Policy, Terms of Sale and Refund Policy pages linked in the footer and at checkout.


### 6.3 Build list

1. Design: warm gold and cream style, large text (19-20px), 6-7 item menu, full footer.
2. Home page with hero, Start Here book guide, Heaven on Earth highlight, latest posts and sign-up.
3. Merge duplicate pages into one page per book and one About Sylvia page; set up 301 redirects in the core plugin.
4. All 25 books as WooCommerce products, using only details Sylvia confirms. Placeholders clearly marked until then.
5. Heaven on Earth and Give page: $5, $15, $30, $50 monthly plus another amount, one-off gift, GoFundMe link, progress toward 100 Dream Partners. On the preview the giving buttons use Pimofy test payment links.
6. Author Support page with her wording exactly as published.
7. SEO: page titles, descriptions, alt text, schema; thank-you pages hidden.
8. Accessibility checks: one heading 1 per page, visible focus, 48px buttons.
9. Run the QA checklist (docs/QA-CHECKLIST.md in the repo) before sharing.


## 7. Sharing the preview with Sylvia


### 7.1 Before sending the link

- Every page loads on phone and desktop, no broken links, no lorem ipsum.
- A banner at the top of every page: "Private preview. Payments are in test mode."
- The "For Sylvia's review" page lists what still needs her approval.
- The password page works on a phone.
- A test purchase, download and gift all work end to end.


### 7.2 What to send her (WhatsApp, separately)

- The preview address.
- The password (in a separate message from the link).
- How to test a purchase: card number 4242 4242 4242 4242, any future expiry date, any 3-digit CVC, any name. No money is taken.
- What to look at: the pages on the "For Sylvia's review" list, and the site on her phone.
- How to give feedback: one list per round on WhatsApp or by email, within 2 working days. Two rounds of design revisions are included.


### 7.3 Walkthrough call

Book a short call to walk her through the preview with screen sharing, then let her explore on her own. Note every change request; anything that is a new feature goes on a change order.


## 8. Approval gate

The site moves into her live WordPress only after all of these are true:

| Check | Done |
|---|---|
| Sylvia has reviewed the preview on phone and computer |  |
| Test purchase completed and the secure download email received |  |
| Download link stops after the limit or expiry |  |
| Test monthly gift and one-off gift completed with receipts |  |
| Book titles, prices, biography, ministry wording and free items approved in writing |  |
| Written approval of the preview received |  |
| Balance of USD 168 received |  |


## 9. Moving the approved work into her live site (hidden)

Her live site is on WordPress.com Personal, so there is no staging site, no SFTP and no database copy. The approved work moves in pieces and waits as hidden drafts.


### 9.1 Before starting

1. Take a full backup set of the live site: XML export, media export, subscriber CSV, screenshots of menus, widgets, Customizer and settings.
2. In her current menu settings, untick "Automatically add new top-level pages to this menu".
3. Plan a quiet time (Kenya morning is Melbourne evening).


### 9.2 What moves and how

| What | From the preview | Into the live site | Stays hidden because |
|---|---|---|---|
| Themes | Zip of each theme folder | Appearance > Themes > Add New > Upload | Uploaded but not activated |
| Core plugin | Zip | Plugins > Add New > Upload, activate | Only hides thank-you pages from Google; redirects left off |
| WooCommerce and payment plugins | Install fresh | Plugins > Add New | Payments disabled or in test mode |
| Shop, Cart, Checkout, My Account pages | Created by WooCommerce | Created automatically on install | Set to Draft straight after installing |
| Pages (books, About, Give, policies) | Set to Draft on the preview, then Tools > Export (Pages) | Tools > Import > WordPress, download attachments | Imported as drafts |
| Products (25 books) | WooCommerce > Products > Export (CSV) | WooCommerce > Products > Import | Set to Draft before export or after import |
| Ebook files | Original files from the private folder | Uploaded to each live product | Product is a draft; direct file address tested (see 9.3) |
| Design settings | Customizer Export/Import plugin (.dat) | Same plugin on live, applied in the theme Live Preview | Saved as a Customizer draft, not published |
| Menus | Recreate by hand (menus do not export cleanly) | Appearance > Menus | Built but not assigned to any location |
| Redirect list | Settings > Sylvia's Dream on the preview | Same settings page on live | Redirects switch stays OFF |


### 9.3 Rules while working inside the live site

- Back up before each working session.
- Never publish a blog post: published posts email all her subscribers. Pages do not.
- Never change anything in Stripe. Existing subscriptions and payment links stay as they are.
- Upload one ebook file and confirm its direct web address does not open for a visitor who has not bought it. WordPress.com handles uploads differently from cPanel, so this must be tested on her site specifically before all files are uploaded.
- Check that the WordPress.com theme and plugin upload screens are available on her Personal plan on the first login; if not, stop and review the plan with Moses.


### 9.4 Final look before launch

Use Appearance > Themes > Live Preview of the child theme on her live site to check the drafts in place. Only logged-in administrators can see this.


## 10. Launch day (about one hour)

Time: Kenya afternoon or early evening is late evening in Melbourne, when her site is quiet. Melbourne is 8 hours ahead of Kenya during Australian daylight saving.

1. Take a fresh full backup set. Record the time.
2. Appearance > Themes: activate Sylvia's Dream (child theme). Apply the saved Customizer draft.
3. Publish the draft pages and products. Set the WooCommerce pages to published.
4. Assign the new menus; set the new Home page in Settings > Reading.
5. Settings > Sylvia's Dream: turn redirects ON.
6. Connect Sylvia's real Stripe (and PayPal) with her on a call; switch to live mode.
7. Make one small real purchase, confirm the download email and check that WordPress.com took no fee, then refund it.
8. Run the QA checklist on the live site: menus, redirects, downloads, giving, policies, mobile.
9. Send Sylvia a short message that the site is live.


### 10.1 Rollback (if anything goes wrong)

1. Appearance > Themes: reactivate Twenty Twenty. Her old site returns within minutes, because her old pages and posts were never deleted.
2. Settings > Sylvia's Dream: turn redirects OFF.
3. If content was affected, restore it by hand from the launch-day backup set (this can take hours on the Personal plan).
4. Never touch Stripe during a rollback.


## 11. After launch

- Sylvia confirms the active Stripe subscription count is unchanged.
- Past ebook buyers receive their new secure download links (sent by Pimofy or by Sylvia from her own account).
- Old download pages stay working and hidden for 30 days, then are retired only with her written OK.
- Restoration test before handover: restore the backups onto a private copy and confirm the site, shop, settings, orders and download access all come back. The cPanel space can host this test copy.
- Recorded training and written guide delivered; 3 months of support begins.


### 11.1 Closing the preview site

1. After the restoration test and handover, take a final backup of the preview and store it in the private folder.
2. Delete the WordPress installation (Softaculous > Remove), its database and database user.
3. Remove the subdomain and its SSL certificate.
4. Delete any test orders or test accounts.
5. Tell Sylvia the preview has been removed.


## 12. Data protection

- No subscriber, buyer, donor or Stripe data is ever placed on the preview site.
- Test orders use only Moses's and Sylvia's own emails.
- Stripe exports and backups live only in the private Google Drive folder, never on the preview, in WhatsApp or in the GitHub repo.
- The preview is password protected, hidden from search and removed after handover.
- This follows the Australian Privacy Act 1988 and the Kenya Data Protection Act 2019, as committed in the quotation.


## 13. Risks and how we handle them

| Risk | Mitigation |
|---|---|
| Preview appears in Google | Password, Discourage search engines, X-Robots-Tag header and robots.txt |
| Sylvia confuses the preview with her real site | Preview banner on every page; test-mode notice at checkout; different web address |
| Test emails reach real people | No customer or subscriber import; email logging; test with own addresses |
| Something on the preview behaves differently on WordPress.com | Test ebook file protection, uploads and plugin availability on her live site before bulk work; final Live Preview check |
| Publishing a post emails all subscribers | Rule: no blog posts are published during preparation |
| WooCommerce install publishes new pages | Set Shop, Cart, Checkout and My Account to Draft immediately; auto-add to menu turned off |
| Launch problem | One-click theme rollback to Twenty Twenty; redirects off; backup set |
| Storage limit on Personal (6 GB) | Check storage used on first login; compress images; ebooks are small |


## 14. Responsibilities and timeline

| Stage | Owner | Working days (after deposit and access) |
|---|---|---|
| cPanel preview setup and content import | Moses | Days 1-2 |
| Day-1 protection plugin on the live site | Moses | Days 1-2 |
| Design on the preview | Moses | Days 2-5 |
| Content consolidation | Moses | Days 3-7 |
| Shop, giving and sign-up | Moses | Days 5-9 |
| SEO, policies and testing | Moses | Days 8-11 |
| Sylvia's review and feedback (2 rounds) | Sylvia | Within 2 working days per round |
| Approval and balance | Sylvia | After review |
| Move into live site as drafts | Moses | 1-2 days |
| Launch | Moses with Sylvia | About 1 hour |
| Restoration test, training, handover | Moses | Days 10-12 |
| Close the preview site | Moses | After handover |


## 15. Questions and answers

Every question we expect from Sylvia, from Moses or another developer taking over, and the common technical problems, with the answer to give or the action to take. Answers to Sylvia are written so they can be said or sent almost word for word.


### 15.1 Questions Sylvia may ask: the preview

| Question | Answer |
|---|---|
| Why is the new site on a different web address? | So your current website keeps working exactly as it is while we build. The preview is a private copy on our hosting. On launch day the new design moves onto sylviasdream.com and the preview is deleted. |
| Will my readers or Dream Partners see the preview? | No. It is password protected, hidden from Google, and we never share the link with anyone but you. |
| Is my current website changed while you build? | No. We only take copies of your public content. Nothing changes on sylviasdream.com until you approve the new site. |
| Can I look at it any time, on my phone? | Yes. Open the link, enter the password, and browse as often as you like, on your phone, tablet or computer. |
| I forgot the password / the page won't open. | Send me a WhatsApp message and I will resend it. Try another browser, or clear the browser history if the page keeps asking. |
| Why does it say test mode? | No real money moves on the preview. You can try buying a book and giving a gift with a test card, and nothing is charged. |
| How do I test a purchase? | Add a book to the cart, go to checkout and use card 4242 4242 4242 4242, any future expiry date, any 3-digit code and any name. You will receive the download email as a real buyer would. |
| Can I change things myself on the preview? | Yes, if you would like to practise. I can give you your own login to try adding a book or editing a page. Anything you change there can be undone. |
| How do I send feedback? | One list per round by WhatsApp or email, ideally within 2 working days. Screenshots help. Two rounds of design changes are included. |
| What if I want something new that is not in the plan? | I will note it and come back to you on whether it fits the current scope. If it does not, I will send a short written quote before any work. |
| Are my customers' or donors' details on the preview? | No. The preview holds only your public pages, posts and images. Subscribers, buyers, donors and Stripe records are never placed there. |
| Who else can see or log into the preview? | Only you and Moses at Pimofy Digital. |
| What happens to the preview after launch? | After launch, the restoration test and handover, we take a final private backup and delete it completely. |
| Does the preview cost me anything? | No. It runs on Pimofy's hosting at no cost to you. |


### 15.2 Questions Sylvia may ask: launch and her live site

| Question | Answer |
|---|---|
| How does the new site get onto sylviasdream.com? | After your approval, I add the finished design, pages and books to your site as hidden drafts. On launch day I switch them on in about an hour, at a quiet time in Melbourne. |
| Will my site go offline on launch day? | No. It stays online. Visitors simply see the new design once it is switched on. |
| What if something goes wrong at launch? | I can switch back to your current design within minutes, because your old pages are never deleted. Backups are taken just before launch. |
| Do I need to be available on launch day? | Only for a few minutes, to connect your Stripe and PayPal accounts to the new shop yourself on a call. I never need your passwords. |
| Will my blog subscribers get emails during the work? | No. We do not publish any new blog posts during the preparation, so no emails are sent. |
| Will my monthly Dream Partners be affected? | No. Existing Stripe subscriptions are not touched. |
| What about people who already bought ebooks? | They receive new secure download links on launch day, and the old download pages keep working, hidden, for 30 days. |
| Will my website address, emails or Google ranking change? | Your address stays sylviasdream.com. Old page addresses are redirected to the new pages, so links in your books, emails and social posts keep working. |
| Do I need to upgrade my WordPress.com plan? | No. Everything runs on your current Personal plan. |
| When is the balance due? | After you have reviewed and approved the preview, including a successful test purchase, secure download and test gift, and before the new site goes live. |


### 15.3 Questions from Moses or a developer taking over

| Question | Answer |
|---|---|
| Which subdomain should we use? | Any Pimofy domain subdomain that is clearly not her real site, for example sylviasdream.pimofydigital.com. Record the final name in HANDOFF.md. |
| Directory Privacy or a password plugin? | A password plugin for Sylvia (phone-friendly, single password). Directory Privacy is optional during the early build and should be removed before sharing if it causes a double prompt. |
| Whose Stripe keys go on the preview? | Pimofy's own Stripe account in test mode. Sylvia's Stripe is only connected on the live site, by her, on a call. |
| How do we show Dream Partner giving on the preview? | Create test-mode payment links or test subscription products in Pimofy's Stripe test account and point the Give page buttons at them. Replace them with her real links on the live site. |
| Can we copy the preview database to WordPress.com? | No. Never. WordPress.com Personal has no database access, and a database copy would overwrite live orders, subscribers and the Author Support page. Move work in pieces as section 9 explains. |
| Should we use a migration plugin (All-in-One WP Migration, Duplicator) to move to live? | No for the live site, because it would replace her whole site. A migration plugin is fine for moving a local build onto the cPanel preview, or for restoration-test copies on cPanel. |
| What if theme or plugin upload is not available on her plan? | Stop and check with Moses before doing anything else. Since April 2026 WordPress.com allows plugins on all paid plans, but confirm the upload screens on the first login. |
| Do the WooCommerce pages publish on install? | Yes. WooCommerce creates Shop, Cart, Checkout and My Account pages. Set them to Draft immediately on the live site and make sure auto-add to menu is off. |
| How do we keep ebook files protected on WordPress.com? | Set Force downloads in WooCommerce, then test by opening a file's direct address in a private browser window. If it opens, do not upload the rest; review the file protection approach first. |
| How do product images and categories move? | The WooCommerce CSV import pulls images from the preview's web addresses, so the preview must be reachable during the import. If the password blocks it, briefly allow the importer or upload images to the live media library first. |
| How do menus move? | Recreate them by hand on the live site. Menus do not export cleanly between sites. |
| How do Customizer settings move? | Customizer Export/Import plugin on both sites. Import while the child theme is in Live Preview and save as a draft, not published, until launch. |
| Where do backups of the preview go? | cPanel or JetBackup on the hosting, plus a copy in the private Google Drive folder at each milestone. |
| Can the cPanel space host the restoration test? | Yes. Restore the live backup set onto a fresh WordPress install in a separate private subdomain or folder, run the checklist in project/SUPPORT-AND-RESTORATION.md, then delete it. |
| What must never go on the preview? | Subscriber lists, Stripe exports, buyer or donor data, Sylvia's real payment keys, and her passwords. |
| How long does the preview stay online? | Until the restoration test and handover are complete. Then take a final backup and delete it (section 11.1). |


### 15.4 Troubleshooting

| Problem | What to do |
|---|---|
| SSL padlock not showing | Re-run AutoSSL; check the subdomain's DNS points to the server; wait up to an hour for the certificate. |
| WordPress import times out or images are missing | Raise max_execution_time and memory_limit; re-run the importer (it skips what already exists); upload missing media manually. |
| Upload size error for ebooks or zips | Raise upload_max_filesize and post_max_size to 128M in the MultiPHP INI Editor. |
| White screen or critical error after activating a plugin | Rename that plugin's folder in cPanel File Manager to deactivate it; check the error_log file in the document root. |
| Stripe test payment fails | Check test keys are entered (not live keys), the site uses https, and the card details are the Stripe test card. |
| No order or download email arrives | Check the email log plugin; check spam; consider an SMTP plugin with Pimofy's mail account for reliable delivery. |
| Sylvia sees two password prompts | Turn off cPanel Directory Privacy and keep only the password plugin. |
| Preview shows in Google | Confirm Discourage search engines, the X-Robots-Tag header and robots.txt; request removal in Google Search Console for the Pimofy domain. |
| Live site storage near 6 GB | Compress images before upload; remove unused media; ebooks are small. Discuss with Sylvia before considering any upgrade. |
| Something changes visibly on the live site during preparation | Undo it straight away (set the item back to Draft or reactivate Twenty Twenty), check the menu settings, and tell Moses. |


## Appendix A. cPanel setup checklist

| Step | Done |
|---|---|
| Subdomain created with its own folder |  |
| AutoSSL valid (https padlock) |  |
| PHP 8.2/8.3 and limits set |  |
| WordPress installed, admin not named admin, 2FA on |  |
| Discourage search engines ticked |  |
| Site-wide password plugin on |  |
| X-Robots-Tag header and robots.txt in place |  |
| Email logging on; no customer or subscriber data imported |  |
| cPanel / JetBackup backup taken |  |


## Appendix B. Before sharing with Sylvia

| Step | Done |
|---|---|
| All pages load on phone and desktop |  |
| Preview banner and test-mode notice visible |  |
| For Sylvia's review page up to date |  |
| Test purchase, download and gift pass |  |
| Password page works on a phone |  |
| Link and password sent separately |  |


## Appendix C. Moving to the live site

| Step | Done |
|---|---|
| Live backup set taken |  |
| Auto-add pages to menu turned off |  |
| Themes uploaded, not activated |  |
| Core plugin active, redirects OFF |  |
| WooCommerce installed; its pages set to Draft |  |
| Pages imported as drafts |  |
| Products imported as drafts; ebook files uploaded |  |
| Direct ebook file address blocked for visitors |  |
| Customizer settings saved as draft |  |
| Menus built, not assigned |  |
| No blog posts published; nothing changed in Stripe |  |
