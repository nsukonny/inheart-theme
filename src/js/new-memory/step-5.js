import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import { isStepFilled } from './common'
import { getTargetElement, hideElement, setTargetElement, showElement } from '../common/global'

const stepData = {}

/**
 * Legend tip button click.
 */
export const legendTipClick = () => {
	const
		tip		= document.querySelector( '.legend-tip' ),
		popup	= document.querySelector( '#coords-popup' ),
		close	= document.querySelector( '#coords-popup .coords-popup-close' )

	if( ! tip || ! popup || ! close ) return

	tip.addEventListener( 'click', () => {
		setTargetElement( '#coords-popup' )
		disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
		showElement( popup )
	} )

	close.addEventListener( 'click', () => {
		hideElement( popup )
		enableBodyScroll( getTargetElement() )
	} )
}

/**
 * Validate Step 5 coords info form.
 */
export const addCoordsFormValidation = () => {
	const
		fields		= document.querySelectorAll( '.new-memory-coords input' ),
		textarea	= document.querySelector( '.new-memory-coords textarea' )

	if( ! fields.length || ! textarea ) return

	fields.forEach( field => {
		field.addEventListener( 'change', checkFieldValue )
		field.addEventListener( 'keyup', checkFieldValue )
		field.addEventListener( 'blur', checkFieldValue )
	} )
	textarea.addEventListener( 'change', checkFieldValue )
	textarea.addEventListener( 'keyup', checkFieldValue )
	textarea.addEventListener( 'blur', checkFieldValue )
}

const checkFieldValue = e => {
	const
		field	= e.target,
		value	= field.value,
		index	= field.name

	if( ! value ) field.classList.add( 'error' )
	else field.classList.remove( 'error' )

	stepData[index] = value
	localStorage.setItem( 'ih-step-5', JSON.stringify( stepData ) )
	isStepFilled( 5 )
}

/**
 * Check if Step 5 coords form is valid.
 *
 * @returns {boolean}
 */
export const checkStep5 = () => {
	const
		fields		= document.querySelectorAll( '.new-memory-coords input' ),
		textarea	= document.querySelector( '.new-memory-coords textarea' )
	let isFormValid	= true

	if( ! fields.length || ! textarea ) return false

	stepData.how_to_find = textarea.value

	// Fill stepData again in the case localStorage was cleared.
	fields.forEach( field => {
		const
			index	= field.name,
			value	= field.value

		if( field.required && ! value ) isFormValid = false

		stepData[index] = value
	} )
	localStorage.setItem( 'ih-step-5', JSON.stringify( stepData ) )

	return isFormValid
}