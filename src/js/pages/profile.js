import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import '../components/sidebar/sidebar'
import {
	checkAjaxWorkingStatus,
	getTargetElement,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	setTargetElement
} from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	expandToFull()
	editMemory()
	// novaPoshtaAPI()
} )

/**
 * Edit memory page.
 */
const editMemory = () => {
	const editButtons = document.querySelectorAll( '.edit-memory' )

	if( ! editButtons.length ) return

	editButtons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )

			const
				memoryId	= btn.dataset.id,
				formData	= new FormData()

			btn.classList.add( 'disabled' )
			formData.append( 'action', 'ih_ajax_edit_memory_page' )
			formData.append( 'id', memoryId )

			ihAjaxRequest( formData ).then( res => {
				btn.classList.remove( 'disabled' )

				if( res ){
					switch( res.success ){
						case true:
							if( res.data.redirect ) window.location.href = res.data.redirect
							break

						case false:
							console.error( res.data.msg )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		} )
	} )
}

/**
 * Expand memory page to full - button click.
 */
const expandToFull = () => {
	const
		memoriesSection	= document.querySelector( '.profile-body' ),
		expandSection	= document.querySelector( '.expand-page' ),
		buttons			= document.querySelectorAll( '.expand-to-full' ),
		back			= document.querySelector( '.profile-breadcrumbs-back' )

	if( ! memoriesSection || ! expandSection || ! buttons.length  ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			const
				card			= btn.closest( '.memory-card' ),
				memoryPageId	= card.dataset.id,
				thumb			= card.querySelector( '.memory-card-thumb-img' ).innerHTML,
				firstName		= card.querySelector( '.memory-card-firstname' ).innerHTML,
				lastName		= card.querySelector( '.memory-card-lastname' ).innerHTML

			memoriesSection.classList.add( 'hidden' )
			expandSection.classList.remove( 'hidden' )
		} )
	} )

	if( back ){
		back.addEventListener( 'click', e => {
			e.preventDefault()
			memoriesSection.classList.remove( 'hidden' )
			expandSection.classList.add( 'hidden' )
		} )
	}
}

const novaPoshtaAPI = () => {
	fetch( 'https://api.novaposhta.ua/v2.0/json/', {
		method: 'POST',
		body: JSON.stringify( {
			apiKey: "1c1680d527abb8098fd0ffbf1345d5ab",
			modelName: "Address",
			calledMethod: "getSettlements",
			methodProperties: {
				FindByString: '',
				Warehouse: 1
			}
		} )
	} )
		.then( json => json.json() )
		.then( res => console.log( res ) )
}