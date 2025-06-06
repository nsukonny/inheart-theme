import Sortable from 'sortablejs'
import { isStepFilled } from './common'
import {
	BYTES_IN_MB,
	addLoader,
	removeLoader,
	checkAjaxWorkingStatus,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification, disableInput, enableInput, customDebounce
} from '../common/global'

const stepData = {}
let cities

let sectionsWrapper,
	sectionsListWrapper,
	militarySectionsWrapper,
	sectionsContent,
	originalContentSection

const loadCities = () => {
	const cityInput	= document.querySelector( '#city' )

	if( ! cityInput ) return

	const citiesWrap = cityInput.closest( 'label' ).querySelector( '.np-cities' )

	const onCityInputChange = e => {
		
		const
			formData = new FormData(),
			input    = e.target,
			val      = input.value.trim(),
			label    = input.closest( 'label' )
		
		if (!val || val.length < 3 || !citiesWrap) return;
		formData.append( 'action', 'ih_ajax_load_cities_from_local_json' )
		formData.append( 'city', val )
		disableInput( input )
		addLoader( label )
		citiesWrap.classList.add( 'hidden' )
		ihAjaxRequest( formData ).then( res => {
			enableInput( input )
			removeLoader( label )
			input.focus()

			if( res ){
				switch( res.success ){
					case true:
						cities = Object.values( res.data.cities )
						citiesWrap.innerHTML = ''

						if( cities.length ){
							cities.forEach( ( city, index ) => {
								citiesWrap.insertAdjacentHTML(
									'beforeend',
									`<span class="np-city" data-index="${ index }">${ city.name }</span>`
								)
							} )
							citiesWrap.classList.remove( 'hidden' )
						}
						break

					case false:
						console.error( res.data.msg )
						break
				}
			}
		} ).catch( err => {
			enableInput( input )
			removeLoader( label )
			console.error( err.message )
		} )
	}

	cityInput.addEventListener( 'input', customDebounce( onCityInputChange ) )
	cityInput.addEventListener( 'focus', () => citiesWrap.classList.remove( 'hidden' ) )

	citiesWrap.addEventListener( 'click', e => {
		e.stopPropagation()

		const target = e.target

		if( target.className && target.classList.contains( 'np-city' ) ){
			cityInput.value = target.innerText
			setTimeout( () => {
				cityInput.blur()
				citiesWrap.classList.add( 'hidden' )
			}, 10 )
		}
	} )

	document.addEventListener( 'click', e => {
		if(
			! citiesWrap.classList.contains( 'hidden' ) &&
			e.target.id !== 'city'
		) citiesWrap.classList.add( 'hidden' )
	} )
}

/**
 * Add section to added sections list.
 */
