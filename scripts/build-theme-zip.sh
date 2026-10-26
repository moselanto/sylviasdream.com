#!/usr/bin/env bash
# Build release zips for the child theme and core plugin after the quality gate passes.
# 1.17.0: builds the minified CSS/JS first (scripts/build-assets.js) so the zip ships them.
set -euo pipefail
cd "$(dirname "$0")/.."
node scripts/check-theme.js
node scripts/build-assets.js
ver=$(grep -m1 '^Version:' sylviasdream-child/style.css | awk '{print $2}')
core=$(grep -m1 'Version:' sylviasdream-core/sylviasdream-core.php | awk '{print $NF}')
mkdir -p dist
rm -f "dist/sylviasdream-child-$ver.zip" "dist/sylviasdream-core-$core.zip"
zip -qr "dist/sylviasdream-child-$ver.zip" sylviasdream-child -x '*.DS_Store'
zip -qr "dist/sylviasdream-core-$core.zip" sylviasdream-core -x '*.DS_Store'
echo "Built dist/sylviasdream-child-$ver.zip and dist/sylviasdream-core-$core.zip"
