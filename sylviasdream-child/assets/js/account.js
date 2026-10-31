/*
 * Sylvia's Dream child theme 1.23.0: My account sign-in helpers.
 *  - Show/hide password buttons (aria-pressed) inside .sd-pw.
 *  - Sign in / Create account tabs on phones when registration is on
 *    (desktop shows both cards side by side; without JS both are stacked).
 * No dependencies. Loaded only on the My account page.
 */
(function () {
	'use strict';

	function initPasswords(root) {
		var buttons = root.querySelectorAll('[data-sd-pw-toggle]');
		Array.prototype.forEach.call(buttons, function (btn) {
			var input = document.getElementById(btn.getAttribute('aria-controls'));
			if (!input) {
				return;
			}
			var text = btn.querySelector('.sd-pw__text');
			btn.hidden = false;
			btn.closest('.sd-pw').classList.add('sd-pw--ready');
			btn.addEventListener('click', function () {
				var show = input.type === 'password';
				input.type = show ? 'text' : 'password';
				btn.setAttribute('aria-pressed', show ? 'true' : 'false');
				if (text) {
					text.textContent = show ? text.getAttribute('data-hide') : text.getAttribute('data-show');
				}
			});
		});
		// Password fields go back to hidden before the form is sent.
		Array.prototype.forEach.call(root.querySelectorAll('form'), function (form) {
			form.addEventListener('submit', function () {
				Array.prototype.forEach.call(form.querySelectorAll('[data-sd-pw-toggle][aria-pressed="true"]'), function (btn) {
					btn.click();
				});
			});
		});
	}

	function initTabs(root) {
		var tabsWrap = root.querySelector('[data-sd-auth-tabs]');
		if (!tabsWrap) {
			return;
		}
		var tabs = tabsWrap.querySelectorAll('[data-sd-tab]');
		var panels = root.querySelectorAll('[data-sd-panel]');
		var mq = window.matchMedia('(max-width: 899px)');
		var current = root.getAttribute('data-sd-start') === 'register' ? 'register' : 'login';

		function select(name, focus) {
			current = name;
			Array.prototype.forEach.call(tabs, function (tab) {
				var on = tab.getAttribute('data-sd-tab') === name;
				tab.setAttribute('aria-selected', on ? 'true' : 'false');
				tab.tabIndex = on ? 0 : -1;
				if (on && focus) {
					tab.focus();
				}
			});
			Array.prototype.forEach.call(panels, function (panel) {
				panel.hidden = mq.matches && panel.getAttribute('data-sd-panel') !== name;
			});
		}

		function apply() {
			if (mq.matches) {
				tabsWrap.hidden = false;
				tabsWrap.setAttribute('role', 'tablist');
				tabsWrap.setAttribute('aria-label', tabsWrap.getAttribute('data-label') || '');
				Array.prototype.forEach.call(tabs, function (tab) {
					tab.setAttribute('role', 'tab');
				});
				Array.prototype.forEach.call(panels, function (panel) {
					panel.setAttribute('role', 'tabpanel');
					panel.setAttribute('aria-labelledby', 'sd-tab-' + panel.getAttribute('data-sd-panel'));
				});
				root.classList.add('sd-auth--tabs');
				select(current, false);
			} else {
				tabsWrap.hidden = true;
				tabsWrap.removeAttribute('role');
				Array.prototype.forEach.call(tabs, function (tab) {
					tab.removeAttribute('role');
					tab.removeAttribute('aria-selected');
					tab.removeAttribute('tabindex');
				});
				Array.prototype.forEach.call(panels, function (panel) {
					panel.hidden = false;
					panel.removeAttribute('role');
					panel.removeAttribute('aria-labelledby');
				});
				root.classList.remove('sd-auth--tabs');
			}
		}

		Array.prototype.forEach.call(tabs, function (tab, i) {
			tab.addEventListener('click', function () {
				select(tab.getAttribute('data-sd-tab'), false);
			});
			tab.addEventListener('keydown', function (e) {
				var next = null;
				if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
					next = tabs[(i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length];
				} else if (e.key === 'Home') {
					next = tabs[0];
				} else if (e.key === 'End') {
					next = tabs[tabs.length - 1];
				}
				if (next) {
					e.preventDefault();
					select(next.getAttribute('data-sd-tab'), true);
				}
			});
		});

		// A notice (e.g. a registration error) is shown above the cards; keep
		// the tab that matches the form that was sent.
		if (mq.addEventListener) {
			mq.addEventListener('change', apply);
		} else if (mq.addListener) {
			mq.addListener(apply);
		}
		apply();
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-sd-auth]'), function (root) {
			initPasswords(root);
			initTabs(root);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
