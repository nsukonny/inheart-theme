document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	togglePasswordVisibility()
} )

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