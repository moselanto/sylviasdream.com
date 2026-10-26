# Content parity audit: live sylviasdream.com vs dev copy

26 October 2026. Live inventory from the public WordPress REST API (56 pages, 15 posts, 8 categories); dev inventory from the logged-in REST API (71 pages, 17 posts).

## Pages
- Every live page exists on dev. Dev adds the new pages (Home `welcome`, Give, Invite Sylvia to speak, Journal, policies, shop pages).
- Draft on dev by design (merge + 301 at launch, `docs/REDIRECT-MAP.md`): `new-home`, `sylvias-dream` (From Fear to Freedom duplicate), `the-hidden-key-to-healing-and-breakthrough` (5319), `my-autobiography` (One Life hardcover page). Their unique text must be in the kept page before launch: From Fear to Freedom French section is in `from-fear-to-freedom-3` (done); Hidden Key fuller description into `-3` and the hardcover text into `one-life`: **to verify**.

## Slugs that differ between live and dev
| Page ID | Live slug | Dev slug |
|---|---|---|
| 3431 (All Shall Be Well thank-you) | `all-shall-be-well` | `all-shall-be-well-2` |
| 4216 (All Shall Be Well book page) | `all-shall-be-well-2` | `all-shall-be-well-2-3` |

The core plugin's default protected list uses the **live** slug `all-shall-be-well` (corrected back on 26 Oct; listing `all-shall-be-well-2` would have hidden the live *book* page from search). The dev setting lists `all-shall-be-well-2`, which is right for dev only. At launch, check the live setting lists `all-shall-be-well`.

## Posts and categories
- All 15 live posts are on dev (plus 2 drafts: "A Letter to My 100-Year-Old Self", "ONE LIFE").
- Live and dev both file all 15 posts under **Salvation**; "Grace For the Journey" holds 1 post. The live `/category/grace-for-the-journey/` archive therefore shows one post. Recommendation: move the devotional posts into Grace For the Journey (Sylvia's OK).

## Text left out by the redesigns (to restore)
- **My Story**: the approved prototype layout replaced Sylvia's sections (From Australia to Africa; A Mission to the Nations; One Life Can Make a Difference; Why I Write; Proverbs 3:5 quote; Let's Walk Together; YouTube Short; Partner with us button; contact form). Restore below Milestones.
- **Podcasts and Videos**: Sylvia's descriptive paragraphs about the interview and her books. Restore under the featured videos.
- Author Support, SENT, From Fear to Freedom, Kenya manual, Give: all wording kept. Heaven on Earth: unchanged on dev (new layout not yet applied).

## Done on dev (26 October 2026, main 818921b)
- My Story: Sylvia's own sections restored after the approved prototype sections (intro, quote, four story cards, Proverbs 3:5, Let's Walk Together with video and Partner with us, contact form).
- Podcasts and Videos: her Lagos captions and "About the interview" paragraphs restored.
- The Hidden Key (6171): fuller description from the duplicate page appended.
- One Life (2766): hardcover text from My Autobiography appended. Its Amazon link (from page 1239) opens the Kindle listing; ask Sylvia for the hardcover link.
- Grace For the Journey category added to all 15 posts (existing categories kept); the archive lists all 15. Repeat on live at launch with Sylvia's OK.
