document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switchDuration()
} )

const switchDuration = () => {
	const switcher = document.querySelector( '.switch' )

	if( ! switcher ) return

	switcher.addEventListener( 'click', () => {
		if( switcher.classList.contains( 'active' ) ) switcher.classList.remove( 'active' )
		else switcher.classList.add( 'active' )
	} )
}