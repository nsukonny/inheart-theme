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
		datepicker( input, {
			formatter: (input, date, instance) => {
				input.value = date.toLocaleDateString()
			},
			position: 'tl',
			startDate: input.id === 'date-of-birth' ?
				new Date(1930, 0, 1) : new Date(2014, 0, 1),
			overlayPlaceholder: currentLang === 'uk' && 'Рік (4 цифри)',
			overlayButton: currentLang === 'uk' && 'Підтвердити',
			customMonths: currentLang === 'uk' && ukrMonths,
			customOverlayMonths: currentLang === 'uk' && ukrMonths,
			customDays: currentLang === 'uk' && ukrDays,
			startDay: currentLang === 'uk' && 1
		} )
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
	const fields	= document.querySelectorAll( '.new-memory-main-info input' )
	let isFormValid	= true

	if( ! fields.length ) return false

	// Fill stepData again in the case localStorage was cleared.
	stepData.lang = document.querySelector( '.new-memory-lang.active' ).dataset.lang
	fields.forEach( field => {
		const
			index	= field.name,
			value	= field.value

		if( field.classList.contains( 'error' ) || ( field.required && ! value ) ){
			isFormValid = false
		}

		if( index === 'photo' && field.dataset.cropped )
			stepData.cropped = field.dataset.cropped

		stepData[index] = value
	} )
	localStorage.setItem( 'ih-step-1', JSON.stringify( stepData ) )

	return isFormValid
}