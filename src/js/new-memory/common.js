import html2canvas from 'html2canvas'
import {
	checkAjaxWorkingStatus, getTargetElement, hideElement,
	ihAjaxRequest,
	setAjaxWorkingStatus, setTargetElement, showElement,
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
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'

let footer,
	progressBar,
	prevStepBtn,
	nextStepBtn,
	nextStepIdGlobal,
	prevStepIdGlobal,
	linkToPage,
	qrLink

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

		// 6th is the last screen just with common info, don't need to make a request.
		if( ! nextStepIdGlobal || prevStepIdGlobal == 6 || prevStepIdGlobal === '6-military' ) return

		const
			formData	= new FormData(),
			dataNext	= nextStepBtn.dataset.next

		formData.append( 'action', `ih_ajax_save_data_step_${ prevStepIdGlobal }` )
		formData.append( 'stepData', localStorage.getItem( `ih-step-${ prevStepIdGlobal }` ) || '' )
		pushToDataLayer( dataNext )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNextStepSection()

						// 6th step is the last, need to clean all the data and redirect.
						if( dataNext == 6 ){
							theLastStep()
							setTimeout( () => window.location.href = res.data.redirect, 3000 )
						}

						if( dataNext === '6-military' ){
							linkToPage	= res.data.link
							qrLink		= res.data.qr_link
							copyToClipboard( linkToPage )
								.then( () => console.log( 'Link copied.' ) )
								.catch( () => console.error( 'Error. Link not copied.' ) )
							theLastMilitaryStep()
						}
						break

					case false:
						showNotification( res.data.msg, 'error' )
						break
				}
			}
		} )
	} )
}

/**
 * Push data to Google Tag Manager data layer.
 * @see https://developers.google.com/tag-platform/tag-manager/datalayer
 *
 * @param nextStep
 */
const pushToDataLayer = nextStep => {
	if( ! nextStep ) return

	window.dataLayer = []

	switch( nextStep ){
		case '1':
			window.dataLayer.push( { 'event': 'continue-step-1' } )
			break;

		case '2':
			window.dataLayer.push( { 'event': 'continue-step-2' } )
			break;

		case '2-military':
			window.dataLayer.push( { 'event': 'continue-step-2-military' } )
			break;

		case '3':
			window.dataLayer.push( { 'event': 'continue-step-3' } )
			break;

		case '3-military':
			window.dataLayer.push( { 'event': 'continue-step-3-military' } )
			break;

		case '4':
			window.dataLayer.push( { 'event': 'continue-step-4' } )
			break;

		case '5':
			window.dataLayer.push( { 'event': 'continue-step-5' } )
			break;

		case '6':
			window.dataLayer.push( { 'event': 'continue-step-6' } )
			break;

		case '6-military':
			window.dataLayer.push( { 'event': 'continue-step-6-military' } )
			break;
	}
}

export const saveStep = stepId => {
	if( ( ! stepId && stepId !== 0 ) || checkAjaxWorkingStatus() ) return

	setAjaxWorkingStatus( true )

	const formData = new FormData()

	formData.append( 'action', `ih_ajax_save_data_step_${ stepId }` )
	formData.append( 'stepData', localStorage.getItem( `ih-step-${ stepId }` ) || '' )

	ihAjaxRequest( formData ).then( res => {
		if( res ){
			switch( res.success ){
				case true:
					console.log( `Step ${ stepId } saved.` )
					break

				case false:
					showNotification( res.data.msg, 'error' )
					break
			}
		}

		setAjaxWorkingStatus( false )
	} )
}

/**
 * Hide current and show next step layout.
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
			nextStepIdGlobal	= isMilitaryTheme() ? '6-military' : 6
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

		if( stepId != 5 && stepId != 6 && stepId !== '6-military' ) saveStep( stepId )
	}else{
		applyProgress( stepForProgress, falsePercentage )
		disallowNextStep()
	}
}

/**
 * Do some actions on the last step.
 */
