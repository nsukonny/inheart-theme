import Cropper from 'cropperjs'
import Sortable from 'sortablejs'
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import {
	checkAjaxWorkingStatus,
	setAjaxWorkingStatus,
	ihAjaxRequest,
	setTargetElement,
	getTargetElement,
	replaceUrlParam,
	BYTES_IN_MB,
	ajaxUrl
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
	removeSidebarAddedSection()
	removeContentSection()
	setActiveSectionContent()
	dragOrderSections()
	sectionsContentInput()

	// Step 3
	checkEpitaphContentLength()

	// Step 4
	uploadMediaPhotos()
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
		else prevStepBtn.setAttribute( 'data-prev', prevStepId - 1 )

		nextStepBtn.setAttribute( 'data-next', prevStepId + 1 )
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
		const
			target			= e.target,
			sectionsCount	= sectionsWrapper.querySelectorAll( '.sections-added-list .section' ).length

		if( target.closest( '.section-add' ) || ( target.className && target.classList.contains( '.section-add' ) ) ){
			const
				addedSectionsWrapper	= document.querySelector( '.sections-added-list' ),
				targetSection			= target.closest( '.section' ),
				imgUrl					= targetSection.dataset.thumb || null,
				clonedSection			= targetSection.cloneNode( true ),
				clonedSectionContent	= sectionsContent.querySelector( '.section-content' ).cloneNode( true ),
				clonedTextarea			= clonedSectionContent.querySelector( '.section-content-text' )

			// Replace in sidebar.
			targetSection.remove()
			addedSectionsWrapper.append( clonedSection )

			// Add new content.
			clonedSectionContent.querySelector( 'textarea' ).innerText = ''
			clonedSectionContent.querySelector( '.section-content-title' ).innerText = clonedSection.querySelector( '.section-label' ).innerText
			// Change SVG IDs and so on.
			clonedSectionContent.querySelector( '[id^="content-drag-"]' ).id = `content-drag-${ sectionsCount }`
			clonedSectionContent.querySelector( '[mask^="url(#content-drag-"]' ).setAttribute( 'mask', `url(#content-drag-${ sectionsCount })` )
			clonedSectionContent.querySelector( '[id^="content-remove-"]' ).id = `content-remove-${ sectionsCount }`
			clonedSectionContent.querySelector( '[mask^="url(#content-remove-"]' ).setAttribute( 'mask', `url(#content-remove-${ sectionsCount })` )
			clonedSectionContent.setAttribute( 'data-id', clonedSection.dataset.id )
			clonedTextarea.value = ''	// Clear value.
			sectionsContent.append( clonedSectionContent )	// Push new section into the DOM.

			// If this is a section with a custom title.
			if( imgUrl ){
				clonedSectionContent.classList.add( 'custom' )
				clonedSectionContent.querySelector( '.section-content-title' ).innerHTML = '<input class="section-content-title-input" placeholder="Придумайте заголовок" />'
				clonedSectionContent.insertAdjacentHTML( 'beforeend', `<img class="section-content-thumb" src="${ imgUrl }" alt="" />` )
			}

			setTimeout( () => clonedSectionContent.click(), 10 )	// Set it as active.
			sectionsContentInput()	// Add event listeners.
			disallowNextStep()	// New section added, it's empty, so next step is not allowed.
		}
	} )
}

/**
 * Remove section from sections list.
 * Step 2.
 */
const removeSidebarAddedSection = () => {
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
				sectionId		= targetSection.dataset.id,
				sectionsAdded	= document.querySelectorAll( '.sections-added-list .section' ),
				sectionContent	= sectionsContent.querySelector( `.section-content[data-id="${ sectionId }"]` )

			if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

			// Section content is not empty - double-check if User wants to delete it.
			if( sectionContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

			const clonedSection = targetSection.cloneNode( true )

			sectionsWrapper.append( clonedSection )
			targetSection.remove()

			sectionContent.remove()
			checkIfAllSectionsContentSet()
		}
	} )
}

/**
 * Remove section from content.
 * Step 2.
 */
