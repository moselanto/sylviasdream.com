# Content audit and redirect map (revised 22 Oct 2026)

Draft from the 13 Oct 2026 audit, corrected on 22 Oct 2026 after a page-by-page content check. The plugin map lives in `sylviasdream-core/inc/redirects.php` (core 0.1.1). Redirects stay OFF until launch day. The map works whether WordPress sits at the domain root or in a subfolder (for example the private preview at `/sylviasdream/`).

## Redirect (301) - retired or duplicate pages

| Old URL | Goes to | Reason |
|---|---|---|
| `/home/` | `/` | Old home page. Its Dream Partner wording is reused on the Give page |
| `/new-home/` | `/` | Unfinished home page published publicly |
| `/sylvias-dream/` | `/from-fear-to-freedom-3/` | From Fear to Freedom duplicate. Its fuller text (incl. the French edition section) is merged into the kept page |
| `/from-fear-to-freedom/` | `/from-fear-to-freedom-3/` | Old attachment / duplicate URL |
| `/the-hidden-key-to-healing-and-breakthrough/` | `/the-hidden-key-to-healing-and-breakthrough-3/` | Duplicate. Its fuller description is merged into the kept page |
| `/my-autobiography/` | `/one-life/` | This is the One Life hardcover book page, not a biography. Unique hardcover text merged into `/one-life/` |

Each old page is set to Draft only after its content is merged, and only when redirects are switched on, so no current URL stops working.

## Removed from the 13 Oct draft

- `/how-to-find-the-real-you-2/` -> was mapped to `/how-to-find-the-real-you/`. It is a **buyer download page** ("Thank you for your purchase ... download my book"), so it is now protected instead of redirected.
- `/my-autobiography/` -> was mapped to `/my-story/`. Corrected to `/one-life/` (see above).
- `/manifesting-miracles-now/` -> was mapped to `/shop-for-books/`. Left out until Sylvia decides whether to keep the "Coming soon" page.

## Protect (keep working, hide from search) - do NOT redirect

Post-purchase / download pages. Listed in `sylviasdream-core/inc/protect-downloads.php`:

- `/thank-you-for-purchasing-the-hidden-key-to-healing-and-breakthrough/`
- `/when-the-word-becomes-life-download/`
- `/thank-you-for-purchasing-learning-to-recognise-gods-voice/`
- `/thank-you-the-report-of-the-lord/`
- `/the-hidden-key-to-healing-and-breakthrough-2/`
- `/thank-you-for-purchasing-guard-your-heart/`
- `/sent-2/`
- `/thank-you-for-your-purchase-2/`
- `/thank-you-angelique-finds-her-light/`
- `/from-chance-to-choice/`
- `/from-fear-to-freedom-2/`
- `/thank-you-the-kings-way/`
- `/thank-you-for-becoming-a-dream-partner/`
- `/all-shall-be-well/`
- `/thank-you-for-your-purchase/`
- `/thank-you-one-life/`
- `/thank-you-angelique/`
- `/your-free-gift/`
- `/how-to-find-the-real-you-2/` (added 22 Oct 2026)

On a site where Settings > Sylvia's Dream has already been saved, add new slugs to the "Download / thank-you page slugs" box by hand; the code list is only the default.

## Keep as is

- `/author-support-publishing-services/` (recently updated by Sylvia; content retained)
- All blog posts (titles to be made unique; URLs unchanged)
- Stripe buy buttons on merged pages are carried across unchanged. Stripe is never edited.
