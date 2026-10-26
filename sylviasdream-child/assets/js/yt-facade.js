/**
 * Sylvia's Dream: click-to-play YouTube facade (1.17.0).
 * Swaps the thumbnail button for the real YouTube iframe on click, and warms
 * up the YouTube connection when the pointer moves over a video.
 */
(function () {
	'use strict';
	var warmed = false;
	function warm() {
		if (warmed) { return; }
		warmed = true;
		['https://www.youtube.com', 'https://www.google.com', 'https://i.ytimg.com'].forEach(function (u) {
			var l = document.createElement('link');
			l.rel = 'preconnect';
			l.href = u;
			document.head.appendChild(l);
		});
	}
	function play(box) {
		var src = box.getAttribute('data-sd-yt');
		if (!src || !/^https:\/\/(www\.)?youtube(-nocookie)?\.com\/embed\//.test(src)) { return; }
		var f = document.createElement('iframe');
		f.src = src;
		f.title = box.getAttribute('data-sd-yt-title') || 'Video';
		f.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
		f.allowFullscreen = true;
		f.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
		box.innerHTML = '';
		box.appendChild(f);
		box.classList.add('is-playing');
		f.focus();
	}
	document.addEventListener('click', function (e) {
		var btn = e.target.closest ? e.target.closest('.sd-yt__btn') : null;
		if (btn) { e.preventDefault(); play(btn.parentNode); }
	});
	document.addEventListener('pointerover', function (e) {
		if (e.target.closest && e.target.closest('.sd-yt')) { warm(); }
	}, { passive: true });
}());
