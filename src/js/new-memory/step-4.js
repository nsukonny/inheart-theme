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

const stepData = localStorage.getItem( 'ih-step-4' ) ? JSON.parse( localStorage.getItem( 'ih-step-4' ) ) : []
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
		fileInstance.forEach( file => {
			if( file.size > 50 * BYTES_IN_MB ){
				console.error( `Не вдалося завантажити фото ${ file.name }` )
				return false
			}

			if( file.type.startsWith( 'image/' ) ) processingUploadMediaPhoto( file, droparea )
			else showNotification( `Тільки зображення - файл ${ file.name } не є зображенням`, 'warning' )
		} )
	}

	droparea.addEventListener( 'drop', handlePhotosUpload )
	inputs.forEach( input => input.addEventListener( 'change', handlePhotosUpload ) )
}

/**
 * Processing photos uploading.
 *
 * @param file
 * @param droparea
 */
const processingUploadMediaPhoto = ( file, droparea ) => {
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
			if( ! document.querySelectorAll( '.droparea-img-loaded' ).length )
				inner.classList.remove( 'hidden' )
		}, 500 )
	} )

	xhr.open( 'POST', ajaxUrl, true )
	xhr.send( dropareaData )

	xhr.onload = () => {
		loader.classList.add( 'hidden' )

		if( xhr.status == 200 ){
			const
				response	= JSON.parse( xhr.response ),
				data		= response.data,
				maskId		= `mask0_${ Math.random() * 10000 }_${ Math.random() * 10000 }`
			let imageHTML	= ''

			if( data.success == 1 ){
				imagesWrapper.classList.remove( 'hidden' )
				imageHTML = `<div class="droparea-img-loaded">
					<img src="${ data.url }" alt="${ file.name }" />
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

				const applyCBb = e => {
					if( checkAjaxWorkingStatus() ) return

					setAjaxWorkingStatus( true )

					const
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
									if( ! imagesWrapper.querySelectorAll( '.droparea-img-loaded' ).length ){
										imagesWrapper.classList.add( 'hidden' )
										inner.classList.remove( 'hidden' )
									}

									checkIfStepIsReady()

									stepData.forEach( ( data, i ) => {
										if( data.id == id ) stepData.splice( i, 1 )
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

				imagesWrapper.querySelector( '.droparea-images-load' ).insertAdjacentHTML( 'beforebegin', imageHTML )
				showNotification( `Фото ${ file.name } успішно завантажено` )
				imagesWrapper.querySelector( `.droparea-img-delete[data-id="${ data.attachId }"]` )
					.addEventListener( 'click', e => showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e ) ) )
				checkIfStepIsReady()

				stepData.push( { id: data.attachId } )
				localStorage.setItem( 'ih-step-4', JSON.stringify( stepData ) )
			}
		}else{
			// If no images loaded yet.
			if( ! document.querySelectorAll( '.droparea-img-loaded' ).length ) inner.classList.remove( 'hidden' )
			else imagesWrapper.classList.remove( 'hidden' )

			showNotification( `Помилка ${ xhr.status }. Повторіть спробу пізніше.`, 'warning' )
		}
	}
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

		if( ! fileInstance.length ) return

		// Create video element to check duration.
		const video = document.createElement( 'video' )

		video.src = URL.createObjectURL( fileInstance[0] )
		video.ondurationchange = () => videoDuration = getPrettyVideoDuration( video.duration )

		if( fileInstance[0].size > 1024 * BYTES_IN_MB ){
			showNotification( `Не вдалося завантажити відео ${ fileInstance[0].name }`, 'error' )
			return false
		}

		if( fileInstance[0].type.startsWith( 'video/' ) ) processingUploadMediaVideo( fileInstance[0], droparea )
		else console.error( `Тільки зображення - файл ${ fileInstance[0].name } не є зображенням` )
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
				thumbsWrapper.classList.remove( 'hidden' )
				showScreenshots( thumbsWrapper.querySelector( '.droparea-thumbs-list' ), data.shots )
				showNotification( `Файл ${ file.name } успішно завантажено` )
			}
		}else{
			// If no videos loaded yet.
			if( ! document.querySelectorAll( '.droparea-video-loaded' ).length ) inner.classList.remove( 'hidden' )
			else videosWrapper.classList.remove( 'hidden' )

			showNotification( `Помилка ${ xhr.status }. Повторіть спробу пізніше.`, 'warning' )
		}
	}
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
	const shotsList = document.querySelector( '.droparea-thumbs-list' )

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
	const button = document.querySelector( '.droparea-thumbs-save' )

	if( ! button ) return

	button.addEventListener( 'click', () => {
		const activeShot = document.querySelector( '.droparea-thumb.active' )

		if( ! activeShot || checkAjaxWorkingStatus() ) return

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
						showElement( document.querySelector( '.droparea-videos' ) )
						clearThumbsList()
						outputVideoWithPoster( res.data, src )
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
 * Output HTML5 video after poster is set.
 *
 * @param {object} videoData
 * @param {string} poster
 */
const outputVideoWithPoster = ( videoData, poster ) => {
	const
		maskId			= `mask0_${ Math.random() * 10000 }_${ Math.random() * 10000 }`,
		videosWrapper	= document.querySelector( '.droparea-videos' ),
		videoHTML		= `<div class="droparea-img-loaded droparea-video-loaded">
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

	const applyCBb = e => {
		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const
			id			= e.target.closest( '.droparea-img-delete' ).dataset.id,
			isVideo		= e.target.closest( '.droparea-img-delete' ).dataset.isVideo,
			formData	= new FormData()

		formData.append( 'action', 'ih_ajax_delete_memory_photo' )
		formData.append( 'id', id )
		formData.append( 'video', isVideo )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						e.target.closest( '.droparea-img-loaded' ).remove()

						// If there are no more images loaded.
						if( ! videosWrapper.querySelectorAll( '.droparea-img-loaded' ).length ){
							hideElement( videosWrapper )
							showElement( document.querySelector( '.droparea-video .droparea-inner' ) )
						}

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

	videosWrapper.querySelector( '.droparea-videos-load' ).insertAdjacentHTML( 'beforebegin', videoHTML )
	videosWrapper
		.querySelector( `.droparea-img-delete[data-id="${ videoData.attachId }"]` )
		.addEventListener( 'click', e => {
			showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e ), 'Дійсно видалити відео?' )
		} )
}

/**
 * Upload custom poster from device.
 */
export const uploadCustomPoster = () => {
	const
		input		= document.querySelector( '.file-load-poster' ),
		loadArea	= document.querySelector( '.droparea-thumbs-list' )

	if( ! input || ! loadArea ) return

	input.addEventListener( 'change', e => {
		const fileInstance = [...e.target.files]

		if( ! fileInstance.length ) return

		const file = fileInstance[0]

		if( file.size > 5 * BYTES_IN_MB ){
			showNotification( 'Розмір повинен бути меньше 5 мб', 'error' )
			return false
		}

		if( file.type.startsWith( 'image/' ) ) processingUploadCustomPoster( file, loadArea )
		else showNotification( `Тільки зображення - файл ${ file.name } не є зображенням`, 'error' )
	} )
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
 * Check if step 4 is ready.
 *
 * @returns {boolean}
 */
export const checkStep4 = () => document.querySelectorAll( '.droparea-img-loaded' ).length > 3

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