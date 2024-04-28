import '../common/forms'
import { submitAuthForm } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	checkFormStatus( document.querySelector( '#form-register' ) )
	submitAuthForm( '#form-register', 'ih_ajax_register' )
} )

export const checkFormStatus = form => {
	if( ! form ) return

	const fields = form.querySelectorAll( 'input:not([type="hidden"])' )

	if( ! fields.length ) return

	const submitButton = form.querySelector( 'button[type="submit"]' )

	const onInputChange = () => {
		let isFormFilled = true

		fields.forEach( field => {
			const value = field.type === 'checkbox' ? field.checked : field.value

			if( ! value ) isFormFilled = false
		} )

		if( isFormFilled ) submitButton.removeAttribute( 'disabled' )
		else submitButton.setAttribute( 'disabled', 'disabled' )
	}

	fields.forEach( field => field.addEventListener( 'change', onInputChange ) )
	fields.forEach( field => field.addEventListener( 'keyup', onInputChange ) )
}