// assets/js/menu.js
(function () {
	document.addEventListener('DOMContentLoaded', () => {
		/* === ССЫЛКИ НА УЗЛЫ (ИДЕНТИФИКАТОРЫ/КЛАССЫ НЕ МЕНЯЛ!) === */
		const desktopMenu = document.querySelector('.menu-block'); // не изменено
		const offcanvas = document.getElementById('offcanvasMenu'); // не изменено
		const overlay = document.getElementById('offcanvasOverlay'); // не изменено
		const hamburgerBtn = document.getElementById('hamburgerBtn'); // не изменено

		/* === ОБЩИЕ УТИЛИТЫ ДЛЯ МЕНЮ (DESKTOP + OFFCANVAS) === */
		function closeAllExpanded(container) {
			if (!container) return; // ДОБАВЛЕНО: защита от null
			container
				.querySelectorAll('.menu-item.has-children.expanded')
				.forEach((item) => item.classList.remove('expanded'));
			container
				.querySelectorAll('.menu-level-1 > li')
				.forEach((item) => item.classList.remove('active'));
		}

		function setActiveSubmenuItem(li, container) {
			if (!container || !li) return; // ДОБАВЛЕНО: защита
			container
				.querySelectorAll('.menu-level-2 li')
				.forEach((el) => el.classList.remove('active'));
			li.classList.add('active');
			if (li.dataset && li.dataset.id) {
				localStorage.setItem('activeSubmenuId', li.dataset.id);
			}
		}

		// Обработчики уровней
		function setupLevel1Handlers(container, isMobile = false) {
			if (!container) return; // ДОБАВЛЕНО
			container.querySelectorAll('.menu-level-1 > li > a').forEach((link) => {
				link.addEventListener('click', (e) => {
					const li = link.parentElement;
					if (!li) return; // ДОБАВЛЕНО

					if (li.classList.contains('has-children')) {
						e.preventDefault(); // не уходим со страницы
						const isExpanded = li.classList.contains('expanded');
						closeAllExpanded(container);
						if (!isExpanded) {
							li.classList.add('expanded');
							li.classList.add('active');
							if (li.dataset && li.dataset.id) {
								localStorage.setItem('activeMenuId', li.dataset.id);
							}
						} else {
							localStorage.removeItem('activeMenuId');
						}
					} else {
						// Пункт без подменю: на мобиле закрываем offcanvas, на десктопе — обычный переход
						if (isMobile) closeMenu(); // не изменено по смыслу, ДОБАВЛЕНО проверка
					}
				});
			});
		}

		function setupLevel2Handlers(container, isMobile = false) {
			if (!container) return; // ДОБАВЛЕНО
			container.querySelectorAll('.menu-level-2 > li > a').forEach((link) => {
				link.addEventListener('click', () => {
					const li = link.parentElement;
					setActiveSubmenuItem(li, container);
					if (isMobile) closeMenu(); // закрываем offcanvas при выборе пункта 2 уровня
				});
			});
		}

		// Восстановление состояния из localStorage
		function restoreActiveStates(container) {
			if (!container) return; // ДОБАВЛЕНО
			const menuId = localStorage.getItem('activeMenuId');
			const submenuId = localStorage.getItem('activeSubmenuId');

			if (menuId) {
				const li = container.querySelector(
					`.menu-level-1 > li[data-id="${menuId}"]`
				);
				if (li) {
					li.classList.add('expanded');
					li.classList.add('active');
				}
			}
			if (submenuId) {
				const subLi = container.querySelector(
					`.menu-level-2 li[data-id="${submenuId}"]`
				);
				if (subLi) subLi.classList.add('active');
			}
		}

		/* === OFFCANVAS (Мобильное меню) === */
		function getHeaderHeight() {
			// высота шапки для позиционирования offcanvas ниже header
			return document.querySelector('header')?.offsetHeight || 0; // не изменено
		}

		function updateOffcanvasPosition() {
			// ДОБАВЛЕНО: вынос в отдельную ф-цию
			if (!offcanvas) return;
			const h = getHeaderHeight();
			offcanvas.style.top = `${h}px`;
			offcanvas.style.height = `calc(100vh - ${h}px)`;
		}

		function openMenu() {
			updateOffcanvasPosition(); // ДОБАВЛЕНО: используем общую ф-цию
			if (offcanvas) offcanvas.classList.add('open'); // не изменено
			document.body.classList.add('offcanvas-open'); // не изменено
			if (hamburgerBtn) hamburgerBtn.classList.add('active'); // не изменено (для анимации и ARIA)
			if (overlay) {
				overlay.classList.add('active'); // ДОБАВЛЕНО: показываем подложку
				overlay.style.display = 'block'; // ДОБАВЛЕНО: fail-safe на случай несовпадения CSS-класса
			}
		}

		function closeMenu() {
			if (offcanvas) offcanvas.classList.remove('open'); // не изменено
			document.body.classList.remove('offcanvas-open'); // не изменено
			if (hamburgerBtn) hamburgerBtn.classList.remove('active'); // не изменено
			if (overlay) {
				overlay.classList.remove('active'); // ДОБАВЛЕНО
				overlay.style.display = 'none'; // ДОБАВЛЕНО
			}
		}

		/* === ПОДКЛЮЧЕНИЕ ОБРАБОТЧИКОВ === */
		// desktop
		setupLevel1Handlers(desktopMenu, false); // не изменено
		setupLevel2Handlers(desktopMenu, false); // не изменено
		restoreActiveStates(desktopMenu); // не изменено

		// offcanvas (mobile)
		setupLevel1Handlers(offcanvas, true); // не изменено
		setupLevel2Handlers(offcanvas, true); // не изменено
		restoreActiveStates(offcanvas); // не изменено

		// гамбургер
		if (hamburgerBtn) {
			// ДОБАВЛЕНО: защита от null
			hamburgerBtn.addEventListener('click', () => {
				if (offcanvas && offcanvas.classList.contains('open')) closeMenu();
				else openMenu();
			});
		}

		// клик по подложке — закрыть
		if (overlay) {
			// ДОБАВЛЕНО
			overlay.addEventListener('click', closeMenu); // ДОБАВЛЕНО
		}

		// клик вне offcanvas — закрыть (оставил как было)
		document.addEventListener('click', (e) => {
			if (
				offcanvas &&
				offcanvas.classList.contains('open') &&
				!offcanvas.contains(e.target) &&
				!(hamburgerBtn && hamburgerBtn.contains(e.target))
			) {
				closeMenu();
			}
		});

		// при ресайзе перепозиционируем offcanvas, если открыт
		window.addEventListener('resize', () => {
			// ДОБАВЛЕНО
			if (offcanvas && offcanvas.classList.contains('open')) {
				updateOffcanvasPosition();
			}
			applyDesktopShiftState(); // ДОБАВЛЕНО: актуализируем состояние "сдвига" для десктопа
		});

		/* === ДОБАВЛЕНО: toggle для desktop-меню (.menu-block) — сдвиг влево === */
		const LS_KEY_SHIFTED = 'desktopMenuShifted'; // ДОБАВЛЕНО

		function isDesktop() {
			// ДОБАВЛЕНО
			return window.innerWidth > 768;
		}

		function applyDesktopShiftState() {
			// ДОБАВЛЕНО
			if (!desktopMenu) return;
			if (!isDesktop()) {
				desktopMenu.classList.remove('menu-block--shifted');
				return;
			}
			const saved = localStorage.getItem(LS_KEY_SHIFTED);
			if (saved === '1') desktopMenu.classList.add('menu-block--shifted');
			else desktopMenu.classList.remove('menu-block--shifted');
		}

		// === DESKTOP RAIL TOGGLE ===
		const menuRail = document.getElementById('menuRail');
		if (menuRail && desktopMenu) {
			menuRail.addEventListener('click', function (e) {
				e.stopPropagation(); // чтобы клик по рейлу не ловили другие слушатели
				desktopMenu.classList.toggle('menu-block--shifted'); // сдвиг/возврат
			});
		}
	});
})();
