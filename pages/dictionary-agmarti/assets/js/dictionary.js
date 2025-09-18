// (ES module) dictionaryTest/resource/js/main.js
const DEBOUNCE_MS = 300;
let debounceTimer = null;
let sharedAudio = null; // один Audio на всё приложение

// helpers
const $ = (sel) => document.querySelector(sel);
const $$ = (sel) => Array.from(document.querySelectorAll(sel));

function debounce(fn, ms) {
	clearTimeout(debounceTimer);
	debounceTimer = setTimeout(fn, ms);
}

/** гарантируем наличие правого панеля деталей */
function ensureDetailsPanel() {
	let panel = $('#details-panel');
	if (!panel) {
		panel = document.createElement('div');
		panel.id = 'details-panel';
		const mc = $('.main-content');
		if (mc) mc.appendChild(panel);
		else document.body.appendChild(panel);
	}
	return panel;
}

// === делегирование по панели деталей — один общий обработчик ===
let detailsPanelBound = false;
function bindDetailsPanelDelegation() {
	if (detailsPanelBound) return;
	const panel = ensureDetailsPanel();

	panel.addEventListener('click', async (ev) => {
		// Любая локальная раскрывашка (темы, синонимы, антонимы, грамм. подпункты)
		const tgl = ev.target.closest('.topic-local-toggle');
		if (tgl) {
			const box = tgl.nextElementSibling;
			if (box) {
				const willOpen = box.hidden;
				box.hidden = !willOpen ? true : false;
				tgl.classList.toggle('open', willOpen);
			}
			return;
		}

		// Клик по конкретной теме → подгрузка слов
		const btnTopic = ev.target.closest('.topic-toggle');
		if (btnTopic) {
			// контейнер со словами темы
			const box = btnTopic.nextElementSibling; // это .topic-words
			if (!box) return;

			const topicId = btnTopic.getAttribute('data-topic-id');
			const willOpen = box.hidden;
			box.hidden = !willOpen ? true : false;
			btnTopic.classList.toggle('open', willOpen);

			if (!willOpen) return; // закрыли — ничего не грузим
			if (box.dataset.loaded === '1') return; // уже загружено

			box.innerHTML = `<div class="topic-loading">იტვირთება…</div>`;
			try {
				const fd = new FormData();
				fd.append('tema', topicId || '43');
				fd.append('level', 'all');
				fd.append('part_of_speech', '13');
				fd.append('word', '');

				const r = await fetch('api/search.php', { method: 'POST', body: fd });
				const json = await r.json();
				const rows = Array.isArray(json?.rows) ? json.rows : [];

				if (rows.length === 0) {
					box.innerHTML = `<div class="topic-empty">ამ თემით სიტყვები ვერ მოიძებნა</div>`;
					box.dataset.loaded = '1';
					return;
				}

				const list = document.createElement('ul');
				list.className = 'topic-list';
				const max = Math.min(rows.length, 20);

				for (let i = 0; i < max; i++) {
					const row = rows[i];
					const li = document.createElement('li');
					li.className = 'topic-word';
					li.textContent = row.word_view || '';
					li.dataset.id = row.id;

					li.addEventListener('click', (e2) => {
						e2.preventDefault();
						e2.stopPropagation();
						const table = document.querySelector('.results-table');
						if (table) {
							table
								.querySelectorAll('tr.active')
								.forEach((tr) => tr.classList.remove('active'));
							const tr = table.querySelector(`tr[data-id="${row.id}"]`);
							if (tr) tr.classList.add('active');
						}
						loadDetails(row.id);
					});

					list.appendChild(li);
				}

				box.innerHTML = '';
				box.appendChild(list);

				// сохраним полный набор и сколько уже вывели
				box.__rows = rows;
				box.__rendered = max;

				// кликабельный футер "… და კიდევ X"
				if (rows.length > max) {
					const footer = document.createElement('div');
					footer.className = 'topic-more';
					// footer.textContent = `… და კიდევ ${rows.length - max}`;
					footer.textContent = `მეტი ...`;
					box.appendChild(footer);
				}

				box.dataset.loaded = '1';
			} catch (e) {
				box.innerHTML = `<div class="topic-error">შეცდომა თემის ჩატვირთვისას</div>`;
				console.warn('topic fetch error', e);
			}
			return;
		}

		// Клик по "… და კიდევ X" → дорисовать оставшиеся слова
		const more = ev.target.closest('.topic-more');
		if (more) {
			// именно .topic-words хранит __rows/__rendered
			const box = more.closest('.topic-words');
			if (!box || !box.__rows) return;

			const list = box.querySelector('ul.topic-list');
			if (!list) return;

			const rows = box.__rows;
			let start = box.__rendered || list.children.length;

			for (let i = start; i < rows.length; i++) {
				const row = rows[i];
				const li = document.createElement('li');
				li.className = 'topic-word';
				li.textContent = row.word_view || '';
				li.dataset.id = row.id;

				li.addEventListener('click', (e2) => {
					e2.preventDefault();
					e2.stopPropagation();
					const table = document.querySelector('.results-table');
					if (table) {
						table
							.querySelectorAll('tr.active')
							.forEach((tr) => tr.classList.remove('active'));
						const tr = table.querySelector(`tr[data-id="${row.id}"]`);
						if (tr) tr.classList.add('active');
					}
					loadDetails(row.id);
				});

				list.appendChild(li);
			}

			box.__rendered = rows.length;
			more.remove();
			return;
		}
	});

	detailsPanelBound = true;
}

