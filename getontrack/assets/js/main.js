/**
 * Get On Track — Main JavaScript
 */
(function () {
	'use strict';

	/* Header scroll effect */
	var header = document.getElementById('site-header');
	if (header) {
		var onScroll = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 20);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/* Mobile navigation toggle */
	var navToggle = document.querySelector('.got-nav-toggle');
	var nav = document.getElementById('site-navigation');
	if (navToggle && nav) {
		navToggle.addEventListener('click', function () {
			var expanded = navToggle.getAttribute('aria-expanded') === 'true';
			navToggle.setAttribute('aria-expanded', String(!expanded));
			nav.classList.toggle('is-open', !expanded);
		});

		nav.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				navToggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('is-open');
			});
		});
	}

	/* Scroll animations */
	var animateEls = document.querySelectorAll(
		'.got-card, .got-process__step, .got-section-header, .got-science-card, .got-faq__item'
	);
	if (animateEls.length && 'IntersectionObserver' in window) {
		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			},
			{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
		);

		animateEls.forEach(function (el, i) {
			el.classList.add('got-animate');
			el.style.transitionDelay = (i % 4) * 0.08 + 's';
			observer.observe(el);
		});
	}

	/* Consultation form (demo handler) */
	var form = document.querySelector('[data-consultation-form]');
	if (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var btn = form.querySelector('button[type="submit"]');
			var originalText = btn.textContent;
			btn.textContent = 'Request Sent!';
			form.classList.add('is-submitted');
			setTimeout(function () {
				btn.textContent = originalText;
				form.classList.remove('is-submitted');
				form.reset();
			}, 3000);
		});
	}

	/* FAQ: close others when one opens (optional accordion behavior) */
	var faqContainer = document.querySelector('[data-faq]');
	if (faqContainer) {
		var items = faqContainer.querySelectorAll('.got-faq__item');
		items.forEach(function (item) {
			item.addEventListener('toggle', function () {
				if (item.open) {
					items.forEach(function (other) {
						if (other !== item) {
							other.open = false;
						}
					});
				}
			});
		});
	}
})();
