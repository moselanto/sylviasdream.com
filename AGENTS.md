# AGENTS.md - working on this repository

WordPress site for Sylvia's Dream (sylviasdream.com), hosted on WordPress.com.

## Layout

- `sylviasdream/` - minimal parent theme. Rarely changes. Never activated directly.
- `sylviasdream-child/` - the site design. Most work happens here.
- `sylviasdream-core/` - plugin for anything that must survive a theme change (protection, redirects, schema, giving progress).
- `docs/` - deployment, client checklist, redirect map, QA.

## Rules

1. **Never invent facts.** Book details, prices, charity details, figures and reviews come only from Sylvia or the live site.
2. **Content stays editable in WordPress.** Use Customizer settings, page blocks, WooCommerce products or plugin settings, not literals in PHP.
3. **Escape on output, sanitise on input.** Nonces and `current_user_can()` on every save.
4. **Never touch existing Stripe subscriptions or payment links.** Giving is separate from the shop cart.
5. **Never redirect or delete thank-you / download pages** until past buyers have their new links.
6. **Never sync the staging database over production.** Deploy code only.
7. **Bump versions** (`style.css` header + version constant) for each release, and update `CHANGELOG.md`, the README badges and docs in the same commit.
8. **Accessibility:** one `h1` per page, visible focus, 48px touch targets, alt text on every image, 19px+ body text.
9. Run `php -l` on every changed PHP file before committing.
