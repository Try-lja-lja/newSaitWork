// assets/js/main.js
// АУДИО (глобально)
(function () {
	if (!window.sharedAudio) window.sharedAudio = null;
	window.playAudio = function (src) {
		try {
			if (!window.sharedAudio) window.sharedAudio = new Audio();
			const a = window.sharedAudio;
			a.pause();
			a.currentTime = 0;
			a.src = src;
			a.load();
			a.play().catch((e) => console.warn('Cannot play audio', e));
		} catch (e) {
			console.warn('Audio error', e);
		}
	};
})();

// ЛЁГКАЯ SPA-СВЯЗКА (только для /resources-adults в любом подкаталоге)
(function () {
	// Совпадение, если путь содержит /resources-adults (и далее / или конец)
	const resourcesAdultsRe = /\/resources-adults(\/|$)/;

	const isSameOrigin = (url) => url.origin === window.location.origin;
	const shouldHandleSPA = (pathname) => resourcesAdultsRe.test(pathname);

	function escapeHtml(s) {
		return String(s).replace(
			/[&<>"']/g,
			(m) =>
				({
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					"'": '&#39;',
				}[m])
		);
	}

	function setPageHeader(text) {
		const el =
			document.querySelector('[data-page-header]') ||
			document.querySelector('.page-header__title') ||
			document.querySelector('.header-page__title');
		if (el) el.textContent = text || '';
	}

	function renderBreadcrumbs(crumbs) {
		const container =
			document.querySelector('[data-breadcrumbs]') ||
			document.querySelector('.breadcrumbs');
		if (!container) return;
		const html = crumbs
			.map((c, i) =>
				i === crumbs.length - 1
					? `<span class="crumb current" aria-current="page">${escapeHtml(
							c.text
					  )}</span>`
					: `<a class="crumb" href="${c.href}">${escapeHtml(c.text)}</a>`
			)
			.join(`<span class="crumb-sep"> / </span>`);
		container.innerHTML = html;
	}

	function findMenuLinkByPath(path) {
		let link = document.querySelector(`a[href="${path}"]`);
		if (!link) link = document.querySelector(`a[href$="${CSS.escape(path)}"]`);
		return link || null;
	}
	function findMenuLinkBySegment(seg) {
		let link =
			document.querySelector(`a[data-slug="${CSS.escape(seg)}"]`) ||
			document.querySelector(`a[href$="/${CSS.escape(seg)}"]`);
		return link || null;
	}
	function segmentToTitle(seg) {
		return decodeURIComponent(seg || '')
			.replace(/-/g, ' ')
			.trim();
	}

	function syncMenuActive(path) {
		const containers = [
			document.querySelector('.menu-block'),
			document.getElementById('offcanvasMenu'),
		].filter(Boolean);

		containers.forEach((container) => {
			container
				.querySelectorAll('li.active')
				.forEach((li) => li.classList.remove('active'));
			container
				.querySelectorAll('li.expanded')
				.forEach((li) => li.classList.remove('expanded'));

			const link =
				container.querySelector(`a[href="${path}"]`) ||
				container.querySelector(`a[href$="${CSS.escape(path)}"]`);
			if (link) {
				const li = link.closest('li');
				if (li) li.classList.add('active');
				let node = li ? li.parentElement : null;
				while (node && node !== container) {
					if (node.tagName === 'UL') {
						const pli = node.closest('li');
						if (pli) pli.classList.add('expanded');
						node = pli ? pli.parentElement : null;
					} else {
						node = node.parentElement;
					}
				}
			}
		});
	}

	function routeResourcesAdults(pathname) {
		// Вытащим сегменты после "resources-adults"
		// Пример: /newSaitWork/pages/resources-adults/textbooks/agmarti
		const idx = pathname.indexOf('/resources-adults');
		const tail = idx >= 0 ? pathname.slice(idx) : pathname;
		const segs = tail.replace(/^\/+|\/+$/g, '').split('/');
		// segs[0] === 'resources-adults'
		const level3 = segs[1] || null;
		const level4 = segs[2] || null;

		let title = '';
		const linkFull = findMenuLinkByPath(pathname);
		if (linkFull) {
			title = (linkFull.textContent || '').trim();
		} else if (level4) {
			const l4 = findMenuLinkBySegment(level4);
			title = l4 ? (l4.textContent || '').trim() : segmentToTitle(level4);
		} else if (level3) {
			const l3 = findMenuLinkBySegment(level3);
			title = l3 ? (l3.textContent || '').trim() : segmentToTitle(level3);
		} else {
			const base = findMenuLinkByPath(pathname);
			title = base
				? (base.textContent || '').trim()
				: 'მოზარდებისა და ზრდასრულებისთვის';
		}

		setPageHeader(title);
		syncMenuActive(pathname);

		const crumbs = [
			{ text: 'სასწავლო რესურსები', href: '#' }, // при желании подставим реальный URL раздела
			{ text: 'მოზარდებისა და ზრდასრულებისთვის', href: '#' },
		];
		if (level3) {
			const t3 =
				(findMenuLinkBySegment(level3)?.textContent || '').trim() ||
				segmentToTitle(level3);
			crumbs.push({ text: t3, href: '#' });
		}
		if (level4) {
			const t4 =
				(findMenuLinkBySegment(level4)?.textContent || '').trim() ||
				segmentToTitle(level4);
			crumbs.push({ text: t4, href: '#' });
		}
		renderBreadcrumbs(crumbs);

		document.dispatchEvent(
			new CustomEvent('route:change', {
				detail: { section: 'resources-adults', level3, level4, title },
			})
		);
	}

	function updateUIForPath(pathname) {
		if (shouldHandleSPA(pathname)) {
			routeResourcesAdults(pathname);
		}
	}

	// Перехват ссылок только для /resources-adults в любом подкаталоге
	document.addEventListener('click', (e) => {
		const a = e.target.closest('a');
		if (!a) return;
		if (e.defaultPrevented || e.button !== 0) return;
		if (a.target === '_blank' || a.hasAttribute('download')) return;

		let url;
		try {
			url = new URL(a.href, window.location.origin);
		} catch {
			return;
		}
		if (!isSameOrigin(url)) return;
		if (!shouldHandleSPA(url.pathname)) return;

		e.preventDefault();
		window.history.pushState({ url: url.pathname }, '', url.pathname);
		updateUIForPath(url.pathname);
	});

	window.addEventListener('popstate', () => {
		updateUIForPath(location.pathname);
	});

	document.addEventListener('DOMContentLoaded', () => {
		updateUIForPath(location.pathname);
	});
})();
