// pages/resources-adults/assets/js/resources-adults.js
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

	function ensureLettersApp() {
		let app = document.getElementById('lettersApp');
		if (!app) {
			app = document.createElement('div');
			app.id = 'lettersApp';
			app.className = 'letters-app';
			const mc =
				document.querySelector('.content-block') ||
				document.querySelector('.main-content') ||
				document.body;
			mc.appendChild(app);
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
			stage.innerHTML = `
        <img class="letter-stage-img" alt="letter large" />
        <button class="audio-btn" type="button" aria-label="Play letter">
        </button>
      `;
			app.appendChild(stage);
		}
		return app;
	}

	const reAdults = /\/resources-adults(\/|$)/;

	function getGrid() {
		return document.querySelector('#lettersApp [data-letters-grid]');
	}
	function getStageImg() {
		return document.querySelector('#lettersApp .letter-stage-img');
	}
	function getMicBtn() {
		return document.querySelector('#lettersApp .mic-btn');
	}

	function renderGrid() {
		const grid = getGrid();
		if (!grid) return;
		grid.innerHTML = LETTERS.map((ch) => {
			const src = `/newSaitWork/assets/letters/img/${encodeURI(ch)}.bmp`;
			const alt = `letter ${ch}`;
			return `<button class="letter-thumb" type="button" data-letter="${ch}" aria-label="Show ${alt}">
        <img src="${src}" alt="${alt}" loading="lazy" />
      </button>`;
		}).join('');
	}

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
		if (typeof window.playAudio === 'function') window.playAudio(audioSrc);
	}

	function bindEvents() {
		const grid = document.querySelector('#lettersApp [data-letters-grid]');
		const mic = getMicBtn();
		if (!grid || !mic) return;

		grid.addEventListener('click', (e) => {
			const btn = e.target.closest('.letter-thumb');
			if (!btn) return;
			const ch = btn.getAttribute('data-letter');
			if (!ch) return;
			setStageGif(ch);
		});

		mic.addEventListener('click', () => {
			const img = getStageImg();
			const ch = img?.dataset?.letter || 'ა';
			playLetterAudioFor(ch);
		});

		document.addEventListener('route:change', (ev) => {
			if (ev?.detail?.section !== 'resources-adults') return;
			// страница статическая — ничего не делаем
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		if (!reAdults.test(location.pathname)) return;
		ensureLettersApp();
		renderGrid();
		setStageStatic('ა'); // НИКОГДА не автозапускаем GIF
		bindEvents();
	});
})();
