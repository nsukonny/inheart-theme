import Cropper from 'cropperjs'
import datepicker from 'js-datepicker'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import {
	checkAjaxWorkingStatus,
	getTargetElement,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	setTargetElement
} from '../common/global'
import { isStepFilled } from './common'

const stepData = localStorage.getItem( 'ih-step-1' ) ?
	JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : { lang: 'uk' }
let cropper

const dateOfBirth = document.getElementById('date-of-birth');
const dateOfDeath = document.getElementById('date-of-death');
let dateOfBirthPicker = null;
let dateOfDeathPicker = null;

document.addEventListener('DOMContentLoaded', () => {
	const savedData = JSON.parse(localStorage.getItem('memoryFormData'));
	if (savedData) {
		const lastnameInput = document.querySelector('input[name="lastname"]');
		const firstnameInput = document.querySelector('input[name="firstname"]');
		const fathernameInput = document.querySelector('input[name="fathername"]');

		if (lastnameInput) lastnameInput.value = savedData.lastname || '';
		if (firstnameInput) firstnameInput.value = savedData.firstname || '';
		if (fathernameInput) fathernameInput.value = savedData.fathername || '';

		localStorage.removeItem('memoryFormData');
	}
});


/**
 * Creates a debounced function that delays invoking the provided function until after a specified delay.
 *
 * @param {Function} fn - The function to debounce.
 * @param {number} [delay=500] - The number of milliseconds to delay; defaults to 500ms.
 * @returns {Function} A new debounced function that delays invoking the original function.
 */
function debounce(fn, delay = 500) {
    let timeout
    return (...args) => {
        clearTimeout(timeout)
        timeout = setTimeout(() => fn.apply(this, args), delay)
    }
}

/**
 * Checks whether the provided date string is valid.
 * Accepts a wide variety of date formats:
 * - dd.mm.yyyy
 * - d/m/yyyy
 * - yyyy-mm-dd
 * - mm-dd-yyyy
 * - and more (any separator: '.', '/', '-', '\', space)
 *
 * @param {string} str - The input date string.
 * @returns {boolean} True if the date is valid, otherwise false.
 */
function isValidDateString(str) {
	return parseDate(str) !== null;
}

/**
 * Converts any supported date string into a standard format: "dd.mm.yyyy".
 *
 * @param {string} str - The raw input date string.
 * @returns {string} The formatted date string, or an empty string if invalid.
 */
function formatDateToStandard(str) {
	const date = parseDate(str);
	if (!date) return '';

	const dd = String(date.getDate()).padStart(2, '0');
	const mm = String(date.getMonth() + 1).padStart(2, '0');
	const yyyy = date.getFullYear();

	return `${dd}.${mm}.${yyyy}`;
}

/**
 * Parses a variety of common date string formats and returns a valid Date object.
 *
 * Supported formats:
 * - dd.mm.yyyy / d.m.yyyy
 * - dd/mm/yyyy / mm/dd/yyyy
 * - dd-mm-yyyy / mm-dd-yyyy
 * - yyyy-mm-dd / yyyy.mm.dd / yyyy/mm/dd
 * - With separators: ".", "/", "-", "\", or space
 *
 * @param {string} str - The date string to parse.
 * @returns {Date|null} A Date object if valid, otherwise null.
 */
function parseDate(str) {
	const cleaned = str.trim().replace(/[\s\/\\\-]+/g, '.'); // Normalize all separators to "."

	const parts = cleaned.split('.').map(Number);
	if (parts.length !== 3 || parts.some(isNaN)) return null;

	let dd, mm, yyyy;
	// Format: yyyy.mm.dd
	if (parts[0] > 1900 && parts[0] <= 2100) {
		[yyyy, mm, dd] = parts;
	// Format: dd.mm.yyyy or mm.dd.yyyy
	} else if (parts[2] > 1900 && parts[2] <= 2100) {
		if (parts[0] > 12) {
			[dd, mm, yyyy] = parts;
		} else if (parts[1] > 12) {
			[mm, dd, yyyy] = parts;
		} else {
			// Default to dd.mm.yyyy if ambiguous
			[dd, mm, yyyy] = parts;
		}
	} else {
		return null;
	}

	// Basic range checks
	if (
		!yyyy || !mm || !dd ||
		dd < 1 || dd > 31 ||
		mm < 1 || mm > 12 ||
		yyyy < 1900 || yyyy > 2100
	) return null;

	const date = new Date(yyyy, mm - 1, dd);

	// Final validation to ensure date is real (e.g., no Feb 31)
	if (
		date.getFullYear() === yyyy &&
		date.getMonth() === mm - 1 &&
		date.getDate() === dd
	) {
		return date;
	}

	return null;
}

