// Headless verification harness: renders every route with a stub DOM,
// collects internal links and confirms each resolves to a real page (not the 404).
const fs = require('fs');
const html = fs.readFileSync('/home/user/proto/index.html', 'utf8');
const js = html.split('<script>')[1].split('</script>')[0];

function stub(name) {
  const handler = {
    get(t, p) {
      if (p in t) return t[p];
      if (p === Symbol.toPrimitive) return () => '';
      return stub(name + '.' + String(p));
    },
    set(t, p, v) { t[p] = v; return true; },
    apply() { return stub(name + '()'); }
  };
  const f = function () {};
  f.classList = { add() {}, remove() {}, toggle() { return false; }, contains() { return false; } };
  f.style = { setProperty() {} };
  f.addEventListener = () => {};
  f.setAttribute = () => {};
  f.getAttribute = () => null;
  f.removeAttribute = () => {};
  f.getBoundingClientRect = () => ({ bottom: 116 });
  f.focus = () => {};
  f.closest = () => null;
  f.hidden = true;
  f.value = '';
  f.textContent = '';
  f.innerHTML = '';
  return new Proxy(f, handler);
}
const nodes = {};
const listeners = {};
global.document = {
  querySelector(s) { return nodes[s] || (nodes[s] = stub(s)); },
  querySelectorAll() { return []; },
  addEventListener() {},
  documentElement: stub('docEl'),
  activeElement: { tagName: 'BODY' },
  title: ''
};
global.window = {
  addEventListener(ev, fn) { (listeners[ev] = listeners[ev] || []).push(fn); },
  scrollTo() {}, innerWidth: 1280, scrollY: 0
};
global.location = { hash: '#/' };
const store = {};
global.localStorage = { getItem: k => store[k] || null, setItem: (k, v) => { store[k] = v; } };
global.sessionStorage = global.localStorage;
global.IntersectionObserver = function () { return { observe() {}, unobserve() {} }; };
global.setTimeout = () => 0; global.clearTimeout = () => {};

const errors = [];
try { eval(js); } catch (e) { errors.push('LOAD: ' + e.stack); }

function render(hash) {
  location.hash = hash;
  try { (listeners.hashchange || []).forEach(f => f()); }
  catch (e) { errors.push(hash + ': ' + e.message); return ''; }
  return String(nodes['#main'].innerHTML || '');
}
const header = html.split('<main')[0] + (nodes['#megabooks'] ? String(nodes['#megabooks'].innerHTML) : '');
const footer = html.split('</main>')[1].split('<script>')[0];

const seen = new Set();
const queue = ['#/'];
const results = [];
const linkRe = /href="(#\/[^"]*)"/g;
let m;
while ((m = linkRe.exec(header + footer))) queue.push(m[1]);
while (queue.length) {
  const h = queue.shift();
  if (seen.has(h)) continue;
  seen.add(h);
  // put something in the cart so cart/checkout/thank-you render fully
  if (h === '#/cart' || h === '#/checkout') store.sd_cart = JSON.stringify([{ s: 'one-life', q: 1 }]);
  const out = render(h);
  const is404 = out.indexOf('This path does not lead anywhere') >= 0;
  const links = [];
  let x; const r = /href="(#\/[^"]*)"/g;
  while ((x = r.exec(out))) { links.push(x[1]); queue.push(x[1]); }
  const imgs = (out.match(/<img /g) || []).length;
  const emptyImg = (out.match(/src=""/g) || []).length;
  results.push({ h, is404, len: out.length, links: links.length, imgs, emptyImg, title: document.title });
}
const mg=nodes['#megabooks']?String(nodes['#megabooks'].innerHTML):'';console.log('MEGA links: '+(mg.match(/href=/g)||[]).length);results.forEach(r => console.log((r.is404 ? 'FAIL-404 ' : 'OK       ') + r.h.padEnd(48) + ' links=' + String(r.links).padStart(3) + ' imgs=' + r.imgs + (r.emptyImg ? ' EMPTYIMG=' + r.emptyImg : '') + '  | ' + r.title));
console.log('\nPages checked: ' + results.length + ', 404s: ' + results.filter(r => r.is404).length + ', errors: ' + errors.length);
errors.forEach(e => console.log('ERR ' + e));
// pages with no outgoing links (dead ends)
const dead = results.filter(r => r.links === 0 && r.is404 === false).map(r => r.h);
console.log('Dead-end pages: ' + (dead.length ? dead.join(', ') : 'none'));