export const addSection = () => {
	sectionsWrapper			= document.querySelector( '.sections-sidebar' )
	sectionsListWrapper		= sectionsWrapper.querySelector( '.sections-list' )
	militarySectionsWrapper	= document.querySelector( '.sections-military' )
	sectionsContent			= document.querySelector( '.sections-content' )
	originalContentSection	= document.querySelector( '.section-content.original' )

	if( ! sectionsWrapper || ! sectionsContent ) return

	sectionsWrapper.addEventListener( 'click', e => {
		const target = e.target

		if(
			! target.closest( '.section-add' ) &&
			! ( target.className && target.classList.contains( 'section-add' ) )
		) return;

		const
			addedSectionsWrapper	= document.querySelector( '.sections-added-list' ),
			targetSection			= target.closest( '.section' ),
			sectionId				= targetSection.id || null,
			isCustom				= targetSection.classList.contains( 'custom' ),
			clonedSection			= targetSection.cloneNode( true ),
			clonedSectionContent	= originalContentSection.cloneNode( true ),
			clonedTextarea			= clonedSectionContent.querySelector( '.section-content-text' ),
			randomId				= Math.random() * 9999 + '_' + Math.random() * 9999

		// If a military section is clicked, but theme is not military - exit.
		if(
			targetSection.classList.contains( 'section-military' ) &&
			! document.body.classList.contains( 'memory-page-theme-military' )
		) return

		// Add specific classname to military section content - need it on step data save.
		if( targetSection.classList.contains( 'section-military' ) )
			clonedSectionContent.classList.add( `section-content-${ sectionId }` )

		// Replace in sidebar, if this is a not a custom title section.
		if( ! isCustom ) targetSection.remove()

		addedSectionsWrapper.append( clonedSection )

		// Add new content.
		clonedSectionContent.className = 'section-content'
		clonedSectionContent.querySelector( 'textarea' ).innerText = ''
		clonedSectionContent.querySelector( '.section-content-title' ).innerText = clonedSection.querySelector( '.section-label' ).innerText

		// Change SVG IDs and so on.
		if( clonedSectionContent.querySelector( '[id^="content-drag-"]' ) )
			clonedSectionContent.querySelector( '[id^="content-drag-"]' ).id = `content-drag-${ randomId }`

		if( clonedSectionContent.querySelector( '[mask^="url(#content-drag-"]' ) )
			clonedSectionContent.querySelector( '[mask^="url(#content-drag-"]' ).setAttribute( 'mask', `url(#content-drag-${ randomId })` )

		if( clonedSectionContent.querySelector( '[id^="content-remove-"]' ) )
			clonedSectionContent.querySelector( '[id^="content-remove-"]' ).id = `content-remove-${ randomId }`

		if( clonedSectionContent.querySelector( '[mask^="url(#content-remove-"]' ) )
			clonedSectionContent.querySelector( '[mask^="url(#content-remove-"]' ).setAttribute( 'mask', `url(#content-remove-${ randomId })` )

		clonedSectionContent.setAttribute( 'data-id', clonedSection.dataset.id )
		clonedTextarea.value = ''	// Clear value.

		// Delete photos in cloned section if they are exist.
		if( clonedSectionContent.querySelector( '.section-content-photos' ) )
			clonedSectionContent.querySelector( '.section-content-photos' ).innerHTML = ''

		if( sectionId === 'last-fight' ) addLastFightSection( clonedSection, clonedSectionContent )

		sectionsContent.append( clonedSectionContent )	// Push new section into the DOM.
		window.scrollTo( { top: sectionsContent.getBoundingClientRect().top + window.scrollY } )

		if( sectionId === 'last-fight' ) loadCities( false, 1 )

		// If this is a section with a custom title.
		if( isCustom ){
			const customTitleSectionId = sectionsWrapper.querySelectorAll( '.section' ).length
			let customSectionsCounter = 0

			clonedSectionContent.classList.add( 'custom' )
			clonedSectionContent.querySelector( '.section-content-title' ).innerHTML = '<input class="section-content-title-input" placeholder="Вигадайте заголовок" />'
			addedSectionsWrapper.querySelectorAll( '.section' ).forEach( section => {
				if( section.classList.contains( 'custom' ) ) customSectionsCounter++
			} )

			// Set unique data-id attr.
			clonedSection.setAttribute( 'data-id', customTitleSectionId )
			clonedSectionContent.setAttribute( 'data-id', customTitleSectionId )
			// Remove add icon, not need it.
			clonedSection.querySelector( '.section-add' ).remove()
		}

		setTimeout( () => clonedSectionContent.click(), 10 )	// Set it as active.
		sectionsContentInput()	// Add event listeners.
		isStepFilled( 2 )
	} )

	// For the case if Last Fight section is already added.
	addLastFightSection( sectionsWrapper.querySelector( '#last-fight' ), sectionsContent.querySelector( '.section-content-last-fight' ) )
	loadCities( false, 1 )

	uploadSectionPhoto()
	deletePhoto()
}

/**
 * Last Fight section is a specific last military section.
 * It has no photos, but has location field which is using Nova Poshta API.
 *
 * @param sidebarSection
 * @param contentSection
 */
