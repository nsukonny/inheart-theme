document.addEventListener('DOMContentLoaded', function() {
	
	var form = document.getElementById('payment-step-0-form');
	var emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
	var phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
	var termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');
	var submitButton = document.querySelector('#payment-step-0-form .payment-submit-btn');
	var validationStatus = document.getElementById('payment-step-0-validation-status');
	
	if (!form || !emailInput || !phoneInput || !termsCheckbox || !submitButton) {
		console.error('Form elements not found in inline script');
		if (validationStatus) validationStatus.innerText = 'Ошибка: элементы формы не найдены';
		return;
	}
	
	// Функция для нормализации телефона (разрешает +380 и 0, преобразует 0 в +380)
	function normalizePhone(phone) {
		// Удаляем все нецифры
		let digits = phone.replace(/\D/g, '');
		// Если начинается с 0 и длина 10, преобразуем в +380
		if (digits.length === 10 && digits[0] === '0') {
			return '+380' + digits.slice(1);
		}
		// Если уже +380XXXXXXXXX (12 цифр, начинается с 380)
		if (digits.length === 12 && digits.startsWith('380')) {
			return '+' + digits;
		}
		return null; // невалидный
	}
	
	// Обработчик blur для поля телефона (автозамена 0 на +380)
	phoneInput.addEventListener('blur', function() {
		let norm = normalizePhone(phoneInput.value);
		if (norm) phoneInput.value = norm;
	});
	
	// Обработчик submit кнопки (предотвращаем отправку и проверяем валидность)
	submitButton.addEventListener('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
		var isPhoneValid = !!normalizePhone(phoneInput.value);
		var isTermsAccepted = termsCheckbox.checked;
		if (isEmailValid && isPhoneValid && isTermsAccepted) {
			// Сохраняем данные (например, в localStorage) и переходим на шаг 1
			var formData = { email: emailInput.value, phone: phoneInput.value, termsAccepted: termsCheckbox.checked, completed: true };
			localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
			// Обновляем шаг в хедере (если есть)
			var currentStepEl = document.querySelector('.payment-current-step');
			if (currentStepEl) { currentStepEl.textContent = '2'; }
			// Скрываем шаг 0 (например, удаляем класс active и добавляем hidden)
			var step0 = document.getElementById('new-memory-step-0');
			if (step0) { step0.classList.remove('active', 'step-visible'); step0.classList.add('hidden', 'step-hidden'); step0.style.display = 'none'; step0.style.visibility = 'hidden'; step0.style.opacity = '0'; }
			// Показываем шаг 1 (например, удаляем hidden и добавляем active)
			var step1 = document.getElementById('new-memory-step-1');
			if (step1) { step1.classList.remove('hidden', 'step-hidden'); step1.classList.add('active', 'step-visible'); step1.style.display = 'flex'; step1.style.visibility = 'visible'; step1.style.opacity = '1'; }
			// Обновляем URL (опционально)
			if (history.pushState) { var newUrl = window.location.pathname + '?step=1'; window.history.pushState({step: 1}, '', newUrl); }
			return false;
		}
	});
	
	// Обработчик submit формы (предотвращаем отправку)
	form.addEventListener('submit', function(e) { e.preventDefault(); e.stopPropagation(); return false; });
	
	// Функция inline-валидации (обновлена для телефона)
	function validateFormInline() {
		var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
		var isPhoneValid = !!normalizePhone(phoneInput.value);
		var isTermsAccepted = termsCheckbox.checked;
		if (isEmailValid && isPhoneValid && isTermsAccepted) {
			submitButton.removeAttribute('disabled');
			if (validationStatus) { validationStatus.innerText = 'Форма готова к отправке'; validationStatus.style.color = 'green'; }
		} else {
			submitButton.setAttribute('disabled', 'true');
			var reasons = [];
			if (!isEmailValid && emailInput.value) reasons.push('Email неверный');
			if (!isPhoneValid && phoneInput.value) reasons.push('Телефон неверный');
			if (!isTermsAccepted) reasons.push('Примите условия');
			if (validationStatus) { validationStatus.innerText = reasons.length ? 'Ошибки: ' + reasons.join(', ') : 'Заполните форму'; validationStatus.style.color = 'red'; }
		}
	}
	
	emailInput.addEventListener('input', validateFormInline);
	phoneInput.addEventListener('input', validateFormInline);
	termsCheckbox.addEventListener('change', validateFormInline);
	
	validateFormInline();
	
	// Function to switch to step 1
	function switchToStep1() {
		
		// Save form data to localStorage
		var formData = {
			email: emailInput.value,
			phone: phoneInput.value,
			termsAccepted: termsCheckbox.checked,
			completed: true
		};
		localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
		
		// Update step number in header
		var currentStepEl = document.querySelector('.payment-current-step');
		if (currentStepEl) {
			currentStepEl.textContent = '2';
		}
		
		// Hide current step with stronger CSS rules
		var step0 = document.getElementById('new-memory-step-0');
		if (step0) {
			// Use special classes for complete guarantee
			step0.classList.remove('active', 'step-visible');
			step0.classList.add('hidden', 'step-hidden');
			
			// Apply inline styles for additional reliability
			step0.style.display = 'none';
			step0.style.visibility = 'hidden';
			step0.style.opacity = '0';
			step0.style.position = 'absolute';
			step0.style.pointerEvents = 'none';
			step0.style.zIndex = '-1';
		}
		
		// Show next step with stronger CSS rules
		var step1 = document.getElementById('new-memory-step-1');
		if (step1) {
			// Remove all styles that might prevent display
			step1.removeAttribute('style');
			
			// Use special classes for complete guarantee
			step1.classList.remove('hidden', 'step-hidden');
			step1.classList.add('active', 'step-visible');
			
			// Force display the block
			step1.style.display = 'flex';
			step1.style.visibility = 'visible';
			step1.style.opacity = '1';
			step1.style.position = 'relative';
			step1.style.pointerEvents = 'auto';
			step1.style.zIndex = '1';
			
			// Check if step is actually visible after our changes
			setTimeout(function() {
				var computedStyle = getComputedStyle(step1);
				// If still not visible, try again
				if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden') {
					console.warn('Step 1 still not visible, forcing display with dynamic style element');
					// Add styles directly to head for complete certainty
					var styleEl = document.createElement('style');
					styleEl.textContent = '#new-memory-step-1 { display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important; }';
					styleEl.textContent += '#new-memory-step-0 { display: none !important; visibility: hidden !important; opacity: 0 !important; position: absolute !important; }';
					document.head.appendChild(styleEl);
					
					// As a last resort, if CSS fails, try reinserting the element
					var parent = step1.parentNode;
					var next = step1.nextSibling;
					parent.removeChild(step1);
					setTimeout(function() {
						if (next) {
							parent.insertBefore(step1, next);
						} else {
							parent.appendChild(step1);
						}
						step1.classList.add('step-visible');
						step1.style.display = 'flex';
					}, 50);
				}
			}, 100);
		}
		
		// Show footer for back button functionality
		var footer = document.querySelector('.new-memory-footer');
		if (footer) {
			footer.classList.remove('hidden');
			footer.style.display = 'block';
			footer.style.visibility = 'visible';
			footer.style.opacity = '1';
		}
		
		// Enable "Back" button
		var prevButton = document.querySelector('.new-memory-prev-step');
		if (prevButton) {
			prevButton.classList.remove('hidden');
			prevButton.setAttribute('data-prev', '0');
			prevButton.style.display = 'inline-flex';
			prevButton.style.visibility = 'visible';
		}
		
		validationStatus.innerText = 'Переход на шаг 2 успешно выполнен';
		validationStatus.style.color = 'green';
	}
	
	// Handle form submission
	form.addEventListener('submit', function(e) {
		
		// Prevent form submission and page reload
		e.preventDefault();
		
		// Check form validity
		var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
		var isPhoneValid = !!normalizePhone(phoneInput.value);
		var isTermsAccepted = termsCheckbox.checked;
		
		if (isEmailValid && isPhoneValid && isTermsAccepted) {
			// If form is valid, switch to step 1
			validationStatus.innerText = 'Обработка данных...';
			validationStatus.style.color = 'blue';
			submitButton.setAttribute('disabled', 'true');
			
			// Save data with "completed" flag
			var formData = {
				email: emailInput.value,
				phone: phoneInput.value,
				termsAccepted: termsCheckbox.checked,
				completed: true  // Mark step as completed
			};
			localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
			
			// Add small delay to simulate submission
			setTimeout(function() {
				// Use main switching function
				switchToStep1();
				
				// Additionally call global forceShowStep1 function if available
				if (typeof window.forceShowStep1 === 'function') {
					window.forceShowStep1();
				}
				
				// Add parameter to URL to save state
				var newUrl = window.location.pathname + '?step=1';
				// Use history.replaceState to avoid creating new browser history entry
				try {
					window.history.replaceState({step: 1}, 'Шаг 2', newUrl);
				} catch (e) {
					console.error('Error updating URL:', e);
				}
			}, 500);
		} else {
			// If form is invalid, show error message
			validationStatus.innerText = 'Пожалуйста, заполните все поля корректно';
			validationStatus.style.color = 'red';
		}
	});

	// Get the Mono Checkout button
	const monoCheckoutButton = document.getElementById('mono-checkout-submit');
	
	// Add click handler for Mono Checkout button
	monoCheckoutButton.addEventListener('click', async function(e) {
		e.preventDefault();
		console.log('Mono Checkout button clicked');

		try {
			// Disable button and show loading state
			monoCheckoutButton.disabled = true;
			monoCheckoutButton.classList.add('loading');
			
			// Prepare form data
			const formData = new FormData();
			formData.append('action', 'ih_ajax_create_mono_payment');
			formData.append('qr-count-qty', '1');

			console.log('Sending request to:', window.ajaxUrlPayment);
			console.log('Form data:', {
				action: 'ih_ajax_create_mono_payment',
				email: emailInput.value,
				phone: phoneInput.value,
				terms: termsCheckbox.checked ? '1' : '0',
				'qr-count-qty': '1'
			});

			// Send request to create Mono payment
			const response = await fetch(window.ajaxUrlPayment, {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: formData
			});
			
			console.log('Response status:', response.status);
			const responseText = await response.text();
			console.log('Raw response:', responseText);
			
			let data;
			try {
				data = JSON.parse(responseText);
				console.log('Parsed response:', data);
			} catch (e) {
				console.error('Failed to parse response as JSON:', e);
				throw new Error('Некоректна відповідь від сервера');
			}

			if (data.success && data.data && data.data.checkoutUrl) {
				console.log('Redirecting to:', data.data.checkoutUrl);
				window.location.href = data.data.checkoutUrl;
			} else {
				console.error('Invalid response format:', data);
				throw new Error(data.data?.msg || 'Помилка при створенні оплати через Mono');
			}
		} catch (error) {
			console.error('Error in Mono Checkout handler:', error);
			// Reset button state
			monoCheckoutButton.disabled = false;
			monoCheckoutButton.classList.remove('loading');
		}
	});
});