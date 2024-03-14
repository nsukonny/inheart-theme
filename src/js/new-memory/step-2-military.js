import { isStepFilled } from './common'

const stepData = {}

export const step2MilitaryFormValidation = () => {
	const fields = document.querySelectorAll( '.new-memory-step-2-military input' )

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
				field.value = option.innerText
				field.setAttribute( 'data-id', option.dataset.id )
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
			value	= ( select.id && select.id === 'army' ) ? select.dataset.id : select.value

		stepData[name] = value

		if( ! value ) allIsSet = false

		localStorage.setItem( 'ih-step-2-military', JSON.stringify( stepData ) )
	} )

	return allIsSet
}