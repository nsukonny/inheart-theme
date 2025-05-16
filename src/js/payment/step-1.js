import Cropper from 'cropperjs'
import datepicker from 'js-datepicker'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import {
	checkAjaxWorkingStatus,
	getTargetElement,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	setTargetElement,
	showNotification
} from '../common/global'
import { isStepFilled } from './common'

// Object to store step data
const stepData = {};

/**
 * Initialize event handlers for step-1 form
 */
export const initStep1Form = () => {
	const form = document.getElementById('payment-step-1-form');
	// Select all text fields, including types text, email, tel
	const inputs = document.querySelectorAll('#payment-step-1-form input[type="text"], #payment-step-1-form input[type="email"], #payment-step-1-form input[type="tel"]');
	const submitButton = document.querySelector('#payment-step-1-form .payment-submit-btn');

	if (!form || !inputs.length || !submitButton) return;

	// Add handlers for all text fields
	inputs.forEach(input => {
		input.addEventListener('input', validateForm);
		input.addEventListener('change', validateForm);
		input.addEventListener('blur', validateForm);
	});

	// Handle form submission to complete the process
	form.addEventListener('submit', (e) => {
		e.preventDefault();
		
		if (!validateForm()) {
			showNotification('Будь ласка, заповніть всі обов\'язкові поля', 'error');
			return;
		}
		
		// Disable button during submission
		submitButton.setAttribute('disabled', 'true');
		submitButton.innerHTML = 'Відправка...';
		
		// Save data to localStorage
		inputs.forEach(input => {
			stepData[input.name] = input.value;
		});
		
		localStorage.setItem('ih-payment-step-1', JSON.stringify(stepData));
		
		// Send form to server
		const formData = new FormData();
		formData.append('action', 'ih_ajax_payment_complete');
		formData.append('step0Data', localStorage.getItem('ih-payment-step-0') || '{}');
		formData.append('step1Data', localStorage.getItem('ih-payment-step-1') || '{}');
		
		ihAjaxRequest(formData).then(res => {
			// Enable button
			submitButton.removeAttribute('disabled');
			submitButton.innerHTML = form.getAttribute('data-button-text') || 'Оплатити';
			
			if (res) {
				if (res.success) {
					showNotification('Форма успішно відправлена', 'success');
					
					// Redirect or other actions after successful submission
					if (res.data && res.data.redirect) {
						setTimeout(() => {
							window.location.href = res.data.redirect;
						}, 2000);
					}
				} else {
					showNotification(res.data && res.data.msg ? res.data.msg : 'Помилка відправки форми', 'error');
				}
			}
		}).catch(error => {
			// Enable button
			submitButton.removeAttribute('disabled');
			submitButton.innerHTML = form.getAttribute('data-button-text') || 'Оплатити';
			
			showNotification('Помилка відправки форми, спробуйте ще раз', 'error');
			console.error('Error sending form:', error);
		});
	});

	/**
	 * Form validation function
	 * @returns {boolean} Validation result
	 */
	function validateForm() {
		let isFormValid = true;
		
		// Check all fields for completion
		inputs.forEach(input => {
			if (input.required && !input.value.trim()) {
				input.classList.add('error');
				isFormValid = false;
			} else {
				input.classList.remove('error');
			}
			
			// Save values to object
			stepData[input.name] = input.value;
		});
		
		// Save to localStorage on each change
		localStorage.setItem('ih-payment-step-1', JSON.stringify(stepData));
		
		return isFormValid;
	}

	// Validate form on page load
	validateForm();
	
	// Restore data from localStorage if available
	const savedData = localStorage.getItem('ih-payment-step-1');
	if (savedData) {
		try {
			const parsedData = JSON.parse(savedData);
			inputs.forEach(input => {
				if (parsedData[input.name]) {
					input.value = parsedData[input.name];
				}
			});
			validateForm();
		} catch (e) {
			console.error('Error parsing saved data', e);
		}
	}
}

/**
 * Check if step 1 form is filled
 * @returns {boolean} - Check result
 */
export const checkStep1 = () => {
	const inputs = document.querySelectorAll('#payment-step-1-form input[required]');
	
	if (!inputs.length) return false;
	
	// Form is valid if all required fields are filled
	return Array.from(inputs).every(input => input.value.trim() !== '');
};
