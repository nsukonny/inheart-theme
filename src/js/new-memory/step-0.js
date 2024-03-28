import { isStepFilled } from './common'

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
			isStepFilled()
		} )
	} )
}

/**
 * Check if step is done.
 *
 * @returns {boolean}
 */
export const checkStep0 = () => {
	const activeTheme = document.querySelector( '.new-memory-theme.active' )

	if( ! activeTheme ) return false

	const theme = activeTheme.dataset.value

	localStorage.setItem( 'ih-step-0', JSON.stringify( { theme } ) )

	if( theme === 'military' ){
		const stepData1 = localStorage.getItem( 'ih-step-1' ) ?
			JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : { lang: 'uk' }

		stepData1.lang = 'uk'
		localStorage.setItem( 'ih-step-1', JSON.stringify( stepData1 ) )
		document.body.classList.add( 'memory-page-theme-military' )
	}else{
		document.body.classList.remove( 'memory-page-theme-military' )
	}

	return true
}