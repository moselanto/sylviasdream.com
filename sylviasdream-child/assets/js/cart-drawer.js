/**
 * Sylvia's Dream - side cart drawer (1.20.0). Vanilla JS, deferred.
 *
 * - Opens after AJAX add to cart (WooCommerce "added_to_cart" event, shop
 *   card buttons and the single book page form) and from the header cart.
 * - Uses WooCommerce's own wc-ajax endpoints only: add_to_cart,
 *   remove_from_cart and get_refreshed_fragments.
 * - Accessible dialog: focus moves in, Tab is trapped, Escape and the
 *   backdrop close it, focus returns to the opener, page scroll is locked.
 * - Every failure falls back to normal page navigation.
 */
(function () {
	'use strict';

	var cfg = window.SDC_MINICART;
	var doc = document;
	var root = doc.documentElement;
	var drawer = doc.getElementById('sd-minicart');
	if (!cfg || !drawer) { return; }

	var panel = drawer.querySelector('.sd-minicart__panel');
	var status = drawer.querySelector('.sd-minicart__status');
	var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
	var lastFocus = null;
	var refreshed = false;
	var closeTimer = null;

	function endpoint(name) {
		return String(cfg.endpoint).replace('%%endpoint%%', name);
	}

	function announce(text) {
		if (!status) { return; }
		status.textContent = '';
		window.setTimeout(function () { status.textContent = text || ''; }, 60);
	}

	function isOpen() { return !drawer.hidden && drawer.classList.contains('is-open'); }

	function lock(on) {
		if (on) {
			var gap = window.innerWidth - root.clientWidth;
			if (gap > 0) { doc.body.style.paddingRight = gap + 'px'; }
			root.classList.add('sd-lock', 'sd-minicart-open');
		} else {
			root.classList.remove('sd-minicart-open');
			if (!root.classList.contains('sd-nav-open')) {
				root.classList.remove('sd-lock');
				doc.body.style.paddingRight = '';
			}
		}
	}

	function setExpanded(open) {
		var links = doc.querySelectorAll('a.sd-cart');
		for (var i = 0; i < links.length; i++) { links[i].setAttribute('aria-expanded', open ? 'true' : 'false'); }
	}

	function open(focusTarget) {
		if (isOpen()) { return; }
		if (closeTimer) { window.clearTimeout(closeTimer); closeTimer = null; }
		if (root.classList.contains('sd-nav-open')) {
			var navClose = doc.querySelector('.sd-nav__close');
			if (navClose) { navClose.click(); }
		}
		lastFocus = doc.activeElement;
		drawer.hidden = false;
		window.requestAnimationFrame(function () {
			drawer.classList.add('is-open');
		});
		lock(true);
		setExpanded(true);
		window.setTimeout(function () {
			var t = focusTarget || drawer.querySelector('.sd-minicart__close') || panel;
			if (t && t.focus) { t.focus(); }
		}, 60);
		if (!refreshed) { refresh(); }
	}

	function close() {
		if (drawer.hidden) { return; }
		drawer.classList.remove('is-open');
		lock(false);
		setExpanded(false);
		closeTimer = window.setTimeout(function () { drawer.hidden = true; }, 320);
		if (lastFocus && lastFocus.focus && doc.body.contains(lastFocus)) { lastFocus.focus(); }
		lastFocus = null;
	}

	function applyFragments(data) {
		if (!data || !data.fragments) { return false; }
		Object.keys(data.fragments).forEach(function (sel) {
			var nodes;
			try { nodes = doc.querySelectorAll(sel); } catch (e) { return; }
			for (var i = 0; i < nodes.length; i++) {
				var tpl = doc.createElement('template');
				tpl.innerHTML = String(data.fragments[sel]).trim();
				if (tpl.content.firstElementChild) { nodes[i].replaceWith(tpl.content.firstElementChild); }
			}
		});
		var jq = window.jQuery;
		if (jq) {
			try { jq(doc.body).trigger('wc_fragments_refreshed'); } catch (e) { /* ignore */ }
		}
		return true;
	}

	function post(name, params) {
		var body = new URLSearchParams();
		Object.keys(params || {}).forEach(function (k) { body.append(k, params[k]); });
		return window.fetch(endpoint(name), {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		}).then(function (r) {
			if (!r.ok) { throw new Error('HTTP ' + r.status); }
			return r.json();
		});
	}

	function refresh() {
		refreshed = true;
		drawer.classList.add('is-loading');
		return post('get_refreshed_fragments', {}).then(function (data) {
			applyFragments(data);
		}).catch(function () { /* keep the server-rendered content */ })
			.then(function () { drawer.classList.remove('is-loading'); });
	}

	function busy(btn, on) {
		if (!btn) { return; }
		btn.classList.toggle('loading', on);
		btn.classList.toggle('is-loading', on);
		if (on) { btn.setAttribute('aria-busy', 'true'); } else { btn.removeAttribute('aria-busy'); }
		if ('disabled' in btn) { btn.disabled = on; }
	}

	function addToCart(id, qty, btn, fallbackUrl) {
		busy(btn, true);
		return post('add_to_cart', { product_id: id, quantity: qty || 1 }).then(function (data) {
			if (!data || data.error) {
				window.location.href = (data && data.product_url) || fallbackUrl || cfg.cartUrl;
				return;
			}
			if (cfg.redirectToCart) { window.location.href = cfg.cartUrl; return; }
			applyFragments(data);
			refreshed = true;
			var jq = window.jQuery;
			if (jq) {
				try { jq(doc.body).trigger('added_to_cart', [data.fragments, data.cart_hash, null, 'sdc']); } catch (e) { /* ignore */ }
			}
			open();
			announce(cfg.i18n && cfg.i18n.added);
		}).catch(function () {
			window.location.href = fallbackUrl || cfg.cartUrl;
		}).then(function () { busy(btn, false); });
	}

	/* WooCommerce's own AJAX buttons (when "Enable AJAX add to cart buttons
	   on archives" is on) fire added_to_cart through jQuery. */
	if (window.jQuery) {
		window.jQuery(doc.body).on('added_to_cart', function (e, fragments, hash, btn, source) {
			if (source === 'sdc') { return; }
			refreshed = true;
			open();
			announce(cfg.i18n && cfg.i18n.added);
		});
	}

	doc.addEventListener('click', function (e) {
		var t = e.target;
		if (!t || !t.closest) { return; }

		if (t.closest('[data-sd-minicart-close]')) { e.preventDefault(); close(); return; }

		var cartLink = t.closest('a.sd-cart');
		if (cartLink && !e.metaKey && !e.ctrlKey && !e.shiftKey) { e.preventDefault(); open(); return; }

		var remove = t.closest('.sd-minicart__remove');
		if (remove) {
			var key = remove.getAttribute('data-cart_item_key');
			if (!key) { return; }
			e.preventDefault();
			drawer.classList.add('is-loading');
			post('remove_from_cart', { cart_item_key: key }).then(function (data) {
				if (!applyFragments(data)) { window.location.href = remove.href; return; }
				var jq = window.jQuery;
				if (jq) { try { jq(doc.body).trigger('removed_from_cart', [data.fragments, data.cart_hash, null]); } catch (err) { /* ignore */ } }
				announce(cfg.i18n && cfg.i18n.removed);
				var c = drawer.querySelector('.sd-minicart__close');
				if (c) { c.focus(); }
			}).catch(function () { window.location.href = remove.href; })
				.then(function () { drawer.classList.remove('is-loading'); });
			return;
		}

		/* Shop cards when WooCommerce's archive AJAX option is off. */
		var card = t.closest('a.add_to_cart_button.product_type_simple[data-product_id]');
		if (card && !card.classList.contains('ajax_add_to_cart') && !e.metaKey && !e.ctrlKey) {
			e.preventDefault();
			addToCart(card.getAttribute('data-product_id'), card.getAttribute('data-quantity') || 1, card, card.href);
		}
	});

	/* Single book page: add simple products without leaving the page. */
	if (cfg.singleAjax) {
		doc.addEventListener('submit', function (e) {
			var form = e.target;
			if (!form || !form.matches || !form.matches('form.cart')) { return; }
			if (!doc.body.classList.contains('single-product')) { return; }
			if (form.classList.contains('variations_form') || form.classList.contains('grouped_form') || form.querySelector('input[name="variation_id"]')) { return; }
			var btn = form.querySelector('.single_add_to_cart_button');
			var idField = form.querySelector('input[name="add-to-cart"]');
			var id = (btn && btn.name === 'add-to-cart' && btn.value) || (idField && idField.value);
			if (!id || !window.fetch || !window.URLSearchParams) { return; }
			var qtyEl = form.querySelector('input.qty, input[name="quantity"]');
			e.preventDefault();
			addToCart(id, qtyEl ? qtyEl.value : 1, btn, form.action || window.location.href);
		});
	}

	doc.addEventListener('keydown', function (e) {
		if (!isOpen()) { return; }
		if (e.key === 'Escape' || e.key === 'Esc') { e.preventDefault(); close(); return; }
		if (e.key !== 'Tab') { return; }
		var items = Array.prototype.filter.call(panel.querySelectorAll(FOCUSABLE), function (el) {
			return el.offsetWidth || el.offsetHeight || el.getClientRects().length;
		});
		if (!items.length) { e.preventDefault(); panel.focus(); return; }
		var first = items[0];
		var last = items[items.length - 1];
		if (e.shiftKey && (doc.activeElement === first || doc.activeElement === panel)) { e.preventDefault(); last.focus(); }
		else if (!e.shiftKey && doc.activeElement === last) { e.preventDefault(); first.focus(); }
	});
}());
