#!/usr/bin/env bash
# Read-only inventory and database export for sylviasdream.com.
# The live site is on the WordPress.com Personal plan, which has NO SSH or WP-CLI.
# Run this only on the private LOCAL copy (LocalWP / wp-env) after importing the backup,
# to record what the backup contains, and again during the restoration test.
# Changes nothing on the site.
# Output goes to ./sd-backup-YYYY-MM-DD/. Download it to a PRIVATE folder; never commit it to Git.
set -euo pipefail

STAMP="$(date +%F)"
OUT="sd-backup-${STAMP}"
mkdir -p "${OUT}"

echo "Pages and posts..."
wp post list --post_type=page,post --post_status=any \
  --fields=ID,post_title,post_name,post_status,post_modified --format=csv > "${OUT}/inventory.csv"

echo "Media..."
wp post list --post_type=attachment --fields=ID,post_title,guid --format=csv > "${OUT}/media.csv"

echo "Menus..."
wp menu list --format=csv > "${OUT}/menus.csv"
for menu in $(wp menu list --field=term_id); do
  wp menu item list "${menu}" --fields=db_id,title,link --format=csv > "${OUT}/menu-${menu}.csv"
done

echo "Theme settings and widgets..."
wp option get "theme_mods_$(wp option get stylesheet)" --format=json > "${OUT}/theme-mods.json" || true
wp widget list sidebar-1 --format=csv > "${OUT}/widgets.csv" || true
wp plugin list --format=csv > "${OUT}/plugins.csv"
wp theme list --format=csv > "${OUT}/themes.csv"

echo "Author Support page content..."
AS_ID="$(wp post list --post_type=page --name=author-support-publishing-services --field=ID || true)"
if [ -n "${AS_ID}" ]; then
  wp post get "${AS_ID}" --field=post_content > "${OUT}/author-support-content.html"
fi

echo "Database export..."
wp db export "${OUT}/database-${STAMP}.sql"

echo "Done: ${OUT}"
