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
	// === УНИВЕРСАЛЬНАЯ SPA-СВЯЗКА ДЛЯ ВНУТРЕННИХ СТРАНИЦ ===
	// [PATH-BASE] /newSaitWork/pages/
	// Совпадение для всех внутренних страниц проекта под /newSaitWork/pages/
	const pagesBaseRe = /^\/newSaitWork\/pages(?:\/|$)/;

	const isSameOrigin = (url) => url.origin === window.location.origin;
	// [PATH-BASE] /newSaitWork/pages/
	const shouldHandleSPA = (pathname) => pagesBaseRe.test(pathname);

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

	function routePages(pathname) {
		// Универсальный разбор пути:
		// /newSaitWork/pages/<section>/<level3>/<level4>
		// [PATH-BASE] /newSaitWork/pages/
		const segs = pathname.replace(/^\/+|\/+$/g, '').split('/');
		// segs[0] = 'newSaitWork', segs[1] = 'pages', segs[2] = <section>, segs[3] = <level3>, segs[4] = <level4>
		const section = segs[2] || null;
		const levels = segs.slice(3); // массив всех вложенных уровней после секции

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
				: segmentToTitle(section || '');
		}

		setPageHeader(title);
		syncMenuActive(pathname);

		// КРОШКИ
		// Для resources-adults сохраняем текущее поведение (совместимость).
		// Для остальных секций базовые крошки не добавляем (сделаем правильно на Шаге 2).

		const crumbs = [];
		if (section) {
			crumbs.push({ text: section, href: '#' });
		}
		levels.forEach((seg) => {
			const title =
				(findMenuLinkBySegment(seg)?.textContent || '').trim() ||
				segmentToTitle(seg);
			crumbs.push({ text: title, href: '#' });
		});

		renderBreadcrumbs(crumbs);

		document.dispatchEvent(
			new CustomEvent('route:change', {
				// Передаём вычисленную секцию (совместимо: для resources-adults будет 'resources-adults')
				detail: { section, level3, level4, title },
			})
		);
	}

	function updateUIForPath(pathname) {
		if (shouldHandleSPA(pathname)) {
			routePages(pathname);
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
