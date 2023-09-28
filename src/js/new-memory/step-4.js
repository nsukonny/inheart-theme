import {
	ajaxUrl,
	BYTES_IN_MB,
	checkAjaxWorkingStatus,
	hideAreYouSurePopup,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showAreYouSurePopup,
	showNotification,
	hideElement,
	showElement
} from '../common/global'
import { allowNextStep, applyProgress, disallowNextStep } from './common'

const
	stepData = localStorage.getItem( 'ih-step-4' ) ?
	JSON.parse( localStorage.getItem( 'ih-step-4' ) ) :
	{ photos: [], videos: [], links: [{url: '', title: '', position: 0}] }
let videoDuration = 0

/**
 * Upload media photos.
 */
export const uploadMediaPhotos = () => {
	const
		droparea	= document.querySelector( '.droparea-photo' ),
		inputs		= document.querySelectorAll( '.file-photo' )
	let fileInstance

	['dragenter', 'dragover', 'dragleave', 'drop'].forEach( event => {
		document.addEventListener( event, evt => evt.preventDefault() )
	} );
	['dragenter', 'dragover'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.add( 'dragover' ) )
	} );
	['dragleave', 'drop'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.remove( 'dragover' ) )
	} )

	const handlePhotosUpload = e => {
		fileInstance = e.target.tagName === 'INPUT' ? [...e.target.files] : [...e.dataTransfer.files]

		if( ! fileInstance.length ) return

		// Loop through all files.
		for( let i = 0; i < fileInstance.length; i++ ){
			if( fileInstance[i].size > 50 * BYTES_IN_MB ){
				showNotification( `Не вдалося завантажити фото ${ fileInstance[i].name }`, 'error' )
				return false
			}

			if( fileInstance[i].type.startsWith( 'image/' ) )
				processingUploadMediaPhoto( fileInstance[i], i, droparea, fileInstance.length )
			else
				showNotification( `Тільки зображення - файл ${ fileInstance[i].name } не є зображенням`, 'warning' )
		}
	}

	droparea.addEventListener( 'drop', handlePhotosUpload )
	inputs.forEach( input => input.addEventListener( 'change', handlePhotosUpload ) )
}

/**
 * Processing photos uploading.
 *
 * @param file
 * @param index
 * @param droparea
 */
const processingUploadMediaPhoto = ( file, index, droparea, count ) => {
	if( ! file || ! droparea ) return

	const
		loader			= droparea.querySelector( '.droparea-loader' ),
		percentsValue	= loader.querySelector( '.droparea-loader-percents span' ),
		progress		= loader.querySelector( 'progress' ),
		cancel			= loader.querySelector( '.droparea-loader-cancel' ),
		inner			= droparea.querySelector( '.droparea-inner' ),
		imagesWrapper	= droparea.querySelector( '.droparea-images' ),
		dropareaData	= new FormData(),
		xhr				= new XMLHttpRequest()

	dropareaData.append( 'file', file )
	dropareaData.append( 'action', 'ih_ajax_upload_memory_photo' )

	xhr.upload.addEventListener( 'progress', e => {
		const
			bytesLoaded = e.loaded,
			bytesTotal	= e.total,
			percent		= parseInt( ( bytesLoaded / bytesTotal ) * 100 )

		inner.classList.add( 'hidden' )
		loader.classList.remove( 'hidden' )
		percentsValue.innerHTML = percent
		progress.value 			= percent
	} )

	cancel.addEventListener( 'click', () => {
		xhr.abort()
		progress.value = 0
		setTimeout( () => {
			loader.classList.add( 'hidden' )

			// No images - just show inner.
			if( ! document.querySelectorAll( '.droparea-img-loaded:not(.droparea-video-loaded)' ).length )
				inner.classList.remove( 'hidden' )
		}, 500 )
	} )

	xhr.open( 'POST', ajaxUrl, true )
	xhr.send( dropareaData )

	xhr.onload = () => {
		if( xhr.status == 200 ){
			const
				response	= JSON.parse( xhr.response ),
				data		= response.data
			let imageHTML	= ''

			if( data.success == 1 ){
				imageHTML = getPhotoHTML( data, file.name )

				imagesWrapper.querySelector( '.droparea-images-load' ).insertAdjacentHTML( 'beforebegin', imageHTML )
				showNotification( `Фото ${ file.name } успішно завантажено` )
				imagesWrapper.querySelector( `.droparea-img-delete[data-id="${ data.attachId }"]` )
					.addEventListener( 'click', e => showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e, droparea ) ) )
				checkIfStepIsReady()

				stepData.photos.push( data.attachId )
				localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )

				// Show images wrapper if the last images was loaded.
				if( index === count - 1 ){
					console.log('start')
					setTimeout( () => {
						loader.classList.add( 'hidden' )
						imagesWrapper.classList.remove( 'hidden' )
						console.log('end after 3s')
					}, 3000 )
				}
			}
		}else{
			// If no images loaded yet.
			if( ! document.querySelectorAll( '.droparea-img-loaded:not(.droparea-video-loaded)' ).length )
				inner.classList.remove( 'hidden' )
			else imagesWrapper.classList.remove( 'hidden' )

			showNotification( `Помилка ${ xhr.status }. Повторіть спробу пізніше.`, 'warning' )
		}
	}
}

