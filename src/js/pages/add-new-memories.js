import { BYTES_IN_MB, showNotification } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	uploadPhoto()
} )

const uploadPhoto = () => {
	const input = document.querySelector( '#photo' )

	if( ! input ) return

	input.addEventListener( 'change', e => {
		const fileInstance = [...e.target.files]

		if( ! fileInstance.length ) return

		if( fileInstance[0].size > 50 * BYTES_IN_MB ){
			showNotification( `Фото ${ fileInstance[0].name } повинне бути меньше 50 Мб`, 'error' )
			return false
		}

		if( fileInstance[0].type.startsWith( 'image/' ) )
			processingUploadPhoto( fileInstance[0] )
		else
			showNotification( `Тільки зображення - файл ${ fileInstance[0].name } не є зображенням`, 'warning' )
	} )
}

/**
 * Processing photo uploading.
 *
 * @param file
 */
const processingUploadPhoto = file => {
	if( ! file ) return

	const formData = new FormData()

	formData.append( 'file', file )
	formData.append( 'action', 'ih_ajax_upload_memories_photo' )
/*
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
					setTimeout( () => {
						loader.classList.add( 'hidden' )
						imagesWrapper.classList.remove( 'hidden' )
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
	}*/
}