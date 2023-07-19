import Cropper from 'cropperjs'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import {
	checkAjaxWorkingStatus,
	setAjaxWorkingStatus,
	ihAjaxRequest,
	setTargetElement,
	getTargetElement,
	replaceUrlParam
} from '../common/global'

let footer,
	progressBar,
	prevStepBtn,
	nextStepBtn,
	cropper

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	// Exit if something is missing.
	if( ! defineGlobalStepsItems() ) return

	// Step 0.
	selectTheme()
	nextStep()

	// Step 1.
	selectLanguage()
	uploadMainPhoto()
	addMainFormValidation()
	prevStep()

	// Step 2.
	addSection()
	removeSection()
	setActiveSectionContent()
} )

/**
 * Define all global variables required for all steps.
 *
 * @returns {boolean}
 */
const defineGlobalStepsItems = () => {
	footer 		= document.querySelector( '.new-memory-footer' )
	progressBar	= document.querySelector( '.new-memory-progress-bar' )
	prevStepBtn	= document.querySelector( '.new-memory-prev-step' )
	nextStepBtn	= document.querySelector( '.new-memory-next-step' )

	if( nextStepBtn ) nextStepBtn.disabled = true

	return ! ( ! footer || ! progressBar || ! prevStepBtn || ! nextStepBtn )
}

/**
 * Step 0.
 * Theme selection.
 */
const selectTheme = () => {
	const themes = document.querySelectorAll( '.new-memory-theme' )

	if( ! themes.length ) return

	themes.forEach( theme => {
		theme.addEventListener( 'click', () => {
			const alreadySelectedTheme = document.querySelector( '.new-memory-theme.active' )

			if( alreadySelectedTheme ) alreadySelectedTheme.classList.remove( 'active' )

			theme.classList.add( 'active' )
			allowNextStep()
		} )
	} )
}

/**
 * Allow to go to the next step (enable Next button).
 *
 * @param {number} nextStepId	ID of the next step.
 */
const allowNextStep = ( nextStepId = 1 ) => {
	nextStepBtn.removeAttribute( 'disabled' )
	nextStepBtn.dataset.next = `${ nextStepId }`
}

const disallowNextStep = () => {
	nextStepBtn.setAttribute( 'disabled', 'true' )
	nextStepBtn.dataset.next = ''
}

/**
 * Fill one specific part of the progress bar with percentage.
 *
 * @param {number} partId		Which part of progress bar is active
 * @param {number} percentage	Percents to fill
 */
const applyProgress = ( partId = 1, percentage = 100 ) => {
	const part = progressBar.querySelector( `[data-part="${ partId }"]` )

	if( ! part ) return

	part.querySelector( '.new-memory-progress-inner' ).style.width = `${ percentage }%`
}

/**
 * Go to the next step.
 */
const nextStep = () => {
	nextStepBtn.addEventListener( 'click', () => {
		if( nextStepBtn.disabled ) return

		const nextStepId = parseInt( nextStepBtn.dataset.next )

		if( ! nextStepId ) return

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ nextStepId }` ).classList.add( 'active' )
		replaceUrlParam( 'step', nextStepId )
		prevStepBtn.classList.remove( 'hidden' )
		prevStepBtn.setAttribute( 'data-prev', nextStepId - 1 )
		disallowNextStep()
	} )
}

/**
 * Go to the previous step.
 */
const prevStep = () => {
	prevStepBtn.addEventListener( 'click', () => {
		if( prevStepBtn.classList.contains( 'hidden' ) ) return

		const prevStepId = parseInt( prevStepBtn.dataset.prev )

		if( ! prevStepId && prevStepId != '0' ) return

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ prevStepId }` ).classList.add( 'active' )
		replaceUrlParam( 'step', prevStepId )
		allowNextStep( prevStepId + 1 )
		applyProgress( prevStepId, 0 )

		if( prevStepId == 0 ) prevStepBtn.classList.add( 'hidden' )
	} )
}

/**
 * Select memory language.
 */
const selectLanguage = () => {
	const langs = document.querySelectorAll( '.new-memory-lang' )

	if( ! langs.length ) return

	langs.forEach( lang => {
		lang.addEventListener( 'click', () => {
			document.querySelector( '.new-memory-lang.active' ).classList.remove( 'active' )
			lang.classList.add( 'active' )
		} )
	} )
}

/**
 * Upload main photo in Main info form, Step 1.
 */
