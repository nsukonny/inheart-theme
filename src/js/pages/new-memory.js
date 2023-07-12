document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	selectTheme()
} )

/**
 * Step 0.
 * Theme selection.
 */
const selectTheme = () => {
	const themes = document.querySelectorAll( '.new-memory-theme' )

	if( ! themes.length ) return

	themes.forEach( theme => {
		theme.addEventListener( 'click', () => {
			const alreadySelectedTheme = document.querySelector( '.new-memory-theme.active' )

			if( alreadySelectedTheme ) alreadySelectedTheme.classList.remove( 'active' )

			theme.classList.add( 'active' )
		} )
	} )
}