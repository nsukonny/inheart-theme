import '../common/forms'
import {
	customDebounce,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	formValidation( document.querySelector( '.profile-settings-form' ) )
	saveChanges()
} )

/**
 * Save profile changes.
 */
const saveChanges = () => {
	const
		form	= document.querySelector( '.profile-settings-form' ),
		button	= document.querySelector( '.save-changes' )

	if( ! form || ! button ) return

	button.addEventListener( 'click', e => {
		e.preventDefault()

		const formData = new FormData( form )

		formData.append( 'action', 'ih_ajax_save_profile' )
		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNotification( res.data.msg )
						setTimeout( () => window.location.reload(), 1000 )
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
 * Validate profile settings changes.
 *
 * @param {HTMLObjectElement} form
 */
const formValidation = form => {
	if( ! form ) return

	const inputs = form.querySelectorAll( 'input' )

	if( ! inputs.length ) return

	const inputChanged = () => {
		const
			formData	= new FormData( form ),
			button		= document.querySelector( '.save-changes' )

		formData.append( 'action', 'ih_ajax_check_profile_settings' )
		clearFormErrors( form )
		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						if( button ) button.removeAttribute( 'disabled' )
						break

					case false:
						if( button ) button.disabled = true

						if( res.data.errors ) processErrors( form, res.data.errors )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	}

	inputs.forEach( input => {
		input.addEventListener( 'input', customDebounce( inputChanged ) )
	} )
}

const clearFormErrors = form => {
	const clearError = field => {
		const
			label	= field.closest( 'label' ),
			hint	= label.querySelector( '.hint' )

		label.classList.remove( 'error' )
		hint.innerText = ''
	}

	form.querySelectorAll( 'input' ).forEach( clearError )
	form.querySelectorAll( 'textarea' ).forEach( clearError )
}

const processErrors = ( form, errors ) => {
	errors = Object.entries( errors )

	errors.forEach( error => {
		const
			fieldName	= error[0],
			errorText	= error[1],
			field		= form.querySelector( `[name="${ fieldName }"]` )

		if( ! field ) return

		const
			label	= field.closest( 'label' ),
			hint	= label.querySelector( '.hint' )

		label.classList.add( 'error' )
		hint.innerText = errorText
	} )
}