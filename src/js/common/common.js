import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import { getTargetElement, setTargetElement } from './global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	toggleMenu()
	toggleProfileMenu()
} )

const toggleMenu = () => {
	const
		menuButton	= document.querySelector( '.header-menu-button' ),
		menuWrap	= document.querySelector( '#header-nav-wrap' ),
		header		= document.querySelector( '.header' )

	if( ! header || ! menuButton || ! menuWrap ) return

	menuButton.addEventListener( 'click', () => {
		setTargetElement( '#header-nav-wrap' )

		if( ! header.classList.contains( 'menu-opened' ) ){
			window.scrollTo(0, 0 )
			header.classList.add( 'menu-visible', 'menu-opened' )
			disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
		}else{
			header.classList.remove( 'menu-opened' )
			setTimeout( () => {
				header.classList.remove( 'menu-visible' )
				enableBodyScroll( getTargetElement() )
			}, 350 )
		}
	} )
}

/**
 * Show/hide header profile dropdown menu.
 */
const toggleProfileMenu = () => {
	const
		button		= document.querySelector( '.header-profile-button' ),
		wrapper		= document.querySelector( '.header-profile' ),
		dropdown	= document.querySelector( '.header-profile-dropdown' )

	if( ! button || ! wrapper || ! dropdown ) return

	button.addEventListener( 'click', () => {
		if( wrapper.classList.contains( 'opened' ) ) wrapper.classList.remove( 'opened' )
		else wrapper.classList.add( 'opened' )
	} )

	// Close header profile dropdown on click outside.
	document.addEventListener( 'click', e => {
		const target = e.target

		if( wrapper.classList.contains( 'opened' ) && ! target.closest( '.header-profile' ) )
			wrapper.classList.remove( 'opened' )
	} )
}