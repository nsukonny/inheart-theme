import {
	checkAjaxWorkingStatus,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification,
	switcherLogic
} from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switcherLogic()
	switchMemories()
} )

const switchMemories = () => {
	const switcherButtons = document.querySelectorAll( '.profile-memories-switcher .tab' )

	if( ! switcherButtons.length ) return

	switcherButtons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				type		= btn.dataset.type,
				formData	= new FormData()

			formData.append( 'action', 'ih_ajax_load_profile_memories' )
			formData.append( 'type', type )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							const wrapper = document.querySelector( '.profile-memories-list' )

							if( wrapper && res.data.memories ) wrapper.innerHTML = res.data.memories
							break

						case false:
							showNotification( res.data.msg, 'error' )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		} )
	} )
}