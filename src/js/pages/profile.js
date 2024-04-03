import '../components/sidebar/sidebar'
import {
	addLoader,
	checkAjaxWorkingStatus,
	customDebounce,
	disableInput,
	enableInput,
	ihAjaxRequest,
	removeLoader,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'

let cities,
	departments

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	expandToFull()
	editMemory()
	loadCities()
	calculateQRCount()
	createOrder()
	updateOrderInfo( document.querySelector( '.expand-page-form' ) )
} )

/**
 * Edit memory page.
 */
const editMemory = () => {
	const editButtons = document.querySelectorAll( '.edit-memory' )

	if( ! editButtons.length ) return

	editButtons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )

			const
				memoryId	= btn.dataset.id,
				formData	= new FormData()

			btn.classList.add( 'disabled' )
			formData.append( 'action', 'ih_ajax_edit_memory_page' )
			formData.append( 'id', memoryId )

			ihAjaxRequest( formData ).then( res => {
				btn.classList.remove( 'disabled' )

				if( res ){
					switch( res.success ){
						case true:
							if( res.data.redirect ) window.location.href = res.data.redirect
							break

						case false:
							console.error( res.data.msg )
							break
					}
				}

				setAjaxWorkingStatus( false )
			} )
		} )
	} )
}

/**
 * Expand memory page to full - button click.
 */
const expandToFull = () => {
	const
		memoriesSection	= document.querySelector( '.profile-body' ),
		expandSection	= document.querySelector( '.expand-page' ),
		buttons			= document.querySelectorAll( '.expand-to-full' ),
		back			= document.querySelector( '.profile-breadcrumbs-back' )

	if( ! memoriesSection || ! expandSection || ! buttons.length  ) return

	const form = expandSection.querySelector( '.expand-page-form' )

	buttons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			const
				card			= btn.closest( '.memory-card' ),
				memoryPageId	= card.dataset.id

			memoriesSection.classList.add( 'hidden' )
			expandSection.classList.remove( 'hidden' )
			form.setAttribute( 'data-page', memoryPageId )
			updateOrderInfo( form )
		} )
	} )

	if( back ){
		back.addEventListener( 'click', e => {
			e.preventDefault()
			memoriesSection.classList.remove( 'hidden' )
			expandSection.classList.add( 'hidden' )
		} )
	}
}

/**
 * Update Order info, e.g. price, because it depends on Memory page theme.
 *
 * @param form
 */