/**
 * Get photo item HTML layout.
 *
 * @param {object}	data		Photo data object from the backend.
 * @param {string}	filename	Photo file name.
 * @returns {string}			Photo item HTML layout.
 */
const getPhotoHTML = ( data, filename ) => {
	const maskId = `mask0_${ Math.random() * 10000 }_${ Math.random() * 10000 }`

	return `<div class="droparea-img-loaded">
		<img src="${ data.url }" alt="${ filename }" />
		<div class="droparea-img-delete flex align-center justify-center" data-id="${ data.attachId }">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="${ maskId }" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
				</mask>
				<g mask="url(#${ maskId })">
					<rect width="24" height="24" fill="currentColor"/>
				</g>
			</svg>
		</div>
	</div>`
}

/**
 * Cancel are you sure popup.
 *
 * @param {Event} e
 */
const cancelCBb = e => {
	e.stopPropagation()
	hideAreYouSurePopup()
}

/**
 * Apply are you sure popup.
 *
 * @param {Event} e
 * @param {HTMLObjectElement} droparea
 */
const applyCBb = ( e, droparea ) => {
	if( checkAjaxWorkingStatus() ) return

	setAjaxWorkingStatus( true )

	const
		inner			= droparea.querySelector( '.droparea-inner' ),
		imagesWrapper	= droparea.querySelector( '.droparea-images' ),
		id			= e.target.closest( '.droparea-img-delete' ).dataset.id,
		formData	= new FormData()

	formData.append( 'action', 'ih_ajax_delete_memory_photo' )
	formData.append( 'id', id )

	ihAjaxRequest( formData ).then( res => {
		if( res ){
			switch( res.success ){
				case true:
					showNotification( res.data.msg )
					e.target.closest( '.droparea-img-loaded' ).remove()

					// If there are no more images loaded.
					if( ! imagesWrapper.querySelectorAll( '.droparea-img-loaded:not(.droparea-video-loaded)' ).length ){
						imagesWrapper.classList.add( 'hidden' )
						inner.classList.remove( 'hidden' )
					}

					checkIfStepIsReady()

					stepData.photos.forEach( ( photoId, i ) => {
						if( photoId == id ) stepData.photos.splice( i, 1 )
					} )

					localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )
					break

				case false:
					showNotification( res.data.msg, 'error' )
					break
			}
		}

		setAjaxWorkingStatus( false )
		hideAreYouSurePopup()
	} )
}

/**
 * Set click listener by default to photo delete buttons.
 */
export const setDefaultDeletePhoto = () => {
	const
		droparea	= document.querySelector( '.droparea-photo' ),
		buttons		= droparea.querySelectorAll( '.droparea-img-delete' )

	if( ! buttons.length ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', e => {
			showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e, droparea ) )
		} )
	} )
}

/**
 * Set click listener by default to video delete buttons.
 */
export const setDefaultDeleteVideo = () => {
	const
		droparea	= document.querySelector( '.droparea-video' ),
		buttons		= droparea.querySelectorAll( '.droparea-img-delete' )

	if( ! buttons.length ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', e => {
			showAreYouSurePopup( e.target, cancelCBb, () => applyVideoCb( e ) )
		} )
	} )
}

/**
 * Upload media video.
 */
