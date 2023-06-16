document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	togglePasswordVisibility()
	hideFieldsErrors()
} )

/**
 * Click on eye button to show/hide password symbols.
 */
const togglePasswordVisibility = () => {
	const eyeButtons = document.querySelectorAll( '.pass-toggle' )

	if( ! eyeButtons.length ) return

	eyeButtons.forEach( eye => {
		const
			wrap		= eye.closest( '.pass-wrapper' ),
			passInput	= wrap.querySelector( 'input[type="password"]' )

		eye.addEventListener( 'click', () => {
			if( wrap.classList.contains( 'pass-visible' ) ){
				wrap.classList.remove( 'pass-visible' )
				passInput.type = 'password'
			}	else{
				wrap.classList.add( 'pass-visible' )
				passInput.type = 'text'
			}
		} )
	} )
}

const hideFieldsErrors = () => {
	const forms = document.querySelectorAll( 'form' )

	if( ! forms.length ) return

	forms.forEach( form => {
		const
			inputs	= form.querySelectorAll( 'input' ),
			note	= form.querySelector( '.note' )

		if( ! inputs.length ) return

		inputs.forEach( input => {
			const type = input.type

			if( type === 'hidden' ) return

			const label = input.closest( 'label.label' ) || input.closest( `.${ type }-wrapper` ).querySelector( 'label' )

			const cleanInputError = () => {
				if( input.required ){
					if( ! input.value ) label.classList.add( 'error' )
					else label.classList.remove( 'error' )
				}	else{
					label.classList.remove( 'error' )
				}

				note.classList.remove( 'note-error', 'note-success' )
			}

			input.addEventListener( 'change', cleanInputError )
			input.addEventListener( 'keyup', cleanInputError )
		} )
	} )
}