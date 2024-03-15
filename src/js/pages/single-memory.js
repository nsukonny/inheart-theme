import lightbox from 'lightbox2'
import { Loader } from '@googlemaps/js-api-loader'
import { showImagePopup, WINDOW_LG } from '../common/global'

let map

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	lightboxGalleryInit()
	textFollowsTheCursor()
	showHiddenVideos()

	readMoreMemory()
	showMoreMemories()
	showMemoryImagePopupOnClick()

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

			const rect = overlay.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const y = e.clientY - rect.top;

			text.style.top  = `${ y - 50 }px`
			text.style.left = `${ x - 47 }px`
		} )
	}

	if( overlaysPhoto.length ) overlaysPhoto.forEach( overlayOnMousemove )

	if( overlaysVideo.length ){
		overlaysVideo.forEach( overlayOnMousemove )
		overlaysVideo.forEach( overlay => {
			const
				video	= overlay.querySelector( 'video' ),
				poster	= overlay.querySelector( '.media-photo-overlay' )

			if( ! video ) return

			const onPlay = () => {
				if( overlay.classList.contains( 'playing' ) ) return

				overlay.classList.add( 'playing' )
				poster.remove()
				video.play()
				video.controls = true
			}

			overlay.addEventListener( 'click', onPlay )
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

if (!document.body.classList.contains('military'))
{
	window.addEventListener( 'scroll', () => {
		const
			topContent		= document.querySelector( '.single-memory-epitaph' ),
			contentBottom	= topContent.getBoundingClientRect().bottom,
			mapSection		= document.querySelector( '.single-memory-place' ),
			mapSectionBot	= mapSection.getBoundingClientRect().bottom

		if( contentBottom < 300 ){
			if( mapSectionBot < window.innerHeight + 100 ){
				if( document.body.classList.contains( 'theme-light' ) ) document.body.classList.remove( 'theme-light' )
			}else{
				if( ! document.body.classList.contains( 'theme-light' ) ) document.body.classList.add( 'theme-light' )
			}
		}else{
			if( document.body.classList.contains( 'theme-light' ) ) document.body.classList.remove( 'theme-light' )
		}
	} )
}

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

/**
 * Read more clicks.
 */
const readMoreMemory = () => {
	const readMores = document.querySelectorAll( '.single-memory-memories-excerpt .read-more' )

	if( ! readMores.length ) return

	readMores.forEach( item => {
		item.addEventListener( 'click', () => {
			const
				wrap	= item.closest( '.single-memory-memories-text' ),
				excerpt	= wrap.querySelector( '.single-memory-memories-excerpt' ),
				text	= wrap.querySelector( '.single-memory-memories-content' )

			if( ! excerpt || ! text ) return

			excerpt.remove()
			text.classList.remove( 'hidden' )
		} )
	} )
}

/**
 * Show more memories click.
 */
const showMoreMemories = () => {
	const btn = document.querySelector( '.single-memory-memories .show-more-posts' )

	if( ! btn ) return

	btn.addEventListener( 'click', e => {
		e.preventDefault()

		const hiddenMemories = btn.closest( '.single-memory-memories' ).querySelectorAll( '.single-memory-memories-item.hide-before-lg' )

		if( ! hiddenMemories.length ) return

		hiddenMemories.forEach( memory => {
			memory.classList.remove( 'hide-before-lg' )
			btn.closest( '.show-more-posts' ).remove()
		} )
	} )
}

/**
 * Show memory's large image in popup on click.
 */
const showMemoryImagePopupOnClick = () => {
	const memoriesImages = document.querySelectorAll( '.single-memory-memories-thumb img' )

	if( ! memoriesImages.length ) return

	memoriesImages.forEach(
		thumb => thumb.addEventListener( 'click', () => {
			showImagePopup( thumb, '.single-memory-memories-thumb' )
		} )
	)
}