import Cropper from 'cropperjs'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import {
	checkAjaxWorkingStatus,
	setAjaxWorkingStatus,
	ihAjaxRequest,
	setTargetElement,
	getTargetElement
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
	// prevStepBtn.classList.remove( 'hidden' )
	// prevStepBtn.dataset.prev = `${ nextStepId - 1 }`
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

const nextStep = () => {
	nextStepBtn.addEventListener( 'click', () => {
		if( nextStepBtn.disabled ) return

		const nextStepId = nextStepBtn.dataset.next

		if( ! nextStepId ) return

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ nextStepId }` ).classList.add( 'active' )
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
							mainPhotoNameEl.innerHTML = mainPhotoName
							mainPhotoNameEl.closest( '.label' ).classList.add( 'added' )
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