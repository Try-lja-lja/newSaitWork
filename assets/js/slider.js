// JS for slider
document.addEventListener('DOMContentLoaded', () => {
	console.log('slider.js: DOMContentLoaded');

	const slider = document.getElementById('mainSlider');
	const img = document.getElementById('sliderImage');

	if (!slider || !img) {
		console.warn('slider.js: slider or image not found');
		return;
	}

	let slides;
	try {
		slides = JSON.parse(slider.dataset.slides);
	} catch (e) {
		console.error('slider.js: Failed to parse slides', e);
		return;
	}

	if (!Array.isArray(slides) || slides.length === 0) {
		console.warn('slider.js: No slides found');
		return;
	}

	let index = 0;

	function nextSlide() {
		index = (index + 1) % slides.length;
		img.src = `assets/img/slider/${slides[index]}`;
	}

	setInterval(nextSlide, 4000); // смена каждые 4 секунды
});
