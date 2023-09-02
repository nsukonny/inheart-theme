import lightbox from 'lightbox2'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	textFollowsTheCursor()

	lightbox.option( {
		'resizeDuration': 200,
		'wrapAround': true
	} )

	document.querySelector( '.media-photos-more button' ).addEventListener( 'click', () => {
		document.querySelector( '.media-photo' ).click()
	} )
} )

const textFollowsTheCursor = () => {
	const overlays = document.querySelectorAll( '.media-photo' )

	if( ! overlays.length ) return

	overlays.forEach( overlay => {
		const text = overlay.querySelector( '.media-photo-cursor-text' )

		if( ! text ) return

		overlay.addEventListener( 'mousemove', e => {
			const
				x	= e.pageX - overlay.offsetLeft,
				y	= e.pageY - overlay.offsetTop

			text.style.top  = `${ y - 50 }px`
			text.style.left = `${ x - 47 }px`
		} )
	} )
}

window.addEventListener( 'scroll', () => {
	const
		topContent		= document.querySelector( '.single-memory-top-inner' ),
		contentBottom	= topContent.getBoundingClientRect().bottom

	if( contentBottom < -100 ){
		if( ! document.body.classList.contains( 'light' ) ) document.body.classList.add( 'light' )
	}else{
		if( document.body.classList.contains( 'light' ) ) document.body.classList.remove( 'light' )
	}
} )