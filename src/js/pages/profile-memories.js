import '../components/sidebar/sidebar'
import '../components/switcher/switcher'
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
	const switcherButtons = document.querySelectorAll( '.profile-memories-switcher .button' )

	if( ! switcherButtons.length ) return

	switcherButtons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				type		= btn.dataset.type,
				pageId		= btn.closest( '.switcher' ).dataset.page,
				formData	= new FormData()

			formData.append( 'action', 'ih_ajax_load_profile_memories' )
			formData.append( 'type', type )
			formData.append( 'id', pageId )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							const
								wrapper = document.querySelector( '.profile-memories-list' ),
								inner	= document.querySelector( '.profile-memories-inner' )

							if( res.data.memories ){
								if( wrapper && ! res.data['no-memories'] ){
									wrapper.innerHTML = res.data.memories
								}else{
									if( inner && res.data['no-memories'] ) inner.innerHTML = res.data.memories
								}
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
	} )
}