# Legacy Stripe and .docx links: replacement map

> **Superseded for the live site (26 Oct 2026, decision in `project/DECISIONS.md`): all existing Stripe payment and contribution links stay exactly as they are on sylviasdream.com.** The replacements below were made on the **dev site only** and must **not** be repeated on live. At switch-over, every book purchase button keeps its existing live Stripe link (the launch pack marks it `#KEEP-LIVE-STRIPE-BUTTON`). On dev, the original links are still in each page's Revisions if they need restoring there. The table is kept as a record of what was changed on dev.

Checked and fixed on dev on 23 October 2026. **The live site still has all of these links.** Repeat the same replacements on live during switch-over (`BACKUP-AND-LAUNCH.md` section 6), only after Sylvia's written OK for each old payment link (`project/PROJECT-BRIEF.md`).

## Journey
Old book button (buy.stripe.com) -> **Books page `/shop-for-books/`** -> WooCommerce product -> checkout -> secure download link in the order email and My account > Downloads.
At launch, when products are published, point each button at its own product page instead of the Books page.

## Rules
- Book-purchase buy.stripe.com links on ordinary pages: replace with the Books page (same label; labels that name Stripe become "Buy on our Books page").
- Never change: the Stripe payment links themselves, Dream Partner / giving links, the Give page, or any thank-you / download page (past buyers still use them for 30 days after launch).
- Public .docx links on ordinary pages: remove the link. Do not replace with another public file URL; ebook files go into the WooCommerce product as protected downloads. Removing a link does not delete or protect the file itself.

## Book links replaced on dev (31 links, 18 pages)
| Page | ID | Links |
|---|---|---|
| All Shall Be Well (`/all-shall-be-well-2-3/`) | 4216 | 2 |
| Angelique (`/angelique/`) | 2786 | 3 |
| Angelique Finds Her Light (`/angelique-finds-her-light/`) | 4356 | 2 |
| From Chance to Choice (`/from-chance-to-choice-2/`) | 4141 | 2 |
| From Fear to Freedom (`/from-fear-to-freedom-3/`) | 4231 | 3 |
| From Fear to Freedom (draft) | 4580 | 1 |
| Guard Your Heart (`/guard-your-heart/`) | 4848 | 1 |
| How to Find the Real You (`/how-to-find-the-real-you/`) | 1256 | 2 |
| Learning to Recognise God's Voice (`/learning-to-recognise-gods-voice/`) | 6090 | 1 |
| My Autobiography (draft) | 1239 | 1 |
| One Life (`/one-life/`) | 2766 | 3 |
| SENT (`/sent/`) | 4686 | 1 |
| Sylvia's Dream (`/sylvia-suhr-helping-you-find-peace-purpose-and-freedom-in-christ/`) | 5022 | 1 |
| The Hidden Key to Healing and Breakthrough (`/the-hidden-key-to-healing-and-breakthrough-3/`) | 6171 | 2 |
| The Hidden Key to Healing and Breakthrough (draft) | 5319 | 1 |
| The King's Way (`/the-kings-way/`) | 3795 | 2 |
| When the Word Becomes Life (`/when-the-word-becomes-life/`) | 5637 | 1 |
| Wisdom for Soul-Winning (`/wisdom-for-soul-winning/`) | 4501 | 2 |

## Stripe links deliberately kept
- All Shall Be Well thank-you page (`/all-shall-be-well-2/`, 3431): thank-you page.
- Heaven on Earth (`/heaven-on-earth/`, 1932), My Story (`/my-story/`, 2665), Sylvia's Dream draft (6304): Dream Partner giving links.

## .docx links
Removed on dev:
- Sylvia's Dream draft (6304): "Download My Story".
- To Ensure You End Up Going to Heaven (`/to-ensure-you-end-up-going-to-heaven/`, 2458): "Download free ebook" (The King's Way). Nothing is free without Sylvia's written approval.

Kept (download / thank-you pages): Thank You - The King's Way (3707), Thank You - The Report of the Lord (6020), Thank You for Becoming a Dream Partner (3598, 7 Days to Trusting God), When the Word Becomes Life Download (6157), and **Free Downloads (`/free-7-day-devotional/`, 2592)**, which offers the book-writing guide "How to Write and Publish a Spirit-Led Christian Book in 7 Days" as a free .docx. Sylvia has said nothing is free without her approval, especially the book-writing guide: ask her before launch.

Every changed page keeps its previous version in Revisions.
