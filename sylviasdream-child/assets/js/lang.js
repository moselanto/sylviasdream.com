/**
 * Sylvia's Dream language switcher (1.8.1).
 * - Selector: English is the site language; other languages use Google's website
 *   translation (started from the page head, see inc/languages.php). The choice is
 *   kept in the "googtrans" cookie.
 * - Suggestion: a visitor whose browser language or location (time zone) points to
 *   French, Spanish or Indonesian is offered that language once. Nothing is switched
 *   without their tap, no data leaves the browser, and "No thanks" is remembered.
 */
(function () {
	'use strict';
	var cfg = window.sdLang || { codes: ['en', 'fr', 'es', 'id'], base: 'en' };
	var base = cfg.base || 'en';
	var DISMISS = 'sdLangDismissed';

	function current() {
		var m = document.cookie.match(/(?:^|;\s*)googtrans=\/[a-z-]+\/([a-zA-Z-]+)/);
		var code = m ? m[1] : base;
		return cfg.codes.indexOf(code) > -1 ? code : base;
	}
	function store(k, v) { try { if (v === null) { localStorage.removeItem(k); } else { localStorage.setItem(k, v); } } catch (e) { /* private mode */ } }
	function read(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }

	function writeCookie(value, expire) {
		var tail = '; path=/' + (expire ? '; expires=Thu, 01 Jan 1970 00:00:00 GMT' : '; max-age=31536000') + '; SameSite=Lax';
		var host = window.location.hostname;
		document.cookie = 'googtrans=' + value + tail;
		document.cookie = 'googtrans=' + value + tail + '; domain=' + host;
		document.cookie = 'googtrans=' + value + tail + '; domain=.' + host;
	}
	function choose(code) {
		if (code === base) { writeCookie('', true); } else { writeCookie('/' + base + '/' + code, false); }
		store('sdLang', code);
		window.location.reload();
	}

	var lang = current();
	Array.prototype.forEach.call(document.querySelectorAll('[data-sd-lang]'), function (sel) {
		sel.value = lang;
		sel.addEventListener('change', function () { choose(sel.value); });
	});

	/* ---------- Suggest a language from the browser or the visitor's region ---------- */
	if (lang !== base || read('sdLang') || read(DISMISS)) { return; }

	var ZONES = {
		fr: ['Europe/Paris', 'Europe/Monaco', 'Europe/Luxembourg', 'Africa/Abidjan', 'Africa/Dakar', 'Africa/Bamako', 'Africa/Ouagadougou', 'Africa/Niamey', 'Africa/Conakry', 'Africa/Lome', 'Africa/Porto-Novo', 'Africa/Douala', 'Africa/Libreville', 'Africa/Kinshasa', 'Africa/Lubumbashi', 'Africa/Brazzaville', 'Africa/Bangui', 'Africa/Ndjamena', 'Africa/Bujumbura', 'Africa/Djibouti', 'Africa/Tunis', 'Africa/Algiers', 'Africa/Casablanca', 'Indian/Antananarivo', 'Indian/Reunion', 'Indian/Mayotte', 'Indian/Comoro', 'America/Martinique', 'America/Guadeloupe', 'America/Cayenne', 'America/Port-au-Prince', 'Pacific/Noumea', 'Pacific/Tahiti'],
		es: ['Europe/Madrid', 'Atlantic/Canary', 'Africa/Ceuta', 'Africa/Malabo', 'America/Mexico_City', 'America/Cancun', 'America/Merida', 'America/Monterrey', 'America/Chihuahua', 'America/Tijuana', 'America/Hermosillo', 'America/Mazatlan', 'America/Bogota', 'America/Lima', 'America/Santiago', 'America/Caracas', 'America/Guayaquil', 'America/La_Paz', 'America/Asuncion', 'America/Montevideo', 'America/Guatemala', 'America/El_Salvador', 'America/Tegucigalpa', 'America/Managua', 'America/Costa_Rica', 'America/Panama', 'America/Havana', 'America/Santo_Domingo', 'America/Puerto_Rico'],
		id: ['Asia/Jakarta', 'Asia/Pontianak', 'Asia/Makassar', 'Asia/Jayapura']
	};
	function fromBrowser() {
		var list = navigator.languages && navigator.languages.length ? navigator.languages : [navigator.language || ''];
		for (var i = 0; i < list.length; i++) {
			var c = String(list[i]).toLowerCase().slice(0, 2);
			if (c === 'en') { return null; }          // reads English first: no suggestion from the browser
			if (c === 'in') { c = 'id'; }              // old code for Indonesian
			if (c !== base && cfg.codes.indexOf(c) > -1) { return c; }
		}
		return null;
	}
	function fromZone() {
		var tz = '';
		try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) { return null; }
		if (tz.indexOf('America/Argentina/') === 0) { return 'es'; }
		for (var code in ZONES) { if (ZONES[code].indexOf(tz) > -1 && cfg.codes.indexOf(code) > -1) { return code; } }
		return null;
	}
	var suggest = fromBrowser() || fromZone();
	if (!suggest) { return; }

	var TEXT = {
		fr: ['Ce site est en anglais. Voulez-vous le lire en français ?', 'Lire en français', 'Rester en anglais'],
		es: ['Este sitio está en inglés. ¿Quiere leerlo en español?', 'Leer en español', 'Seguir en inglés'],
		id: ['Situs ini berbahasa Inggris. Ingin membacanya dalam Bahasa Indonesia?', 'Baca dalam Bahasa Indonesia', 'Tetap dalam bahasa Inggris']
	}[suggest];
	if (!TEXT) { return; }

	var box = document.createElement('div');
	box.className = 'sd-lang-offer notranslate';
	box.setAttribute('translate', 'no');
	box.setAttribute('role', 'dialog');
	box.setAttribute('aria-live', 'polite');
	box.setAttribute('aria-label', 'Language');
	box.setAttribute('lang', suggest);
	box.innerHTML = '<p class="sd-lang-offer__text"></p><div class="sd-lang-offer__actions"><button type="button" class="sd-lang-offer__yes"></button><button type="button" class="sd-lang-offer__no"></button></div>';
	box.querySelector('.sd-lang-offer__text').textContent = TEXT[0];
	box.querySelector('.sd-lang-offer__yes').textContent = TEXT[1];
	box.querySelector('.sd-lang-offer__no').textContent = TEXT[2];
	box.querySelector('.sd-lang-offer__yes').addEventListener('click', function () { choose(suggest); });
	box.querySelector('.sd-lang-offer__no').addEventListener('click', function () {
		store(DISMISS, String(Date.now()));
		box.classList.remove('is-visible');
		window.setTimeout(function () { box.remove(); }, 300);
	});
	document.body.appendChild(box);
	window.setTimeout(function () { box.classList.add('is-visible'); }, 900);
}());
