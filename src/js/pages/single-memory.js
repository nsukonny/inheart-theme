import lightbox from 'lightbox2'
import { Loader } from '@googlemaps/js-api-loader'

let map, infowindows = []

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	lightboxGalleryInit()
	textFollowsTheCursor()
	showHiddenVideos()

	const
		mapEl	= document.querySelector( '#map' ),
		apiKey	= mapEl ? mapEl.dataset.key : null

	if( ! mapEl ) return

	mapEl.removeAttribute( 'data-key' )
	initGoogleMap( mapEl, apiKey )
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

window.addEventListener( 'scroll', () => {
	const
		topContent		= document.querySelector( '.single-memory-top-inner' ),
		contentBottom	= topContent.getBoundingClientRect().bottom,
		mapSection		= document.querySelector( '.single-memory-place' ),
		mapSectionBot	= mapSection.getBoundingClientRect().bottom

	if( contentBottom < -100 ){
		if( mapSectionBot < window.innerHeight + 100 ){
			if( document.body.classList.contains( 'theme-light' ) ) document.body.classList.remove( 'theme-light' )
		}else{
			if( ! document.body.classList.contains( 'theme-light' ) ) document.body.classList.add( 'theme-light' )
		}
	}else{
		if( document.body.classList.contains( 'theme-light' ) ) document.body.classList.remove( 'theme-light' )
	}
} )

const initGoogleMap = async ( mapEl, apiKey ) => {
	const
		lat		= parseFloat( mapEl.dataset.lat ),
		lng		= parseFloat( mapEl.dataset.long ),
		center	= { lat, lng },
		loader	= new Loader( {
			apiKey: apiKey,
			version: 'weekly',
		} )

	loader.load().then( async () => {
		const styledMapType = new google.maps.StyledMapType( [
				{
					"featureType": "administrative",
					"elementType": "geometry",
					"stylers": [
						{
							"color": "#a7a7a7",
						},
					],
				},
				{
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"color": "#000000",
						},
					],
				},
				{
					"featureType": "landscape",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"color": "#efefef",
						},
					],
				},
				{
					"featureType": "poi",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"color": "#dadada",
						},
					],
				},
				{
					"featureType": "poi",
					"elementType": "labels",
					"stylers": [
						{
							"visibility": "off",
						},
					],
				},
				{
					"featureType": "poi",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off",
						},
					],
				},
				{
					"featureType": "road",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000",
						},
					],
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off",
						},
					],
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff",
						},
					],
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"color": "#b3b3b3",
						},
					],
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff",
						},
					],
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#d6d6d6",
						},
					],
				},
				{
					"featureType": "road.local",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on",
						},
						{
							"color": "#ffffff",
						},
						{
							"weight": 1.8,
						},
					],
				},
				{
					"featureType": "road.local",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#d7d7d7",
						},
					],
				},
				{
					"featureType": "transit",
					"elementType": "all",
					"stylers": [
						{
							"color": "#808080",
						},
						{
							"visibility": "off",
						},
					],
				},
				{
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#007aea",
						},
					],
				},
				{
					"featureType": "water",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000",
						},
					],
				},
			], { name: "Styled Map" } )

		map = new google.maps.Map( mapEl, {
			center,
			zoom: 15,
			disableDefaultUI		: true,
			zoomControl				: true,
			scrollwheel				: false,
			disableDoubleClickZoom	: true,
			mapTypeControlOptions	: {
				mapTypeIds: ["roadmap", "satellite", "hybrid", "terrain", "styled_map"],
			},
		} )

		map.mapTypes.set( 'styled_map', styledMapType )
		map.setMapTypeId( 'styled_map' )
	} )
}