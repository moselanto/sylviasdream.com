# Launch content pack (for sylviasdream.com)

Generated 26 Oct 2026 from `sylviasdream-child/content/blocks/` with every dev address rewritten for the live site:
- `https://topnotchmall.co.ke/sylviasdream/...` -> `https://sylviasdream.com/...`
- site-relative `/sylviasdream/...` (theme images, buttons) -> `/...`

Paste each file into the matching live page's Code editor during the switch-over window (`docs/MIGRATION-PLAN.md` Phase C). Never paste the `content/blocks/` versions on live: those are for the dev site.

Regenerate this folder whenever a file in `content/blocks/` changes (same two replacements), and keep `content/blocks/` as the source.

Before pasting:
1. Every page edited directly on dev must first be copied back into `content/blocks/` (MIGRATION-PLAN blocker 3).
2. Images below marked "Upload to live first" must be in the live Media Library.
3. Book titles and subtitles follow Amazon (see `project/BOOK-CATALOGUE.md`, Amazon check 26 Oct).

## Images used by these pages
| File (under /wp-content/uploads/) | Status | Used in |
|---|---|---|
| `2023/10/kenya-family.jpg` | Already on live (imported from live); check it opens | give.html |
| `2026/02/chatgpt-image-feb-10-2026-11_05_30-am.jpg` | Already on live (imported from live); check it opens | book-from-fear-to-freedom.html |
| `2026/03/Kenya-manual-pic.jpg` | Already on live (imported from live); check it opens | kenya-ministry-training-manual.html |
| `2026/03/Kenya_Training_Manual.pdf` | Already on live (imported from live); check it opens | kenya-ministry-training-manual.html |
| `2026/03/sent-ebook-final.pdf` | Already on live (imported from live); check it opens | book-sent.html |
| `2026/05/PENDAHULUAN-Sent-Bahasa.pdf` | Already on live (imported from live); check it opens | book-sent.html |
| `2026/05/Sent-Indonesian-1-683x1024.png` | Already on live (imported from live); check it opens | book-sent.html |
| `2026/10/ChatGPT-Image-Oct-1-2026-10_29_13-PM-1024x768.png` | **Upload to live first** (added on dev Oct 2026); then fix the address if WordPress files it under another month | heaven-on-earth.html |
| `2026/10/ChatGPT-Image-Oct-9-2026-01_24_56-PM-1024x576.png` | **Upload to live first** (added on dev Oct 2026); then fix the address if WordPress files it under another month | author-support.html |
| `2026/10/from-fear-to-freedom.png` | **Upload to live first** (added on dev Oct 2026); then fix the address if WordPress files it under another month | book-from-fear-to-freedom.html |
| `2026/10/one-life-683x1024.png` | **Upload to live first** (added on dev Oct 2026); then fix the address if WordPress files it under another month | book-one-life.html |
| `2026/10/sent-683x1024.png` | **Upload to live first** (added on dev Oct 2026); then fix the address if WordPress files it under another month | book-sent.html |
