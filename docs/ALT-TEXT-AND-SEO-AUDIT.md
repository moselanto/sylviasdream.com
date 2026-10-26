# Image descriptions (alt text) and search data audit (dev site, 26 Oct 2026)

Theme 1.16.1 active. Checked signed in as admin; nothing changed except the theme update.

## 1. Images
- Media Library: **219 images; 194 have no saved alt text; 25 do.**
- Theme 1.16.1 now fills alt text automatically on screen for book covers that have none ("Book cover of <title> by Sylvia Suhr"): cover blocks on book pages and WooCommerce product images. Checked: One Life shows "Book cover of One Life: The Narrow Path by Sylvia Suhr"; SENT keeps its own alt texts. The saved Media Library field stays empty for these, which is fine for visitors and search engines.
- Almost all of the 194 are "Unattached" (uploaded once, not linked to a post), so many are duplicates or old versions that no page shows. Writing alt text for images no page uses has no benefit.

### Groups (media IDs)
| Group | IDs | What to do |
|---|---|---|
| Current book covers attached to products | 6441-6448 | Covered automatically by the theme |
| Other book cover files (old versions, duplicates, Amazon thumbnails) | 6416, 6413, 6412, 6408, 6407, 6177, 6174, 5869, 5430, 5427, 5321, 5264, 4913, 4906, 4883, 4863, 4851, 4515, 4510, 4462, 4380, 4372, 4207, 4136, 4051, 3936, 3930, 3929, 3922, 3900, 3805, 3801, 3491, 3416, 3106, 3103, 3077, 3076, 3017, 3008, 3005, 3003, 3000, 2999, 2976, 2768, 1258, 759, 642, 641, 627, 625, 624, 623, 622, 621, 491 | Only those used on a page need alt text: set "Book cover of <title> by Sylvia Suhr" (title from the file); leave the rest |
| Logos and site icons | 4953, 4952, 4950, 4949, 3264, 2644 | Logo: "Sylvia's Dream logo"; cropped icons can stay empty (decorative) |
| Ministry and Kenya photos (people, schools, villages) | 4016, 3999, 3988, 3986, 3983, 3637, 3623, 3484, 2740, 2739, 2662, 2089, 2087, 2086, 2084, 2082, 2081, 2079, 2077, 2076, 2075, 1953, 1949, 1947, 1946, 1944, 1943, 1244, 1243, 1079, 648, 647, 644, 583, 582, 426, 139, 49, 38, 4926 | Need a real description of each photo; names of people only with consent. Several carry the caption "dav" (camera default), which also shows on pages |
| Blog and devotional illustrations (ChatGPT images, "Grace for the Journey" art) | 6299, 6242, 6226, 6213, 6144, 6092, 6073, 6061, 6011, 5925, 5870, 5848, 5805, 5765, 5737, 5711, 5665, 5660, 5656, 5639, 5603, 5598, 5300, 5298, 5286, 5278, 5275, 5208, 5205, 5202, 5198, 5194, 5163, 4813, 4811, 4796, 4700, 4689, 4612, 4551, 4359, 4314, 4265, 3826, 3783, 3734, 3709, 3703, 3700, 3271, 3262, 3261, 2704, 2701, 2696, 2646, 2641, 2621, 2460, 2426, 673 | Describe the ones shown on pages and posts (featured images first); decorative backgrounds can stay empty |
| Placeholders, screenshots and junk | 6370 (WooCommerce placeholder), 6106, 5691, 5675, 4240, 3809, 3423, 3259, 709 ("image"), 5929 (QR code), 5885, 5236, 5036, 1369, 1237, 672 (www.yify-torrents.com_), 33, 31, 19, 14, 9, 7 (theme placeholders) | No alt text needed; candidates to delete from the Media Library on live with Sylvia's OK (672 looks unrelated to the site) |

Next step: list which of these images are actually shown on published pages and posts, then write descriptions for those only (book covers first, then ministry photos, then blog images).

## 2. Search data (seven key pages: Home, One Life, My Story, Heaven on Earth, Shop For Books, Grace for the Journey, Contact)
| Check | Result | Note |
|---|---|---|
| Page title | Present on all ("<page> – Sylvia's Dream"; Home "Sylvia's Dream") | Heaven on Earth title is very long (about 85 characters) |
| Meta description | **None on any page** | Needs Sylvia's wording (or her page intro) per page |
| Robots | noindex, nofollow | Correct for the private dev site ("Discourage search engines" on); must be off on live |
| Canonical | Self-referencing on all | Good |
| Open Graph (og:title, og:description, og:image) | **None** | Shared links show no preview; on live Jetpack usually adds these, check after launch |
| Structured data (JSON-LD) | Home only: Person, WebSite, Organization (core plugin) | Book data appears only on published products; pages and posts have none (add Article for posts, BreadcrumbList) |
| H1 count | 1 on every page | Good |

Method: values read from the loaded pages (the browser cannot open view-source).

### Recommended fixes
1. Meta descriptions for every page and book (Sylvia's wording or her own intro sentence), set with an SEO plugin or the core plugin.
2. Open Graph tags (title, description, image) from the core plugin when Jetpack's are not present, so social shares show a picture and text.
3. Article schema on posts and BreadcrumbList on pages (core plugin).
4. Shorten the Heaven on Earth page title for search results (Sylvia to approve).
5. At launch: confirm "Discourage search engines" is OFF on live, submit the sitemap in Search Console and Bing.

## 3. Done 26 Oct (night)
- Alt text saved in the Media Library for 23 images shown on published pages: Heaven on Earth slideshows and gallery (648, 1943, 1944, 1946, 1947, 1949, 2075, 2076, 2077, 2079, 2081, 2082, 2084, 2086, 2087, 2089, 3999; 1949 also on Give), post and page illustrations (6299, 6226, 6144, 6073, 5925, 5603). Descriptions say only what is visible; no names. No other page needed one.
- Core plugin 0.2.0-0.2.1: Open Graph tags and WebPage / BlogPosting / BreadcrumbList data now on pages and posts (checked on My Story and a Grace for the Journey post).
- Theme 1.16.2 shows the saved library alt text in image blocks already on pages.
- Site Health (dev host, not the plugin): "Page cache is not detected and the server response time is slow" (about 4.2 s against 0.6 s). This is the cPanel dev hosting; WordPress.com live has its own caching. Not a launch blocker; check speed on live after launch.
