import { checkAjaxWorkingStatus, setAjaxWorkingStatus, ihAjaxRequest } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	logout()
} )

/**
 * AJAX logout.
 */
const logout = () => {
	const logoutLinks = document.querySelectorAll( '.logout' )

	if( ! logoutLinks.length ) return

	logoutLinks.forEach( link => processLogout( link ) )
}

const processLogout = clickedItem => {
	if( ! clickedItem ) return

	clickedItem.addEventListener( 'click', e => {
		e.preventDefault()

		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )
		clickedItem.classList.add( 'loading' )

		const formData = new FormData()

		formData.append( 'action', 'ih_ajax_logout' )
		formData.append( 'page', 2 )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						clickedItem.innerText 	= res.data.msg
						window.location.href 	= res.data.redirect
						break

					case false:
						console.error( res.data.msg )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}