export const uploadMediaVideo = () => {
	const
		droparea	= document.querySelector( '.droparea-video' ),
		inputs		= document.querySelectorAll( '.file-video' )
	let fileInstance

	['dragenter', 'dragover', 'dragleave', 'drop'].forEach( event => {
		document.addEventListener( event, evt => evt.preventDefault() )
	} );
	['dragenter', 'dragover'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.add( 'dragover' ) )
	} );
	['dragleave', 'drop'].forEach( event => {
		droparea.addEventListener( event, () => droparea.classList.remove( 'dragover' ) )
	} )

	const handleVideoUpload = e => {
		fileInstance = e.target.tagName === 'INPUT' ? [...e.target.files] : [...e.dataTransfer.files]

		if( ! fileInstance.length || checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		// Create video element to check duration.
		const video = document.createElement( 'video' )

		video.src = URL.createObjectURL( fileInstance[0] )
		video.ondurationchange = () => videoDuration = getPrettyVideoDuration( video.duration )

		if( fileInstance[0].size > 1024 * BYTES_IN_MB ){
			showNotification( 'Не більше 1 гб', 'error' )
			return false
		}

		if( fileInstance[0].type.startsWith( 'video/' ) ) processingUploadMediaVideo( fileInstance[0], droparea )
		else showNotification( `Тільки відеофайли - файл ${ fileInstance[0].name } не відео`, 'error' )
	}

	droparea.addEventListener( 'drop', handleVideoUpload )
	inputs.forEach( input => input.addEventListener( 'change', handleVideoUpload ) )
}

/**
 * Pretty video duration output string.
 *
 * @param durationInSeconds
 * @returns {string}
 */
const getPrettyVideoDuration = durationInSeconds => {
	durationInSeconds = Math.floor( durationInSeconds )

	if( ! durationInSeconds || durationInSeconds < 1 ) return

	if( durationInSeconds < 60 ) return `00:00:${ durationInSeconds }`

	let hours	= Math.floor( durationInSeconds / ( 60 * 60 ) ),
		minutes	= Math.floor( ( durationInSeconds / 60 ) % 60 ),
		seconds	= Math.floor( durationInSeconds % 60 )

	hours	= hours < 10 ? `0${ hours }` : hours
	minutes	= minutes < 10 ? `0${ minutes }` : minutes
	seconds	= seconds < 10 ? `0${ seconds }` : seconds

	return `${ hours }:${ minutes }:${ seconds }`
}

/**
 * Processing photos uploading.
 *
 * @param file
 * @param droparea
 */
const processingUploadMediaVideo = ( file, droparea ) => {
	if( ! file || ! droparea ) return

	const
		loader			= droparea.querySelector( '.droparea-loader' ),
		percentsValue	= loader.querySelector( '.droparea-loader-percents span' ),
		progress		= loader.querySelector( 'progress' ),
		cancel			= loader.querySelector( '.droparea-loader-cancel' ),
		inner			= droparea.querySelector( '.droparea-inner' ),
		videosWrapper	= droparea.querySelector( '.droparea-videos' ),
		thumbsWrapper	= droparea.querySelector( '.droparea-thumbs' ),
		dropareaData	= new FormData(),
		xhr				= new XMLHttpRequest()

	dropareaData.append( 'file', file )
	dropareaData.append( 'action', 'ih_ajax_upload_memory_video' )

	xhr.upload.addEventListener( 'progress', e => {
		const
			bytesLoaded = e.loaded,
			bytesTotal	= e.total,
			percent		= parseInt( ( bytesLoaded / bytesTotal ) * 100 )

		inner.classList.add( 'hidden' )
		loader.classList.remove( 'hidden' )
		percentsValue.innerHTML = percent
		progress.value 			= percent
	} )

	cancel.addEventListener( 'click', () => {
		xhr.abort()
		progress.value = 0
		setTimeout( () => {
			inner.classList.remove( 'hidden' )
			loader.classList.add( 'hidden' )
		}, 500 )
	} )

	xhr.open( 'POST', ajaxUrl, true )
	xhr.send( dropareaData )

	xhr.onload = () => {
		loader.classList.add( 'hidden' )

		if( xhr.status == 200 ){
			const
				response	= JSON.parse( xhr.response ),
				data		= response.data

			if( data.success == 1 ){
				// If there are already loaded videos.
				if( document.querySelectorAll( '.droparea-video-loaded' ).length ){
					const thumbsWrapperCloned = thumbsWrapper.cloneNode( true )

					document.querySelector( '.droparea-videos-load' ).append( thumbsWrapperCloned )
					uploadCustomPoster()
					hideElement( document.querySelector( '.droparea-videos-load-inner' ) )
					showElement( thumbsWrapperCloned )
					thumbsWrapper.remove()
					showScreenshots( thumbsWrapperCloned.querySelector( '.droparea-thumbs-list' ), data.shots )
				}else{
					thumbsWrapper.classList.remove( 'hidden' )
					showScreenshots( thumbsWrapper.querySelector( '.droparea-thumbs-list' ), data.shots )
				}

				showNotification( `Файл ${ file.name } успішно завантажено` )
			}
		}else{
			// If no videos loaded yet.
			if( ! document.querySelectorAll( '.droparea-video-loaded' ).length ) inner.classList.remove( 'hidden' )
			else videosWrapper.classList.remove( 'hidden' )

			showNotification( `Помилка ${ xhr.status }. Повторіть спробу пізніше.`, 'warning' )
		}

		setAjaxWorkingStatus( false )
	}
}