const removeContentSection = () => {
	const
		sectionsWrapper	= document.querySelector( '.sections-sidebar' ),
		sectionsContent	= document.querySelector( '.sections-content' )

	if( ! sectionsWrapper || ! sectionsContent ) return

	sectionsContent.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.closest( '.section-remove' ) ||
			( target.className && target.classList.contains( '.section-remove' ) )
		){
			const
				sectionsWrapper	= document.querySelector( '.sections-list' ),
				targetContent	= target.closest( '.section-content' ),
				sectionId		= targetContent.dataset.id,
				targetSection	= document.querySelector( `.sections-added-list .section[data-id="${ sectionId }"]` ),
				sectionsAdded	= document.querySelectorAll( '.sections-added-list .section' )

			if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

			// Section content is not empty - double-check if User wants to delete it.
			if( targetContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

			const clonedSection = targetSection.cloneNode( true )

			sectionsWrapper.append( clonedSection )
			targetSection.remove()

			targetContent.remove()
			checkIfAllSectionsContentSet()
		}
	} )
}

/**
 * Set active section content.
 * Step 2.
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

			// Focus textarea if target is not input (like custom title).
			if( target.tagName !== 'INPUT' ) targetSection.querySelector( '.section-content-text' ).focus()
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

/**
 * Re-order sections with drag-and-drop events.
 * Step 2.
 */
const dragOrderSections = () => {
	const
		wrapper			= document.querySelector( '.sections-added-list' ),
		contentWrapper	= document.querySelector( '.sections-content' )

	Sortable.create( wrapper, {
		handle	: '.section-drag',
		onEnd	:  evt => {
			const
				item		= evt.item,
				itemId		= item.dataset.id,
				oldIndex	= evt.oldIndex,
				newIndex	= evt.newIndex,
				step		= oldIndex < newIndex ? 2 : 1,
				content		= contentWrapper.querySelector( `.section-content[data-id="${ itemId }"]` ),
				cloned		= content.cloneNode( true ),
				putBefore	= contentWrapper.querySelector( `.section-content:nth-child(${ newIndex + step })` )

			if( putBefore ) putBefore.parentNode.insertBefore( cloned, putBefore )
			else contentWrapper.append( cloned )

			content.remove()
		}
	} )
	Sortable.create( contentWrapper, {
		handle	: '.section-drag',
		onEnd	:  evt => {
			const
				item		= evt.item,
				itemId		= item.dataset.id,
				oldIndex	= evt.oldIndex,
				newIndex	= evt.newIndex,
				step		= oldIndex < newIndex ? 2 : 1,
				section		= wrapper.querySelector( `.section[data-id="${ itemId }"]` ),
				cloned		= section.cloneNode( true ),
				putBefore	= wrapper.querySelector( `.section:nth-child(${ newIndex + step })` )

			if( putBefore ) putBefore.parentNode.insertBefore( cloned, putBefore )
			else wrapper.append( cloned )

			section.remove()
		}
	} )
}

/**
 * Check if User could be allowed to go further - if all textareas are set.
 * Step 2.
 */
const sectionsContentInput = () => {
	const textareas	= document.querySelectorAll( '.section-content-text' )

	if( ! textareas.length ) return

	textareas.forEach( area => {
		const titleInput = area.closest( '.section-content' ).querySelector( '.section-content-title-input' )

		// Remove event listeners.
		area.removeEventListener( 'keyup', checkIfAllSectionsContentSet )
		area.removeEventListener( 'change', checkIfAllSectionsContentSet )
		area.removeEventListener( 'blur', checkIfAllSectionsContentSet )
		// Add event listeners.
		area.addEventListener( 'keyup', checkIfAllSectionsContentSet )
		area.addEventListener( 'change', checkIfAllSectionsContentSet )
		area.addEventListener( 'blur', checkIfAllSectionsContentSet )

		if( titleInput ){
			// Remove event listeners.
			titleInput.removeEventListener( 'keyup', checkIfAllSectionsContentSet )
			titleInput.removeEventListener( 'change', checkIfAllSectionsContentSet )
			titleInput.removeEventListener( 'blur', checkIfAllSectionsContentSet )
			titleInput.removeEventListener( 'keyup', duplicateValueToSidebarSection )
			titleInput.removeEventListener( 'change', duplicateValueToSidebarSection )
			// Add event listeners.
			titleInput.addEventListener( 'keyup', checkIfAllSectionsContentSet )
			titleInput.addEventListener( 'change', checkIfAllSectionsContentSet )
			titleInput.addEventListener( 'blur', checkIfAllSectionsContentSet )
			titleInput.addEventListener( 'keyup', duplicateValueToSidebarSection )
			titleInput.addEventListener( 'change', duplicateValueToSidebarSection )
		}
	} )
}

