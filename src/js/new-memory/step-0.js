import { allowNextStep } from './common'

/**
 * Theme selection.
 */
export const selectTheme = () => {
	const themes = document.querySelectorAll( '.new-memory-theme' )

	if( ! themes.length ) return

	themes.forEach( theme => {
		theme.addEventListener( 'click', () => {
			const alreadySelectedTheme = document.querySelector( '.new-memory-theme.active' )

			if( alreadySelectedTheme ) alreadySelectedTheme.classList.remove( 'active' )

			theme.classList.add( 'active' )
			localStorage.setItem( 'ih-step-0', JSON.stringify( { theme: theme.dataset.value } ) )
			allowNextStep()
		} )
	} )
}

/**
 * Check if step is done.
 *
 * @returns {boolean}
 */
export const checkStep0 = () => !! document.querySelector( '.new-memory-theme.active' )