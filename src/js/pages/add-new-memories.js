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
	const
		input	= document.querySelector( '#photo' ),
		preview	= document.querySelector( '#photo-preview' )

	if( ! input || ! preview ) return

	input.addEventListener( 'change', e => {
		const [file] = e.target.files

		if( ! file ) return

		if( file.size > 50 * BYTES_IN_MB ){
			showNotification( `Фото ${ file.name } повинне бути меньше 50 Мб`, 'error' )
			return false
		}

		if( file.type.startsWith( 'image/' ) ){
			if( file ){
				preview.src = URL.createObjectURL( file )
				input.closest( 'label' ).classList.add( 'hidden' )
			}
		}else{
			showNotification(`Тільки зображення - файл ${file.name} не є зображенням`, 'warning')
		}
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

		const formData = new FormData( form )

		formData.append( 'action', 'ih_ajax_add_person_memory' )
		formData.append( 'page', form.dataset.page )
		setAjaxWorkingStatus( true )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						console.log( res.data.msg )
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