/**
 * Check if all textareas are set. This means we can go further.
 * Step 2.
 */
const checkIfAllSectionsContentSet = () => {
	const textareas	= document.querySelectorAll( '.section-content-text' )
	let allIsSet	= true

	if( ! textareas.length ) return

	textareas.forEach( area => {
		const titleInput = area.closest( '.section-content' ).querySelector( '.section-content-title-input' )

		// If textarea or title input is not set.
		if( ! area.value || ( titleInput && ! titleInput.value ) ) allIsSet = false
	} )

	if( allIsSet ){
		allowNextStep( 3 )
		applyProgress( 2 )
	}else{
		disallowNextStep()
	}
}

/**
 * Set custom section title in the sidebar the same as in the title input.
 * Step 2.
 *
 * @param {Event} e
 */
const duplicateValueToSidebarSection = e => {
	const
		target				= e.target,
		value				= target.value,
		id					= target.closest( '.section-content' ).dataset.id,
		sidebarSectionTitle	= document.querySelector( `.sections-added-list .section[data-id="${ id }"] .section-label` )

	sidebarSectionTitle.innerHTML = value || 'Свій заголовок'
}

/**
 * Listen to epitaph textarea changes.
 * Step 3.
 */
const checkEpitaphContentLength = () => {
	const textarea = document.querySelector( '.epitaph-text' )

	if( ! textarea ) return

	textarea.addEventListener( 'keyup', onEpitaphChange )
	textarea.addEventListener( 'change', onEpitaphChange )
	textarea.addEventListener( 'focus', onEpitaphChange )
}

/**
 * Check epitaph textarea length and compare with allowed length.
 * Step 3.
 *
 * @param {Event} e
 */
const onEpitaphChange = e => {
	const
		textarea		= e.target,
		value			= textarea.value,
		symbolsTyped	= document.querySelector( '.symbols-count-typed' ),
		symbolsAllowed	= parseInt( document.querySelector( '.symbols-count-allowed' ).innerText.trim() )

	if( ! symbolsTyped || ! symbolsAllowed ) return

	if( ! value ){
		disallowNextStep()
		applyProgress( 3, 0 )
		return
	}

	if( value.length > symbolsAllowed ) textarea.value = value.substring( 0, symbolsAllowed )

	symbolsTyped.innerHTML = textarea.value.length
	applyProgress( 3 )
	allowNextStep( 4 )
}

/**
 * Upload media photos.
 * Step 4.
 */
const uploadMediaPhotos = () => {
	const droparea = document.querySelector( '.droparea-photo' )
	let fileInstance

	['dragenter', 'dragover', 'dragleave', 'drop'].forEach( event => {
		document.addEventListener( event, evt => evt.preventDefault() )
	} );
	['dragenter', 'dragover'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.add( 'dragover' ) )
	} );
	['dragleave', 'drop'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.remove( 'dragover' ) )
	} )

	droparea.addEventListener( 'drop', e => {
		fileInstance = [...e.dataTransfer.files]

		if( ! fileInstance.length ) return

		fileInstance.forEach( file => {
			if( file.size > 5 * BYTES_IN_MB ){
				alert( 'Не вдалося завантажити фото' )
				return false
			}

			if( file.type.startsWith( 'image/' ) ){
				processingUploadMediaPhoto( file, droparea )
			} else {
				alert( 'Завантажте тільки зображення' )
				return false
			}
		} )
	} )
}

/**
 *
 * @param file
 * @param droparea
 */
