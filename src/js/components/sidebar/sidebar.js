let sidebar

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	sidebar	= document.querySelector( '.sidebar' )

	showSidebar()
	hideSidebar()
} )

const showSidebar = () => {
	const btn = document.querySelector( '.menu-button' )

	if( ! btn || ! sidebar ) return

	btn.addEventListener( 'click', e => {
		e.preventDefault()

		sidebar.classList.add( 'show' )
	} )
}

const hideSidebar = () => {
	const btn = document.querySelector( '.sidebar-header-return' )

	if( ! btn || ! sidebar ) return

	btn.addEventListener( 'click', e => {
		e.preventDefault()

		sidebar.classList.remove( 'show' )
	} )

	sidebar.addEventListener( 'click', e => {
		const target = e.target

		if( target.className && target.classList.contains( 'sidebar' ) )
			sidebar.classList.remove( 'show' )
	} )
}