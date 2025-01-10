import { isStepFilled } from './common'
import { ihAjaxRequest, showNotification } from '../common/global'

/**
 * Theme selection.
 */
export const selectTheme = () => {
	const
		themes       = document.querySelectorAll( '.new-memory-theme' ),
		activeTheme  = document.querySelector( '.new-memory-theme.active' )
	let initialTheme = activeTheme ? activeTheme.dataset.value : undefined

	if( ! themes.length ) return

	themes.forEach( theme => {
		theme.addEventListener( 'click', () => {
			const alreadySelectedTheme = document.querySelector( '.new-memory-theme.active' )

			if( alreadySelectedTheme ) alreadySelectedTheme.classList.remove( 'active' )

			theme.classList.add( 'active' )
			localStorage.setItem( 'ih-step-0', JSON.stringify( { theme: theme.dataset.value } ) )

			// Update page when changed from military to simple theme OR back.
			if(
				( initialTheme === 'military' && theme.dataset.value !== 'military' ) ||
				( initialTheme !== 'military' && theme.dataset.value === 'military' )
			){
				const formData = new FormData()

				formData.append( 'action', `ih_ajax_save_data_step_0` )
				formData.append( 'stepData', localStorage.getItem( `ih-step-0` ) || '' )

				ihAjaxRequest( formData ).then( res => {
					if( res ){
						switch( res.success ){
							case true:
								window.location.reload()
								break

							case false:
								showNotification( res.data.msg, 'error' )
								break
						}
					}
				} )
			} else {
				isStepFilled()
			}
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