const processingUploadMediaPhoto = ( file, droparea ) => {
	if( ! file || ! droparea ) return

	const
		loader			= droparea.querySelector( '.droparea-loader' ),
		percentsValue	= loader.querySelector( '.droparea-loader-percents span' ),
		progress		= loader.querySelector( 'progress' ),
		cancel			= loader.querySelector( '.droparea-loader-cancel' ),
		inner			= droparea.querySelector( '.droparea-inner' ),
		imagesWrapper	= droparea.querySelector( '.droparea-images' ),
		dropareaData	= new FormData(),
		xhr				= new XMLHttpRequest()

	dropareaData.append( 'file', file )
	dropareaData.append( 'action', 'ih_ajax_upload_memory_photo' )

	xhr.upload.addEventListener( 'progress', e => {
		const
			bytesLoaded = e.loaded,
			bytesTotal	= e.total,
			percent		= parseInt( ( bytesLoaded / bytesTotal ) * 100 )

		inner.classList.add( 'hidden' )
		loader.classList.remove( 'hidden' )
		percentsValue.innerHTML = percent
		progress.value 			= percent
	} )

	cancel.addEventListener( 'click', () => {
		xhr.abort()
		progress.value = 0
		setTimeout( () => {
			inner.classList.remove( 'hidden' )
			loader.classList.add( 'hidden' )
		}, 500 )
	} )

	xhr.open( 'POST', ajaxUrl, true )
	xhr.send( dropareaData )

	xhr.onload = () => {
		loader.classList.add( 'hidden' )

		if( xhr.status == 200 ){
			const
				response	= JSON.parse( xhr.response ),
				data		= response.data,
				maskId		= `mask0_${ Math.random() * 10000 }_${ Math.random() * 10000 }`
			let imageHTML	= ''

			if( data.success == 1 ){
				imagesWrapper.classList.remove( 'hidden' )
				imageHTML = `<div class="droparea-img-loaded">
					<img src="${ data.url }" alt="${ file.name }" />
					<div class="droparea-img-delete flex align-center justify-center" data-id="${ data.attach_id }">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="${ maskId }" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
							</mask>
							<g mask="url(#${ maskId })">
								<rect width="24" height="24" fill="currentColor"/>
							</g>
						</svg>
					</div>
				</div>`

				const cancelCBb = e => {
					e.stopPropagation()
					hideAreYouSurePopup()
				}
				const applyCBb = e => {
					if( checkAjaxWorkingStatus() ) return

					setAjaxWorkingStatus( true )

					const
						id			= e.target.closest( '.droparea-img-delete' ).dataset.id,
						formData	= new FormData()

					formData.append( 'action', 'ih_ajax_delete_memory_photo' )
					formData.append( 'id', id )

					ihAjaxRequest( formData ).then( res => {
						if( res ){
							switch( res.success ){
								case true:
									e.target.closest( '.droparea-img-loaded' ).remove()

									// If there are no more images loaded.
									if( ! imagesWrapper.querySelectorAll( '.droparea-img-loaded' ).length ){
										imagesWrapper.classList.add( 'hidden' )
										inner.classList.remove( 'hidden' )
									}

									break

								case false:
									console.error( res.data.msg )
									break
							}
						}

						setAjaxWorkingStatus( false )
						hideAreYouSurePopup()
					} )
				}

				imagesWrapper.insertAdjacentHTML( 'beforeend', imageHTML )
				imagesWrapper.querySelector( `.droparea-img-delete[data-id="${ data.attach_id }"]` )
					.addEventListener( 'click', e => showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e ) ) )
			}
		}else{
			// If no images loaded yet.
			if( ! document.querySelectorAll( '.droparea-img-loaded' ).length ) inner.classList.remove( 'hidden' )
			else imagesWrapper.classList.remove( 'hidden' )

			console.error( `Файл не загружен. Ошибка ${ xhr.status } при загрузке файла.` )
		}
	}
}

/**
 * Append popup.
 * Step 4.
 *
 * @param {HTMLObjectElement}	container		Where to add popup.
 * @param {function}			cancelCallback	Fires on popup cancel button click.
 * @param {function}			applyCallback	Fires on popup apply button click.
 */
const showAreYouSurePopup = ( container, cancelCallback, applyCallback ) => {
	if( document.querySelector( '.popup-sure' ) ) return

	const popup = `<div class="popup-sure">
		<div class="popup-sure-text">Дійсно видалити фото?</div>
		<div class="popup-sure-buttons flex flex-wrap">
			<button class="popup-sure-cancel" type="button">Залишити</button>
			<button class="popup-sure-apply" type="button">Видалити</button>
		</div>
	</div>`

	container.insertAdjacentHTML( 'beforeend', popup )
	document.querySelector( '.popup-sure-cancel' ).addEventListener( 'click', cancelCallback )
	document.querySelector( '.popup-sure-apply' ).addEventListener( 'click', applyCallback )
}

/**
 * Remove popup.
 * Step 4.
 */
const hideAreYouSurePopup = () => {
	const popup = document.querySelector( '.popup-sure' )

	if( popup ) popup.remove()
}