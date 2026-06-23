(function () {
	'use strict';
	document.addEventListener('click', function (event) {
		var tab = event.target.closest('.vb-tabs-nav button');
		if (!tab) return;
		var wrap = tab.closest('.vb-tabs');
		var index = tab.getAttribute('data-vb-tab');
		wrap.querySelectorAll('.vb-tabs-nav button').forEach(function (btn) { btn.classList.remove('active'); });
		wrap.querySelectorAll('.vb-tab-panel').forEach(function (panel) { panel.classList.remove('active'); });
		tab.classList.add('active');
		var panel = wrap.querySelector('.vb-tab-panel[data-vb-panel="' + index + '"]');
		if (panel) panel.classList.add('active');
	});

	function rotateSlider(slider) {
		var slides = slider.querySelectorAll('.vb-slide');
		if (slides.length < 2) return;
		var current = 0;
		setInterval(function () {
			slides[current].classList.remove('active');
			current = (current + 1) % slides.length;
			slides[current].classList.add('active');
		}, 4500);
	}
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.vb-slider').forEach(rotateSlider);
	});
})();

(function () {
	'use strict';
	function initCountdown(el) {
		var target = new Date((el.getAttribute('data-vb-countdown') || '').replace(' ', 'T'));
		if (isNaN(target.getTime())) return;
		function tick() {
			var diff = Math.max(0, target.getTime() - Date.now());
			var days = Math.floor(diff / 86400000); diff -= days * 86400000;
			var hours = Math.floor(diff / 3600000); diff -= hours * 3600000;
			var minutes = Math.floor(diff / 60000); diff -= minutes * 60000;
			var seconds = Math.floor(diff / 1000);
			var values = { days: days, hours: hours, minutes: minutes, seconds: seconds };
			Object.keys(values).forEach(function (key) {
				var node = el.querySelector('[data-unit="' + key + '"]');
				if (node) node.textContent = String(values[key]).padStart(2, '0');
			});
		}
		tick(); setInterval(tick, 1000);
	}
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.vb-countdown').forEach(initCountdown);
	});
})();

(function () {
	'use strict';

	document.addEventListener('submit', function (event) {
		var form = event.target.closest('.vb-form');
		if (!form) return;
		event.preventDefault();
		var status = form.querySelector('.vb-form-status');
		if (status) status.textContent = 'Sending...';
		var data = new FormData(form);
		data.append('action', 'vb_submit_form');
		data.append('nonce', (window.VB_FRONTEND && VB_FRONTEND.nonce) ? VB_FRONTEND.nonce : '');
		fetch((window.VB_FRONTEND && VB_FRONTEND.ajaxUrl) ? VB_FRONTEND.ajaxUrl : '/wp-admin/admin-ajax.php', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		}).then(function (res) { return res.json(); }).then(function (res) {
			if (status) status.textContent = res && res.data && res.data.message ? res.data.message : (res.success ? 'Sent.' : 'Something went wrong.');
			if (res && res.success) form.reset();
		}).catch(function () { if (status) status.textContent = 'Could not send. Please try again.'; });
	});

	function openPopup(overlay) {
		if (!overlay) return;
		overlay.classList.add('open');
		overlay.setAttribute('aria-hidden', 'false');
	}
	function closePopup(overlay) {
		if (!overlay) return;
		overlay.classList.remove('open');
		overlay.setAttribute('aria-hidden', 'true');
	}

	document.addEventListener('click', function (event) {
		var openBtn = event.target.closest('.vb-popup-open');
		if (openBtn) {
			openPopup(openBtn.closest('.vb-popup-module').querySelector('.vb-popup-overlay'));
		}
		var closeBtn = event.target.closest('.vb-popup-close');
		if (closeBtn) closePopup(closeBtn.closest('.vb-popup-overlay'));
		if (event.target.classList && event.target.classList.contains('vb-popup-overlay')) closePopup(event.target);
	});

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.vb-popup-module').forEach(function (mod) {
			var trigger = mod.getAttribute('data-vb-popup-trigger');
			var overlay = mod.querySelector('.vb-popup-overlay');
			if (trigger === 'timed') {
				var delay = parseInt(mod.getAttribute('data-vb-popup-delay') || '5', 10) * 1000;
				setTimeout(function () { openPopup(overlay); }, delay);
			}
			if (trigger === 'exit_intent') {
				var shown = false;
				document.addEventListener('mouseout', function (e) {
					if (!shown && e.clientY <= 0) { shown = true; openPopup(overlay); }
				});
			}
		});
	});
})();
