document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switcher()
} )

const switcher = () => {
	const switchers = document.querySelectorAll( '.switcher' )

	if( ! switchers.length ) return

	switchers.forEach( switcher => {
		const buttons = switcher.querySelectorAll( '.button' )

		if( ! buttons.length ) return

		buttons.forEach( btn => {
			btn.addEventListener( 'click', () => {
				const activeButton = switcher.querySelector( '.active' )

				if( activeButton ) activeButton.classList.remove( 'active' )

				btn.classList.add( 'active' )
			} )
		} )
	} )
}