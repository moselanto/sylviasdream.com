# Contributing

Working rules are in [AGENTS.md](AGENTS.md); start with [HANDOFF.md](HANDOFF.md).

## Workflow
- Commit directly to `main`; one complete change per commit.
- Every release commit includes: the version bump in `sylviasdream-child/style.css` (`Version:`) and `functions.php`, a CHANGELOG entry, README badges when versions change, and the docs affected (ARCHITECTURE, EDITING-GUIDE, QA checklists, OPEN-ITEMS, PROGRESS-LOG).
- Page layouts built on the site are kept in `sylviasdream-child/content/blocks/`.

## Build and check
```bash
node scripts/check-theme.js        # theme.json, block markup, rules
bash scripts/build-theme-zip.sh    # runs the check, then writes dist/*.zip
```
PHP is linted on PHP 8.0-8.3 by GitHub Actions on every push.

## Coding standards
- Presentation in the child theme; behaviour that must survive a theme change in the core plugin.
- Escape all output, sanitise all input, nonce and capability checks on every save.
- Every visible string translatable (`sylviasdream-child` text domain) and editable in WordPress where it is content.
- Accessibility: keyboard, visible focus, labels, 48px targets, reduced motion.

## Releasing
1. Build the zip.
2. Dev: Appearance > Themes > Add New > Upload > Replace active with uploaded.
3. Paste any changed `content/blocks/*.html` into the matching page's Code editor.
4. Run the relevant QA checklist items; record results in `project/PROGRESS-LOG.md`.
5. Live only at launch, per `docs/BACKUP-AND-LAUNCH.md`.