// собрать фильтры в FormData (letter удалён)
function gatherFilters() {
	const word = ($('#form_Search').value || '').trim();
	const level = $('#form_level').value || '';
	const part = $('#form_part_of_speech').value || '';
	const tema = $('#form_tema').value || '';

	const fd = new FormData();
	fd.append('word', word || ''); // '' = без слова
	fd.append('level', level || 'all'); // 'all' = все уровни
	fd.append('part_of_speech', part || '13'); // '13' = все части речи
	fd.append('tema', tema || '43'); // '43' = все темы
	return fd;
}

// сообщение об ошибке/подсказке в правой панели
function showSideMessage(msg) {
	const panel = ensureDetailsPanel();
	panel.innerHTML = `<div class="center-message">${
		msg ? String(msg) : ''
	}</div>`;
}

// выполняем поиск
async function doSearch() {
	const fd = gatherFilters();
	try {
		const res = await fetch('api/search.php', { method: 'POST', body: fd });
		const json = await res.json();
		renderResults(json);
	} catch (err) {
		console.error('Search error', err);
		$('#results').innerHTML = '<div class="error">საძიებო შეცდომა</div>';
	}
}

// быстрый пакетный рендер таблицы
function renderResults(data) {
	const target = $('#results');

	// нормализуем данные
	const rows = Array.isArray(data?.rows)
		? data.rows
		: Array.isArray(data)
		? data
		: [];

	if (rows.length === 0) {
		target.innerHTML =
			'<p class="no-results">ამ პარამეტრით სიტყვები არ იძებნება</p>';
		return;
	}

	// создаём таблицу
	target.innerHTML = '';
	const table = document.createElement('table');
	table.className = 'results-table';
	const tbody = document.createElement('tbody');
	table.appendChild(tbody);
	target.appendChild(table);

	// делегирование кликов по таблице — навешиваем сразу
	table.addEventListener('click', (ev) => {
		const btn = ev.target.closest('.audio-btn');
		if (btn) {
			ev.preventDefault();
			ev.stopPropagation();
			playAudio(btn.dataset.src);
			return;
		}
		// клик по строке — подсветить и показать детали
		const tr = ev.target.closest('tr[data-id]');
		if (!tr) return;

		table
			.querySelectorAll('tr.active')
			.forEach((r) => r.classList.remove('active'));
		tr.classList.add('active');
		loadDetails(tr.getAttribute('data-id'));
	});

	const CHUNK = 300;
	let i = 0;

	function pump() {
		const end = Math.min(i + CHUNK, rows.length);
		let buf = '';
		for (; i < end; i++) {
			const r = rows[i];
			const id = r.id;
			const wordView = escapeHtml(r.word_view || '');
			const level = escapeHtml(r.level || 'WL');
			const audioHref = 'resource/audio/' + wordView + '.mp3';

			buf += `
        <tr data-id="${id}">
          <td class="word-cell">${wordView}</td>
          <td class="level">${level}</td>
          <td class="audio-cell">
            <button type="button"
                    class="audio-btn"
                    data-src="${audioHref}"
                    aria-label="აუდიო მოსმენა">
              <img src="resource/img/headphonesBlue.svg" class="audio-icon" alt="">
            </button>
          </td>
        </tr>`;
		}
		tbody.insertAdjacentHTML('beforeend', buf);

		if (i < rows.length) {
			requestAnimationFrame(pump);
		}
	}

	requestAnimationFrame(pump);
}

