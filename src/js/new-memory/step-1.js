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
 * Validates whether a given string is a valid date in the "dd.mm.yyyy" format.
 *
 * Validation rules:
 * - The string must contain exactly three parts separated by dots.
 * - The day (dd) must be between 1 and 31.
 * - The month (mm) must be between 1 and 12.
 * - The year (yyyy) must be between 1900 and 2100.
 * - The date must actually exist (e.g., 31.02.2024 is invalid).
 *
 * @param {string} str - A string representing a date in "dd.mm.yyyy" format.
 * @returns {boolean} true if the date is valid, false otherwise.
 */
function isValidDateString(str) {
	const parts = splitDateParts(str);
	if (!parts) return false;

	const { dd, mm, yyyy } = parts;

	if (
		dd < 1 || dd > 31 ||
		mm < 1 || mm > 12 ||
		yyyy < 1900 || yyyy > 2100
	) {
		return false;
	}

	const date = new Date(yyyy, mm - 1, dd);
	return (
		date.getFullYear() === yyyy &&
		date.getMonth() === mm - 1 &&
		date.getDate() === dd
	);
}

/**
 * Parses a date string into day, month, and year components.
 *
 * Supported formats:
 * - "dd.mm.yyyy"
 * - "yyyy-mm-dd"
 * - "dd/mm/yyyy"
 *
 * This function detects the delimiter used and extracts the parts accordingly.
 * It returns `null` if the format is unsupported, the parts are missing,
 * or if any part is not a valid number.
 *
 * @param {string} str - The input date string (e.g. "12.01.1998", "1992-01-21", "1/12/1994").
 * @returns {{dd: number, mm: number, yyyy: number} | null} An object with parsed numeric day, month, and year — or `null` if invalid.
 *
 * @example
 * splitDateParts('12.01.1998') // { dd: 12, mm: 1, yyyy: 1998 }
 * splitDateParts('1992-01-21') // { dd: 21, mm: 1, yyyy: 1992 }
 * splitDateParts('1/12/1994')  // { dd: 1, mm: 12, yyyy: 1994 }
 * splitDateParts('invalid')    // null
 */
function splitDateParts(str) {
	const trimmed = str.trim();
	let dd, mm, yyyy;

	let parts = [];
	if (trimmed.includes('.')) {
		parts = trimmed.split('.');
		if (parts.length !== 3) return null;
		[dd, mm, yyyy] = parts.map(Number);
	} else if (trimmed.includes('-')) {
		parts = trimmed.split('-');
		if (parts.length !== 3) return null;
		[yyyy, mm, dd] = parts.map(Number);
	} else if (trimmed.includes('/')) {
		parts = trimmed.split('/');
		if (parts.length !== 3) return null;
		[dd, mm, yyyy] = parts.map(Number);
	} else {
		return null;
	}

	if (
		isNaN(dd) || isNaN(mm) || isNaN(yyyy)
	) {
		return null;
	}

	return { dd, mm, yyyy };
}

/**
 * Parses a date string in "dd.mm.yyyy" format into a valid Date object.
 *
 * Requirements:
 * - The input must contain exactly 3 parts separated by dots.
 * - Day, month, and year must be valid numbers.
 * - The date must actually exist (e.g., 31.02.2024 is invalid).
 *
 * @param {string} str - The date string in "dd.mm.yyyy" format.
 * @returns {Date|null} A valid Date object if the input is correct, otherwise null.
 */
function parseDate(str) {
	const parts = splitDateParts(str);
	if (!parts) return null;

	const { dd, mm, yyyy } = parts;
	const date = new Date(yyyy, mm - 1, dd);

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
		const dp = datepicker(input, {
			formatter: (input, date, instance) => {
					input.value = date.toLocaleDateString()
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
			startDay: currentLang === 'uk' && 1
		})

		if (input.id === 'date-of-birth') {
			dateOfBirthPicker = dp;
		} else if (input.id === 'date-of-death') {
			dateOfDeathPicker = dp;
		}
	
		input.addEventListener('input', debounce(() => {
			const value = input.value.trim()

			if (isValidDateString(value)) {
				const [dd, mm, yyyy] = value.split('.').map(Number);
				const date = new Date(yyyy, mm - 1, dd);
				dp.setDate(date, { render: true });
			}

			validateDates();

		}, 500))
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
		mainPhotoNameEl	= document.querySelector( '.new-memory-main-info .filename' )
	let mainPhotoName

	if( ! popup || ! photo || ! img || ! saveBtn || ! mainPhotoNameEl ) return

	photo.addEventListener( 'change', () => {
		const
			fReader	= new FileReader(),
			file	= photo.files[0]

		mainPhotoName = file.name
		fReader.readAsDataURL( file )
		fReader.onload = e => {
			img.src = e.target.result
			popup.classList.remove( 'hidden' )
			setTargetElement( '#photo-popup' )
			disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
			cropper = new Cropper( img, {
				aspectRatio	: 302 / 389,
				movable		: false,
				rotatable	: false,
				scalable	: true,
				zoomable	: false,
				zoomOnTouch	: false
			} )
		}
	} )

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
	const fields = document.querySelectorAll( '.new-memory-main-info input:not([type="file"])' )

	if( ! fields.length ) return

	fields.forEach( field => {
		field.addEventListener( 'change', e => checkFieldValue( e ) )
		field.addEventListener( 'keyup', e => checkFieldValue( e ) )
		field.addEventListener( 'blur', e => checkFieldValue( e ) )
	} )

	const checkFieldValue = e => {
		const
			field	= e.target,
			value	= field.value,
			index	= field.name

		if( ! value ) field.classList.add( 'error' )
		else field.classList.remove( 'error' )

		stepData[index] = value
		localStorage.setItem( 'ih-step-1', JSON.stringify( stepData ) )
		isStepFilled( 1 )
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

		if (field.classList.contains('error') || (field.required && !value)) {
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
};