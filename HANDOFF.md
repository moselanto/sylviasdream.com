# HANDOFF: start here (for a person or AI agent taking over)

Last updated: Saturday 17 October 2026, about 12:00 Kenya time (EAT, UTC+3).

## 1. What this project is
Pimofy Digital (Moses Munyi) is rebuilding **sylviasdream.com** for **Sylvia Suhr** (Christian author and missionary, Melbourne, Australia; ministry Heaven on Earth in Kenya). Custom theme, WooCommerce book shop with secure ebook downloads, Dream Partner giving page, content clean-up and SEO. Fixed price **USD 280** (quotation PMF-QT-2026-0106). The site stays on the **WordPress.com Personal plan**.

## 2. Read in this order
1. This file.
2. [`AGENTS.md`](AGENTS.md): rules for working in this repo (never invent facts, no private data in Git, nothing free without approval, never touch Stripe).
3. [`project/README.md`](project/README.md): map of every project file.
4. [`project/PROJECT-BRIEF.md`](project/PROJECT-BRIEF.md) and [`project/DECISIONS.md`](project/DECISIONS.md): what was agreed and why.
5. [`project/OPEN-ITEMS.md`](project/OPEN-ITEMS.md): what is waiting, and on whom.
6. [`project/CORRESPONDENCE.md`](project/CORRESPONDENCE.md): the message history with Sylvia and how to write to her.
7. Then the runbooks in `docs/` when you start real work.

## 3. Current status (17 Oct 2026)
| Area | Status |
|---|---|
| Quotation | Revised v2 sent (Personal plan, 25 titles, valid to 31 Oct). Sylvia accepted staying on Personal. **Not yet signed; deposit (USD 112) not yet paid** |
| Access | Sylvia said she will invite mosemqute@gmail.com as WordPress Administrator. **Not confirmed received.** No Stripe access (she declined; she exports the minimum herself) |
| Sylvia's latest message | Asked what Stripe data is needed, asked for a restoration test before handover, asked about restoration support costs. Reply drafted (see CORRESPONDENCE.md); **check with Moses whether it was sent** |
| Call | Times offered for Sat 17 to Wed 21 Oct. **Sylvia has not confirmed a time.** Call guide: `project/CALL-GUIDE.md` |
| Book list | v2 (25 titles) sent; Amazon check done 17 Oct; **awaiting Sylvia's answers.** v3 not started |
| Code | Parent theme 1.0.0, child theme 0.1.1, core plugin 0.1.0. Scaffold only. **Nothing is live.** Live site still runs Twenty Twenty |
| Prototype | `prototype/index.html` v3 (single-file preview). Not yet updated with Amazon findings |
| Live site | Untouched. No backups taken yet (no access yet) |

## 4. What to do next (in order)
1. **Confirm with Moses** whether the reply to Sylvia's 17 Oct message was sent, and settle the open policy question: is restoration help free in the first 3 months for any cause, or only for problems from our work? (`project/SUPPORT-AND-RESTORATION.md`)
2. **Hold the call** once Sylvia picks a time (`project/CALL-GUIDE.md`). Capture decisions in `project/DECISIONS.md`.
3. **Wait for acceptance and deposit.** No work on her live site before the signed acceptance page and USD 112 are in.
4. **On first WordPress login, change nothing.** Check: plan (Personal), storage used, how giving works today (Stripe links or WordPress.com Donations block, which costs 8%), theme and plugin upload available, domain registrar, whether a full-database backup plugin (e.g. UpdraftPlus) runs.
5. **Backups** per `docs/BACKUP-AND-LAUNCH.md` section 1, into the private Google Drive folder.
6. **Protect** paid downloads (section 2): upload and activate `sylviasdream-core`, redirects OFF.
7. **Book list v3 + prototype update** from Sylvia's answers (`project/BOOK-CATALOGUE.md`).
8. **Build locally**, prepare live drafts, preview with Sylvia, test purchase/download/gift, balance USD 168, launch, post-launch, restoration test, handover: `project/WORKFLOW.md` phases 6-11.

## 5. Things that must not happen
- No changes to the live site before backups.
- No Stripe edits, ever. Existing subscriptions and payment links stay as they are; old links are re-pointed or switched off only with Sylvia's written OK.
- No free downloads without Sylvia's written approval (including the book-writing guide).
- No publishing of biography, ministry info or "purchases support Heaven on Earth" wording without her approval. Author Support wording stays exactly as she wrote it.
- No street address or town on the site: "Australia" only.
- No passwords requested or accepted.
- No Stripe exports, subscriber or buyer lists, backups, phone numbers or bank details committed to Git.
- Do not promise any fee is zero; WordPress.com pricing shows no WooCommerce fee on Personal, confirmed by a real test purchase before launch.
- Do not mention this repo to Sylvia (Moses's instruction, 16 Oct).
- Never sync a local or staging database over production.

## 6. Where things are that are NOT in this repo
| Item | Where |
|---|---|
| Quotation v2 (.docx), book list v2 (.docx), website audit (.docx) | Moses's Town library, collection "Sylvias Dream". Text versions without private details: `project/QUOTATION.md`, `project/BOOK-CATALOGUE.md`, `project/WEBSITE-AUDIT.md` |
| Call prep guide and spoken call script | Moses's Town documents (summary in `project/CALL-GUIDE.md`) |
| Payment details (bank, M-PESA, PayPal) and phone numbers | Quotation .docx only |
| Stripe exports, subscriber CSV, backups | Private Google Drive folder "Sylvia's Dream - Backups (private)" (to be created) |
| Book covers, ebook files, photos | Coming from Sylvia by email; store privately, then upload to WordPress |

## 7. Repo map
| Path | What |
|---|---|
| `sylviasdream/` | Parent theme (minimal shell, never activated alone) |
| `sylviasdream-child/` | The design. Activate this one |
| `sylviasdream-core/` | Plugin: download-page protection, redirects (off by default), schema, Dream Partner progress shortcode, Settings > Sylvia's Dream |
| `prototype/` | Design preview v3 and `verify.js` (`node prototype/verify.js`) |
| `docs/` | Deployment, backup and launch runbook, client checklist, QA checklist, redirect map |
| `scripts/backup-inventory.sh` | WP-CLI inventory, local copy only |
| `project/` | Brief, decisions, catalogue, quotation scope, audit, Stripe data request, support and restoration, workflow, call guide, correspondence, open items |

## 8. Known gaps in the code
- `php -l` has not been run on the 0.1.1 child theme changes (no PHP in the last build environment). Run it before uploading.
- Book data, covers and prices are placeholders until Sylvia confirms them.
- Protected slugs in `sylviasdream-core` must be checked against Sylvia's payment-link list before activation.

## 9. Keeping this file current
Whoever finishes a step updates section 3, `project/OPEN-ITEMS.md`, `project/DECISIONS.md` and `CHANGELOG.md` in the same commit.