const theLastStep = () => {
	const
		screen	= document.querySelector( '#new-memory-step-6' ),
		step1	= localStorage.getItem( 'ih-step-1' ) ? JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : null

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
		screen.querySelector( '.page-created-thumb-img' )
			.innerHTML = `<img src="${ thumb }" alt="${ firstName } ${ middleName } ${ lastName }" />`
	}

	screen.querySelector( '.page-created-firstname' ).innerHTML	= `${ firstName} ${ middleName }`
	screen.querySelector( '.page-created-lastname' ).innerHTML	= lastName
	screen.querySelector( '.page-created-dates' ).innerHTML		= `${ bornAt } - ${ diedAt }`

	clearData()
}

const theLastMilitaryStep = () => {
	const
		screen	= document.querySelector( '#new-memory-step-6-military' ),
		step1	= localStorage.getItem( 'ih-step-1' ) ?
			JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : null,
		step2	= localStorage.getItem( 'ih-step-2-military' ) ?
			JSON.parse( localStorage.getItem( 'ih-step-2-military' ) ) : null

	if( ! screen || ! step1 || ! step2 ) return

	const
		dateBorn	= screen.querySelector( '.military-created-date-born' ),
		dateDied	= screen.querySelector( '.military-created-date-died' ),
		thumb		= step1.cropped || '',
		firstName	= step1.firstname,
		middleName	= step1.fathername,
		lastName	= step1.lastname,
		bornAtObj	= new Date( step1['date-of-birth'] ),
		bornDay		= bornAtObj.getDate() < 10 ? `0${ bornAtObj.getDate() }` : bornAtObj.getDate(),
		bornMonth	= formatMonthsRome( bornAtObj.getMonth() + 1 ),
		bornYear	= bornAtObj.getFullYear(),
		diedAtObj	= new Date( step1['date-of-death'] ),
		diedDay		= diedAtObj.getDate() < 10 ? `0${ diedAtObj.getDate() }` : diedAtObj.getDate(),
		diedMonth	= formatMonthsRome( diedAtObj.getMonth() + 1 ),
		diedYear	= diedAtObj.getFullYear(),
		brigade		= step2.brigade || '',
		army		= step2.armyTitle || '',
		armyThumb	= step2.armyThumb || ''

	// Push data to HTML.
	if( thumb ){
		screen.querySelector( '.military-created-thumb' )
			.innerHTML = `<img src="${ thumb }" alt="${ lastName } ${ firstName } ${ middleName }" />`
	}

	dateBorn.querySelector( '.military-created-date-day' ).innerHTML	= bornDay
	dateBorn.querySelector( '.military-created-date-month' ).innerHTML	= bornMonth
	dateBorn.querySelector( '.military-created-date-year' ).innerHTML	= bornYear
	dateDied.querySelector( '.military-created-date-day' ).innerHTML	= diedDay
	dateDied.querySelector( '.military-created-date-month' ).innerHTML	= diedMonth
	dateDied.querySelector( '.military-created-date-year' ).innerHTML	= diedYear

	screen.querySelector( '.military-created-lastname' ).innerHTML		= lastName
	screen.querySelector( '.military-created-firstname' ).innerHTML		= firstName
	screen.querySelector( '.military-created-fathername' ).innerHTML	= middleName

	screen.querySelector( '.military-created-brigade' ).innerHTML	= brigade
	screen.querySelector( '.military-created-army p' ).innerHTML	= army
	screen.querySelector( '.military-created-link-url' ).href		= linkToPage
	screen.querySelector( '.military-created-link-url' ).innerHTML	= linkToPage.replace( /http(s)?:\/\//, '' )
	screen.querySelector( '.military-created-qr a' ).href			= qrLink

	if( armyThumb ){
		screen.querySelector( '.military-created-army' ).insertAdjacentHTML(
			'afterbegin',
			`<img src="${ armyThumb }" alt="${ army }" />`
		)
	}

	html2canvas( document.querySelector( '.military-created-info' ) ).then( canvas => {
		canvas.id = 'military-created-canvas'
		canvas.classList.add( 'hidden' )
		document.body.appendChild( canvas )

		const shareLink = screen.querySelector( '.military-created-share-button' )

		shareLink.setAttribute( 'download', `${ lastName }_${ firstName }_${ middleName }_preview.png` )
		shareLink.setAttribute( 'href', canvas.toDataURL( 'image/png' ).replace( 'image/png', 'image/octet-stream' ) )
	} )

	clearData()
}

const formatMonthsRome = monthNumeric => {
	switch( monthNumeric ){
		case 1:
			return 'I'

		case 2:
			return 'II'

		case 3:
			return 'III'

		case 4:
			return 'IV'

		case 5:
			return 'V'

		case 6:
			return 'VI'

		case 7:
			return 'VII'

		case 8:
			return 'VIII'

		case 9:
			return 'IX'

		case 10:
			return 'X'

		case 11:
			return 'XI'

		default:
			return 'XII'
	}
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

const clearData = () => {
	document.querySelector( '.new-memory-footer' ).classList.add( 'hidden' )
	localStorage.removeItem( 'ih-step-0' )
	localStorage.removeItem( 'ih-step-1' )
	localStorage.removeItem( 'ih-step-2' )
	localStorage.removeItem( 'ih-step-2-military' )
	localStorage.removeItem( 'ih-step-3' )
	localStorage.removeItem( 'ih-step-3-military' )
	localStorage.removeItem( 'ih-step-4' )
	localStorage.removeItem( 'ih-step-5' )
}

/**
 * Copy something to clipboard.
 *
 * @param {String} textToCopy	Specific text to copy.
 */
const copyToClipboard = textToCopy => {
	// Navigator clipboard api needs a secure context (https).
	if( navigator.clipboard && window.isSecureContext ){
		return navigator.clipboard.writeText( textToCopy )
	}	else {
		// text area method
		let textArea = document.createElement( 'textarea' )
		textArea.value = textToCopy;
		// Make the textarea out of viewport.
		textArea.style.position = 'absolute'
		textArea.style.left = '200%'
		textArea.style.top = '200%'
		textArea.style.opacity = 0
		document.body.appendChild( textArea )
		textArea.select()

		return new Promise( ( res, rej ) => {
			document.execCommand( 'copy' ) ? res() : rej()
			textArea.remove()
		} )
	}
}

/**
 * Instagram's notification popup events.
 */
export const instagramPopupEvents = () => {
	const
		popup	= document.querySelector( '#instagram-popup' ),
		button	= document.querySelector( '.military-created-share-button' )

	if( ! popup || ! button ) return

	const close = popup.querySelector( '.coords-popup-close' )

	button.addEventListener( 'click', e => {
		if( ! button.classList.contains( 'allow-download' ) ) e.preventDefault()
		else return

		button.classList.add( 'allow-download' )
		setTargetElement( '#instagram-popup' )
		disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
		showElement( popup )
	} )

	close.addEventListener( 'click', () => {
		hideElement( popup )
		enableBodyScroll( getTargetElement() )
		button.click()
	} )
}

/**
 * Save Memory Page creation progress on header button click.
 */
export const saveProgress = () => {
	const
		btn		= document.querySelector( '.save-memory-page-progress' ),
		next	= document.querySelector( '.new-memory-next-step' ),
		prev	= document.querySelector( '.new-memory-prev-step' )

	if( ! btn || ! next || ! prev ) return

	btn.addEventListener( 'click', () => {
		const nextStep = next.dataset.next
		let currentStep

		currentStep = ! nextStep ? 1 : getPrevStepId( nextStep )
		saveStep( currentStep )
		setTimeout( () => window.location.href = btn.dataset.redirect, 500 )
	} )
}