/**
 * Validates that the "date of birth" is not later than the "date of death".
 *
 * - Both fields are optional and may be null or empty during validation.
 * - If both dates are valid and birthDate > deathDate, applies an "error" class.
 * - Removes "error" classes otherwise.
 *
 * @returns {void}
 */
function validateDates() {
	if (!dateOfBirth || !dateOfDeath) return

	const birthStr = dateOfBirth.value;
	const deathStr = dateOfDeath.value;

	// Check date correctness
	if (birthStr) {
		if (!isValidDateString(birthStr)) {
			dateOfBirth.classList.add('error-date');
		} else {
			dateOfBirth.classList.remove('error-date');
		}
	}

	if (deathStr) {
		if (!isValidDateString(deathStr)) {
			dateOfDeath.classList.add('error-date');
		} else {
			dateOfDeath.classList.remove('error-date');
		}
	}

	const birthDate = parseDate(birthStr);
	const deathDate = parseDate(deathStr);

	// If both dates are valid
	if (birthDate && deathDate) {
		if (birthDate > deathDate) {
			// Auto-set date of death to match date of birth
			dateOfDeathPicker.setDate(birthDate, {render: true});
		}
	}
}

/**
 * Use JS-Datepicker for the dates.
 */
export const initDatepickers = () => {
	const
		inputs      = document.querySelectorAll( '.date-input' ),
		currentLang = document.documentElement.getAttribute( 'lang' ),
		ukrMonths   = [
			'Січень',
			'Лютий',
			'Березень',
			'Квітень',
			'Травень',
			'Червень',
			'Липень',
			'Серпень',
			'Вересень',
			'Жовтень',
			'Листопад',
			'Грудень'
		],
		ukrDays     = ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']

	if ( ! inputs.length ) return

	inputs.forEach( input => {
		// Prevent paste of non-numeric characters
		input.addEventListener('paste', (e) => {
			e.preventDefault();
			const pastedText = (e.clipboardData || window.clipboardData).getData('text');
			const cleanedText = pastedText.replace(/[^\d\.\/\-]/g, '');
			document.execCommand('insertText', false, cleanedText);
		});

		// Set placeholder
		input.placeholder = '__.__._____';

		// Prevent non-numeric characters input (except separators)
		input.addEventListener('keypress', (e) => {
			// Allow only digits and dot
			if (!/[\d\.]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
				e.preventDefault();
				return false;
			}

			// Automatically add dot after entering 2 and 5 characters
			const value = input.value;
			if (e.key !== '.' && /^\d{2}$/.test(value) || /^\d{2}\.\d{2}$/.test(value)) {
				input.value = value + '.';
			}
		});

		const dp = datepicker(input, {
			formatter: (input, date, instance) => {
				input.value = formatDateToStandard(date.toLocaleDateString());
				validateDates();
			},
			position: 'tl',
			startDate: input.id === 'date-of-birth'
				? new Date(1930, 0, 1)
				: new Date(2014, 0, 1),
			overlayPlaceholder: currentLang === 'uk' && 'Рік (4 цифри)',
			overlayButton: currentLang === 'uk' && 'Підтвердити',
			customMonths: currentLang === 'uk' && ukrMonths,
			customOverlayMonths: currentLang === 'uk' && ukrMonths,
			customDays: currentLang === 'uk' && ukrDays,
			startDay: currentLang === 'uk' && 1,
			onSelect: (instance, date) => {
				validateDates();
				requestAnimationFrame(() => {
					instance.hide();
				});
			}
		})

		if (input.id === 'date-of-birth') {
			dateOfBirthPicker = dp;
		} else if (input.id === 'date-of-death') {
			dateOfDeathPicker = dp;
		}
	
		input.addEventListener('input', debounce((e) => {
			// Filter out non-numeric characters
			const value = e.target.value;
			const cleanedValue = value.replace(/[^\d\.\/\-]/g, '');
			if (value !== cleanedValue) {
				e.target.value = cleanedValue;
			}

			// Automatically format entered date
			const parts = cleanedValue.split(/[\.\/\-]/);
			if (parts.length === 1 && parts[0].length > 2) {
				// If more than 2 digits entered without separator, add dots
				let formattedValue = '';
				const digits = parts[0];
				
				if (digits.length >= 2) {
					formattedValue += digits.substring(0, 2) + '.';
					
					if (digits.length >= 4) {
						formattedValue += digits.substring(2, 4) + '.';
						formattedValue += digits.substring(4, Math.min(8, digits.length));
					} else {
						formattedValue += digits.substring(2, digits.length);
					}
					
					e.target.value = formattedValue;
				}
			}

			if (/^\d{4}$/.test(cleanedValue)) {
				const year = parseInt(cleanedValue);
				if (year >= 1900 && year <= 2100) {
					e.target.value = `01.01.${year}`;
				}
			}

			const formatted = formatDateToStandard(e.target.value);
			if (formatted) {
				e.target.value = formatted;
				dp.setDate(parseDate(formatted), { render: true });
				dp.hide();
			}

			// Check date correctness
			if (e.target.value) {
				if (!isValidDateString(e.target.value)) {
					e.target.classList.add('error-date');
				} else {
					e.target.classList.remove('error-date');
				}
			} else {
				e.target.classList.remove('error-date');
			}

			validateDates();
		}, 500));
	} )
}

