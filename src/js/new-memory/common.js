import { replaceUrlParam } from '../common/global'

let footer,
	progressBar,
	prevStepBtn,
	nextStepBtn

/**
 * Define all global variables required for all steps.
 *
 * @returns {boolean}
 */
export const defineGlobalStepsItems = () => {
	footer 		= document.querySelector( '.new-memory-footer' )
	progressBar	= document.querySelector( '.new-memory-progress-bar' )
	prevStepBtn	= document.querySelector( '.new-memory-prev-step' )
	nextStepBtn	= document.querySelector( '.new-memory-next-step' )

	if( nextStepBtn ) nextStepBtn.disabled = true

	return ! ( ! footer || ! progressBar || ! prevStepBtn || ! nextStepBtn )
}

/**
 * Allow to go to the next step (enable Next button).
 *
 * @param {number} nextStepId	ID of the next step.
 */
export const allowNextStep = ( nextStepId = 1 ) => {
	nextStepBtn.removeAttribute( 'disabled' )
	nextStepBtn.dataset.next = `${ nextStepId }`
}

export const disallowNextStep = () => {
	nextStepBtn.setAttribute( 'disabled', 'true' )
	nextStepBtn.dataset.next = ''
}

/**
 * Fill one specific part of the progress bar with percentage.
 *
 * @param {number} partId		Which part of progress bar is active
 * @param {number} percentage	Percents to fill
 */
export const applyProgress = ( partId = 1, percentage = 100 ) => {
	const part = progressBar.querySelector( `[data-part="${ partId }"]` )

	if( ! part ) return

	part.querySelector( '.new-memory-progress-inner' ).style.width = `${ percentage }%`
}

/**
 * Go to the next step.
 */
export const nextStep = () => {
	nextStepBtn.addEventListener( 'click', () => {
		if( nextStepBtn.disabled ) return

		const nextStepId = parseInt( nextStepBtn.dataset.next )

		if( ! nextStepId ) return

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ nextStepId }` ).classList.add( 'active' )
		replaceUrlParam( 'step', nextStepId )
		prevStepBtn.classList.remove( 'hidden' )
		prevStepBtn.setAttribute( 'data-prev', nextStepId - 1 )
		disallowNextStep()
	} )
}

/**
 * Go to the previous step.
 */
export const prevStep = () => {
	prevStepBtn.addEventListener( 'click', () => {
		if( prevStepBtn.classList.contains( 'hidden' ) ) return

		const prevStepId = parseInt( prevStepBtn.dataset.prev )

		if( ! prevStepId && prevStepId != '0' ) return

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ prevStepId }` ).classList.add( 'active' )
		replaceUrlParam( 'step', prevStepId )
		allowNextStep( prevStepId + 1 )
		applyProgress( prevStepId, 0 )

		if( prevStepId == 0 ) prevStepBtn.classList.add( 'hidden' )
		else prevStepBtn.setAttribute( 'data-prev', prevStepId - 1 )

		nextStepBtn.setAttribute( 'data-next', prevStepId + 1 )
	} )
}