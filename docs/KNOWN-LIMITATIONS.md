# Known limitations

Current as of theme 1.5.7 on the dev site (26 October 2026).

| Area | Limitation | Why | Resolves when |
|---|---|---|---|
| Books | Catalogue and Featured books show no products | All 25 products are Draft until Sylvia confirms prices and ebook files | Products published |
| Books mega menu | "Start here" shows three drawn covers linking to the Books page | No featured products are published | Three books marked Featured |
| Payments | No real checkout or giving payment on dev; Give buttons are test placeholders | Stripe not connected; Pimofy never edits Stripe | Sylvia completes Stripe set-up |
| Newsletter | Home card shows "Contact Sylvia"; Devotionals sign-up form does not render | Dev is not connected to Sylvia's WordPress.com account, by decision | Checked on live at launch |
| Downloads | Limit and expiry not set | Sylvia to choose (suggested 5 / 30 days) | Her answer, recorded in DECISIONS |
| Hosting | No staging, SFTP, SSH or one-click restore on live | WordPress.com Personal plan | Not planned to change |
| Dev host | cPanel shows "License is not for this host" | Host licence | Host fixes it; WP Admin theme upload meanwhile |
| QA | Real-device testing not yet done; automated checks used a 1439px desktop, 720px tablet and 320px phone preview | Browser tool cannot resize or zoom | Run `docs/PHONE-QA-v4-NAV.md` on real phones and check 1024/1280/1920 desktops |
| Content | Wording on Home, About, Give, Heaven on Earth, policies awaits Sylvia's approval | Client approval rule | Approvals received |