/**
 * Upload video links.
 */
export const videoLinkInput = () => {
	const inputs = document.querySelectorAll( 'input[name="video-link"]' )

	if( ! inputs.length ) return

	inputs.forEach( input => {
		input.addEventListener( 'change', onVideoLinkInput )
		input.addEventListener( 'keyup', onVideoLinkInput )
		input.addEventListener( 'blur', onVideoLinkInput )
	} )
}

/**
 * Video link input change handler.
 *
 * @param {Event} e
 */
const onVideoLinkInput = e => {
	const
		target	= e.target,
		value	= target.value,
		button	= target.closest( '.video-link-row' ).querySelector( '.video-link-upload' )

	if( value ) button.disabled = false
	else button.disabled = true
}

/**
 * Upload video link.
 */
export const uploadVideoLink = () => {
	const buttons = document.querySelectorAll( '.video-link-upload' )

	if( ! buttons.length ) return

	buttons.forEach( btn => {
		btn.addEventListener( 'click', e => {
			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )

			const
				input		= e.target.closest( '.video-link-row' ).querySelector( 'input' ),
				link		= input.value,
				formData	= new FormData()

			if( ! link ) return

			formData.append( 'action', 'ih_ajax_upload_video_link' )
			formData.append( 'link', link )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							input.value 	= ''
							btn.disabled 	= true
							showNotification( res.data.msg )
							break

						case false:
							showNotification( res.data.msg, 'error' )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		} )
	} )
}

/**
 * Return shorter filename.
 *
 * @param {string} filename
 * @returns {string}
 */
const getFilename = filename => {
	const extension = /(?:\.([^.]+))?$/.exec( filename )[1]

	filename = filename.replace( /[_\-]/g, ' ' )

	return filename.substring( 0, filename.length - extension.length - 1 )
}

/**
 * Show screenshots from the uploaded video.
 *
 * @param {HTMLObjectElement}	container	Where to append screenshots.
 * @param {string}				shots		JSON string from the server with the screenshots' data.
 */
const showScreenshots = ( container, shots ) => {
	shots = JSON.parse( shots )

	if( ! container || ! shots.length ) return

	shots.forEach( shot => {
		const shotHTML = `<div class="droparea-thumb">
			<img class="droparea-thumb-img" src="${ shot }" alt="" />
		</div>`
		container.insertAdjacentHTML( 'beforeend', shotHTML )
	} )
}

/**
 * Make selected screenshot active.
 */
export const selectScreenshot = () => {
	const shotsList = document.querySelector( '.droparea-video' )

	if( ! shotsList ) return

	shotsList.addEventListener( 'click', e => {
		const
			target		= e.target,
			prevActive	= document.querySelector( '.droparea-thumb.active' )

		if( ! target.closest( '.droparea-thumb' ) ) return

		if( prevActive ) prevActive.classList.remove( 'active' )

		target.closest( '.droparea-thumb' ).classList.add( 'active' )
	} )
}

/**
 * Save selected video poster.
 */
