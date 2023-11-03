export const ajaxUrl	= window.wpData.ajaxUrl,
	TRANSITION_DURATION	= 350,
	BYTES_IN_MB			= 1048576,
	WINDOW_LG			= 992

let isAjaxWorking = false,
	targetElement

// Check if AJAX is working now.
export let checkAjaxWorkingStatus = () => isAjaxWorking

/**
 * Set AJAX status.
 *
 * @param {Boolean} status
 */
export let setAjaxWorkingStatus = status => isAjaxWorking = status

/**
 * Custom AJAX request.
 *
 * @param	{Object}	formData	Data for fetch body.
 * @param 	{Object}	settings	Fetch settings object.
 * @returns	{Array}					Response data array.
 */
export let ihAjaxRequest = async ( formData, settings = {} ) => {
	let defaultSettings	= { method: 'post', ...settings, body: formData },
		response		= await fetch( ajaxUrl, defaultSettings )

	return await response.json()
}

/**
 * Highlight form labels with errors.
 *
 * @param {HTMLObjectElement}	form
 * @param {Object}				errors	Contains items with props: field, error.
 */
export const showFormErrors = ( form, errors ) => {
	errors.forEach( error => {
		let fieldErrorLabels = form.querySelectorAll( `label[data-for="${ error.field }"]` )

		if( ! fieldErrorLabels.length ){
			fieldErrorLabels = form.querySelectorAll( `label[for="${ error.field }"]` )

			if( ! fieldErrorLabels.length ) return
		}

		fieldErrorLabels.forEach( label => {
			label.classList.add( 'error' )

			const fieldError = label.querySelector( '.field-error' )

			if( ! fieldError ) return

			fieldError.classList.remove( 'hidden' )
			fieldError.innerHTML = error.error
		} )
	} )
}

/**
 * Hide all fields errors inside specific form.
 *
 * @param {HTMLObjectElement}	form
 */
export const hideFormErrors = form => {
	const labelsWithErrors = form.querySelectorAll( 'label.error' )

	if( ! labelsWithErrors.length ) return

	labelsWithErrors.forEach( label => hideFormFieldError( label ) )
}

/**
 * Hide an error for specific field.
 *
 * @param {HTMLObjectElement}	label	Animated label with error.
 */
export const hideFormFieldError = label => {
	label.classList.remove( 'error' )

	const field = label.querySelector( '.field-error' )

	if( ! field ) return

	field.innerHTML = ''
	field.classList.add( 'hidden' )
}

/**
 * Set variable for disableScrollLock.
 *
 * @param	{String}	elementId	Specific element ID.
 * @returns	{Boolean}				True if element is set, false if not.
 */
export const setTargetElement = elementId => {
	if( ! elementId ){
		targetElement = null
		return false
	}

	const el = document.querySelector( elementId )

	if( el ){
		targetElement = el
		return true
	}

	return false
}

/**
 * Get element for disableScrollLock.
 *
 * @returns targetElement value.
 */
export const getTargetElement = () => targetElement

/**
 * Submit form via AJAX.
 *
 * @param {String}	formSelector	Specific form CSS-selector.
 * @param {String}	action			AJAX action name.
 */