/**
 * Select memory language.
 */
export const selectLanguage = () => {
	const langs = document.querySelectorAll( '.new-memory-lang' )

	if( ! langs.length ) return

	langs.forEach( lang => {
		lang.addEventListener( 'click', () => {
			document.querySelector( '.new-memory-lang.active' ).classList.remove( 'active' )
			lang.classList.add( 'active' )
			stepData.lang = lang.dataset.lang
			localStorage.setItem( 'ih-step-1', JSON.stringify( stepData ) )
		} )
	} )
}

/**
 * Upload main photo in Main info form, Step 1.
 */
export const uploadMainPhoto = () => {
	const
		popup			= document.querySelector( '.photo-popup' ),
		photo			= document.querySelector( '#photo' ),
		img				= document.querySelector( '.popup-photo' ),
		saveBtn			= document.querySelector( '.popup-save-photo' ),
		mainPhotoNameEl	= document.querySelector( '.new-memory-main-info .filename' ),
		photoButtons	= document.querySelectorAll( '.new-memory-main-info .label-file .button' )
	let mainPhotoName

	if( ! popup || ! photo || ! img || ! saveBtn || ! mainPhotoNameEl || !photoButtons.length ) {
		console.error('Required elements not found:', {
			popup: !!popup,
			photo: !!photo,
			img: !!img,
			saveBtn: !!saveBtn,
			mainPhotoNameEl: !!mainPhotoNameEl,
			photoButtons: photoButtons.length
		})
		return
	}

	photo.addEventListener( 'change', () => {
		const file = photo.files[0]
		if (!file) return

		console.log('File selected:', file.name, 'Size:', file.size)

		// Store original content for both buttons
		const originalContents = Array.from(photoButtons).map(button => button.querySelector('.content'))
		if (!originalContents.every(content => content)) {
			console.error('Original content not found in some buttons')
			return
		}

		// Create loading text
		const loadingText = document.createElement('span')
		loadingText.className = 'content'
		loadingText.textContent = 'Фото завантажується'

		// Add uploading class and reset progress to all buttons
		photoButtons.forEach(button => {
			button.classList.add('uploading')
			button.style.setProperty('--upload-progress', '0%')
			// Replace content
			const originalContent = button.querySelector('.content')
			originalContent.style.display = 'none'
			button.appendChild(loadingText.cloneNode(true))
		})
		console.log('Added uploading class and reset progress')

		const fReader = new FileReader()
		mainPhotoName = file.name

		// Start artificial progress animation
		let progress = 0
		const progressInterval = setInterval(() => {
			progress += 1
			console.log('Progress:', progress.toFixed(2) + '%')
			// Update progress for all buttons
			photoButtons.forEach(button => {
				button.style.setProperty('--upload-progress', `${progress}%`)
			})
			
			if (progress >= 100) {
				clearInterval(progressInterval)
				// Show popup only after reaching 100%
				setTimeout(() => {
					console.log('Progress complete, showing popup')
					// Restore all buttons
					photoButtons.forEach((button, index) => {
						button.classList.remove('uploading')
						button.style.removeProperty('--upload-progress')
						// Remove loading text and restore original content
						button.querySelectorAll('.content').forEach(content => {
							if (content !== originalContents[index]) {
								content.remove()
							}
						})
						originalContents[index].style.display = 'inline-flex'
					})

					img.src = fReader.result
					popup.classList.remove('hidden')
					setTargetElement('#photo-popup')
					disableBodyScroll(getTargetElement(), { reserveScrollBarGap: true })
					cropper = new Cropper(img, {
						aspectRatio: 302 / 389,
						movable: false,
						rotatable: false,
						scalable: true,
						zoomable: false,
						zoomOnTouch: false
					})
				}, 300)
			}
		}, 30)

		fReader.readAsDataURL(file)
		fReader.onload = e => {
			// Store the result but don't show popup yet
			fReader.result = e.target.result
		}

		fReader.onerror = () => {
			clearInterval(progressInterval)
			console.error('Error reading file')
			// Restore all buttons on error
			photoButtons.forEach((button, index) => {
				button.classList.remove('uploading')
				button.style.removeProperty('--upload-progress')
				// Remove loading text and restore original content
				button.querySelectorAll('.content').forEach(content => {
					if (content !== originalContents[index]) {
						content.remove()
					}
				})
				originalContents[index].style.display = 'inline-flex'
			})
		}
	})

	// Close popup.
	popup.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.className &&
			( target.classList.contains( 'popup' ) || target.classList.contains( 'popup-discard-photo' ) )
		){
			popup.classList.add( 'hidden' )
			enableBodyScroll( getTargetElement() )

			if( cropper ) cropper.destroy()
		}
	} )

	// Save cropped image.
	saveBtn.addEventListener( 'click', () => {
		const formData = new FormData()

		popup.classList.add( 'hidden' )
		enableBodyScroll( getTargetElement() )
		formData.append( 'action', 'ih_ajax_upload_main_photo' )

		cropper.getCroppedCanvas().toBlob( blob => {
			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )
			formData.append( 'cropped', blob, mainPhotoName )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							mainPhotoNameEl.innerHTML = res.data.short_filename
							mainPhotoNameEl.closest( '.label' ).classList.add( 'added' )
							stepData.cropped = res.data.url
							photo.setAttribute( 'data-cropped', stepData.cropped )
							localStorage.setItem( 'ih-step-1', JSON.stringify( stepData ) )
							isStepFilled( 1 )
							break

						case false:
							console.error( res.data.msg )
							break
					}
				}

				setAjaxWorkingStatus( false )
				cropper.destroy()
			} )
		} )
	} )
}