export const saveVideoPoster = () => {
	const area = document.querySelector( '.droparea-video' )

	if( ! area ) return

	area.addEventListener( 'click', e => {
		const
			target		= e.target,
			activeShot = document.querySelector( '.droparea-thumb.active' )

		if(
			! activeShot || checkAjaxWorkingStatus() ||
			! target.className || ! target.classList.contains( 'droparea-thumbs-save' )
		) return

		setAjaxWorkingStatus( true )

		const
			src			= activeShot.querySelector( '.droparea-thumb-img' ).src,
			formData	= new FormData()

		formData.append( 'action', 'ih_ajax_set_poster' )
		formData.append( 'src', src )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNotification( res.data.msg )
						hideElement( document.querySelector( '.droparea-thumbs' ) )
						clearThumbsList()
						outputVideoWithPoster( res.data, src )

						if( document.querySelectorAll( '.droparea-video-loaded' ).length > 1 )
							showElement( document.querySelector( '.droparea-videos-load-inner' ) )
						else
							showElement( document.querySelector( '.droparea-videos' ) )
						break

					case false:
						showNotification( res.data.msg, 'error' )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}

/**
 * Remove all video thumbs (posters).
 */
const clearThumbsList = () => {
	const thumbs = document.querySelectorAll( '.droparea-thumb' )

	if( ! thumbs.length ) return

	thumbs.forEach( thumb => thumb.remove() )
}

/**
 * Get video item HTML layout.
 *
 * @param {object}	videoData	Video data object.
 * @param {string}	poster		Video poster URL.
 * @returns {string}			Video item HTML layout.
 */
const getVideoHTML = ( videoData, poster ) => {
	const maskId = `mask0_${ Math.random() * 10000 }_${ Math.random() * 10000 }`

	return `<div class="droparea-img-loaded droparea-video-loaded">
		<div class="droparea-video-wrapper">
			<video src="${ videoData.url }" poster="${ poster }"></video>
			<div
				class="droparea-img-delete flex align-center justify-center"
				data-id="${ videoData.attachId }"
				data-is-video="1"
			>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="${ maskId }" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
					</mask>
					<g mask="url(#${ maskId })">
						<rect width="24" height="24" fill="currentColor"/>
					</g>
				</svg>
			</div>
		</div>
		<div class="droparea-video-title">${ getFilename( videoData.filename ) }</div>
		<div class="droparea-video-duration">${ videoDuration }</div>
	</div>`
}

/**
 * Output HTML5 video after poster is set.
 *
 * @param {object} videoData
 * @param {string} poster
 */
const outputVideoWithPoster = ( videoData, poster ) => {
	const
		videosWrapper	= document.querySelector( '.droparea-videos' ),
		videoHTML		= getVideoHTML( videoData, poster )

	videosWrapper.querySelector( '.droparea-videos-load' ).insertAdjacentHTML( 'beforebegin', videoHTML )
	videosWrapper
		.querySelector( `.droparea-img-delete[data-id="${ videoData.attachId }"]` )
		.addEventListener( 'click', e => {
			showAreYouSurePopup( e.target, cancelCBb, () => applyVideoCb( e ), 'Дійсно видалити відео?' )
		} )

	stepData.videos.push( { id: videoData.attachId, poster: videoData.posterId } )
	localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )
}

/**
 * Delete video.
 *
 * @param {Event} e
 */
const applyVideoCb = e => {
	if( checkAjaxWorkingStatus() ) return

	setAjaxWorkingStatus( true )

	const
		id				= e.target.closest( '.droparea-img-delete' ).dataset.id,
		isVideo			= e.target.closest( '.droparea-img-delete' ).dataset.isVideo,
		formData		= new FormData(),
		videosWrapper	= document.querySelector( '.droparea-videos' )

	formData.append( 'action', 'ih_ajax_delete_memory_photo' )
	formData.append( 'id', id )
	formData.append( 'video', isVideo )

	ihAjaxRequest( formData ).then( res => {
		if( res ){
			switch( res.success ){
				case true:
					e.target.closest( '.droparea-video-loaded' ).remove()

					// If there are no more video loaded.
					if( ! videosWrapper.querySelectorAll( '.droparea-video-loaded' ).length ){
						hideElement( videosWrapper )
						showElement( document.querySelector( '.droparea-video .droparea-inner' ) )

						const
							thumbsWrapper		= document.querySelector( '.droparea-thumbs' ),
							thumbsWrapperCloned	= thumbsWrapper.cloneNode( true )

						document.querySelector( '.droparea-video' ).append( thumbsWrapperCloned )
						uploadCustomPoster()
						thumbsWrapper.remove()
					}

					stepData.videos.forEach( ( video, i ) => {
						if( video.id == id ) stepData.videos.splice( i, 1 )
					} )

					localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )
					break

				case false:
					showNotification( res.data.msg, 'error' )
					break
			}
		}

		setAjaxWorkingStatus( false )
		hideAreYouSurePopup()
	} )
}

