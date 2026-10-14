# Sylvia's Dream (child theme)

The actual site design. **Activate this theme** (the parent `sylviasdream` must also be installed).

## Editing map

| What | Where in WordPress |
|---|---|
| Home hero text, buttons and photo | Appearance > Customise > Sylvia's Dream |
| Home sections below the hero | Edit the Home page (blocks) |
| Main menu (6-7 items) | Appearance > Menus > Primary Navigation |
| Footer link columns | Appearance > Menus > Footer menus |
| Footer newsletter | Appearance > Widgets > Footer: Newsletter sign-up |
| Contact email and social links | Appearance > Customise > Sylvia's Dream |
| Books, prices, ebook files | Products (WooCommerce) |
| Dream Partner count and goal | Settings > Sylvia's Dream (plugin) |

## File map

| File | Purpose |
|---|---|
| `style.css` | Theme header and design system (tokens, header, hero, cards, footer) |
| `functions.php` | Version, assets, WooCommerce support, includes |
| `inc/customizer.php` | Editable hero, contact and social settings |
| `inc/template-tags.php` | Social links and link helpers |
| `inc/widgets.php` | Footer newsletter widget area |
| `header.php` / `footer.php` | Site header with mobile menu, full footer |
| `front-page.php` | Home page: Customizer hero + page blocks |
| `page.php` | Standard page |
| `woocommerce.php` | Shop wrapper |
| `assets/js/nav.js` | Mobile menu toggle |
