import { isStepFilled, disallowNextStep, allowNextStep } from './common'
import { ihAjaxRequest, showNotification } from '../common/global'

// Object to store step data
const stepData = {};

/**
 * Initialize event handlers for step-0 form
 */
export const initStep0Form = () => {
	console.log('initStep0Form called - initialization start');
	
	const form = document.getElementById('payment-step-0-form');
	const emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
	const phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
	const termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');
	const submitButton = document.querySelector('#payment-step-0-form .payment-submit-btn');

	if (!form || !emailInput || !phoneInput || !termsCheckbox || !submitButton) {
		console.error('Error: Form elements not found', {
			form: !!form,
			emailInput: !!emailInput,
			phoneInput: !!phoneInput,
			termsCheckbox: !!termsCheckbox,
			submitButton: !!submitButton
		});
		return;
	}
	
	console.log('Step 0 form initialized with elements', {
		formId: form.id,
		emailInputName: emailInput.name,
		phoneInputName: phoneInput.name,
		termsCheckboxName: termsCheckbox.name,
		submitButtonText: submitButton.textContent.trim()
	});

	// More explicit handler functions for debugging
	const handleEmailInput = () => {
		console.log('Email input event triggered');
		validateForm();
	};
	
	const handlePhoneInput = () => {
		console.log('Phone input event triggered');
		validateForm();
	};
	
	const handleCheckboxChange = () => {
		console.log('Checkbox change event triggered');
		validateForm();
	};

	// Email validation with explicit handlers
	emailInput.addEventListener('input', handleEmailInput);
	emailInput.addEventListener('change', handleEmailInput);
	emailInput.addEventListener('blur', handleEmailInput);

	// Phone validation with explicit handlers
	phoneInput.addEventListener('input', handlePhoneInput);
	phoneInput.addEventListener('change', handlePhoneInput);
	phoneInput.addEventListener('blur', handlePhoneInput);

	// Checkbox validation with explicit handler
	termsCheckbox.addEventListener('change', handleCheckboxChange);
	
	// Check manual event triggering for testing
	console.log('Trying to trigger input events manually...');
	try {
		// Create and dispatch events on fields
		const inputEvent = new Event('input', { bubbles: true });
		emailInput.dispatchEvent(inputEvent);
		phoneInput.dispatchEvent(inputEvent);
	} catch (e) {
		console.error('Failed to dispatch manual events:', e);
	}

	// Handle form submission to proceed to next step
	form.addEventListener('submit', (e) => {
		e.preventDefault();
		
		// Save data to localStorage
		stepData.email = emailInput.value;
		stepData.phone = phoneInput.value;
		stepData.termsAccepted = termsCheckbox.checked;
		
		localStorage.setItem('ih-payment-step-0', JSON.stringify(stepData));
		
		// Hide current step
		const step0 = document.getElementById('new-memory-step-0');
		if (step0) {
			step0.classList.remove('active');
			step0.style.display = 'none';
		}
		
		// Show next step
		const step1 = document.getElementById('new-memory-step-1');
		if (step1) {
			step1.classList.remove('hidden');
			step1.classList.add('active');
			step1.style.display = 'flex'; // Explicitly show step-1
		}
		
		// Show footer for back button functionality
		const footer = document.querySelector('.new-memory-footer');
		if (footer) {
			footer.classList.remove('hidden');
			footer.style.display = 'block';
		}
		
		// Show Back button
		const prevButton = document.querySelector('.new-memory-prev-step');
		if (prevButton) {
			prevButton.classList.remove('hidden');
			prevButton.setAttribute('data-prev', '0');
		}
		
		// Output success message
		console.log('Successfully moved to step 1');
	});

	/**
	 * Form validation function
	 */
	function validateForm() {
		const isEmailValid = isValidEmail(emailInput.value);
		const isPhoneValid = isValidPhone(phoneInput.value);
		const isTermsAccepted = termsCheckbox.checked;
		
		console.log('Form validation:', {
			email: emailInput.value,
			isEmailValid,
			phone: phoneInput.value,
			isPhoneValid,
			termsAccepted: isTermsAccepted
		});

		// Update storage data
		stepData.email = emailInput.value;
		stepData.phone = phoneInput.value;
		stepData.termsAccepted = termsCheckbox.checked;

		// Show/hide errors
		if (emailInput.value && !isEmailValid) {
			emailInput.classList.add('error');
		} else {
			emailInput.classList.remove('error');
		}

		if (phoneInput.value && !isPhoneValid) {
			phoneInput.classList.add('error');
		} else {
			phoneInput.classList.remove('error');
		}

		// Enable/disable submit button
		if (isEmailValid && isPhoneValid && isTermsAccepted) {
			console.log('Enabling submit button');
			submitButton.removeAttribute('disabled');
			return true;
		} else {
			console.log('Disabling submit button - Reasons:', {
				emailValid: isEmailValid,
				phoneValid: isPhoneValid,
				termsAccepted: isTermsAccepted
			});
			submitButton.setAttribute('disabled', 'true');
			return false;
		}
	}

	// Validate form on page load
	setTimeout(() => {
		validateForm();
		console.log("Initial form validation completed");
	}, 500);
	
	// Restore data from localStorage if available
	const savedData = localStorage.getItem('ih-payment-step-0');
	if (savedData) {
		try {
			const parsedData = JSON.parse(savedData);
			
			if (parsedData.email) {
				emailInput.value = parsedData.email;
			}
			
			if (parsedData.phone) {
				phoneInput.value = parsedData.phone;
			}
			
			if (parsedData.termsAccepted) {
				termsCheckbox.checked = parsedData.termsAccepted;
			}
			
			validateForm();
		} catch (e) {
			console.error('Error parsing saved data', e);
		}
	}
}

/**
 * Email validation
 * @param {string} email - Email to validate
 * @returns {boolean} - Validation result
 */
function isValidEmail(email) {
	if (!email) return false;
	const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return emailRegex.test(email);
}

/**
 * Phone number validation
 * @param {string} phone - Phone number to validate
 * @returns {boolean} - Validation result
 */
function isValidPhone(phone) {
	if (!phone) {
		console.log('Phone validation failed: empty value');
		return false;
	}
	
	// Clean phone number from extra characters and validate
	const cleanPhone = phone.replace(/\s+/g, '');
	
	// Check for + at start and minimum 10 digits
	const phoneRegex = /^\+\d{10,}$/;
	const isValid = phoneRegex.test(cleanPhone);
	
	console.log('Phone validation:', {
		originalPhone: phone,
		cleanPhone,
		isValid,
		pattern: '^\+\d{10,}$'
	});
	
	return isValid;
}

/**
 * Check if step 0 form is filled
 * @returns {boolean} - Check result
 */
export const checkStep0 = () => {
	const emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
	const phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
	const termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');

	if (!emailInput || !phoneInput || !termsCheckbox) {
		console.log('Form elements not found:', {
			emailInput: !!emailInput,
			phoneInput: !!phoneInput,
			termsCheckbox: !!termsCheckbox
		});
		return false;
	}

	const isEmailValid = isValidEmail(emailInput.value);
	const isPhoneValid = isValidPhone(phoneInput.value);
	const isTermsAccepted = termsCheckbox.checked;
	
	console.log('Step0 check results:', {
		email: emailInput.value,
		isEmailValid,
		phone: phoneInput.value,
		isPhoneValid,
		isTermsAccepted
	});

	return isEmailValid && isPhoneValid && isTermsAccepted;
};