import {
	checkAjaxWorkingStatus,
	setAjaxWorkingStatus,
	ihAjaxRequest,
	BYTES_IN_MB,
	ajaxUrl,
	showAreYouSurePopup,
	hideAreYouSurePopup
} from '../common/global'

/**
 * Upload media photos.
 */
export const uploadMediaPhotos = () => {
	const droparea = document.querySelector( '.droparea-photo' )
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

	droparea.addEventListener( 'drop', e => {
		fileInstance = [...e.dataTransfer.files]

		if( ! fileInstance.length ) return

		fileInstance.forEach( file => {
			if( file.size > 5 * BYTES_IN_MB ){
				alert( 'Не вдалося завантажити фото' )
				return false
			}

			if( file.type.startsWith( 'image/' ) ){
				processingUploadMediaPhoto( file, droparea )
			} else {
				alert( 'Завантажте тільки зображення' )
				return false
			}
		} )
	} )
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

				const cancelCBb = e => {
					e.stopPropagation()
					hideAreYouSurePopup()
				}
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
									e.target.closest( '.droparea-img-loaded' ).remove()

									// If there are no more images loaded.
									if( ! imagesWrapper.querySelectorAll( '.droparea-img-loaded' ).length ){
										imagesWrapper.classList.add( 'hidden' )
										inner.classList.remove( 'hidden' )
									}

									break

								case false:
									console.error( res.data.msg )
									break
							}
						}

						setAjaxWorkingStatus( false )
						hideAreYouSurePopup()
					} )
				}

				imagesWrapper.insertAdjacentHTML( 'beforeend', imageHTML )
				imagesWrapper.querySelector( `.droparea-img-delete[data-id="${ data.attachId }"]` )
					.addEventListener( 'click', e => showAreYouSurePopup( e.target, cancelCBb, () => applyCBb( e ) ) )
			}
		}else{
			// If no images loaded yet.
			if( ! document.querySelectorAll( '.droparea-img-loaded' ).length ) inner.classList.remove( 'hidden' )
			else imagesWrapper.classList.remove( 'hidden' )

			console.error( `Файл не загружен. Ошибка ${ xhr.status } при загрузке файла.` )
		}
	}
}