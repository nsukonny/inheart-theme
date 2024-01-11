import '../components/sidebar/sidebar'
import {
	addLoader,
	checkAjaxWorkingStatus,
	customDebounce, disableInput, enableInput,
	ihAjaxRequest, removeLoader,
	setAjaxWorkingStatus
} from '../common/global'

let cities,
	departments

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	expandToFull()
	editMemory()
	loadCities()
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

	buttons.forEach( btn => {
		btn.addEventListener( 'click', () => {
			const
				card			= btn.closest( '.memory-card' ),
				memoryPageId	= card.dataset.id,
				thumb			= card.querySelector( '.memory-card-thumb-img' ).innerHTML,
				firstName		= card.querySelector( '.memory-card-firstname' ).innerHTML,
				lastName		= card.querySelector( '.memory-card-lastname' ).innerHTML

			memoriesSection.classList.add( 'hidden' )
			expandSection.classList.remove( 'hidden' )
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
 * Load matching cities list.
 */
const loadCities = () => {
	const
		cityInput	= document.querySelector( '#city' ),
		formData	= new FormData()

	if( ! cityInput ) return

	const citiesWrap = cityInput.closest( 'label' ).querySelector( '.np-cities' )

	formData.append( 'action', 'ih_ajax_load_cities' )

	const onCityInputChange = e => {
		const
			input	= e.target,
			val		= input.value,
			label	= input.closest( 'label' )

		if( ! val || ! citiesWrap ) return

		formData.append( 'city', val )
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
			loadDepartments( cities[index] )
		}
	} )

	document.addEventListener( 'click', e => {
		if(
			! citiesWrap.classList.contains( 'hidden' ) &&
			e.target.id !== 'city'
		) citiesWrap.classList.add( 'hidden' )
	} )
}

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