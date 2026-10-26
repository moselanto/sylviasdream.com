/**
 * Sylvia's Dream language switcher (1.8.0).
 * English is the site language; other languages use Google's website translation,
 * loaded only when a visitor chooses one. The choice is kept in the "googtrans" cookie.
 */
(function () {
	'use strict';
	var cfg = window.sdLang || { codes: ['en', 'fr', 'es', 'id'], base: 'en' };
	var base = cfg.base || 'en';
	var selects = document.querySelectorAll('[data-sd-lang]');
	if (!selects.length) { return; }

	function current() {
		var m = document.cookie.match(/(?:^|;\s*)googtrans=\/[a-z-]+\/([a-zA-Z-]+)/);
		var code = m ? m[1] : base;
		return cfg.codes.indexOf(code) > -1 ? code : base;
	}

	function writeCookie(value, expire) {
		var tail = '; path=/' + (expire ? '; expires=Thu, 01 Jan 1970 00:00:00 GMT' : '; max-age=31536000') + '; SameSite=Lax';
		var host = window.location.hostname;
		document.cookie = 'googtrans=' + value + tail;
		document.cookie = 'googtrans=' + value + tail + '; domain=' + host;
		document.cookie = 'googtrans=' + value + tail + '; domain=.' + host;
	}

	function choose(code) {
		if (code === base) {
			writeCookie('', true);
		} else {
			writeCookie('/' + base + '/' + code, false);
		}
		try { window.localStorage.setItem('sdLang', code); } catch (e) { /* private mode */ }
		window.location.reload();
	}

	var lang = current();
	Array.prototype.forEach.call(selects, function (sel) {
		sel.value = lang;
		sel.addEventListener('change', function () { choose(sel.value); });
	});

	if (lang === base) { return; }

	// Load Google's translator only for a non-English choice.
	document.documentElement.classList.add('sd-translated');
	var holder = document.createElement('div');
	holder.id = 'sd-gt';
	holder.hidden = true;
	document.body.appendChild(holder);
	window.sdGoogleInit = function () {
		if (!window.google || !window.google.translate) { return; }
		/* global google */
		new google.translate.TranslateElement({
			pageLanguage: base,
			includedLanguages: cfg.codes.join(','),
			autoDisplay: false
		}, 'sd-gt');
	};
	var s = document.createElement('script');
	s.src = 'https://translate.google.com/translate_a/element.js?cb=sdGoogleInit';
	s.async = true;
	document.body.appendChild(s);
}());
