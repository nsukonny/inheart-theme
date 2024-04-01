import { isStepFilled } from './common'

const stepData = {}

export const step2MilitaryFormValidation = () => {
	const fields = document.querySelectorAll( '.new-memory-step-2-military input:not(#title)' )

	if( ! fields.length ) return

	fields.forEach( field => {
		const
			label	= field.closest( '.label' ),
			options	= label.querySelectorAll( '.option' )

		const checkFieldValue = e => {
			const
				field	= e.target,
				value	= field.value,
				index	= field.name

			if( ! value ) field.classList.add( 'error' )
			else field.classList.remove( 'error' )

			stepData[index] = value
			localStorage.setItem( 'ih-step-2-military', JSON.stringify( stepData ) )

			isStepFilled( '2-military' )
		}

		field.addEventListener( 'focus', openDropdown )
		field.addEventListener( 'click', openDropdown )

		field.addEventListener( 'input', checkFieldValue )
		field.addEventListener( 'change', checkFieldValue )
		field.addEventListener( 'keyup', checkFieldValue )
		field.addEventListener( 'blur', checkFieldValue )

		if( ! options.length ) return

		// Select option.
		options.forEach( option => {
			option.addEventListener( 'click', () => {
				const activeOption = label.querySelector( '.option.active' )

				if( activeOption ) activeOption.classList.remove( 'active' )

				option.classList.add( 'active' )
				field.value = option.innerText
				field.setAttribute( 'data-id', option.dataset.id )
				field.setAttribute( 'data-thumb', option.dataset.thumb )
				setTimeout( () => {
					closeDropdown()
					isStepFilled( '2-military' )
				}, 10 )
			} )
		} )
	} )

	document.addEventListener( 'click', e => {
		const target = e.target

		if( ! target.closest( '.label' ) ) closeDropdown()
	} )

	processRanksDropdown()
	filterBrigades()
}

/**
 * Ranks dropdown with different options lists.
 */
const processRanksDropdown = () => {
	const
		field		= document.querySelector( '.new-memory-step-2-military #title' ),
		armyLabel	= document.querySelector( '.new-memory-step-2-military .label-army' ),
		armyOptions	= armyLabel.querySelectorAll( '.option' )

	if( ! field ) return

	const
		label	= field.closest( 'label' ),
		options	= label.querySelectorAll( '.option' )

	field.addEventListener( 'focus', openRanksDropdown )
	field.addEventListener( 'click', openRanksDropdown )

	if( options.length ){
		options.forEach( option => {
			option.addEventListener( 'click', () => {
				const activeOption = label.querySelector( '.option.active' )

				if( activeOption ) activeOption.classList.remove( 'active' )

				option.classList.add( 'active' )
				field.value = option.innerText
				field.setAttribute( 'data-id', option.dataset.id )
				field.setAttribute( 'data-thumb', option.dataset.thumb )
				setTimeout( () => {
					closeRanksDropdown()
					isStepFilled( '2-military' )
				}, 10 )
			} )
		} )
	}

	document.addEventListener( 'click', e => {
		const target = e.target

		if( ! target.closest( 'label.active' ) ) closeRanksDropdown()
	} )

	if( armyOptions.length ){
		armyOptions.forEach( option => {
			option.addEventListener( 'click', () => {
				const ranksType = option.dataset.ranks

				if( label.classList.contains( `label-${ ranksType }-ranks` ) ) return

				field.value = ''
				label.classList.remove( 'label-army-ranks', 'label-sea-ranks', 'label-police-ranks' )
				label.classList.add( `label-${ ranksType }-ranks` )
			} )
		} )
	}
}

export const openDropdown = e => {
	const
		target		= e.target,
		label		= target.closest( '.label' ),
		dropdown	= label.querySelector( '.options' )

	closeDropdown()

	if( ! dropdown ) return

	dropdown.classList.add( 'active' )
}

export const closeDropdown = () => {
	const dropdown = document.querySelector( '.options.active' )

	if( dropdown ) dropdown.classList.remove( 'active' )
}

const openRanksDropdown = e => {
	const
		target	= e.target,
		label	= target.closest( '.label' )

	closeRanksDropdown()

	if( label ) label.classList.add( 'active' )
}

const closeRanksDropdown = () => {
	const label = document.querySelector( '.new-memory-step-2-military label.active' )

	if( label ) label.classList.remove( 'active' )
}

/**
 * Brigades field - filter list on input.
 */
const filterBrigades = () => {
	const field = document.querySelector( '#brigade' )

	if( ! field ) return

	const options = [...field.closest( 'label' ).querySelectorAll( '.option' )]

	if( ! options.length ) return

	const onBrigadeFilter = e => {
		const value = e.target.value && e.target.value.toLowerCase()

		options.forEach( option => {
			const name = option.innerText.trim().toLowerCase()

			if( name.includes( value ) ) option.classList.remove( 'hidden' )
			else option.classList.add( 'hidden' )
		} )
	}

	const onBrigadeFilterClear = () => {
		options.forEach( option => option.classList.remove( 'hidden' ) )
	}

	field.addEventListener( 'input', onBrigadeFilter )
	field.addEventListener( 'change', onBrigadeFilter )
	field.addEventListener( 'keyup', onBrigadeFilter )

	field.addEventListener( 'focus', onBrigadeFilterClear )
	field.addEventListener( 'click', onBrigadeFilterClear )
}

/**
 * Check if Step 2 Military is ready.
 *
 * @returns {boolean}
 */
export const checkStep2Military = () => {
	const selects	= document.querySelectorAll( '.new-memory-step-2-military .input-wrapper.select input' )
	let allIsSet	= true

	if( ! selects.length ) return false

	selects.forEach( select => {
		const
			name	= select.name,
			isArmy	= select.id && select.id === 'army',
			value	= isArmy ? select.dataset.id : select.value

		stepData[name] = value

		// Write text just for the last step.
		if( isArmy ){
			stepData.armyTitle 	= select.value
			stepData.armyThumb 	= select.dataset.thumb
		}

		if( ! value ) allIsSet = false

		localStorage.setItem( 'ih-step-2-military', JSON.stringify( stepData ) )
	} )

	return allIsSet
}