import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import { getTargetElement, setTargetElement } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	expandToFull()
} )

/**
 * Expand memory page to full button click.
 */
export const expandToFull = () => {
	const
		popup	= document.querySelector( '.expand-to-full-popup' ),
		buttons	= document.querySelectorAll( '.expand-to-full' )

	if( ! popup || ! buttons.length  ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			const
				card		= btn.closest( '.memory-card' ),
				thumb		= card.querySelector( '.page-created-thumb-img' ).innerHTML,
				firstName	= card.querySelector( '.page-created-firstname' ).innerHTML,
				lastName	= card.querySelector( '.page-created-lastname span' ).innerHTML,
				dates		= card.querySelector( '.page-created-dates' ).innerHTML

			setTargetElement( '#expand-to-full-popup' )
			disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
			popup.querySelector( '.popup-header-thumb' ).innerHTML	= thumb
			popup.querySelector( '.popup-name-first' ).innerHTML	= firstName
			popup.querySelector( '.popup-name-last' ).innerHTML		= lastName
			popup.querySelector( '.popup-dates' ).innerHTML			= dates
			popup.classList.remove( 'hidden' )
		} )
	} )

	// Close popup.
	popup.addEventListener( 'click', e => {
		const target = e.target

		if(
			target.className &&
			( target.classList.contains( 'popup' ) || target.classList.contains( 'popup-close' ) )
		){
			popup.classList.add( 'hidden' )
			enableBodyScroll( getTargetElement() )
		}
	} )
}