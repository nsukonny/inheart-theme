import '../components/sidebar/sidebar'
import '../components/switcher/switcher'
import {
	checkAjaxWorkingStatus,
	ihAjaxRequest,
	setAjaxWorkingStatus,
	showNotification,
	switcherLogic
} from '../common/global'
import { disableBodyScroll } from 'body-scroll-lock'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switcherLogic()
	switchMemories()
	publishMemory()
	deleteMemory()
	memoryRejectedSubmit()
} )

/**
 * Switch between others and yours memories.
 */
const switchMemories = () => {
	const switcherButtons = document.querySelectorAll( '.profile-memories-switcher .button' )

	if( ! switcherButtons.length ) return

	switcherButtons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				type		= btn.dataset.type,
				pageId		= btn.closest( '.switcher' ).dataset.page,
				formData	= new FormData()

			formData.append( 'action', 'ih_ajax_load_profile_memories' )
			formData.append( 'type', type )
			formData.append( 'id', pageId )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							const wrapper = document.querySelector( '.profile-memories-list' )

							// If memories exist.
							if( res.data.memories ){
								wrapper.innerHTML = res.data.memories

								if( wrapper.classList.contains( 'none' ) ) wrapper.classList.remove( 'none' )
							}else{	// No memories.
								if( res.data['no-memories'] ){
									wrapper.insertAdjacentHTML( 'afterend', res.data['no-memories'] )
									wrapper.remove()
								}
							}
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
 * Publish memory.
 */
const publishMemory = () => {
	const
		profileBody		= document.querySelector( '.profile-body' ),
		popupConfirm	= document.querySelector( '.popup-confirm.publish' )

	if( ! profileBody || ! popupConfirm ) return

	profileBody.addEventListener( 'click', e => {
		const
			target	= e.target,
			clientX	= e.clientX,
			clientY	= e.clientY

		if( ! target.className || ! target.classList.contains( 'memory-preview-publish' ) ) return

		// If we are here - publish button was clicked. Show popup.
		const buttonsWrap = target.closest( '.memory-preview-actions' )

		// Popup already exists.
		if( buttonsWrap.querySelector( '.popup-confirm.publish' ) ) return

		const popupConfirmClone = popupConfirm.cloneNode( true )

		buttonsWrap.appendChild( popupConfirmClone )
		popupConfirmClone.style.left = `${ clientX }px`
		popupConfirmClone.style.top = `${ clientY }px`
		popupConfirmClone.classList.remove( 'hidden' )
		document.body.classList.add( 'overflow-hidden' )

		popupConfirmClone.querySelector( '.popup-confirm-no' ).addEventListener( 'click', () => {
			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
		} )
		popupConfirmClone.querySelector( '.popup-confirm-yes' ).addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				memory		= target.closest( '.memory-preview' ),
				memoryId	= memory.dataset.id || '',
				formData	= new FormData()

			formData.append( 'action', 'ih_ajax_publish_profile_memory' )
			formData.append( 'id', memoryId )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							showNotification( res.data.msg )
							memory.insertAdjacentHTML( 'afterend', res.data.memory )
							memory.remove()
							break

						case false:
							showNotification( res.data.msg, 'error' )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )

			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
		} )
	} )
}

/**
 * Delete memory.
 */
const deleteMemory = () => {
	const
		profileBody		= document.querySelector( '.profile-body' ),
		popupConfirm	= document.querySelector( '.popup-confirm.delete' )

	if( ! profileBody || ! popupConfirm ) return

	profileBody.addEventListener( 'click', e => {
		const
			target	= e.target,
			clientX	= e.clientX,
			clientY	= e.clientY

		if(
			! target.className ||
			! target.classList.contains( 'memory-preview-delete' ) ||
			! target.closest( '.memory-preview-delete' )
		) return

		// If we are here - delete button was clicked. Show popup.
		const buttonsWrap = target.closest( '.memory-preview-actions' )

		// Popup already exists.
		if( buttonsWrap.querySelector( '.popup-confirm.publish' ) ) return

		const popupConfirmClone = popupConfirm.cloneNode( true )

		buttonsWrap.appendChild( popupConfirmClone )
		popupConfirmClone.style.left = `${ clientX }px`
		popupConfirmClone.style.top = `${ clientY }px`
		popupConfirmClone.classList.remove( 'hidden' )
		document.body.classList.add( 'overflow-hidden' )

		popupConfirmClone.querySelector( '.popup-confirm-no' ).addEventListener( 'click', () => {
			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
		} )
		popupConfirmClone.querySelector( '.popup-confirm-yes' ).addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				memory		= target.closest( '.memory-preview' ),
				memoryId	= memory.dataset.id || '',
				type		= target.dataset.type || '',
				formData	= new FormData()

			if( ! type ) showNotification( 'Невідома помилка. Перезавантажте сторінку та спробуйте ще раз.', 'error' )

			formData.append( 'action', 'ih_ajax_delete_profile_memory' )
			formData.append( 'id', memoryId )
			formData.append( 'type', type )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				if( res ){
					switch( res.success ){
						case true:
							showNotification( res.data.msg )
							memory.remove()

							if( ! profileBody.querySelectorAll( '.memory-preview' ).length ) window.location.reload()
							break

						case false:
							showNotification( res.data.msg, 'error' )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )

			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
		} )
	} )
}

/**
 * Your memory has been rejected - click OK.
 */
const memoryRejectedSubmit = () => {
	const profileBody = document.querySelector( '.profile-body' )

	if( ! profileBody ) return

	profileBody.addEventListener( 'click', e => {
		const target = e.target

		if(
			! target.className ||
			! target.classList.contains( 'memory-preview-rejected-submit' ) ||
			! target.closest( '.memory-preview-rejected-submit' )
		) return

		if( checkAjaxWorkingStatus() ) return

		const
			memory		= target.closest( '.memory-preview' ),
			memoryId	= memory.dataset.id || '',
			type		= target.dataset.type || '',
			formData	= new FormData()

		if( ! type ) showNotification( 'Невідома помилка. Перезавантажте сторінку та спробуйте ще раз.', 'error' )

		formData.append( 'action', 'ih_ajax_delete_profile_memory' )
		formData.append( 'id', memoryId )
		formData.append( 'type', type )
		setAjaxWorkingStatus( true )

		ihAjaxRequest( formData ).then( res => {
			if( res ){
				switch( res.success ){
					case true:
						showNotification( res.data.msg )
						memory.remove()

						if( ! profileBody.querySelectorAll( '.memory-preview' ).length ) window.location.reload()
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