export const submitAjaxForm = ( formSelector, action ) => {
	const form = document.querySelector( formSelector )

	if( ! form ) return

	return form.addEventListener( 'submit', e => {
		e.preventDefault()

		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const note		= form.querySelector( '.note' ),
			formData	= new FormData( form )

		hideFormErrors( form )

		if( note ) note.innerHTML = ''

		form.classList.add( 'disabled' )
		formData.append( 'action', action )

		ihAjaxRequest( formData ).then( res => {
			form.classList.remove( 'disabled' )

			if( res ){
				switch( res.success ){
					case true:
						if( note ) note.innerHTML = res.data.msg

						form.reset()
						break

					case false:
						console.error( res.data.msg )

						if( res.data.errors ){
							showFormErrors( form, res.data.errors )
						}	else {
							if( note ) note.innerHTML = res.data.msg
						}

						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}

/**
 * Submit Authorization forms via AJAX.
 *
 * @param {String}	formSelector	Specific form CSS-selector.
 * @param {String}	action			AJAX action name.
 */
export const submitAuthForm = ( formSelector, action ) => {
	const form = document.querySelector( formSelector )

	if( ! form ) return

	form.addEventListener( 'submit', e => {
		e.preventDefault()

		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const
			note		= form.querySelector( '.note' ),
			formData	= new FormData( form )

		hideFormErrors( form )

		if( note ) note.innerHTML = ''

		form.classList.add( 'disabled' )
		formData.append( 'action', action )

		ihAjaxRequest( formData ).then( res => {
			form.classList.remove( 'disabled' )

			if( res ){
				switch( res.success ){
					case true:
						if( res.data.redirect )
							setTimeout( () => window.location.href = res.data.redirect, 1000 )

						if( note ){
							note.classList.add( 'note-success' )
							note.innerHTML = res.data.msg
						}

						break

					case false:
						if( note ){
							note.classList.add( 'note-error' )
							note.innerHTML = res.data.msg
						}

						if( res.data.errors ) showFormErrors( form, res.data.errors )

						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}

/**
 * Append popup.
 *
 * @param {HTMLObjectElement}	container		Where to add popup.
 * @param {function}			cancelCallback	Fires on popup cancel button click.
 * @param {function}			applyCallback	Fires on popup apply button click.
 */
export const showAreYouSurePopup = ( container, cancelCallback, applyCallback, question = 'Дійсно видалити фото?' ) => {
	if( document.querySelector( '.popup-sure' ) ) return

	const popup = `<div class="popup-sure">
		<div class="popup-sure-text">${ question }</div>
		<div class="popup-sure-buttons flex flex-wrap">
			<button class="popup-sure-cancel" type="button">Залишити</button>
			<button class="popup-sure-apply" type="button">Видалити</button>
		</div>
	</div>`

	container.insertAdjacentHTML( 'beforeend', popup )
	document.querySelector( '.popup-sure-cancel' ).addEventListener( 'click', cancelCallback )
	document.querySelector( '.popup-sure-apply' ).addEventListener( 'click', applyCallback )
}

/**
 * Remove popup.
 */
export const hideAreYouSurePopup = () => {
	const popup = document.querySelector( '.popup-sure' )

	if( popup ) popup.remove()
}

/**
 * Show popup notification.
 *
 * @param {string} text Notification text.
 * @param {string} type	'success' | 'error' | 'warning'
 */
export const showNotification = ( text = 'Please set the text', type = 'success' ) => {
	const notification = document.createElement( 'div' )

	notification.className 	= `notification ${ type }`
	notification.innerText 	= text
	document.body.querySelector( '.wrapper' ).appendChild( notification )

	setTimeout( () => {
		notification.classList.add( 'show' )
	}, 10 )
	setTimeout( () => {
		notification.classList.remove( 'show' )
	}, 5000 )
	setTimeout( () => {
		notification.remove()
	}, 5000 + TRANSITION_DURATION )
}

/**
 * Show specific element by removing class '.hidden'.
 *
 * @param {HTMLObjectElement} el
 */
export const showElement = el => {
	if( ! el || ! el.classList.contains( 'hidden' ) ) return

	el.classList.remove( 'hidden' )
}

/**
 * Hide specific element by adding class '.hidden'.
 *
 * @param {HTMLObjectElement} el
 */
export const hideElement = el => {
	if( ! el || el.classList.contains( 'hidden' ) ) return

	el.classList.add( 'hidden' )
}

/**
 * Format number value.
 *
 * @param {string|number} number	Parameter value will be turned to float anyway.
 * @returns {string}
 */
export const formatNumber = number => {
	return parseFloat( number ).toLocaleString( 'us' )
}

/**
 * Custom debounce function to improve performance.
 *
 * @param callback
 * @param delay		Value in milliseconds.
 * @returns {(function(...[*]): void)|*}
 */
export const customDebounce = ( callback, delay = 500 ) => {
	let timer

	return ( ...args ) => {
		if( timer ) clearTimeout( timer )

		timer = setTimeout( () => callback( ...args ), delay )
	}
}

/**
 * Switcher tabs logic.
 */
export const switcherLogic = () => {
	const switchers = document.querySelectorAll( '.switcher' )

	if( ! switchers.length ) return

	switchers.forEach( switcher => {
		switcher.addEventListener( 'click', e => {
			const target = e.target

			if( target.className && target.classList.contains( 'tab' ) && ! target.classList.contains( 'active' ) ){
				switcher.querySelector( '.tab.active' ).classList.remove( 'active' )
				target.classList.add( 'active' )
			}
		} )
	} )
}