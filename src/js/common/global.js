export const ajaxUrl	= window.wpData.ajaxUrl,
	TRANSITION_DURATION	= 350,
	WINDOW_WIDTH_XL		= 1280,
	BYTES_IN_MB			= 1048576

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
	targetElement = document.querySelector( elementId )

	return targetElement;
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
 * Update URL parameter.
 *
 * @param {string} paramName
 * @param {string|number} paramValue
 */
export const replaceUrlParam = ( paramName, paramValue ) => {
	let url = new URL( window.location.href )

	url.searchParams.set( paramName, paramValue )
	window.history.pushState( `Step ${ paramValue }`, '', url.href )
}