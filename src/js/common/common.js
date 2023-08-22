document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	toggleProfileMenu()
} )

/**
 * Show/hide header profile dropdown menu.
 */
const toggleProfileMenu = () => {
	const
		button		= document.querySelector( '.header-profile-button' ),
		wrapper		= document.querySelector( '.header-profile' ),
		dropdown	= document.querySelector( '.header-profile-dropdown' )

	if( ! button || ! wrapper || ! dropdown ) return

	button.addEventListener( 'click', () => {
		if( wrapper.classList.contains( 'opened' ) ) wrapper.classList.remove( 'opened' )
		else wrapper.classList.add( 'opened' )
	} )

	// Close header profile dropdown on click outside.
	document.addEventListener( 'click', e => {
		const target = e.target

		if( wrapper.classList.contains( 'opened' ) && ! target.closest( '.header-profile' ) )
			wrapper.classList.remove( 'opened' )
	} )
}