import Sortable from 'sortablejs'
import { isStepFilled, saveStep } from './common'
import {
	addLoader,
	BYTES_IN_MB,
	checkAjaxWorkingStatus,
	ihAjaxRequest, removeLoader,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'

const
	stepData = localStorage.getItem( 'ih-step-2' ) ?
		JSON.parse( localStorage.getItem( 'ih-step-2' ) ) : {}

let sectionsWrapper,
	militarySectionsWrapper,
	sectionsContent

/**
 * Add section to added sections list.
 */
export const addSection = () => {
	sectionsWrapper			= document.querySelector( '.sections-sidebar' )
	militarySectionsWrapper	= document.querySelector( '.sections-military' )
	sectionsContent			= document.querySelector( '.sections-content' )

	if( ! sectionsWrapper || ! sectionsContent ) return

	sectionsWrapper.addEventListener( 'click', e => {
		const target = e.target

		if( target.closest( '.section-add' ) || ( target.className && target.classList.contains( 'section-add' ) ) ){
			const
				addedSectionsWrapper	= document.querySelector( '.sections-added-list' ),
				targetSection			= target.closest( '.section' ),
				imgUrl					= targetSection.dataset.thumb || null,
				clonedSection			= targetSection.cloneNode( true ),
				clonedSectionContent	= sectionsContent.querySelector( '.section-content' ).cloneNode( true ),
				clonedTextarea			= clonedSectionContent.querySelector( '.section-content-text' ),
				randomId				= Math.random() * 9999 + '_' + Math.random() * 9999

			// If a military section is clicked, but theme is not military - exit.
			if(
				targetSection.classList.contains( 'section-military' ) &&
				! document.body.classList.contains( 'memory-page-theme-military' )
			) return

			// Replace in sidebar.
			targetSection.remove()
			addedSectionsWrapper.append( clonedSection )

			// Add new content.
			clonedSectionContent.querySelector( 'textarea' ).innerText = ''
			clonedSectionContent.querySelector( '.section-content-title' ).innerText = clonedSection.querySelector( '.section-label' ).innerText
			// Change SVG IDs and so on.
			clonedSectionContent.querySelector( '[id^="content-drag-"]' ).id = `content-drag-${ randomId }`
			clonedSectionContent.querySelector( '[mask^="url(#content-drag-"]' ).setAttribute( 'mask', `url(#content-drag-${ randomId })` )
			clonedSectionContent.querySelector( '[id^="content-remove-"]' ).id = `content-remove-${ randomId }`
			clonedSectionContent.querySelector( '[mask^="url(#content-remove-"]' ).setAttribute( 'mask', `url(#content-remove-${ randomId })` )
			clonedSectionContent.setAttribute( 'data-id', clonedSection.dataset.id )
			clonedTextarea.value = ''	// Clear value.

			// Delete photos in cloned section if they are exist.
			if( clonedSectionContent.querySelector( '.section-content-photos' ) )
				clonedSectionContent.querySelector( '.section-content-photos' ).innerHTML = ''

			sectionsContent.append( clonedSectionContent )	// Push new section into the DOM.
			window.scrollTo( { top: sectionsContent.getBoundingClientRect().top + window.scrollY } )

			// If this is a section with a custom title.
			if( imgUrl ){
				clonedSectionContent.classList.add( 'custom' )
				clonedSectionContent.querySelector( '.section-content-title' ).innerHTML = '<input class="section-content-title-input" placeholder="Придумайте заголовок" />'
				clonedSectionContent.insertAdjacentHTML( 'beforeend', `<img class="section-content-thumb" src="${ imgUrl }" alt="" />` )
			}

			setTimeout( () => clonedSectionContent.click(), 10 )	// Set it as active.
			sectionsContentInput()	// Add event listeners.
			isStepFilled( 2 )
		}
	} )

	uploadSectionPhoto()
	deletePhoto()
}

/**
 * Remove section from sections list.
 */
export const removeSidebarAddedSection = () => {
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
				sectionContent	= sectionsContent.querySelector( `.section-content[data-id="${ sectionId }"]` ),
				isMilitary		= targetSection.classList.contains( 'section-military' )

			if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

			// Section content is not empty - double-check if User wants to delete it.
			if( sectionContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

			const clonedSection = targetSection.cloneNode( true )

			clonedSection.querySelector( '.section-label' ).innerText = clonedSection.dataset.title

			if( isMilitary && militarySectionsWrapper ) militarySectionsWrapper.append( clonedSection )
			else sectionsWrapper.append( clonedSection )

			targetSection.remove()
			sectionContent.remove()

			for( let key in stepData ){
				if( key == sectionId ){
					let photos = stepData[key].photos

					if( photos ) photos.forEach( photo => deleteContentPhoto( photo ) )

					delete stepData[key]
				}
			}

			localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
			isStepFilled( 2 )
			saveStep( 2 )
		}
	} )
}

