#!/usr/bin/env node
/*
 * Build minified copies of the child theme's CSS and JS (no dependencies).
 *   node scripts/build-assets.js
 * Writes style.min.css, assets/css/*.min.css and assets/js/*.min.js next to
 * each source. The theme loads a .min file only when it exists and is at least
 * as new as its source (sdc_asset() in inc/performance.php), so a stale or
 * missing build always falls back to the readable source.
 *
 * The minifier is deliberately conservative: it removes comments and
 * redundant whitespace but never rewrites code, strings, regular expressions
 * or url() values. .min files are build output (see .gitignore).
 */
'use strict';
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..', 'sylviasdream-child');

function minifyCss(src) {
  let out = '';
  let i = 0;
  while (i < src.length) {
    const c = src[i];
    if (c === '/' && src[i + 1] === '*') {
      const end = src.indexOf('*/', i + 2);
      const comment = src.slice(i, end < 0 ? src.length : end + 2);
      if (comment.startsWith('/*!')) out += comment; // keep licence comments
      i = end < 0 ? src.length : end + 2;
      out += ' ';
      continue;
    }
    if (c === '"' || c === "'") {
      let j = i + 1;
      while (j < src.length && src[j] !== c) { if (src[j] === '\\') j++; j++; }
      out += src.slice(i, j + 1);
      i = j + 1;
      continue;
    }
    if (/\s/.test(c)) {
      while (i < src.length && /\s/.test(src[i])) i++;
      out += ' ';
      continue;
    }
    out += c;
    i++;
  }
  // Spaces around braces, semicolons and commas are never significant in CSS.
  // (Spaces around + - * / > ~ : are left alone: calc() and selectors need some.)
  out = out.replace(/\s*([{};,])\s*/g, '$1').replace(/;}/g, '}');
  return out.trim() + '\n';
}

function minifyJs(src) {
  // Remove comments outside strings/regex/template literals, then trim lines and
  // drop blank ones. Line breaks are kept, so automatic semicolon insertion
  // behaves exactly as in the source.
  let out = '';
  let i = 0;
  let last = ''; // last significant char, to tell a regex from a division
  while (i < src.length) {
    const c = src[i];
    const n = src[i + 1];
    if (c === '/' && n === '*') {
      const end = src.indexOf('*/', i + 2);
      i = end < 0 ? src.length : end + 2;
      out += ' ';
      continue;
    }
    if (c === '/' && n === '/') {
      while (i < src.length && src[i] !== '\n') i++;
      continue;
    }
    if (c === '"' || c === "'" || c === '`') {
      let j = i + 1;
      while (j < src.length && src[j] !== c) { if (src[j] === '\\') j++; j++; }
      out += src.slice(i, j + 1);
      i = j + 1;
      last = c;
      continue;
    }
    if (c === '/' && (last === '' || /[(,=:[!&|?{};+\-*%<>~^]/.test(last) || /\b(return|typeof|case|in|of)$/.test(out.trimEnd()))) {
      let j = i + 1;
      let cls = false;
      while (j < src.length) {
        const d = src[j];
        if (d === '\\') { j += 2; continue; }
        if (d === '[') cls = true; else if (d === ']') cls = false;
        else if (d === '/' && !cls) break;
        else if (d === '\n') break;
        j++;
      }
      j++;
      while (j < src.length && /[a-z]/i.test(src[j])) j++;
      out += src.slice(i, j);
      i = j;
      last = '/';
      continue;
    }
    out += c;
    if (!/\s/.test(c)) last = c;
    i++;
  }
  return out.split('\n').map((l) => l.trim()).filter((l) => l !== '').join('\n') + '\n';
}

function walk(dir, out = []) {
  if (!fs.existsSync(dir)) return out;
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) walk(p, out); else out.push(p);
  }
  return out;
}

const targets = [path.join(root, 'style.css')]
  .concat(walk(path.join(root, 'assets', 'css')), walk(path.join(root, 'assets', 'js')))
  .filter((f) => /\.(css|js)$/.test(f) && !/\.min\.(css|js)$/.test(f));

let saved = 0;
for (const f of targets) {
  const src = fs.readFileSync(f, 'utf8');
  const min = f.endsWith('.css') ? minifyCss(src) : minifyJs(src);
  const dest = f.replace(/\.(css|js)$/, '.min.$1');
  fs.writeFileSync(dest, min);
  saved += src.length - min.length;
  console.log(`${path.relative(root, dest)}  ${src.length} -> ${min.length} bytes`);
}
console.log(`Built ${targets.length} minified files, ${Math.round(saved / 1024)} KB smaller in total.`);
