import lightbox from 'lightbox2'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	lightboxGalleryInit()
	textFollowsTheCursor()
	showHiddenVideos()
} )

const lightboxGalleryInit = () => {
	const moreButton = document.querySelector( '.media-photos-more button' )

	lightbox.option( {
		'resizeDuration': 200,
		'wrapAround': true
	} );

	if( moreButton ) moreButton.addEventListener( 'click', () => {
		document.querySelector( '.media-photo' ).click()
	} )
}

const textFollowsTheCursor = () => {
	const
		overlaysPhoto	= document.querySelectorAll( '.media-photo' ),
		overlaysVideo	= document.querySelectorAll( '.media-video-top' ),
		overlaysLink	= document.querySelectorAll( '.media-link' )

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

	if( overlaysLink.length ) overlaysLink.forEach( overlayOnMousemove )
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

window.initMap = function(){
	const styledMapType = new google.maps.StyledMapType( { name: "Styled Map" } )
	const mapArgs = {
		disableDefaultUI		: true,
		mapTypeId				: google.maps.MapTypeId.ROADMAP,
		zoomControl				: true,
		scrollwheel				: false,
		disableDoubleClickZoom	: true,
		mapTypeControlOptions	: {
			mapTypeIds: ["roadmap", "satellite", "hybrid", "terrain", "styled_map"]
		}
	}
	const map = new google.maps.Map( document.getElementById( 'map' ), mapArgs )

	// Associate the styled map with the MapTypeId and set it to display.
	map.mapTypes.set( 'styled_map', styledMapType )
	map.setMapTypeId( 'styled_map' )

	// Add markers.
	map.markers = [initMarker( map )]

	// Set map's zoom and center.
	map.setZoom( 8 )
	map.setCenter( map.markers[0].getPosition() )

	return map
}

const initMarker = map => {
	const
		lat = document.querySelector( '#map' ).dataset.lat,
		lng = document.querySelector( '#map' ).dataset.long,
		latLng = {
			lat: parseFloat( lat ),
			lng: parseFloat( lng )
		}

	// Create marker instance.
	return new google.maps.Marker( {
		id			: 'marker',
		position	: latLng,
		map			: map,
		is_active	: true
	} )
}

window.addEventListener( 'scroll', () => {
	const
		topContent		= document.querySelector( '.single-memory-top-inner' ),
		contentBottom	= topContent.getBoundingClientRect().bottom,
		mapSection		= document.querySelector( '.single-memory-place' ),
		mapSectionTop	= mapSection.getBoundingClientRect().top

	if( window.scrollY > 0 && ! mapSection.classList.contains( 'loaded' ) ){
		const script	= document.createElement( 'script' )

		mapSection.classList.add( 'loaded' )
		script.src		= 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAw82kHiqky3qjTcbYY3wG_3FHrBirrbNc&callback=initMap'
		script.async	= true
		document.head.appendChild( script )
	}

	if( contentBottom < -100 ){
		if( mapSectionTop < window.innerHeight ){
			if( document.body.classList.contains( 'light' ) ) document.body.classList.remove( 'light' )
		}else{
			if( ! document.body.classList.contains( 'light' ) ) document.body.classList.add( 'light' )
		}
	}else{
		if( document.body.classList.contains( 'light' ) ) document.body.classList.remove( 'light' )
	}
} )