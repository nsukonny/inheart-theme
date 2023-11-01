import '../common/forms'
import { ihAjaxRequest, setAjaxWorkingStatus, showNotification } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

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

	const fields = form.querySelectorAll( 'input' )

	if( ! fields ) return

	fields.forEach( field => {
		field.addEventListener( 'keyup', () => button.removeAttribute( 'disabled' ) )
	} )

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