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

	button.addEventListener( 'click', e => {
		e.preventDefault()

		const formData = new FormData( form )

		formData.append( 'action', 'ih_ajax_save_profile' )
		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNotification( res.data.msg )
						setTimeout( () => window.location.href = button.href, 1000 )
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