/**
 * Remove section from content.
 */
export const removeContentSection = () => {
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
				sectionsAdded	= document.querySelectorAll( '.sections-added-list .section' ),
				isMilitary		= targetSection.classList.contains( 'section-military' )

			if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

			// Section content is not empty - double-check if User wants to delete it.
			if( targetContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

			const clonedSection = targetSection.cloneNode( true )

			if( isMilitary && militarySectionsWrapper ) militarySectionsWrapper.append( clonedSection )
			else sectionsWrapper.append( clonedSection )

			targetSection.remove()
			targetContent.remove()

			for( let key in stepData ){
				if( key == sectionId ){
					let photos = stepData[key].photos

					if( photos ) photos.forEach( photo => deleteContentPhoto( photo ) )

					delete stepData[key]
				}
			}

			localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
			isStepFilled( 2 )
			saveStep( 2 )
		}
	} )
}

/**
 * Set active section content.
 */
export const setActiveSectionContent = () => {
	if( ! sectionsContent ) return

	sectionsContent.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.closest( '.section-content' ) ||
			( target.className && target.classList.contains( 'section-content' ) )
		){
			if(
				target.className &&
				( target.classList.contains( 'section-content-photo-delete' ) || target.closest( '.popup-confirm' ) ||
				target.classList.contains( 'section-content-form-add' ) || target.closest( '.section-content-form-add' ) )
			) return

			const
				targetSection	= target.closest( '.section-content' ),
				activeSection	= sectionsContent.querySelector( '.section-content.active' )

			if( activeSection ) activeSection.classList.remove( 'active' )

			if( targetSection ) targetSection.classList.add( 'active' )

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
 */
export const dragOrderSections = () => {
	const wrapper = document.querySelector( '.sections-added-list' )

	Sortable.create( wrapper, {
		handle	: '.section-drag',
		onEnd	:  evt => {
			const
				item		= evt.item,
				itemId		= item.dataset.id,
				oldIndex	= evt.oldIndex,
				newIndex	= evt.newIndex,
				step		= oldIndex < newIndex ? 2 : 1,
				content		= sectionsContent.querySelector( `.section-content[data-id="${ itemId }"]` ),
				cloned		= content.cloneNode( true ),
				putBefore	= sectionsContent.querySelector( `.section-content:nth-child(${ newIndex + step })` )

			if( putBefore ) putBefore.parentNode.insertBefore( cloned, putBefore )
			else sectionsContent.append( cloned )

			content.remove()
			checkSectionsIndexes()
		}
	} )
	Sortable.create( sectionsContent, {
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
			checkSectionsIndexes()
		}
	} )
}

const checkSectionsIndexes = () => {
	const sections = document.querySelectorAll( '.section-content' )

	if( ! sections.length ) return

	sections.forEach( ( section, i ) => stepData[section.dataset.id].position = i )
	localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
}

/**
 * Check if User could be allowed to go further - if all textareas are set.
 */
export const sectionsContentInput = () => {
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
 * If all textareas are set - can go further.
 */
const checkIfAllSectionsContentSet = () => isStepFilled( 2 )

/**
 * Check if step 2 is ready.
 *
 * @returns {boolean}
 */
export const checkStep2 = () => {
	const textareas	= document.querySelectorAll( '.section-content-text' )
	let allIsSet	= true

	if( ! textareas.length ) return

	textareas.forEach( ( area, i ) => {
		const
			section		= area.closest( '.section-content' ),
			title		= section.querySelector( '.section-content-title' ),
			titleInput	= title.querySelector( '.section-content-title-input' ),
			value		= area.value,
			index		= section.dataset.id,
			isCustom	= section.classList.contains( 'custom' ) ? 1 : '',
			photos		= section.querySelectorAll( '.section-content-photo' )

		if( titleInput ) stepData[index] = { ...stepData[index], title: titleInput.value }
		else stepData[index] = { ...stepData[index], title: title.innerText }

		// If textarea or title input is not set.
		if( ! value || ( titleInput && ! titleInput.value ) ) allIsSet = false

		stepData[index].text 		= value
		stepData[index].position 	= i
		stepData[index].custom 		= isCustom
		stepData[index].photos 		= []

		if( photos.length ) photos.forEach( photo => stepData[index].photos.push( photo.dataset.id ) )

		localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
	} )

	return allIsSet
}

/**
 * Set custom section title in the sidebar the same as in the title input.
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
 * Delete photo from a section content.
 */
const deletePhoto = () => {
	if( ! sectionsContent ) return

	const popupConfirm = sectionsContent.querySelector( '.popup-confirm.delete' )

	sectionsContent.addEventListener( 'click', e => {
		e.stopPropagation()

		const
			target	= e.target,
			clientX	= e.clientX < 200 ? 200 : e.clientX,
			clientY	= e.clientY

		if( ! ( target.className && target.classList.contains( 'section-content-photo-delete' ) ) || ! popupConfirm ) return

		const
			id					= target.dataset.id,
			popupConfirmClone	= popupConfirm.cloneNode( true )

		// If popup already exists - exit.
		if( target.classList.contains( 'active' ) ) return

		target.classList.add( 'active' )
		target.appendChild( popupConfirmClone )
		popupConfirmClone.style.left = `${ clientX }px`
		popupConfirmClone.style.top = `${ clientY }px`
		popupConfirmClone.classList.remove( 'hidden' )
		document.body.classList.add( 'overflow-hidden' )

		popupConfirmClone.querySelector( '.popup-confirm-no' ).addEventListener( 'click', () => {
			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
			target.classList.remove( 'active' )
		} )
		popupConfirmClone.querySelector( '.popup-confirm-yes' ).addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			deleteContentPhoto( id, target )
			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
		} )
	} )
}

