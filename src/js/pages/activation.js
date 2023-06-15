import { checkAjaxWorkingStatus, ihAjaxRequest, setAjaxWorkingStatus } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	sendActivationLinkAgain()
} )

/**
 * Resend activation link to User.
 */
const sendActivationLinkAgain = () => {
	const links = document.querySelectorAll( 'a[href="#send-activation-again"]' )

	if( ! links.length ) return

	links.forEach( link => {
		link.addEventListener( 'click', e => {
			e.preventDefault()

			const
				urlParams	= new URLSearchParams( window.location.search ),
				params		= urlParams ? Object.fromEntries( urlParams.entries() ) : null,
				formData	= params ? new FormData() : null

			if( ! params ) return

			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )
			formData.append( 'action', 'ih_ajax_resend_activation_link' )
			formData.append( 'user_id', params.user )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							window.location.href = res.data.redirect
							break

						case false:
							alert( res.data.msg )
							console.error( res.data.msg )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		} )
	} )
}