/**
 * Validate Step 1 Main info form.
 */
export const addMainFormValidation = () => {
	const fields = document.querySelectorAll('.new-memory-main-info input:not([type="file"])')

	if (!fields.length) return

	fields.forEach(field => {
		field.addEventListener('change', e => checkFieldValue(e))
		field.addEventListener('keyup', e => checkFieldValue(e))
		field.addEventListener('blur', e => checkFieldValue(e))
	})

	const checkFieldValue = e => {
		const
			field = e.target,
			value = field.value,
			index = field.name

		// Only add error class if field is required and empty
		if (field.required && !value) field.classList.add('error')
		else field.classList.remove('error')

		stepData[index] = value
		localStorage.setItem('ih-step-1', JSON.stringify(stepData))
		isStepFilled(1)
	}
}

/**
 * Check if Step 1 Main info form is valid.
 *
 * @returns {boolean}
 */
export const checkStep1 = () => {
	const fields = document.querySelectorAll('.new-memory-main-info input');
	let isFormValid = true;

	if (!fields.length) return false;

	stepData.lang = document.querySelector('.new-memory-lang.active')?.dataset.lang || 'en';

	let birthDate = null;
	let deathDate = null;

	fields.forEach(field => {
		const index = field.name;
		const value = field.value.trim();

		// Only check for errors if field is required
		if (field.required && (field.classList.contains('error') || !value)) {
			isFormValid = false;
		}

		if (index === 'photo' && field.dataset.cropped) {
			stepData.cropped = field.dataset.cropped;
		}

		if (index === 'date-of-birth') {
			if (!isValidDateString(value)) {
				isFormValid = false;
			} else {
				birthDate = parseDate(value);
			}
		}

		if (index === 'date-of-death') {
			if (!isValidDateString(value)) {
				isFormValid = false;
			} else {
				deathDate = parseDate(value);
			}
		}

		stepData[index] = value;
	});

	if (birthDate && deathDate && birthDate > deathDate) {
		isFormValid = false;
	}

	localStorage.setItem('ih-step-1', JSON.stringify(stepData));

	return isFormValid;
}