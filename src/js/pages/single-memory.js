import lightbox from 'lightbox2'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	lightboxGalleryInit()
	textFollowsTheCursor()
	showHiddenVideos()
} )

const lightboxGalleryInit = () => {
	lightbox.option( {
		'resizeDuration': 200,
		'wrapAround': true
	} )

	document.querySelector( '.media-photos-more button' ).addEventListener( 'click', () => {
		document.querySelector( '.media-photo' ).click()
	} )
}

const textFollowsTheCursor = () => {
	const
		overlaysPhoto	= document.querySelectorAll( '.media-photo' ),
		overlaysVideo	= document.querySelectorAll( '.media-video-top' )

	const overlayOnMousemove = overlay => {
		const text = overlay.querySelector( '.media-photo-cursor-text' )

		if( ! text ) return

		overlay.addEventListener( 'mousemove', e => {
			const
				x	= e.pageX - overlay.offsetLeft,
				y	= e.pageY - overlay.offsetTop

			text.style.top  = `${ y - 50 }px`
			text.style.left = `${ x - 47 }px`
		} )
	}

	if( overlaysPhoto.length ) overlaysPhoto.forEach( overlayOnMousemove )

	if( overlaysVideo.length ){
		overlaysVideo.forEach( overlayOnMousemove )
		overlaysVideo.forEach( overlay => {
			const video = overlay.querySelector( 'video' )

			if( ! video ) return

			const onPlay = () => {
				if( ! overlay.classList.contains( 'playing' ) ){
					overlay.classList.add( 'playing' )
					video.play()
					video.controls = true
				}
			}

			overlay.addEventListener( 'click', onPlay )
			video.addEventListener( 'play', onPlay )
			video.addEventListener( 'pause', () => {
				if( overlay.classList.contains( 'playing' ) ){
					overlay.classList.remove( 'playing' )
					video.removeAttribute( 'controls' )
				}
			} )
		} )
	}
}

const showHiddenVideos = () => {
	const
		button	= document.querySelector( '.media-video-more' ),
		videos	= document.querySelectorAll( '.media-video.hidden' )

	if( ! button || ! videos.length ) return

	button.addEventListener( 'click', () => {
		videos.forEach( video => video.classList.remove( 'hidden' ) )
		button.remove()
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