/**
 * Upload custom poster from device.
 */
export const uploadCustomPoster = () => {
	const input = document.querySelector( '.file-load-poster' )

	if( ! input ) return

	input.removeEventListener( 'change', onCustomPosterInputChange )
	input.addEventListener( 'change', onCustomPosterInputChange )
}

/**
 * Upload custom poster input change handler.
 *
 * @param {Event} e
 * @returns {boolean}
 */
const onCustomPosterInputChange = e => {
	const
		fileInstance	= [...e.target.files],
		loadArea		= document.querySelector( '.droparea-thumbs-list' )

	if( ! fileInstance.length ) return

	const file = fileInstance[0]

	if( file.size > 5 * BYTES_IN_MB ){
		showNotification( 'Розмір повинен бути меньше 5 мб', 'error' )
		return false
	}

	if( file.type.startsWith( 'image/' ) ) processingUploadCustomPoster( file, loadArea )
	else showNotification( `Тільки зображення - файл ${ file.name } не є зображенням`, 'error' )
}

/**
 * Upload custom poster handler.
 *
 * @param file
 * @param droparea
 */
const processingUploadCustomPoster = ( file, droparea ) => {
	if( ! file || ! droparea ) return

	const dropareaData = new FormData()

	if( checkAjaxWorkingStatus() ) return

	setAjaxWorkingStatus( true )

	dropareaData.append( 'action', 'ih_ajax_upload_custom_poster' )
	dropareaData.append( 'file', file )

	ihAjaxRequest( dropareaData ).then( res => {
		if( res ){
			switch( res.success ){
				case true:
					const imageHTML = `<div class="droparea-thumb">
						<img class="droparea-thumb-img" src="${ res.data.url }" alt="${ file.name }" />
					</div>`

					droparea.insertAdjacentHTML( 'beforeend', imageHTML )
					showNotification( `Обкладинка ${ file.name } успішно завантажено` )
					break

				case false:
					showNotification( res.data.msg, 'error' )
					break
			}
		}

		setAjaxWorkingStatus( false )
	} )
}

/**
 * Listen to external links fields.
 */
export const externalLinksFieldsInput = () => {
	const fields = document.querySelectorAll( '.step-media-link' )

	if( ! fields.length ) return

	fields.forEach( field => {
		const
			urlField	= field.querySelector( 'input[id^="media-link-"]' ),
			urlTitle	= field.querySelector( 'input[id^="media-name-link-"]' )

		if( ! urlField || ! urlTitle ) return

		urlField.removeEventListener( 'keyup', onExternalLinkFieldInput )
		urlField.removeEventListener( 'change', onExternalLinkFieldInput )
		urlField.removeEventListener( 'blur', onExternalLinkFieldInput )
		urlField.addEventListener( 'change', onExternalLinkFieldInput )
		urlField.addEventListener( 'keyup', onExternalLinkFieldInput )
		urlField.addEventListener( 'blur', onExternalLinkFieldInput )

		urlTitle.removeEventListener( 'keyup', onExternalLinkFieldInput )
		urlTitle.removeEventListener( 'change', onExternalLinkFieldInput )
		urlTitle.removeEventListener( 'blur', onExternalLinkFieldInput )
		urlTitle.addEventListener( 'change', onExternalLinkFieldInput )
		urlTitle.addEventListener( 'keyup', onExternalLinkFieldInput )
		urlTitle.addEventListener( 'blur', onExternalLinkFieldInput )
	} )
}

/**
 * Handle external links input.
 *
 * @param {Event} e
 */
const onExternalLinkFieldInput = e => {
	const
		target		= e.target,
		value		= target.value,
		label		= target.closest( 'label' ),
		link		= target.closest( '.step-media-link' ),
		index		= link.dataset.id,
		type		= target.dataset.type === 'url' ? 'url' : 'title',
		secondField	= type === 'url' ?
			link.querySelector( 'input[data-type="title"]' ) :
			link.querySelector( 'input[data-type="url"]' ),
		addLinkBtn	= document.querySelector( '.media-links-add' )

	if( ! value ) label.classList.add( 'error' )
	else label.classList.remove( 'error' )

	// Both fields are set - show buttons.
	if( value && secondField.value ){
		link.classList.add( 'filled' )
		addLinkBtn.classList.remove( 'hidden' )
	}else{
		if( document.querySelectorAll( '.step-media-link' ).length === 1 ){
			link.classList.remove( 'filled' )
			addLinkBtn.classList.add( 'hidden' )
		}
	}

	stepData.links[index][type] = value
	localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )
}

