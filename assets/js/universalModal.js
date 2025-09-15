(function () {
	document.addEventListener('DOMContentLoaded', () => {
		console.log('universalModal.js: DOMContentLoaded');

		const modal = document.getElementById('universalModal');
		const modalClose = document.getElementById('universalModalClose');
		const modalContentArea = document.getElementById('modalContentArea');

		const triggers = document.querySelectorAll('[data-modal]');
		console.log(`Найдено ${triggers.length} триггер(ов) с data-modal`);

		// Открытие модального окна
		triggers.forEach((trigger) => {
			trigger.addEventListener('click', () => {
				const modalId = trigger.getAttribute('data-modal');
				console.log(`Клик по модалке: ${modalId}`);

				const template = document.getElementById(modalId);
				if (template) {
					modalContentArea.innerHTML = template.innerHTML;
					const responseDiv = document.getElementById('contactResponse');
					if (responseDiv) responseDiv.textContent = '';
					modal.style.display = 'block';
					console.log(`Показана модалка: ${modalId}`);
				} else {
					console.warn(`Шаблон с id="${modalId}" не найден`);
				}
			});
		});

		// Закрытие по крестику
		modalClose.addEventListener('click', () => {
			modal.style.display = 'none';
			modalContentArea.innerHTML = '';
			console.log('Модалка закрыта по крестику');
		});

		// Закрытие по клику вне контента
		window.addEventListener('click', (e) => {
			if (e.target === modal) {
				modal.style.display = 'none';
				modalContentArea.innerHTML = '';
				console.log('Модалка закрыта по клику вне области');
			}
		});
	});
	document.addEventListener('submit', function (e) {
		const form = e.target;
		if (form.id === 'contactForm') {
			e.preventDefault();

			const formData = new FormData(form);
			fetch(form.action, {
				method: 'POST',
				body: formData,
			})
				.then((res) => res.json())
				.then((data) => {
					const responseDiv = document.getElementById('contactResponse');
					responseDiv.textContent = data.message;
					responseDiv.style.color = data.success ? 'green' : 'red';
					if (data.success) {
						form.reset();
					}
				})
				.catch(() => {
					alert('Error occurred while sending the form.');
				});
		}
	});
})();