function escapeHtml(str) {
	if (!str) return '';
	return ('' + str)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#039;');
}

function playAudio(src) {
	try {
		if (!sharedAudio) sharedAudio = new Audio();
		sharedAudio.pause();
		sharedAudio.currentTime = 0;
		sharedAudio.src = src;
		sharedAudio.load();
		sharedAudio.play().catch((e) => {
			console.warn('Cannot play audio', e);
		});
	} catch (e) {
		console.warn('Audio error', e);
	}
}

async function loadDetails(id) {
	const panel = $('#details-panel');
	if (!panel) return;

	panel.innerHTML = `
    <div class="word-title">ID=${id}</div>
    <div class="pos-line"></div>
    <div class="center-message">იტვირთება…</div>
  `;

	try {
		const res = await fetch(
			`api/word_details.php?id=${encodeURIComponent(id)}`
		);
		const data = await res.json();
		if (!data?.success) {
			panel.innerHTML = `<div class="center-message">ჩატვირთვის შეცდომა</div>`;
			return;
		}

		const word = data.word || {};
		const pos = word.part_of_speech || { id: 0, ka: '' };
		const uses = Array.isArray(data.uses) ? data.uses : [];

		const esc = (s) =>
			s
				? ('' + s)
						.replace(/&/g, '&amp;')
						.replace(/</g, '&lt;')
						.replace(/>/g, '&gt;')
						.replace(/"/g, '&quot;')
						.replace(/'/g, '&#039;')
				: '';

		const renderUse = (u, idx) => {
			const syns = Array.isArray(u.synonyms) ? u.synonyms : [];
			const ants = Array.isArray(u.antonyms) ? u.antonyms : [];
			const topics = Array.isArray(u.topics) ? u.topics : [];
			const idioms = Array.isArray(u.idioms) ? u.idioms : [];

			const topicsBlock = topics.length
				? `
        <div class="topic-local">
          <button class="topic-local-toggle" type="button">
            <span class="caret">▼</span> თემატური ჯგუფი
          </button>
          <div class="topic-local-box" hidden>
            ${topics
							.map(
								(t) => `
              <div class="topic-item">
                <button class="topic-toggle" type="button" data-topic-id="${
									t.id
								}">
                  <span class="caret">▼</span> ${esc(t.label)}
                </button>
                <div class="topic-words" hidden></div>
              </div>
            `
							)
							.join('')}
          </div>
        </div>
      `
				: '';

			const synBlock = syns.length
				? `
        <div class="section">
          <button class="topic-local-toggle syn-toggle" type="button">
            <span class="caret">▼</span> სინონიმ(ებ)ი
          </button>
          <div class="topic-local-box syn-box" hidden>
            <ul class="topic-list">
              ${syns
								.map((s) => `<li class="topic-word static">${esc(s)}</li>`)
								.join('')}
            </ul>
          </div>
        </div>
      `
				: '';

			const antBlock = ants.length
				? `
        <div class="section">
          <button class="topic-local-toggle ant-toggle" type="button">
            <span class="caret">▼</span> ანტონიმ(ებ)ი
          </button>
          <div class="topic-local-box ant-box" hidden>
            <ul class="topic-list">
              ${ants
								.map((s) => `<li class="topic-word static">${esc(s)}</li>`)
								.join('')}
            </ul>
          </div>
        </div>
      `
				: '';

			const idiomBlock = idioms.length
				? `
    <div class="section">
      <button class="topic-local-toggle idiom-toggle" type="button">
        <span class="caret">▼</span> იდიომ(ებ)ი
      </button>
      <div class="topic-local-box idiom-box" hidden>
        <div class="idiom-list">
          ${idioms
						.map((it) => {
							const t = it.idiom ? esc(it.idiom) : '—';
							const interp = it.interpretation ? esc(it.interpretation) : '';
							const ex = it.use ? esc(it.use) : '';
							// формат похож на „use“: заголовок + пояснение + пример
							return `
              <div class="idiom-item">
                <div class="idiom-title">${t}</div>
                ${
									interp
										? `<div class="idiom-interpretation">${interp}</div>`
										: ''
								}
                ${
									ex
										? `<div class="idiom-example">${ex.replace(
												/\. /g,
												'.<br>'
										  )}</div>`
										: ''
								}
              </div>
            `;
						})
						.join('')}
        </div>
      </div>
    </div>
  `
				: '';

			return `
        <div class="use-card">
          <div class="use-head">
            ${uses.length > 1 ? `<span class="use-num">${idx + 1}.</span>` : ''}
            ${u.level ? `<span class="use-level">${esc(u.level)}</span>` : ''}
          </div>

          ${
						u.interpretation
							? `<div class="use-interpretation">${esc(u.interpretation)}</div>`
							: ''
					}

      ${
				u.translate
					? `<div class="use-translate">${esc(u.translate)}</div>`
					: ''
			}		

          ${
						u.use_text
							? `<div class="use-examples">${esc(u.use_text).replace(
									/\. /g,
									'.<br>'
							  )}</div>`
							: ''
					}

          ${topicsBlock}
          ${synBlock}
          ${antBlock}
      ${idiomBlock}

          <hr class="use-sep">
        </div>
      `;
		};

		panel.innerHTML = `
      <div class="word-title">${esc(word.word_view || '')}</div>
      <div class="pos-line">${esc(pos.ka || '')}</div>

      <div class="uses">
        ${uses.map((u, i) => renderUse(u, i)).join('')}
      </div>
    `;

		// делегирование по панели уже навешано глобально в bindDetailsPanelDelegation()

		// ===== ДОБАВЛЯЕМ МОРՖОЛОГИЮ ПО ЧАСТЯМ РЕЧИ =====
		if (+pos.id === 1) {
			await embedNounCases(panel, word.id, esc);
		} else if (+pos.id === 2) {
			await embedAdjectiveSections(panel, word.id, esc);
		} else if (+pos.id === 3) {
			await embedNumeralSections(panel, word.id, esc);
		} else if (+pos.id === 4) {
			await embedPronounSections(panel, word.id, esc);
		} else if (+pos.id === 5) {
			await embedVerbSections(panel, word.id, esc);
		} else if (+pos.id === 6) {
			await embedVerbalNoun(panel, word.id, esc);
		} else if (+pos.id === 7) {
			await embedParticiple(panel, word.id, esc);
		} else if (+pos.id === 8) {
			await embedAdverb(panel, word.id, esc);
		} else if (+pos.id === 9) {
			await embedParticle(panel, word.id, esc);
		} else if (+pos.id === 10) {
			await embedConjunction(panel, word.id, esc);
		} else if (+pos.id === 11) {
			await embedPostposition(panel, word.id, esc);
		} else if (+pos.id === 12) {
			await embedInterjection(panel, word.id, esc);
		}
	} catch (e) {
		panel.innerHTML = `<div class="center-message">სერვერის შეცდომა</div>`;
		console.warn('details load error', e);
	}
}

// ========== ГРАММАТИКА: СУЩЕСТВИТЕЛЬНОЕ ==========
async function embedNounCases(panel, wordId, esc) {
	try {
		const r = await fetch(`api/noun.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		// Берём подписи из API либо из централизованного L.cases
		const labels = j.labels || {
			nominative: L.cases.nominative,
			ergative: L.cases.ergative,
			dative: L.cases.dative,
			genetive: L.cases.genetive,
			instrumental: L.cases.instrumental,
			transformative: L.cases.transformative,
			vocative: L.cases.vocative,
		};
		const order = j.order || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const cases = j.cases || {};

		const rowsHtml = order
			.map((key) => {
				const ka = labels[key] || '';
				const s = cases[key]?.s || '';
				const p = cases[key]?.p || '';
				return `
        <tr>
          <td class="case-name">${esc(ka)}</td>
          <td class="case-s">${s ? esc(s) : '—'}</td>
          <td class="case-p">${p ? esc(p) : '—'}</td>
        </tr>
      `;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section noun-cases';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle cases-toggle" type="button">
        <span class="caret">▼</span> ბრუნება
      </button>
      <div class="topic-local-box cases-box" hidden>
        <table class="case-table">
          <thead>
            <tr>
              <th>ბრუნება</th>
              <th>მხოლობითი რიცხვი</th>
              <th>მრავლობითი რიცხვი</th>
            </tr>
          </thead>
          <tbody>${rowsHtml}</tbody>
        </table>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('noun fetch error', e);
	}
}

// ========== ГРАММАТИКА: ПРИЛАГАТЕЛЬНОЕ ==========
async function embedAdjectiveSections(panel, wordId, esc) {
	try {
		const r = await fetch(`api/adjective.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const deg = j.degrees || {};
		const cases = j.cases || {};
		const caseLabels = j.labels || {
			nominative: 'სახელობითი',
			ergative: 'მოთხრობითი',
			dative: 'მიცემითი',
			genetive: 'ნათესაობითი',
			instrumental: 'მოქმედებითი',
			transformative: 'ვითარებითი',
			vocative: 'წოდებითი',
		};
		const order = j.order || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const degLabels = (j.all_labels &&
			(j.all_labels.adj_degrees || j.all_labels.degrees)) ||
			(j.labels && (j.labels.adj_degrees || j.labels.degrees)) || {
				positive: 'დადებითი',
				comparative: 'ოდნაობითი',
				superlative: 'უფროობითი',
			};

		const degreeRows = [];
		if (deg.positive)
			degreeRows.push(
				`<tr><td class="case-name">${esc(
					degLabels.positive
				)}</td><td class="case-s" colspan="2">${esc(deg.positive)}</td></tr>`
			);
		if (deg.comparative)
			degreeRows.push(
				`<tr><td class="case-name">${esc(
					degLabels.comparative
				)}</td><td class="case-s" colspan="2">${esc(deg.comparative)}</td></tr>`
			);
		if (deg.superlative)
			degreeRows.push(
				`<tr><td class="case-name">${esc(
					degLabels.superlative
				)}</td><td class="case-s" colspan="2">${esc(deg.superlative)}</td></tr>`
			);

		const caseRows = order
			.map((key) => {
				const ka = caseLabels[key] || '';
				const s = cases[key]?.s || '';
				const p = cases[key]?.p || '';
				return `
        <tr>
          <td class="case-name">${esc(ka)}</td>
          <td class="case-s">${s ? esc(s) : '—'}</td>
          <td class="case-p">${p ? esc(p) : '—'}</td>
        </tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section adj-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <button class="topic-local-toggle degrees-toggle" type="button">
            <span class="caret">▼</span> ხარისხის ფორმები
          </button>
          <div class="topic-local-box degrees-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ფორმა</th>
                  <th colspan="2">მნიშვნელობა</th>
                </tr>
              </thead>
              <tbody>${
								degreeRows.length
									? degreeRows.join('')
									: `<tr><td colspan="3">—</td></tr>`
							}</tbody>
            </table>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle cases-toggle" type="button">
            <span class="caret">▼</span> ბრუნება
          </button>
          <div class="topic-local-box cases-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ბრუნება</th>
                  <th>მხოლობითი რიცხვი</th>
                  <th>მრავლობითი რიცხვი</th>
                </tr>
              </thead>
              <tbody>${caseRows}</tbody>
            </table>
          </div>
        </div>

      </div>`;
		panel.appendChild(block);
	} catch (e) {
		console.warn('adjective fetch error', e);
	}
}

// ========== ГРАММАТИКА: ЧИСЛИТЕЛЬНОЕ ==========
async function embedNumeralSections(panel, wordId, esc) {
	try {
		const r = await fetch(`api/numeral.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const kind = j.kind?.label || '-';
		const cases = j.cases || {};
		const order = (j.labels && j.labels.cases_order) || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const labels = (j.labels && j.labels.cases) || L.cases;

		const rowsHtml = order
			.map((key) => {
				const ka = labels[key] || '';
				const s = cases[key]?.s || '';
				return `
        <tr>
          <td class="case-name">${esc(ka)}</td>
          <td class="case-s">${s ? esc(s) : '—'}</td>
        </tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section numeral-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <button class="topic-local-toggle kind-toggle" type="button">
            <span class="caret">▼</span> ჯგუფი
          </button>
          <div class="topic-local-box kind-box" hidden>
            <div class="simple-kv"><strong>${esc(kind)}</strong></div>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle cases-toggle" type="button">
            <span class="caret">▼</span> ბრუნება
          </button>
          <div class="topic-local-box cases-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ბრუნვა</th>
                  <th>მხოლობითი რიცხვი</th>
                </tr>
              </thead>
              <tbody>${rowsHtml}</tbody>
            </table>
          </div>
        </div>

      </div>`;
		panel.appendChild(block);
	} catch (e) {
		console.warn('numeral fetch error', e);
	}
}

// ========== ГРАММАТИКА: МЕСТОИМЕНИЕ ==========
async function embedPronounSections(panel, wordId, esc) {
	try {
		const r = await fetch(`api/pronoun.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const characteristic = j.characteristic || '';
		const cases = j.cases || {};
		const order = (j.labels && j.labels.cases_order) || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const labels = (j.labels && j.labels.cases) || L.cases;

		const rowsHtml = order
			.map((key) => {
				const ka = labels[key] || '';
				const s = cases[key]?.s || '';
				const p = cases[key]?.p || '';
				return `
        <tr>
          <td class="case-name">${esc(ka)}</td>
          <td class="case-s">${s ? esc(s) : '—'}</td>
          <td class="case-p">${p ? esc(p) : '—'}</td>
        </tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section pronoun-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <button class="topic-local-toggle char-toggle" type="button">
            <span class="caret">▼</span> ჯგუფი
          </button>
          <div class="topic-local-box char-box" hidden>
            <div class="simple-kv"><strong>${esc(
							characteristic || '—'
						)}</strong></div>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle cases-toggle" type="button">
            <span class="caret">▼</span> ბრუნება
          </button>
          <div class="topic-local-box cases-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ბრუნება</th>
                  <th>მხოლობითი რიცხვი</th>
                  <th>მრავლობითი რიცხვი</th>
                </tr>
              </thead>
              <tbody>${rowsHtml}</tbody>
            </table>
          </div>
        </div>

      </div>`;
		panel.appendChild(block);
	} catch (e) {
		console.warn('pronoun fetch error', e);
	}
}

// ========== ГРАММАТИКА: ГЛАГОЛ ==========
async function embedVerbSections(panel, wordId, esc) {
	try {
		const r = await fetch(`api/verb.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const g = j.grammar || {};
		const tenses = j.tenses || {};
		const labels = (j.labels && j.labels.tenses) || {};

		const grammarRows = `
      <div class="simple-kv"><strong>საწყისი:</strong> ${esc(
				g.infinitive || '—'
			)}</div>
      <div class="simple-kv"><strong>გარდამავლობა:</strong> ${esc(
				g.transitivity?.label || '—'
			)}</div>
      <div class="simple-kv"><strong>გვარი:</strong> ${esc(
				g.voice?.label || '—'
			)}</div>
      ${
				g.peculiarity
					? `<div class="simple-kv"><strong>თავისებურება:</strong> ${esc(
							g.peculiarity
					  )}</div>`
					: ''
			}
    `;

		const tenseRows = Object.keys(labels)
			.map((key) => {
				const label = labels[key] || key;
				const val = tenses[key] || '';
				return `<tr><td class="case-name">${esc(
					label
				)}</td><td class="case-s" colspan="2">${
					val ? esc(val) : '—'
				}</td></tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section verb-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <div class="topic-local-box" style="display:block;">
            ${grammarRows}
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle tenses-toggle" type="button">
            <span class="caret">▼</span> უღლება
          </button>
          <div class="topic-local-box tenses-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ფორმა</th>
                  <th colspan="2">მნიშვნელობა</th>
                </tr>
              </thead>
              <tbody>${tenseRows}</tbody>
            </table>
          </div>
        </div>

      </div>`;
		panel.appendChild(block);
	} catch (e) {
		console.warn('verb fetch error', e);
	}
}

// ====== ვერბალური სახ. (საწყისი, id=6) ======
async function embedVerbalNoun(panel, wordId, esc) {
	try {
		const r = await fetch(`api/verbnoun.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		// ზმნ(ებ)ისა
		const verbOf = j.verb_of ? esc(j.verb_of) : '—';

		// падежи (только ед.ч.)
		const labels = j.labels?.cases || L.cases;
		const order = j.labels?.cases_order || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const rows = order
			.map((k) => {
				const name = esc(labels[k] || '');
				const s = j.cases?.[k]?.s ? esc(j.cases[k].s) : '—';
				return `<tr><td class="case-name">${name}</td><td class="case-s">${s}</td></tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section verbnoun-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <button class="topic-local-toggle vn-toggle" type="button">
            <span class="caret">▼</span> ზმნ(ებ)ისა
          </button>
          <div class="topic-local-box vn-box" hidden>
            <div class="section-body">${verbOf}</div>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle cases-toggle" type="button">
            <span class="caret">▼</span> ბრუნება
          </button>
          <div class="topic-local-box cases-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ბრუნება</th>
                  <th>მხოლობითი რიცხვი</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>
          </div>
        </div>

      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('verbnoun fetch error', e);
	}
}

// ====== მიმღეობა (participle, id=7) ======
async function embedParticiple(panel, wordId, esc) {
	try {
		const r = await fetch(
			`api/participle.php?id=${encodeURIComponent(wordId)}`
		);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const verbOf = j.verb_of ? esc(j.verb_of) : '—';
		const voice = j.voice?.label ? esc(j.voice.label) : '—';

		const labels = j.labels?.cases || L.cases;
		const order = j.labels?.cases_order || [
			'nominative',
			'ergative',
			'dative',
			'genetive',
			'instrumental',
			'transformative',
			'vocative',
		];
		const rows = order
			.map((k) => {
				const name = esc(labels[k] || '');
				const s = j.cases?.[k]?.s ? esc(j.cases[k].s) : '—';
				const p = j.cases?.[k]?.p ? esc(j.cases[k].p) : '—';
				return `<tr><td class="case-name">${name}</td><td class="case-s">${s}</td><td class="case-p">${p}</td></tr>`;
			})
			.join('');

		const block = document.createElement('div');
		block.className = 'section participle-grammar';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle grammar-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box grammar-box" hidden>

        <div class="section">
          <button class="topic-local-toggle p-verb-toggle" type="button">
            <span class="caret">▼</span> ზმნ(ებ)ისა
          </button>
          <div class="topic-local-box p-verb-box" hidden>
            <div class="section-body">${verbOf}</div>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle p-voice-toggle" type="button">
            <span class="caret">▼</span> გვარი
          </button>
          <div class="topic-local-box p-voice-box" hidden>
            <div class="section-body">${voice}</div>
          </div>
        </div>

        <div class="section">
          <button class="topic-local-toggle cases-toggle" type="button">
            <span class="caret">▼</span> ბრუნება
          </button>
          <div class="topic-local-box cases-box" hidden>
            <table class="case-table">
              <thead>
                <tr>
                  <th>ბრუნება</th>
                  <th>მხოლობითი რიცხვი</th>
                  <th>მრავლობითი რიცხვი</th>
                </tr>
              </thead>
              <tbody>${rows}</tbody>
            </table>
          </div>
        </div>

      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('participle fetch error', e);
	}
}

// ====== ზმნიზედა (adverb, id=8) ======
async function embedAdverb(panel, wordId, esc) {
	try {
		const r = await fetch(`api/adverb.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const txt = j.semantic_group ? esc(j.semantic_group) : '—';

		const block = document.createElement('div');
		block.className = 'section adverb-sem';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle" type="button">
        <span class="caret">▼</span> სემანტიკური ჯგუფი
      </button>
      <div class="topic-local-box" hidden>
        <div class="section-body">${txt}</div>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('adverb fetch error', e);
	}
}

// ====== ნაწილაკი (particle, id=9) ======
async function embedParticle(panel, wordId, esc) {
	try {
		const r = await fetch(`api/particle.php?id=${encodeURIComponent(wordId)}`);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const txt = j.semantic_group ? esc(j.semantic_group) : '—';

		const block = document.createElement('div');
		block.className = 'section particle-sem';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle" type="button">
        <span class="caret">▼</span> სემანტიკური ჯგუფი
      </button>
      <div class="topic-local-box" hidden>
        <div class="section-body">${txt}</div>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('particle fetch error', e);
	}
}

// ====== კავშირი (conjunction, id=10) ======
async function embedConjunction(panel, wordId, esc) {
	try {
		const r = await fetch(
			`api/conjunction.php?id=${encodeURIComponent(wordId)}`
		);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const txt =
			j.semantic_group !== undefined &&
			j.semantic_group !== null &&
			j.semantic_group !== ''
				? esc(String(j.semantic_group))
				: '—';

		const block = document.createElement('div');
		block.className = 'section conj-gram';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box" hidden>
        <div class="section-body">${txt}</div>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('conjunction fetch error', e);
	}
}

// ====== თანდებული (postposition, id=11) ======
async function embedPostposition(panel, wordId, esc) {
	try {
		const r = await fetch(
			`api/postposition.php?id=${encodeURIComponent(wordId)}`
		);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const label = j.case?.label ? esc(j.case.label) : '—';

		const block = document.createElement('div');
		block.className = 'section postpos-gram';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle" type="button">
        <span class="caret">▼</span> გრამატიკული დახასიათება
      </button>
      <div class="topic-local-box" hidden>
        <div class="section-body">იყენდება — ${label}</div>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('postposition fetch error', e);
	}
}

// ====== შორისდებული (interjection, id=12) ======
async function embedInterjection(panel, wordId, esc) {
	try {
		const r = await fetch(
			`api/interjection.php?id=${encodeURIComponent(wordId)}`
		);
		const j = await r.json();
		if (!(j?.success && j.exists)) return;

		const txt = j.semantic_group ? esc(j.semantic_group) : '—';

		const block = document.createElement('div');
		block.className = 'section interj-sem';
		block.style.marginBottom = '50px';
		block.innerHTML = `
      <button class="topic-local-toggle" type="button">
        <span class="caret">▼</span> სემანტიკური ჯგუფი
      </button>
      <div class="topic-local-box" hidden>
        <div class="section-body">${txt}</div>
      </div>
    `;
		panel.appendChild(block);
	} catch (e) {
		console.warn('interjection fetch error', e);
	}
}

// слушатели
function initListeners() {
	// ввод слова
	$('#form_Search').addEventListener('input', () => {
		debounce(() => doSearch(), DEBOUNCE_MS);
	});

	// изменение селектов — сразу ищем
	['#form_level', '#form_part_of_speech', '#form_tema'].forEach((sel) => {
		const el = $(sel);
		if (el) el.addEventListener('change', () => doSearch());
	});

	// первый запрос при загрузке
	ensureDetailsPanel(); // чтобы панель была сразу под шапкой
	bindDetailsPanelDelegation(); // навешиваем делегирование по панели ОДИН раз
	doSearch();
}

document.addEventListener('DOMContentLoaded', initListeners);
