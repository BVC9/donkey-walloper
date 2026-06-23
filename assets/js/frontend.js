(function () {
	'use strict';

	var activePopup = null;
	var lastFocusedBeforePopup = null;

	function activateTab(tab, shouldFocus) {
		var wrap = tab.closest('.vb-tabs');
		if (!wrap) return;
		var index = tab.getAttribute('data-vb-tab');

		wrap.querySelectorAll('.vb-tabs-nav button').forEach(function (btn) {
			var isActive = btn === tab;
			btn.classList.toggle('active', isActive);
			btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
			btn.setAttribute('tabindex', isActive ? '0' : '-1');
		});

		wrap.querySelectorAll('.vb-tab-panel').forEach(function (panel) {
			var isActive = panel.getAttribute('data-vb-panel') === index;
			panel.classList.toggle('active', isActive);
			panel.hidden = !isActive;
		});

		if (shouldFocus) tab.focus();
	}

	function initTabs(wrap, wrapIndex) {
		var tabs = wrap.querySelectorAll('.vb-tabs-nav button');
		var panels = wrap.querySelectorAll('.vb-tab-panel');
		var baseId = wrap.getAttribute('data-vb-tabs') || 'vb-tabs-' + wrapIndex;

		wrap.setAttribute('data-vb-tabs', baseId);
		var nav = wrap.querySelector('.vb-tabs-nav');
		if (nav) nav.setAttribute('role', 'tablist');

		tabs.forEach(function (tab, index) {
			var panel = panels[index];
			var isActive = tab.classList.contains('active') || index === 0;
			var tabId = tab.id || baseId + '-tab-' + (index + 1);
			var panelId = panel && panel.id ? panel.id : baseId + '-panel-' + (index + 1);

			tab.id = tabId;
			tab.setAttribute('role', 'tab');
			tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
			tab.setAttribute('aria-controls', panelId);
			tab.setAttribute('tabindex', isActive ? '0' : '-1');

			if (panel) {
				panel.id = panelId;
				panel.setAttribute('role', 'tabpanel');
				panel.setAttribute('aria-labelledby', tabId);
				panel.hidden = !isActive;
				panel.classList.toggle('active', isActive);
			}
		});
	}

	document.addEventListener('click', function (event) {
		var tab = event.target.closest('.vb-tabs-nav button');
		if (tab) activateTab(tab, false);
	});

	document.addEventListener('keydown', function (event) {
		var tab = event.target.closest('.vb-tabs-nav button');
		if (!tab) return;

		var tabs = Array.prototype.slice.call(tab.closest('.vb-tabs-nav').querySelectorAll('button'));
		var current = tabs.indexOf(tab);
		var next = current;

		if (event.key === 'ArrowRight' || event.key === 'ArrowDown') next = (current + 1) % tabs.length;
		else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') next = (current - 1 + tabs.length) % tabs.length;
		else if (event.key === 'Home') next = 0;
		else if (event.key === 'End') next = tabs.length - 1;
		else if (event.key === 'Enter' || event.key === ' ') next = current;
		else return;

		event.preventDefault();
		activateTab(tabs[next], true);
	});

	function initSlider(slider) {
		var slides = slider.querySelectorAll('.vb-slide');
		if (slides.length < 2) return;

		var current = 0;
		for (var i = 0; i < slides.length; i++) {
			if (slides[i].classList.contains('active')) {
				current = i;
				break;
			}
		}
		var timer = null;
		var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function setSlide(index) {
			slides[current].classList.remove('active');
			slides[current].setAttribute('aria-hidden', 'true');
			current = index;
			slides[current].classList.add('active');
			slides[current].setAttribute('aria-hidden', 'false');
		}

		function start() {
			if (reduceMotion || timer) return;
			timer = setInterval(function () {
				setSlide((current + 1) % slides.length);
			}, 4500);
		}

		function stop() {
			if (!timer) return;
			clearInterval(timer);
			timer = null;
		}

		slider.addEventListener('mouseenter', stop);
		slider.addEventListener('mouseleave', start);
		slider.addEventListener('focusin', stop);
		slider.addEventListener('focusout', start);
		start();
	}

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
		tick();
		setInterval(tick, 1000);
	}

	document.addEventListener('submit', function (event) {
		var form = event.target.closest('.vb-form');
		if (!form) return;
		event.preventDefault();

		var status = form.querySelector('.vb-form-status');
		var button = form.querySelector('button[type="submit"]');
		var originalText = button ? button.textContent : '';
		var data = new FormData(form);

		data.append('action', 'vb_submit_form');
		data.append('nonce', (window.VB_FRONTEND && VB_FRONTEND.nonce) ? VB_FRONTEND.nonce : '');

		if (button) {
			button.disabled = true;
			button.textContent = 'Sending...';
		}
		if (status) {
			status.classList.remove('success', 'error');
			status.textContent = 'Sending...';
		}

		fetch((window.VB_FRONTEND && VB_FRONTEND.ajaxUrl) ? VB_FRONTEND.ajaxUrl : '/wp-admin/admin-ajax.php', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		}).then(function (res) {
			return res.json();
		}).then(function (res) {
			var success = !!(res && res.success);
			var message = res && res.data && res.data.message ? res.data.message : (success ? 'Sent.' : 'Something went wrong.');
			if (status) {
				status.classList.toggle('success', success);
				status.classList.toggle('error', !success);
				status.textContent = message;
			}
			if (success) form.reset();
		}).catch(function () {
			if (status) {
				status.classList.add('error');
				status.textContent = 'Could not send. Please try again.';
			}
		}).finally(function () {
			if (button) {
				button.disabled = false;
				button.textContent = originalText;
			}
		});
	});

	function getFocusable(overlay) {
		return Array.prototype.slice.call(overlay.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'));
	}

	function openPopup(overlay, opener) {
		if (!overlay || overlay.classList.contains('open')) return;
		var module = overlay.closest('.vb-popup-module');
		if (module) module.setAttribute('data-vb-popup-shown', 'true');

		lastFocusedBeforePopup = opener || document.activeElement;
		activePopup = overlay;
		overlay.classList.add('open');
		overlay.setAttribute('aria-hidden', 'false');
		document.body.classList.add('vb-popup-active');

		var focusable = getFocusable(overlay);
		(focusable[0] || overlay).focus();
	}

	function closePopup(overlay) {
		if (!overlay) return;
		overlay.classList.remove('open');
		overlay.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('vb-popup-active');
		activePopup = null;
		if (lastFocusedBeforePopup && document.contains(lastFocusedBeforePopup)) {
			lastFocusedBeforePopup.focus();
		}
		lastFocusedBeforePopup = null;
	}

	document.addEventListener('click', function (event) {
		var openBtn = event.target.closest('.vb-popup-open');
		if (openBtn) {
			openPopup(openBtn.closest('.vb-popup-module').querySelector('.vb-popup-overlay'), openBtn);
		}
		var closeBtn = event.target.closest('.vb-popup-close');
		if (closeBtn) closePopup(closeBtn.closest('.vb-popup-overlay'));
		if (event.target.classList && event.target.classList.contains('vb-popup-overlay')) closePopup(event.target);
	});

	document.addEventListener('keydown', function (event) {
		if (!activePopup) return;
		if (event.key === 'Escape') {
			event.preventDefault();
			closePopup(activePopup);
			return;
		}
		if (event.key !== 'Tab') return;

		var focusable = getFocusable(activePopup);
		if (!focusable.length) {
			event.preventDefault();
			activePopup.focus();
			return;
		}
		var first = focusable[0];
		var last = focusable[focusable.length - 1];
		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	});

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.vb-tabs').forEach(initTabs);
		document.querySelectorAll('.vb-slider').forEach(initSlider);
		document.querySelectorAll('.vb-countdown').forEach(initCountdown);
		document.querySelectorAll('.vb-popup-overlay').forEach(function (overlay) {
			overlay.setAttribute('tabindex', '-1');
		});
		document.querySelectorAll('.vb-popup-module').forEach(function (mod) {
			var trigger = mod.getAttribute('data-vb-popup-trigger');
			var overlay = mod.querySelector('.vb-popup-overlay');
			if (trigger === 'timed') {
				var delay = parseInt(mod.getAttribute('data-vb-popup-delay') || '5', 10) * 1000;
				setTimeout(function () {
					if (mod.getAttribute('data-vb-popup-shown') !== 'true') openPopup(overlay);
				}, delay);
			}
			if (trigger === 'exit_intent') {
				document.addEventListener('mouseout', function (e) {
					if (mod.getAttribute('data-vb-popup-shown') !== 'true' && e.clientY <= 0) openPopup(overlay);
				});
			}
		});
	});
})();
