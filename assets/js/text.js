document.addEventListener('DOMContentLoaded', function () {
	const toggleButton = document.getElementById('toggleText');
	const textBlock = document.querySelector('.why-block .text');

	const lang = window.CURRENT_LANG || 'ka';

	const labels = {
		ka: { expand: 'ვრცლად', collapse: 'დახურვა' },
		en: { expand: 'Read more', collapse: 'Close' },
	};

	// Сохраняем исходную max-height
	const collapsedHeight = 200;

	toggleButton.addEventListener('click', function () {
		const isExpanded = textBlock.classList.contains('expanded');
		const currentLang = labels[lang] ? lang : 'ka';
		const label = isExpanded
			? labels[currentLang].expand
			: labels[currentLang].collapse;

		if (isExpanded) {
			// Сворачиваем
			textBlock.style.maxHeight = collapsedHeight + 'px';
			textBlock.classList.remove('expanded');
			toggleButton.classList.remove('rotate');
		} else {
			// Разворачиваем до полной высоты
			textBlock.style.maxHeight = textBlock.scrollHeight + 'px';
			textBlock.classList.add('expanded');
			toggleButton.classList.add('rotate');
		}

		toggleButton.innerHTML = `${label}<span>▼</span>`;
	});

	// Важно: сбрасываем max-height при resize (на всякий случай)
	window.addEventListener('resize', function () {
		if (textBlock.classList.contains('expanded')) {
			textBlock.style.maxHeight = textBlock.scrollHeight + 'px';
		}
	});
});