/**
 * Get External link HTML structure.
 *
 * @param index
 */
const getExternalLinkHTML = ( index = 0 ) => {
	const linkClass = index ? ' filled' : ''

	return `<div class="step-media-link flex flex-wrap${ linkClass }" data-id="${ index }">
			<label for="media-link-${ index }" class="label dark half">
				<span class="label-text">Посилання</span>
				<input
					id="media-link-${ index }"
					name="media-link-${ index }"
					type="text"
					data-type="url"
					placeholder="Додати посилання"
					required
				/>
			</label>
			<label for="media-name-link-${ index }" class="label dark half end">
				<span class="label-text">Назва посилання</span>
				<input
					id="media-name-link-${ index }"
					name="media-name-link-${ index }"
					type="text"
					data-type="title"
					placeholder="Додати посилання"
					required
				/>
			</label>
			<button class="media-link-delete" title="Видалити посилання" type="button">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask_link_${ index }" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
					</mask>
					<g mask="url(#mask_link_${ index })">
						<rect width="24" height="24" fill="currentColor"/>
					</g>
				</svg>
			</button>
		</div>`
}

/**
 * Add external link.
 */
export const externalLinkAdd = () => {
	const button = document.querySelector( '.media-links-add' )

	if( ! button ) return

	button.addEventListener( 'click', () => {
		const id = document.querySelectorAll( '.step-media-link' ).length

		document.querySelector( '.step-media-links' ).insertAdjacentHTML( 'beforeend', getExternalLinkHTML( id ) )
		externalLinksFieldsInput()
		externalLinkDelete()
		checkStep4()
	} )
}

/**
 * Delete external link.
 */
export const externalLinkDelete = () => {
	const buttons = document.querySelectorAll( '.media-link-delete' )

	if( ! buttons.length ) return

	buttons.forEach( btn => {
		btn.removeEventListener( 'click', onExternalLinkDelete )
		btn.addEventListener( 'click', onExternalLinkDelete )
	} )
}

/**
 * Handle external link deletion.
 */
const onExternalLinkDelete = e => {
	const link = e.target.closest( '.step-media-link' )

	stepData.links.splice( link.dataset.id, 1 )
	link.remove()
	localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )

	// No more links
	if( ! document.querySelectorAll( '.step-media-link' ).length ){
		document.querySelector( '.step-media-links' ).innerHTML = getExternalLinkHTML()
		externalLinksFieldsInput()
		externalLinkDelete()
		checkStep4()
		document.querySelector( '.media-links-add' ).classList.add( 'hidden' )
	}
}

/**
 * Check if step 4 is ready.
 * Update local storage if it was cleared.
 *
 * @returns {boolean}
 */
export const checkStep4 = () => {
	const
		photos	= document.querySelectorAll( '.droparea-img-loaded:not(.droparea-video-loaded)' ),
		videos	= document.querySelectorAll( '.droparea-video-loaded' ),
		links	= document.querySelectorAll( '.step-media-link' )

	if( photos.length < 1 ) return false

	stepData.photos = []
	photos.forEach( photo => stepData.photos.push( photo.querySelector( '.droparea-img-delete' ).dataset.id ) )

	if( videos.length ){
		stepData.videos = []
		videos.forEach( video => {
			const
				videoTag	= video.querySelector( 'video' ),
				poster		= videoTag ? videoTag.dataset.posterId : ''

			stepData.videos.push( { poster: poster || '', id: video.querySelector( '.droparea-img-delete' ).dataset.id } )
		} )
	}

	if( links.length ){
		stepData.links = []
		links.forEach( field => {
			const
				urlField	= field.querySelector( 'input[id^="media-link-"]' ),
				urlTitle	= field.querySelector( 'input[id^="media-name-link-"]' )

			if( ! urlField || ! urlTitle ) return

			stepData.links.push( {
				url		: urlField.value,
				title	: urlTitle.value,
				position: field.dataset.id
			} )
		} )
	}

	localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )

	return true
}

/**
 * Allow or disallow next step.
 */
const checkIfStepIsReady = () => {
	if( checkStep4() ){
		applyProgress( 4 )
		allowNextStep( 5 )
	}else{
		disallowNextStep()
		applyProgress( 4, 0 )
	}
}