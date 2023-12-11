document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switcher()
} )

const switcher = () => {
	const switchers = document.querySelectorAll( '.switcher' )

	if( ! switchers.length ) return

	switchers.forEach( switcher => {
		const buttons = switcher.querySelectorAll( '.switch' )

		if( ! buttons.length ) return

		buttons.forEach( btn => {
			btn.addEventListener( 'click', () => {
				const activeButton = switcher.querySelector( '.switch.active' )

				if( activeButton ) activeButton.classList.remove( 'active' )

				btn.classList.add( 'active' )
			} )
		} )
	} )
}