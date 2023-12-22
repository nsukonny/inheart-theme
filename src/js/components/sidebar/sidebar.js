import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'
import { getTargetElement, setTargetElement, WINDOW_XL } from '../../common/global'

let sidebar

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	sidebar = document.querySelector( '.sidebar' )

	showSidebar()
	hideSidebar()
} )

const showSidebar = () => {
	const buttons = document.querySelectorAll( '.menu-button' )

	if( ! buttons.length || ! sidebar ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', e => {
			e.preventDefault()

			sidebar.classList.add( 'show' )
			setTargetElement( '#sidebar-inner' )
			disableBodyScroll( getTargetElement(), { reserveScrollBarGap: true } )
		} )
	} )
}

const hideSidebar = () => {
	const btn = document.querySelector( '.sidebar-header-return' )

	if( ! btn || ! sidebar ) return

	btn.addEventListener( 'click', e => {
		e.preventDefault()

		onHideSidebar()
	} )

	sidebar.addEventListener( 'click', e => {
		const target = e.target

		if( target.className && target.classList.contains( 'sidebar' ) ) onHideSidebar()
	} )
}

const onHideSidebar = () => {
	sidebar.classList.remove( 'show' )
	enableBodyScroll( getTargetElement() )
}

window.addEventListener( 'resize', () => {
	if( window.innerWidth >= WINDOW_XL && sidebar && sidebar.classList.contains( 'show' ) )
		onHideSidebar()
} )