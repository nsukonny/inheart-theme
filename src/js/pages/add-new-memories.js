import '../components/sidebar/sidebar'
import {
	BYTES_IN_MB,
	checkAjaxWorkingStatus,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	uploadPhoto()
	submitForm( document.querySelector( '.add-new-memory-form' ) )
} )

/**
 * Show memory photo preview.
 */
const uploadPhoto = () => {
	const input = document.querySelector( '#photo' )

	if( ! input ) return

	const
		wrap	= input.closest( '.label-file-wrap' ),
		label	= wrap.querySelector( '.label' ),
		result	= wrap.querySelector( '.label-file-wrap-result' )

	input.addEventListener( 'change', e => {
		const [file] = e.target.files

		if( ! file ) return

		if( file.size > 50 * BYTES_IN_MB ){
			showNotification( `Фото ${ file.name } повинне бути меньше 50 Мб`, 'error' )
			return false
		}

		if( file.type.startsWith( 'image/' ) ){
			if( file ){
				const image = document.createElement( 'img' )

				image.src = URL.createObjectURL( file )
				label.classList.add( 'hidden' )
				result.appendChild( image )
				result.classList.remove( 'hidden' )
			}
		}else{
			showNotification(`Тільки зображення - файл ${file.name} не є зображенням`, 'warning')
		}
	} )

	// Delete image.
	result.addEventListener( 'click', e => {
		const target = e.target

		if( ! target.className || ! target.classList.contains( 'button' ) ) return

		result.querySelector( 'img' ).remove()
		result.classList.add( 'hidden' )
		label.classList.remove( 'hidden' )
		input.value = ''
	} )
}

/**
 * Submit form to add a memory from a person.
 *
 * @param {HTMLElement} form
 */
const submitForm = form => {
	if( ! form ) return

	form.addEventListener( 'submit', e => {
		e.preventDefault()

		if( checkAjaxWorkingStatus() ) return

		const
			formData		= new FormData( form ),
			fieldset		= form.querySelector( 'fieldset' ),
			memoryCreated	= form.querySelector( '.memory-created' ),
			bottomText		= document.querySelector( '.add-new-memory-bottom' ),
			buttonSave		= document.querySelector( '.button.save-changes' ),
			buttonSeePage	= document.querySelector( '.button.see-memory-page' )

		formData.append( 'action', 'ih_ajax_add_person_memory' )
		formData.append( 'page', form.dataset.page )
		setAjaxWorkingStatus( true )
		form.classList.add( 'loading' )

		ihAjaxRequest( formData ).then( res => {
			form.classList.remove( 'loading' )

			if( res ){
				switch( res.success ){
					case true:
						form.classList.add( 'success' )

						if( bottomText ) bottomText.classList.remove( 'hidden' )

						if( memoryCreated ){
							fieldset.classList.add( 'hidden' )
							memoryCreated.classList.remove( 'hidden' )
						}

						if( buttonSave && buttonSeePage ){
							buttonSave.classList.add( 'hidden' )
							buttonSeePage.classList.remove( 'hidden' )
						}
						break

					case false:
						console.error( res.data.msg )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )
}