const uploadMainPhoto = () => {
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
			fReader		= new FileReader(),
			file		= photo.files[0]

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
				scalable	: false,
				zoomable	: false,
				zoomOnTouch	: false
			} )
		}
	} )

	// Close popup.
	popup.addEventListener( 'click', e => {
		const target = e.target

		if( target.className && target.classList.contains( 'popup' ) ){
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
							mainPhotoNameEl.innerHTML = cutFilename( mainPhotoName )
							mainPhotoNameEl.closest( '.label' ).classList.add( 'added' )
							isMainFormValid()
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
 * Return shorter filename.
 *
 * @param {string} filename
 * @returns {string}
 */
const cutFilename = filename => {
	const
		extension	= /(?:\.([^.]+))?$/.exec( filename )[1],
		firstPart	= filename.substring( 0, filename.length - extension.length - 2 )

	return ( filename.length - extension.length > 17 ) ? firstPart.substring( 0, 10 ) + '...' + extension : filename
}

/**
 * Validate Step 1 Main info form.
 */
const addMainFormValidation = () => {
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
			value	= field.value

		if( ! value ) field.classList.add( 'error' )
		else field.classList.remove( 'error' )

		isMainFormValid()
	}
}

/**
 * Check if Step 1 Main info form is valid.
 *
 * @returns {boolean}
 */
const isMainFormValid = () => {
	const fields = document.querySelectorAll( '.new-memory-main-info input' )

	if( ! fields.length ) return false

	let isFormValid = true

	fields.forEach( field => {
		if( field.classList.contains( 'error' ) || ! field.value ) isFormValid = false
	} )

	if( isFormValid ){
		allowNextStep( 2 )
		applyProgress()
	}else{
		disallowNextStep()
	}

	return isFormValid
}

/**
 * Add section to added sections list.
 * Step 2.
 */
const addSection = () => {
	const
		sectionsWrapper	= document.querySelector( '.sections-sidebar' ),
		sectionsContent	= document.querySelector( '.sections-content' )

	if( ! sectionsWrapper || ! sectionsContent ) return

	sectionsWrapper.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.closest( '.section-add' ) ||
			( target.className && target.classList.contains( '.section-add' ) )
		){
			const
				addedSectionsWrapper	= document.querySelector( '.sections-added-list' ),
				targetSection			= target.closest( '.section' )

			if( ! addedSectionsWrapper ) return

			const
				clonedSection			= targetSection.cloneNode( true ),
				clonedSectionContent	= sectionsContent.querySelector( '.section-content' ).cloneNode( true )

			// Replace in sidebar.
			targetSection.remove()
			addedSectionsWrapper.append( clonedSection )

			// Add new content.
			clonedSectionContent.querySelector( 'textarea' ).innerText = ''
			clonedSectionContent.querySelector( '.section-content-title' ).innerText = clonedSection.querySelector( '.section-label' ).innerText
			clonedSectionContent.setAttribute( 'data-id', clonedSection.dataset.id )
			sectionsContent.append( clonedSectionContent )
		}
	} )
}

/**
 * Remove section from sections list.
 * Step 2.
 */
const removeSection = () => {
	const
		sectionsWrapper	= document.querySelector( '.sections-sidebar' ),
		sectionsContent	= document.querySelector( '.sections-content' )

	if( ! sectionsWrapper || ! sectionsContent ) return

	sectionsWrapper.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.closest( '.section-remove' ) ||
			( target.className && target.classList.contains( '.section-remove' ) )
		){
			const
				sectionsWrapper	= document.querySelector( '.sections-list' ),
				targetSection	= target.closest( '.section' ),
				sectionId		= targetSection.dataset.id

			if( ! sectionsWrapper ) return

			const clonedSection = targetSection.cloneNode( true )

			sectionsWrapper.append( clonedSection )
			targetSection.remove()

			sectionsContent.querySelector( `.section-content[data-id="${ sectionId }"]` ).remove()
		}
	} )
}

/**
 * Set active section content.
 */
const setActiveSectionContent = () => {
	const sectionsContent = document.querySelector( '.sections-content' )

	if( ! sectionsContent ) return

	sectionsContent.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.closest( '.section-content' ) ||
			( target.className && target.classList.contains( '.section-content' ) )
		){
			const
				targetSection	= target.closest( '.section-content' ),
				activeSection	= sectionsContent.querySelector( '.section-content.active' )

			if( activeSection ) activeSection.classList.remove( 'active' )

			targetSection.classList.add( 'active' )
		}
	} )

	// Click outside - set section inactive.
	document.addEventListener( 'click', e => {
		const
			target			= e.target,
			activeSection	= sectionsContent.querySelector( '.section-content.active' )

		if( ! activeSection ) return

		if(
			! target.closest( '.section-content' ) &&
			( target.className && ! target.classList.contains( '.section-content' ) )
		) activeSection.classList.remove( 'active' )
	} )
}