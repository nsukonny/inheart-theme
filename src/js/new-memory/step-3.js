import { allowNextStep, applyProgress, disallowNextStep, isStepFilled } from './common'

const
	stepData = localStorage.getItem( 'ih-step-3' ) ?
		JSON.parse( localStorage.getItem( 'ih-step-3' ) ) :
		{ 'epitaph': '', 'epitaph-lastname': '', 'epitaph-firstname': '', 'epitaph-role': '' }
let textarea

/**
 * Listen to epitaph textarea changes.
 */
export const checkEpitaphContentLength = () => {
	const fields = document.querySelectorAll( '.epitaph input' )

	textarea = document.querySelector( '.epitaph-text' )

	if( ! textarea ) return

	stepData.epitaph = textarea.value
	localStorage.setItem( 'ih-step-3', JSON.stringify( stepData ) )
	textarea.addEventListener( 'keyup', onEpitaphChange )
	textarea.addEventListener( 'change', onEpitaphChange )
	textarea.addEventListener( 'focus', onEpitaphChange )
	textarea.addEventListener( 'blur', onEpitaphChange )

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
		localStorage.setItem( 'ih-step-3', JSON.stringify( stepData ) )

		isStepFilled( 3 )
	}
}

/**
 * Check epitaph textarea length and compare with allowed length.
 */
const onEpitaphChange = () => {
	// This is the first time textarea focused - clean it from the "placeholder" text.
	if( textarea.classList.contains( 'clear-on-focus' ) ){
		textarea.value = ''
		textarea.classList.remove( 'clear-on-focus' )
	}

	const
		value			= textarea.value,
		symbolsTyped	= document.querySelector( '.symbols-count-typed' ),
		symbolsAllowed	= parseInt( document.querySelector( '.symbols-count-allowed' ).innerText.trim() )

	if( ! symbolsTyped || ! symbolsAllowed ) return

	stepData.epitaph = value
	localStorage.setItem( 'ih-step-3', JSON.stringify( stepData ) )

	if( ! value ){
		isStepFilled( 3 )
		symbolsTyped.innerHTML = 0
		return
	}

	// Cut text if there are too many symbols.
	if( value.length > symbolsAllowed ) textarea.value = value.substring( 0, symbolsAllowed )

	symbolsTyped.innerHTML = textarea.value.length

	isStepFilled( 3 )
}

/**
 * Check if step 3 is ready.
 *
 * @returns {boolean}
 */
export const checkStep3 = () => {
	const
		epitaphValue	= textarea.value,
		fields			= document.querySelectorAll( '.epitaph input' )
	let isFormValid		= true

	if( ! epitaphValue || textarea.classList.contains( 'clear-on-focus' ) ) return false

	if( fields.length ){
		fields.forEach( field => {
			const
				index	= field.name,
				value	= field.value

			if( field.required && ! value ) isFormValid = false

			stepData[index] = value
		} )
	}

	localStorage.setItem( 'ih-step-3', JSON.stringify( stepData ) )

	return isFormValid
}