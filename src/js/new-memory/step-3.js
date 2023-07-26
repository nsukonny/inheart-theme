import { allowNextStep, applyProgress, disallowNextStep } from './common'

/**
 * Listen to epitaph textarea changes.
 */
export const checkEpitaphContentLength = () => {
	const textarea = document.querySelector( '.epitaph-text' )

	if( ! textarea ) return

	textarea.addEventListener( 'keyup', onEpitaphChange )
	textarea.addEventListener( 'change', onEpitaphChange )
	textarea.addEventListener( 'focus', onEpitaphChange )
	textarea.addEventListener( 'blur', onEpitaphChange )
}

/**
 * Check epitaph textarea length and compare with allowed length.
 *
 * @param {Event} e
 */
const onEpitaphChange = e => {
	const
		textarea		= e.target,
		value			= textarea.value,
		symbolsTyped	= document.querySelector( '.symbols-count-typed' ),
		symbolsAllowed	= parseInt( document.querySelector( '.symbols-count-allowed' ).innerText.trim() )

	if( ! symbolsTyped || ! symbolsAllowed ) return

	if( ! value ){
		disallowNextStep()
		applyProgress( 3, 0 )
		symbolsTyped.innerHTML = 0
		return
	}

	if( value.length > symbolsAllowed ) textarea.value = value.substring( 0, symbolsAllowed )

	symbolsTyped.innerHTML = textarea.value.length
	applyProgress( 3 )
	allowNextStep( 4 )
}