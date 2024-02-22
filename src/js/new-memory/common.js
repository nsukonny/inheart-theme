import {
	checkAjaxWorkingStatus,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'
import { checkStep0 } from './step-0'
import { checkStep1 } from './step-1'
import { checkStep2 } from './step-2'
import { checkStep3 } from './step-3'
import { checkStep4 } from './step-4'
import { checkStep5 } from './step-5'
import { checkStep2Military } from './step-2-military'
import { checkStep3Military } from './step-3-military'

let footer,
	progressBar,
	prevStepBtn,
	nextStepBtn,
	nextStepIdGlobal,
	prevStepIdGlobal

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

	return ! ( ! footer || ! progressBar || ! prevStepBtn || ! nextStepBtn )
}

const isMilitaryTheme = () => {
	let stepData = localStorage.getItem( 'ih-step-0' )

	if( ! stepData ) return false

	stepData = JSON.parse( stepData )

	return stepData.theme === 'military'
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

/**
 * Disable next step button.
 */
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
	const part = progressBar.querySelector( `[data-part="${ parseInt( partId ) }"]` )

	if( ! part ) return

	part.querySelector( '.new-memory-progress-inner' ).style.width = `${ percentage }%`
}

/**
 * Save current step data and go to the next step.
 */
export const nextStep = () => {
	nextStepBtn.addEventListener( 'click', () => {
		if( nextStepBtn.disabled ) return

		if( ! nextStepIdGlobal || checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const
			formData	= new FormData(),
			dataNext	= parseInt( nextStepBtn.dataset.next )

		formData.append( 'action', `ih_ajax_save_data_step_${ prevStepIdGlobal }` )
		formData.append( 'stepData', localStorage.getItem( `ih-step-${ prevStepIdGlobal }` ) || '' )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNextStepSection()

						// 6th step is the last, need to clean all the data and redirect.
						if( dataNext === 6 ){
							theLastStep()
							setTimeout( () => window.location.href = res.data.redirect, 3000 )
						}
						break

					case false:
						showNotification( res.data.msg, 'error' )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}

/**
 * Hide current and show next step layout.
 *
 * @param {number} nextStepId
 */
const showNextStepSection = () => {
	document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
	document.querySelector( `#new-memory-step-${ nextStepIdGlobal }` ).classList.add( 'active' )
	prevStepBtn.classList.remove( 'hidden' )
	prevStepBtn.setAttribute( 'data-prev', prevStepIdGlobal )
	isStepFilled( nextStepIdGlobal )
}

/**
 * Go to the previous step.
 */
export const prevStep = () => {
	prevStepBtn.addEventListener( 'click', () => {
		if( prevStepBtn.classList.contains( 'hidden' ) ) return

		let prevStepIdUpd	= prevStepBtn.dataset.prev,
			prevStepId		= parseInt( prevStepIdUpd )

		if( ! prevStepId && prevStepId != '0' ) return

		if( ! prevStepIdUpd.includes( '-military' ) ) prevStepIdUpd = prevStepId

		document.querySelector( '.new-memory-step.active' ).classList.remove( 'active' )
		document.querySelector( `#new-memory-step-${ prevStepIdUpd }` ).classList.add( 'active' )
		applyProgress( prevStepId + 1, 0 )

		isStepFilled( prevStepIdUpd )

		if( prevStepId == 0 ) prevStepBtn.classList.add( 'hidden' )
		else prevStepBtn.setAttribute( 'data-prev', getPrevStepId( prevStepIdGlobal ) )

		nextStepBtn.setAttribute( 'data-next', nextStepIdGlobal )
	} )
}

/**
 * Check if specific step is ready.
 *
 * @param stepId
 */
export const isStepFilled = ( stepId = 0 ) => {
	let cb,	// Callback function for each step, returns true if step is ready, otherwise - false.
		stepForProgress	= 0,
		percentage		= 100,
		falsePercentage	= 0

	switch( stepId ){
		case 1:
			cb					= checkStep1
			nextStepIdGlobal	= isMilitaryTheme() ? '2-military' : 2
			prevStepIdGlobal	= 1
			stepForProgress		= 1
			break

		case 2:
			cb					= checkStep2
			nextStepIdGlobal	= 3
			prevStepIdGlobal	= 2
			stepForProgress		= 2
			falsePercentage		= isMilitaryTheme() ? 66 : 0
			break

		case '2-military':
			cb					= checkStep2Military
			nextStepIdGlobal 	= '3-military'
			prevStepIdGlobal 	= '2-military'
			percentage			= 33
			stepForProgress		= 2
			break

		case '3-military':
			cb					= checkStep3Military
			nextStepIdGlobal	= 2
			prevStepIdGlobal	= '3-military'
			percentage			= 66
			falsePercentage		= 33
			stepForProgress		= 2
			break

		case 3:
			cb					= checkStep3
			nextStepIdGlobal	= 4
			prevStepIdGlobal	= 3
			stepForProgress		= 3
			break

		case 4:
			cb					= checkStep4
			nextStepIdGlobal	= 5
			prevStepIdGlobal	= 4
			stepForProgress		= 4
			break

		case 5:
			cb					= checkStep5
			nextStepIdGlobal	= 6
			prevStepIdGlobal	= 5
			stepForProgress		= 5
			break

		default:
			cb					= checkStep0
			nextStepIdGlobal	= 1
			prevStepIdGlobal	= 0
	}

	if( cb() ){
		applyProgress( stepForProgress, percentage )
		allowNextStep( nextStepIdGlobal )
	}else{
		applyProgress( stepForProgress, falsePercentage )
		disallowNextStep()
	}
}

/**
 * Do some actions on the last step.
 */
const theLastStep = () => {
	const step1 = localStorage.getItem( 'ih-step-1' ) ? JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : null

	if( ! step1 ) return

	const
		thumb		= step1.cropped || '',
		firstName	= step1.firstname,
		middleName	= step1.fathername,
		lastName	= step1.lastname,
		bornAtObj	= new Date( step1['date-of-birth'] ),
		bornDay		= bornAtObj.getDate() < 10 ? `0${ bornAtObj.getDate() }` : bornAtObj.getDate(),
		bornMonth	= ( bornAtObj.getMonth() + 1 ) < 10 ? `0${ bornAtObj.getMonth() + 1 }` : bornAtObj.getMonth() + 1,
		bornAt		= `${ bornDay }.${ bornMonth }.${ bornAtObj.getFullYear() }`,
		diedAtObj	= new Date( step1['date-of-death'] ),
		diedDay		= diedAtObj.getDate() < 10 ? `0${ diedAtObj.getDate() }` : diedAtObj.getDate(),
		diedMonth	= ( diedAtObj.getMonth() + 1 ) < 10 ? `0${ diedAtObj.getMonth() + 1 }` : diedAtObj.getMonth() + 1,
		diedAt		= `${ diedDay }.${ diedMonth }.${ diedAtObj.getFullYear() }`

	// Push data to HTML.
	if( thumb ){
		document.querySelector( '.page-created-thumb-img' )
			.innerHTML = `<img src="${ thumb }" alt="${ firstName } ${ middleName } ${ lastName }" />`
	}

	document.querySelector( '.page-created-firstname' ).innerHTML	= `${ firstName} ${ middleName }`
	document.querySelector( '.page-created-lastname' ).innerHTML	= lastName
	document.querySelector( '.page-created-dates' ).innerHTML		= `${ bornAt} - ${ diedAt }`

	// Hide footer, clean localStorage.
	document.querySelector( '.new-memory-footer' ).classList.add( 'hidden' )
	localStorage.removeItem( 'ih-step-0' )
	localStorage.removeItem( 'ih-step-1' )
	localStorage.removeItem( 'ih-step-2' )
	localStorage.removeItem( 'ih-step-3' )
	localStorage.removeItem( 'ih-step-4' )
	localStorage.removeItem( 'ih-step-5' )
}

const getPrevStepId = stepId => {
	switch( stepId ){
		case 2:
			return isMilitaryTheme() ? '3-military' : 1

		case '2-military':
			return 1

		case '3-military':
			return '2-military'

		case 3:
			return 2

		case 4:
			return 3

		case 5:
			return 4

		default:
			return 0
	}
}