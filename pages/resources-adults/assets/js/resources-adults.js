// pages/resources-adults/assets/js/resources-adults.js
// Миниатюры: .bmp (hover → -blue.bmp, leave → обратно, click → фикс blue).
// Большая зона: старт — JPG "ა" (фото), по клику на миниатюру — GIF выбранной буквы.
// Аудио — по кнопке .audio-btn / .mic-btn.

(function () {
	const LETTERS = [
		'ა',
		'ბ',
		'გ',
		'დ',
		'ე',
		'ვ',
		'ზ',
		'თ',
		'ი',
		'კ',
		'ლ',
		'მ',
		'ნ',
		'ო',
		'პ',
		'ჟ',
		'რ',
		'ს',
		'ტ',
		'უ',
		'ფ',
		'ქ',
		'ღ',
		'ყ',
		'შ',
		'ჩ',
		'ც',
		'ძ',
		'წ',
		'ჭ',
		'ხ',
		'ჯ',
		'ჰ',
	];

	const reAdults = /\/resources-adults(\/|$)/;
	let activeLetter = 'ა'; // активная миниатюра (blue), по умолчанию "ა"

	function ensureLettersApp() {
		let app = document.getElementById('lettersApp');
		if (!app) {
			app = document.createElement('div');
			app.id = 'lettersApp';
			app.className = 'letters-app';
			const host =
				document.querySelector('.content-block') ||
				document.querySelector('.main-content') ||
				document.body;
			host.appendChild(app);
		}
		if (!app.querySelector('[data-letters-grid]')) {
			const grid = document.createElement('div');
			grid.className = 'letters-grid';
			grid.setAttribute('data-letters-grid', '1');
			app.appendChild(grid);
		}
		if (!app.querySelector('[data-letter-stage]')) {
			const stage = document.createElement('div');
			stage.className = 'letter-stage';
			stage.setAttribute('data-letter-stage', '1');
			// СТАРТ: фото (JPG) "ა"
			stage.innerHTML = `
        <img class="letter-stage-img"
             src="/newSaitWork/assets/letters/img/${encodeURI('ა')}.jpg"
             alt="letter ა" data-letter="ა" data-mode="static" />
        <button class="audio-btn" type="button" aria-label="Play letter">
          <img alt="audio" src="/newSaitWork/assets/img/headphonesBlue.svg" />
        </button>
      `;
			app.appendChild(stage);
		}
		return app;
	}

	function getGrid() {
		return document.querySelector('#lettersApp [data-letters-grid]');
	}
	function getStageImg() {
		return document.querySelector('#lettersApp .letter-stage-img');
	}
	function getAudioBtn() {
		return document.querySelector(
			'#lettersApp .audio-btn, #lettersApp .mic-btn'
		);
	}

	function renderGrid() {
		const grid = getGrid();
		if (!grid) return;

		grid.innerHTML = LETTERS.map((ch) => {
			const normal = `/newSaitWork/assets/letters/img/${encodeURI(ch)}.bmp`;
			const blue = `/newSaitWork/assets/letters/img/${encodeURI(ch)}-blue.bmp`;
			const alt = `letter ${ch}`;
			return `
        <button class="letter-thumb" type="button" data-letter="${ch}" aria-label="Show ${alt}">
          <img src="${normal}" alt="${alt}" loading="lazy"
               data-src="${normal}" data-src-blue="${blue}" />
        </button>`;
		}).join('');

		// Прелоад blue-версий (чтобы не мигало)
		LETTERS.forEach((ch) => {
			const i = new Image();
			i.src = `/newSaitWork/assets/letters/img/${encodeURI(ch)}-blue.bmp`;
		});

		// Отметим активную миниатюру (должна быть синей на старте)
		markActiveThumb(activeLetter);
	}

	// --- БОЛЬШАЯ ЗОНА ---
	function setStageStatic(ch) {
		const img = getStageImg();
		if (!img) return;
		img.classList.add('letter-gif--invert');
		img.src = `/newSaitWork/assets/letters/gif/${encodeURI(ch)}.gif`;
		img.alt = `letter ${ch}`;
		img.dataset.mode = 'static';
		img.dataset.letter = ch;
	}

	function setStageGif(ch) {
		const img = getStageImg();
		if (!img) return;
		img.src = `/newSaitWork/assets/letters/gif/${encodeURI(ch)}.gif`;
		img.alt = `letter ${ch} (gif)`;
		img.classList.add('letter-gif--invert');
		img.dataset.mode = 'gif';
		img.dataset.letter = ch;
	}

	function playLetterAudioFor(ch) {
		const audioSrc = `/newSaitWork/assets/letters/audioLetters/${encodeURI(
			ch
		)}.mp3`;
		if (typeof window.playAudio === 'function') {
			window.playAudio(audioSrc);
		} else {
			console.warn('playAudio is not available');
		}
	}

	// --- МИНИАТЮРЫ (BMP ↔ BMP-BLUE) ---
	function getThumbByLetter(ch) {
		return document.querySelector(
			`.letters-grid .letter-thumb[data-letter="${CSS.escape(ch)}"]`
		);
	}

	function setThumbBlue(btn) {
		const img = btn?.querySelector('img');
		if (!img) return;
		const blue = img.getAttribute('data-src-blue');
		if (blue) img.src = blue;
		btn.classList.add('is-active');
	}

	function setThumbNormal(btn) {
		const img = btn?.querySelector('img');
		if (!img) return;
		const normal = img.getAttribute('data-src');
		if (normal) img.src = normal;
		btn.classList.remove('is-active');
	}

	function markActiveThumb(ch) {
		// Сброс прошлой активной
		const prev = document.querySelector(
			'.letters-grid .letter-thumb.is-active'
		);
		if (prev) setThumbNormal(prev);
		// Новая активная
		const next = getThumbByLetter(ch);
		if (next) setThumbBlue(next);
	}

	function bindEvents() {
		const grid = getGrid();
		const audioBtn = getAudioBtn();
		if (!grid || !audioBtn) return;

		// CLICK: фиксируем blue и ставим GIF в большой зоне
		grid.addEventListener('click', (e) => {
			const btn = e.target.closest('.letter-thumb');
			if (!btn) return;
			const ch = btn.getAttribute('data-letter');
			if (!ch) return;

			activeLetter = ch;
			markActiveThumb(activeLetter);
			setStageGif(ch);
		});

		// HOVER: временная подсветка blue
		grid.addEventListener('mouseover', (e) => {
			const btn = e.target.closest('.letter-thumb');
			if (!btn) return;
			// если не активная — подсветим
			if (!btn.classList.contains('is-active')) {
				const img = btn.querySelector('img');
				const blue = img?.getAttribute('data-src-blue');
				if (blue) img.src = blue;
			}
		});

		// LEAVE: возвращаем, если не активная
		grid.addEventListener('mouseout', (e) => {
			const btn = e.target.closest('.letter-thumb');
			if (!btn) return;
			if (!btn.classList.contains('is-active')) {
				const img = btn.querySelector('img');
				const normal = img?.getAttribute('data-src');
				if (normal) img.src = normal;
			}
		});

		// AUDIO: проигрываем текущую букву из большой зоны
		audioBtn.addEventListener('click', () => {
			const img = getStageImg();
			const ch = img?.dataset?.letter || 'ა';
			playLetterAudioFor(ch);
		});

		// route:change — для этой страницы ничего не меняем
		document.addEventListener('route:change', (ev) => {
			if (ev?.detail?.section !== 'resources-adults') return;
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		if (!reAdults.test(location.pathname)) return;
		ensureLettersApp();
		renderGrid();
		// старт: в сцене — JPG "ა", активная миниатюра — "ა" (синяя)
		setStageStatic('ა');
		bindEvents();
	});
})();
