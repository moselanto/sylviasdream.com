#!/usr/bin/env node
/*
 * Repository checks that need no PHP:
 *  - every *.json in the themes parses
 *  - block comments (<!-- wp:x --> ... <!-- /wp:x -->) are balanced and
 *    correctly nested in patterns/*.php and content/blocks/*.html
 *  - block attribute JSON parses
 *  - no CSS class names starting with "ad-" (ad blockers hide them)
 *  - style.css Version matches SYLVIASDREAM_CHILD_VERSION
 * Run: node scripts/check-theme.js
 */
'use strict';
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const errors = [];
const err = (file, msg) => errors.push(`${path.relative(root, file)}: ${msg}`);

function walk(dir, out = []) {
  if (!fs.existsSync(dir)) return out;
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    if (e.name === 'node_modules' || e.name.startsWith('.')) continue;
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p, out); else out.push(p);
  }
  return out;
}

const themeDirs = ['sylviasdream', 'sylviasdream-child', 'sylviasdream-core'].map((d) => path.join(root, d));
const files = themeDirs.flatMap((d) => walk(d));

// 1. JSON
for (const f of files.filter((f) => f.endsWith('.json'))) {
  try { JSON.parse(fs.readFileSync(f, 'utf8')); } catch (e) { err(f, `invalid JSON: ${e.message}`); }
}

// 2. Block markup
function checkBlocks(file, text) {
  // Strip PHP so translated strings inside attributes do not confuse the parser.
  const src = text.replace(/<\?php[\s\S]*?\?>/g, 'X');
  const re = /<!--\s+(\/)?wp:([a-z0-9-]+(?:\/[a-z0-9-]+)?)(\s+(\{[\s\S]*?\}))?\s*(\/)?-->/g;
  const stack = [];
  let m;
  let count = 0;
  while ((m = re.exec(src))) {
    count++;
    const [, closing, name, , attrs, selfClosing] = m;
    if (attrs) {
      try { JSON.parse(attrs); } catch (e) { err(file, `bad attributes on wp:${name}: ${attrs.slice(0, 80)}`); }
    }
    if (selfClosing) continue;
    if (closing) {
      const top = stack.pop();
      if (top !== name) { err(file, `closing wp:${name} but expected ${top ? 'wp:' + top : 'nothing'}`); return count; }
    } else {
      stack.push(name);
    }
  }
  if (stack.length) err(file, `unclosed blocks: ${stack.join(', ')}`);
  return count;
}
const blockFiles = files.filter((f) => /[\\/]patterns[\\/].+\.php$/.test(f) || /[\\/]content[\\/]blocks[\\/].+\.html$/.test(f));
for (const f of blockFiles) {
  const n = checkBlocks(f, fs.readFileSync(f, 'utf8'));
  if (n === 0) err(f, 'no blocks found');
}

// 3. No "ad-" class names
for (const f of files.filter((f) => /\.(css|php|html|js)$/.test(f))) {
  const t = fs.readFileSync(f, 'utf8');
  const hits = t.match(/(?:class(?:Name)?=["'][^"']*\bad-[a-z]|\.ad-[a-z][\w-]*\s*[{,:])/g);
  if (hits) err(f, `class starting with "ad-": ${hits[0]}`);
}

// 4. Child version consistency
const child = path.join(root, 'sylviasdream-child');
if (fs.existsSync(path.join(child, 'style.css'))) {
  const css = fs.readFileSync(path.join(child, 'style.css'), 'utf8').match(/^Version:\s*(\S+)/m);
  const php = fs.readFileSync(path.join(child, 'functions.php'), 'utf8').match(/SYLVIASDREAM_CHILD_VERSION',\s*'([^']+)'/);
  if (!css || !php || css[1] !== php[1]) err(child, `version mismatch: style.css ${css && css[1]} vs functions.php ${php && php[1]}`);
}

if (errors.length) {
  console.error(errors.map((e) => `ERROR ${e}`).join('\n'));
  process.exit(1);
}
console.log(`OK: ${files.length} files checked, ${blockFiles.length} block files.`);
