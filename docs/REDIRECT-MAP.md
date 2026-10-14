# Content audit and redirect map (draft, 13 Oct 2026)

To be confirmed with Sylvia at kick-off. The plugin map lives in `sylviasdream-core/inc/redirects.php`.

## Redirect (301) - retired or duplicate pages

| Old URL | Goes to | Reason |
|---|---|---|
| `/home/` | `/` | Old home page |
| `/new-home/` | `/` | Unfinished home page published publicly |
| `/sylvias-dream/` | `/from-fear-to-freedom-3/` | Menu "Home" pointed here; it is a From Fear to Freedom page |
| `/from-fear-to-freedom/` | `/from-fear-to-freedom-3/` | Duplicate |
| `/the-hidden-key-to-healing-and-breakthrough/` | `/the-hidden-key-to-healing-and-breakthrough-3/` | Duplicate |
| `/how-to-find-the-real-you-2/` | `/how-to-find-the-real-you/` | Duplicate |
| `/my-autobiography/` | `/my-story/` | Merged into About Sylvia |
| `/manifesting-miracles-now/` | `/shop-for-books/` | Outdated "Coming soon" (confirm) |

Final targets will change if book pages move to WooCommerce product URLs; update this table and the plugin together.

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

## Keep as is

- `/author-support-publishing-services/` (recently updated by Sylvia; content retained)
- All blog posts (titles to be made unique; URLs unchanged)