/**
 * Delete section's photo by ID.
 *
 * @param {Number} id					Photo ID.
 * @param {HTMLObjectElement} target	Image wrapper element.
 */
const deleteContentPhoto = ( id, target = undefined ) => {
	const formData = new FormData()

	formData.append( 'action', 'ih_ajax_delete_memory_photo' )
	formData.append( 'id', id )

	if( target ) addLoader( target.closest( '.section-content-photo' ) )

	ihAjaxRequest( formData ).then( res => {
		if( target ) removeLoader( target.closest( '.section-content-photo' ) )

		if( res ){
			switch( res.success ){
				case true:
					if( target ) target.closest( '.section-content-photo' ).remove()

					for( let key of Object.keys( stepData ) ){
						if( stepData[key].photos ){
							for( let i = 0; i < stepData[key].photos.length; i++ ){
								if( stepData[key].photos[i] == id )
									stepData[key].photos.splice( i, 1 )
							}
						}
					}

					localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
					saveStep( 2 )
					break

				case false:
					showNotification( res.data.msg, 'error' )
					break
			}
		}

		setAjaxWorkingStatus( false )
	} )
}

/**
 * Upload section photo.
 */
const uploadSectionPhoto = () => {
	if( ! sectionsContent ) return

	let fileInstance

	const handlePhotosUpload = e => {
		fileInstance = [...e.target.files]

		if( ! fileInstance.length ) return

		if( fileInstance[0].size > 50 * BYTES_IN_MB ){
			showNotification( `Не вдалося завантажити фото ${ fileInstance[0].name }`, 'error' )
			return false
		}

		const
			sectionContent		= e.target.closest( '.section-content' ),
			sectionContentId	= sectionContent.dataset.id

		const processingUploadMediaPhoto = ( file, area ) => {
			if( ! file || ! area ) return

			const formData = new FormData()

			formData.append( 'file', file )
			formData.append( 'action', 'ih_ajax_upload_section_photo' )

			addLoader( sectionContent )

			ihAjaxRequest( formData ).then( res => {
				removeLoader( sectionContent )

				if( res ){
					switch( res.success ){
						case true:
							showNotification( res.data.msg )
							area.insertAdjacentHTML( 'beforeend', res.data.image )

							const attachId = res.data.attach_id

							// Search section by its ID and add attached photo ID to its photos array.
							for( let key of Object.keys( stepData ) ){
								if( key == sectionContentId ) stepData[key].photos.push( attachId )
							}

							localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
							saveStep( 2 )
							break

						case false:
							showNotification( res.data.msg, 'error' )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		}

		if( fileInstance[0].type.startsWith( 'image/' ) )
			processingUploadMediaPhoto( fileInstance[0], sectionContent.querySelector( '.section-content-photos' ) )
		else
			showNotification( `Тільки зображення - файл ${ fileInstance[0].name } не є зображенням`, 'warning' )
	}

	sectionsContent.addEventListener( 'click', e => {
		e.stopPropagation()

		const target = e.target

		if(
			! target.className ||
			! ( target.classList.contains( 'section-content-form-add' ) || target.closest( '.section-content-form-add' ) )
		) return

		const input = target.closest( '.section-content-form' ).querySelector( 'input.section-add-photo' )

		if( ! input ) return

		input.removeEventListener( 'change', handlePhotosUpload )
		input.addEventListener( 'change', handlePhotosUpload )
	} )
}