const updateOrderInfo = form => {
	const pageId = form.dataset.page

	if( ! form || ! pageId ) return

	const formData = new FormData()

	if( checkAjaxWorkingStatus() ) return

	setAjaxWorkingStatus( true )

	form.classList.add( 'loading' )
	formData.append( 'action', 'ih_ajax_update_order_info' )
	formData.append( 'page', pageId )

	ihAjaxRequest( formData ).then( res => {
		form.classList.remove( 'loading' )

		if( res ){
			switch( res.success ){
				case true:
					form.querySelector( 'button[type="submit"] span' ).innerText = res.data.price
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
 * Load matching cities list.
 */
export const loadCities = ( withDepartments = true, allCities = 0 ) => {
	const cityInput	= document.querySelector( '#city' )

	if( ! cityInput ) return

	const citiesWrap = cityInput.closest( 'label' ).querySelector( '.np-cities' )

	const onCityInputChange = e => {
		const
			formData	= new FormData(),
			input		= e.target,
			val			= input.value,
			label		= input.closest( 'label' )

		if( ! val || ! citiesWrap ) return

		formData.append( 'action', 'ih_ajax_load_cities' )
		formData.append( 'city', val )
		formData.append( 'all', allCities )
		disableInput( input )
		addLoader( label )
		citiesWrap.classList.add( 'hidden' )
		ihAjaxRequest( formData ).then( res => {
			enableInput( input )
			removeLoader( label )
			input.focus()

			if( res ){
				switch( res.success ){
					case true:
						cities = res.data.cities
						citiesWrap.innerHTML = ''
						cities.forEach( ( city, index ) => {
							const item = `<span class="np-city" data-index="${ index }">
								${ city.SettlementTypeDescription[0] }. ${ city.Description }, ${ city.AreaDescription }
							</span>`
							citiesWrap.insertAdjacentHTML( 'beforeend', item )
						} )
						citiesWrap.classList.remove( 'hidden' )
						break

					case false:
						console.error( res.data.msg )
						break
				}
			}
		} ).catch( err => {
			enableInput( input )
			removeLoader( label )
			console.error( err.message )
		} )
	}

	cityInput.addEventListener( 'input', customDebounce( onCityInputChange ) )
	cityInput.addEventListener( 'focus', () => citiesWrap.classList.remove( 'hidden' ) )

	citiesWrap.addEventListener( 'click', e => {
		e.stopPropagation()

		const target = e.target
		let index

		if( target.className && target.classList.contains( 'np-city' ) ){
			index			= target.dataset.index
			cityInput.value = target.innerText
			setTimeout( () => {
				cityInput.blur()
				citiesWrap.classList.add( 'hidden' )
			}, 10 )

			if( withDepartments ) loadDepartments( cities[index] )
		}
	} )

	document.addEventListener( 'click', e => {
		if(
			! citiesWrap.classList.contains( 'hidden' ) &&
			e.target.id !== 'city'
		) citiesWrap.classList.add( 'hidden' )
	} )
}

/**
 * Load city departments list.
 */
const loadDepartments = city => {
	if( ! city ) return

	const
		select			= document.querySelector( '#departments' ),
		departmentsWrap	= select.closest( 'label' ).querySelector( '.np-departments' ),
		formData		= new FormData(),
		label			= select.closest( 'label' )

	formData.append( 'action', 'ih_ajax_load_departments' )
	formData.append( 'ref', city.Ref )
	select.value = ''
	disableInput( select )
	addLoader( label )
	departmentsWrap.classList.add( 'hidden' )
	ihAjaxRequest( formData ).then( res => {
		enableInput( select )
		removeLoader( label )
		select.focus()

		if( res ){
			switch( res.success ){
				case true:
					select.focus()
					departments = res.data.departments

					if( departments.length ){
						departmentsWrap.innerHTML = ''
						departmentsWrap.classList.remove( 'hidden' )
						departments.forEach( department => {
							const item = `<span class="np-department">${ department.Description }</span>`
							departmentsWrap.insertAdjacentHTML( 'beforeend', item )
						} )
						departmentsWrap.classList.remove( 'hidden' )
					}else{
						select.value = 'Нажаль, інформації немає'
					}
					break

				case false:
					console.error( res.data.msg )
					break
			}
		}
	} ).catch( err => {
		enableInput( select )
		removeLoader( label )
		console.error( err.message )
	} )

	const searchDepartments = e => {
		const
			val			= e.target.value,
			departments	= document.querySelectorAll( '.np-department' )

		if( ! departments.length ) return

		departments.forEach( dep => dep.classList.remove( 'hidden' ) )

		if( ! val ) return

		departments.forEach( dep => {
			if( ! dep.innerText.toLowerCase().includes( val.toLowerCase() ) ) dep.classList.add( 'hidden' )
		} )
	}

	select.addEventListener( 'focus', () => departmentsWrap.classList.remove( 'hidden' ) )
	select.addEventListener( 'input', searchDepartments )

	departmentsWrap.addEventListener( 'click', e => {
		e.stopPropagation()

		const target = e.target

		if( target.className && target.classList.contains( 'np-department' ) ){
			select.value = target.innerText
			setTimeout( () => {
				select.blur()
				departmentsWrap.classList.add( 'hidden' )
			}, 10 )
		}
	} )

	document.addEventListener( 'click', e => {
		if(
			! departmentsWrap.classList.contains( 'hidden' ) &&
			e.target.id !== 'departments'
		) departmentsWrap.classList.add( 'hidden' )
	} )
}

/**
 * Re-calculate full price on QR count change.
 */
const calculateQRCount = () => {
	const
		buttonsWrap = document.querySelector( '.qr-count-buttons' ),
		priceBtn	= document.querySelector( '.full-price-btn' )

	if( ! buttonsWrap || ! priceBtn ) return

	const
		buttons	= buttonsWrap.querySelectorAll( '.button.qty' ),
		minus	= buttonsWrap.querySelector( '.button.qty.minus' ),
		countEl	= buttonsWrap.querySelector( '.qr-count-qty' ),
		priceEl	= priceBtn.querySelector( 'span' )

	if( ! buttons.length || ! minus || ! countEl ) return

	/**
	 * Quantity input changed callback.
	 *
	 * @param {Event} e
	 */
	const onQuantityChange = e => {
		const
			target		= e.target,
			value		= target.value,
			formData	= new FormData()

		if( checkAjaxWorkingStatus() || ! value ) return

		setAjaxWorkingStatus( true )

		target.classList.add( 'disabled' )
		priceBtn.classList.add( 'disabled' )
		priceBtn.disabled = true

		formData.append( 'action', 'ih_ajax_change_qty' )
		formData.append( 'count', value )

		ihAjaxRequest( formData ).then( res => {
			target.classList.remove( 'disabled' )
			priceBtn.classList.remove( 'disabled' )
			priceBtn.removeAttribute( 'disabled' )

			if( res ){
				switch( res.success ){
					case true:
						priceEl.innerHTML = res.data.price
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
	 * Quantity change via buttons callback.
	 *
 	 * @param {Event} e
	 */
	const onButtonClick = e => {
		const btn	= e.target
		let count	= countEl.value.trim().replace( ' ', '' )

		if( btn.disabled ) return

		if( btn.classList.contains( 'plus' ) ){
			count++

			if( minus.disabled ) minus.removeAttribute( 'disabled' )
		}else{
			if( btn.classList.contains( 'minus' ) && count > 1 ) count--

			if( count === 1 ) minus.disabled = true
		}

		countEl.value = count
		countEl.dispatchEvent( new Event( 'change' ) )
	}

	countEl.addEventListener( 'change', onQuantityChange )
	countEl.addEventListener( 'input', onQuantityChange )

	buttons.forEach( btn => {
		btn.addEventListener( 'click', onButtonClick )
	} )
}

const createOrder = () => {
	const form = document.querySelector( '.expand-page-form' )

	if( ! form ) return

	form.addEventListener( 'submit', e => {
		e.preventDefault()

		const
			target		= e.target,
			formData	= new FormData( form )

		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		target.classList.add( 'disabled', 'loading' )
		formData.append( 'action', 'ih_ajax_create_order' )
		formData.append( 'page', form.dataset.page )

		ihAjaxRequest( formData ).then( res => {
			target.classList.remove( 'disabled', 'loading' )

			if( res ){
				switch( res.success ){
					case true:
						if( res.data.pageUrl ) window.location.href = res.data.pageUrl
						else showNotification( 'Невідома помилка', 'error' )
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