const addLastFightSection = ( sidebarSection, contentSection ) => {
	if( ! sidebarSection || ! contentSection ) return

	// Don't need drag'n'drop for this section.
	if( sidebarSection.querySelector( '.section-drag' ) ) sidebarSection.querySelector( '.section-drag' ).remove()
	if( contentSection.querySelector( '.section-drag' ) ) contentSection.querySelector( '.section-drag' ).remove()

	const
		lastFightSection	= document.querySelector( '.section-content-last-fight.hidden' ),
		lastFightFormOrigin	= lastFightSection.querySelector( '.section-content-form-np' ),
		lastFightForm		= lastFightFormOrigin.cloneNode( true ),
		form				= contentSection.querySelector( '.section-content-form' ),
		btn					= lastFightForm.querySelector( '.last-fight-show-field' ),
		input				= lastFightForm.querySelector( '#city' )

	contentSection.classList.add( 'section-content-last-fight' )
	form.parentNode.insertBefore( lastFightForm, form.nextSibling )
	form.remove()
	// Remove original form, because it has the same #city input, Nova Poshta API will not work properly.
	lastFightFormOrigin.remove()

	btn.addEventListener( 'click', () => {
		const label = lastFightForm.querySelector( '.section-content-np' )

		if( ! label ) return

		label.classList.remove( 'hidden' )
		btn.classList.add( 'hidden' )
	} )

	const onCityInputChange = () => isStepFilled( 2 )

	input.addEventListener( 'input', onCityInputChange )
	input.addEventListener( 'focus', onCityInputChange )
	input.addEventListener( 'blur', onCityInputChange )
}

/**
 * Remove Last Fight section.
 * We need to return its form to the hidden original block,
 * hide input and show button (for the next time it will be added again).
 *
 * @param contentSection
 */
const removeLastFightSection = contentSection => {
	if( ! contentSection ) return

	const
		lastFightSection	= document.querySelector( '.section-content-last-fight.hidden' ),
		lastFightFormCopy	= contentSection.querySelector( '.section-content-form-np' ).cloneNode( true )

	lastFightFormCopy.querySelector( '.last-fight-show-field' ).classList.remove( 'hidden' )
	lastFightFormCopy.querySelector( '.section-content-np' ).classList.add( 'hidden' )
	lastFightFormCopy.querySelector( '#city' ).value = ''
	lastFightSection.appendChild( lastFightFormCopy )
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
				targetSection	= target.closest( '.section' ),
				sectionId		= targetSection.dataset.id,
				sectionRealId	= targetSection.id,
				isCustom		= targetSection.classList.contains( 'custom' ),
				sectionsAdded	= document.querySelectorAll( '.sections-added-list .section' ),
				sectionContent	= sectionsContent.querySelector( `.section-content[data-id="${ sectionId }"]` ),
				isMilitary		= targetSection.classList.contains( 'section-military' )

			if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

			// Section content is not empty - double-check if User wants to delete it.
			if( sectionContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

			const clonedSection = targetSection.cloneNode( true )

			if( ! isCustom ) returnSectionToItsPlace( isMilitary, parseInt( clonedSection.dataset.id ), clonedSection )

			if( sectionRealId === 'last-fight' ) removeLastFightSection( sectionContent )

			removeSectionStepData( sectionId, targetSection, sectionContent )
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
			! target.closest( '.section-remove' ) &&
			! ( target.className && target.classList.contains( '.section-remove' ) )
		) return

		const
			targetContent	= target.closest( '.section-content' ),
			sectionId		= targetContent.dataset.id,
			sectionRealId	= sectionsWrapper.querySelector( `.section[data-id="${ sectionId }"]` ).id,
			targetSection	= document.querySelector( `.sections-added-list .section[data-id="${ sectionId }"]` ),
			sectionsAdded	= document.querySelectorAll( '.sections-added-list .section' ),
			isMilitary		= targetSection.classList.contains( 'section-military' )

		if( ! sectionsAdded.length || sectionsAdded.length < 2 ) return

		// Section content is not empty - double-check if User wants to delete it.
		if( targetContent.querySelector( '.section-content-text' ).value && ! confirm( 'Дійсно видалити цю секцію?' ) ) return

		const clonedSection = targetSection.cloneNode( true )

		returnSectionToItsPlace( isMilitary, parseInt( clonedSection.dataset.id ), clonedSection )

		if( sectionRealId === 'last-fight' ) removeLastFightSection( targetContent )

		removeSectionStepData( sectionId, targetSection, targetContent )
	} )
}

/**
 * Insert deleted section to its place in the sidebar by its data-order value.
 *
 * @param isMilitary
 * @param id
 * @param clonedSection
 */
