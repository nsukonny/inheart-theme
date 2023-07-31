import Sortable from 'sortablejs'
import { allowNextStep, disallowNextStep, applyProgress } from './common'

/**
 * Add section to added sections list.
 */
export const addSection = () => {
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
 */
export const removeSidebarAddedSection = () => {
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
 */
export const removeContentSection = () => {
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
 */
export const setActiveSectionContent = () => {
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
 */
export const dragOrderSections = () => {
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
 * Check if all textareas are set. This means we can go further.
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