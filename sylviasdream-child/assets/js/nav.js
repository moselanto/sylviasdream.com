/**
 * Sylvia's Dream - header behaviour (vanilla JS, no dependencies, deferred).
 *
 * - Sticky header gains .is-scrolled after scrolling (shrink + shadow).
 * - Desktop (>= 1024px): dropdowns open on hover (CSS) and by keyboard /
 *   click on the submenu toggle; Escape closes and returns focus.
 * - Mobile and tablet (< 1024px): hamburger opens a slide-in drawer with
 *   accordion submenus, focus trap, Escape / overlay close and body scroll lock.
 * - Search panel toggle.
 */
(function () {
	'use strict';

	var doc = document;
	var root = doc.documentElement;
	var header = doc.getElementById('sd-header');
	if (!header) { return; }

	var nav = doc.getElementById('sd-primary-nav');
	var toggle = header.querySelector('.sd-nav-toggle');
	var overlay = doc.querySelector('.sd-overlay');
	var desktop = window.matchMedia('(min-width: 1024px)');
	var lastFocus = null;
	var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	/* ---------- Sticky header state ---------- */
	var ticking = false;
	function onScroll() {
		if (ticking) { return; }
		ticking = true;
		window.requestAnimationFrame(function () {
			header.classList.toggle('is-scrolled', (window.scrollY || window.pageYOffset) > 12);
			ticking = false;
		});
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	/* ---------- Drawer (mobile / tablet) ---------- */
	var dockMore = doc.querySelector('[data-sd-dock-more]');

	function setToggleLabel(open) {
		if (dockMore) { dockMore.setAttribute('aria-expanded', open ? 'true' : 'false'); }
		if (!toggle) { return; }
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		var label = toggle.querySelector('.screen-reader-text');
		if (label) {
			label.textContent = open ? (toggle.getAttribute('data-label-close') || 'Close menu') : (toggle.getAttribute('data-label-open') || label.getAttribute('data-open') || 'Open menu');
		}
	}

	function lockScroll(lock) {
		if (lock) {
			var gap = window.innerWidth - root.clientWidth;
			if (gap > 0) { doc.body.style.paddingRight = gap + 'px'; }
			root.classList.add('sd-lock');
		} else {
			root.classList.remove('sd-lock');
			doc.body.style.paddingRight = '';
		}
	}

	function isOpen() {
		return !!nav && nav.classList.contains('is-open');
	}

	function openDrawer() {
		if (!nav || desktop.matches) { return; }
		lastFocus = doc.activeElement;
		closeSearch(false);
		nav.classList.add('is-open');
		root.classList.add('sd-nav-open');
		if (overlay) {
			overlay.hidden = false;
			window.requestAnimationFrame(function () { overlay.classList.add('is-visible'); });
		}
		setToggleLabel(true);
		lockScroll(true);
		var first = nav.querySelector('.sd-nav__close') || nav.querySelector(FOCUSABLE);
		if (first) { window.setTimeout(function () { first.focus(); }, 50); }
	}

	function closeDrawer(returnFocus) {
		if (!nav || !isOpen()) { return; }
		nav.classList.remove('is-open');
		root.classList.remove('sd-nav-open');
		if (overlay) {
			overlay.classList.remove('is-visible');
			overlay.hidden = true;
		}
		setToggleLabel(false);
		lockScroll(false);
		if (returnFocus !== false) {
			(toggle || lastFocus || doc.body).focus();
		}
	}

	if (toggle && nav) {
		var srLabel = toggle.querySelector('.screen-reader-text');
		if (srLabel) { toggle.setAttribute('data-label-open', srLabel.textContent); }
		var closeBtn = nav.querySelector('.sd-nav__close .screen-reader-text');
		if (closeBtn) { toggle.setAttribute('data-label-close', closeBtn.textContent); }

		toggle.addEventListener('click', function () {
			if (isOpen()) { closeDrawer(); } else { openDrawer(); }
		});
	}

	// Phone bottom bar: "More" opens and closes the same menu drawer.
	if (dockMore && nav) {
		dockMore.addEventListener('click', function () {
			if (isOpen()) { closeDrawer(false); dockMore.focus(); } else { openDrawer(); }
		});
	}

	doc.addEventListener('click', function (e) {
		var closer = e.target.closest ? e.target.closest('[data-sd-close]') : null;
		if (closer) { closeDrawer(); }
	});

	// Focus trap inside the open drawer.
	doc.addEventListener('keydown', function (e) {
		if (e.key !== 'Tab' || !isOpen()) { return; }
		var items = Array.prototype.filter.call(nav.querySelectorAll(FOCUSABLE), function (el) {
			return el.offsetWidth > 0 || el.offsetHeight > 0 || el === doc.activeElement;
		});
		if (!items.length) { return; }
		var first = items[0];
		var last = items[items.length - 1];
		if (!nav.contains(doc.activeElement)) {
			e.preventDefault();
			first.focus();
		} else if (e.shiftKey && doc.activeElement === first) {
			e.preventDefault();
			last.focus();
		} else if (!e.shiftKey && doc.activeElement === last) {
			e.preventDefault();
			first.focus();
		}
	});

	// Leaving the mobile range closes the drawer.
	function onBreakpoint() {
		if (desktop.matches) {
			closeDrawer(false);
		}
		closeAllSubs();
	}
	if (desktop.addEventListener) {
		desktop.addEventListener('change', onBreakpoint);
	} else if (desktop.addListener) {
		desktop.addListener(onBreakpoint);
	}

	/* ---------- Submenus ---------- */
	var subToggles = header.querySelectorAll('.sd-subtoggle');

	function setSub(li, open) {
		var btn = li.querySelector(':scope > .sd-subtoggle');
		li.classList.toggle('is-open', open);
		li.classList.remove('is-closed');
		if (btn) { btn.setAttribute('aria-expanded', open ? 'true' : 'false'); }
	}

	function closeAllSubs(except) {
		Array.prototype.forEach.call(header.querySelectorAll('.sd-menu > li.is-open'), function (li) {
			if (li !== except) { setSub(li, false); }
		});
	}

	Array.prototype.forEach.call(subToggles, function (btn) {
		var li = btn.parentElement;
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			var open = !li.classList.contains('is-open');
			if (desktop.matches) {
				closeAllSubs(li);
				// A hovered menu that is clicked shut should stay shut until the pointer leaves.
				if (!open) { li.classList.add('is-closed'); }
			}
			setSub(li, open);
			if (!open && desktop.matches) { li.classList.add('is-closed'); }
		});

		// Keep aria-expanded in step with hover on desktop.
		li.addEventListener('mouseenter', function () {
			if (desktop.matches && !li.classList.contains('is-closed')) { btn.setAttribute('aria-expanded', 'true'); }
		});
		li.addEventListener('mouseleave', function () {
			if (desktop.matches) {
				li.classList.remove('is-closed');
				if (!li.classList.contains('is-open')) { btn.setAttribute('aria-expanded', 'false'); }
			}
		});

		// Desktop: leaving the item with the keyboard closes its submenu.
		li.addEventListener('focusout', function (e) {
			if (desktop.matches && e.relatedTarget && !li.contains(e.relatedTarget)) {
				setSub(li, false);
			}
		});
	});

	// Click outside closes desktop dropdowns.
	doc.addEventListener('click', function (e) {
		if (desktop.matches && !e.target.closest('.sd-menu > li')) { closeAllSubs(); }
	});

	/* ---------- Search panel ---------- */
	var searchBtn = header.querySelector('.sd-search-toggle');
	var searchPanel = doc.getElementById('sd-search-panel');

	function closeSearch(returnFocus) {
		if (!searchPanel || searchPanel.hidden) { return; }
		searchPanel.hidden = true;
		searchBtn.setAttribute('aria-expanded', 'false');
		if (returnFocus !== false) { searchBtn.focus(); }
	}

	if (searchBtn && searchPanel) {
		searchBtn.addEventListener('click', function () {
			if (searchPanel.hidden) {
				closeDrawer(false);
				searchPanel.hidden = false;
				searchBtn.setAttribute('aria-expanded', 'true');
				var input = searchPanel.querySelector('input[type="search"]');
				if (input) { input.focus(); }
			} else {
				closeSearch();
			}
		});
	}

	/* ---------- Escape ---------- */
	doc.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape' && e.key !== 'Esc') { return; }
		if (isOpen()) {
			// In the drawer, Escape first collapses an open accordion item it is inside.
			closeDrawer();
			return;
		}
		if (searchPanel && !searchPanel.hidden) {
			closeSearch();
			return;
		}
		var openLi = header.querySelector('.sd-menu > li.is-open, .sd-menu > li:focus-within, .sd-menu > li:hover');
		if (openLi) {
			var btn = openLi.querySelector(':scope > .sd-subtoggle');
			setSub(openLi, false);
			openLi.classList.add('is-closed');
			if (btn) { btn.focus(); }
		}
	});
})();