const returnSectionToItsPlace = ( isMilitary, id, clonedSection ) => {
	if( isMilitary && militarySectionsWrapper ){
		const next = militarySectionsWrapper.querySelector( `.section[data-id="${ id + 1 }"]` )

		if( ! next ){
			const sections = militarySectionsWrapper.querySelectorAll( '.section' )

			if( ! sections.length ){
				militarySectionsWrapper.append( clonedSection )
			}else{
				let inserted = false

				sections.forEach( section => {
					const sectionOrder = parseInt( section.dataset.order )

					if( sectionOrder > id ){
						section.parentNode.insertBefore( clonedSection, section )
						inserted = true
					}
				} )

				if( ! inserted ) militarySectionsWrapper.append( clonedSection )
			}
		}else{
			next.parentNode.insertBefore( clonedSection, next )
		}
	}else{
		const next = sectionsListWrapper.querySelector( `.section[data-id="${ id + 1 }"]` )

		if( ! next ){
			let sections = sectionsListWrapper.querySelectorAll( '.section' )

			if( ! sections.length ){
				sectionsListWrapper.append( clonedSection )
			}else{
				let inserted = false

				sections.forEach( section => {
					if( inserted ) return

					const sectionOrder = parseInt( section.dataset.order )

					if( sectionOrder > id ){
						section.parentNode.insertBefore( clonedSection, section )
						inserted = true
					}
				} )

				if( ! inserted ) sectionsListWrapper.append( clonedSection )
			}
		}else{
			next.parentNode.insertBefore( clonedSection, next )
		}
	}
}

/**
 * Remove section data from stepData by ID.
 *
 * @param sectionId
 * @param sidebarSection
 * @param contentSection
 */
const removeSectionStepData = ( sectionId, sidebarSection, contentSection ) => {
	for( let key in stepData ){
		if( key == sectionId ){
			let photos = stepData[key].photos

			if( photos ) photos.forEach( photo => deleteContentPhoto( photo ) )

			delete stepData[key]
		}
	}

	sidebarSection.remove()
	contentSection.remove()
	localStorage.setItem( 'ih-step-2', JSON.stringify( stepData ) )
	isStepFilled( 2 )
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
			// Don't expand content if clicked some of the elements below (yeah... I know...).
			if(
				( target.className &&
					(
						target.classList.contains( 'section-content-photo-delete' ) ||
						target.classList.contains( 'section-content-form-add' ) ||
						target.classList.contains( 'last-fight-show-field' ) ||
						target.classList.contains( 'section-content-np' )
					)
				) ||
				target.closest( '.popup-confirm' ) || target.closest( '.section-content-form-add' ) ||
				target.closest( '.last-fight-show-field' ) || target.closest( '.section-content-np' )
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
	const sections = document.querySelectorAll( '.section-content:not(.hidden)' )

	if( ! sections.length ) return

	sections.forEach( ( section, i ) => {
		stepData[section.dataset.id].position = i
	} )
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
	const textareas	= sectionsContent.querySelectorAll( '.section-content-text' )
	let allIsSet	= true

	if( ! textareas.length ) return false

	textareas.forEach( ( area, i ) => {
		const
			section		= area.closest( '.section-content' ),
			title		= section.querySelector( '.section-content-title' ),
			titleInput	= title.querySelector( '.section-content-title-input' ),
			value		= area.value,
			index		= section.dataset.id,
			isCustom	= section.classList.contains('custom'),
			photos		= section.querySelectorAll( '.section-content-photo' ),
			city		= section.querySelector( '#city' ),
			isCto		= section.classList.contains( 'section-content-cto' ),
			isWar		= section.classList.contains( 'section-content-war' ),
			isLastFight	= section.classList.contains( 'section-content-last-fight' )

		if( titleInput ) stepData[index] = { ...stepData[index], title: titleInput.value }
		else stepData[index] = { ...stepData[index], title: title.innerText }

		// If textarea or title input is not set.
		if( titleInput && ! titleInput.value ) allIsSet = false

		// If this is CTO section.
		if( isCto ) stepData[index].isCto = 1

		// If this is War section.
		if( isWar ) stepData[index].isWar = 1

		// If this is Last Fight section.
		if( isLastFight ){
			stepData[index].isLastFight = 1

			if( city ){
				const cityText = city.value

				if( cityText ) stepData[index].city = city.value
			}
		}

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
					isStepFilled( 2 )
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

		if( fileInstance[0].size > 10 * BYTES_IN_MB ){
			showNotification( 'MAX 10 Mb', 'error' )
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
							isStepFilled( 2 )
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