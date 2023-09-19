import { formatNumber } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switchDuration()
	planSelection()
	qrCheckboxToggle()
	promo()
} )

const switchDuration = () => {
	const
		switcher		= document.querySelector( '.switch' ),
		plans			= document.querySelectorAll( '.tariff-plan' ),
		planPricePlace	= document.querySelector( '.tariff-order-col.plan-price span' )

	if( ! switcher || ! plans.length || ! planPricePlace ) return

	switcher.addEventListener( 'click', () => {
		if( switcher.classList.contains( 'active' ) ) switcher.classList.remove( 'active' )
		else switcher.classList.add( 'active' )

		plans.forEach( plan => {
			const
				singleYearPrice	= plan.dataset.price,
				tenYearsPrice	= plan.dataset.priceTen,
				amountEl		= plan.querySelector( '.tariff-plan-price-amount' )

			if( ! singleYearPrice || ! tenYearsPrice || ! amountEl ) return

			if( switcher.classList.contains( 'active' ) ) amountEl.innerHTML = tenYearsPrice
			else amountEl.innerHTML = singleYearPrice
		} )

		const activePlan = document.querySelector( '.tariff-plan.active' )

		// If there's an active plan - get its price and set to totals table.
		if( activePlan ){
			if( switcher.classList.contains( 'active' ) )
				planPricePlace.innerHTML = activePlan.dataset.priceTen
			else
				planPricePlace.innerHTML = activePlan.dataset.price
		}

		updateTotal()
	} )
}

const planSelection = () => {
	const
		plans			= document.querySelectorAll( '.tariff-plan' ),
		qrMetalPriceEl	= document.querySelector( '.tariff-order-rust-qr-price' )

	if( ! plans.length ) return

	plans.forEach( plan => {
		plan.addEventListener( 'click', () => {
			const
				activePlan		= document.querySelector( '.tariff-plan.active' ),
				qrMetalPrice	= plan.dataset.qrMetalPrice || '',
				planName		= plan.querySelector( '.tariff-plan-title' ).innerHTML,
				planPrice		= plan.querySelector( '.tariff-plan-price-amount' ).innerHTML,
				planNamePlace	= document.querySelector( '.tariff-order-col.plan-name' ),
				planPricePlace	= document.querySelector( '.tariff-order-col.plan-price span' ),
				orderPart		= document.querySelector( '.tariff-right' )

			if( activePlan ) activePlan.classList.remove( 'active' )

			plan.classList.add( 'active' )
			qrMetalPriceEl.innerHTML = qrMetalPrice

			if( orderPart ) orderPart.classList.remove( 'hidden' )

			if( planName && planNamePlace ) planNamePlace.innerHTML = planName

			if( planPrice && planPricePlace ) planPricePlace.innerHTML = planPrice

			updateTotal()
		} )
	} )
}

const qrCheckboxToggle = () => {
	const
		checkbox		= document.querySelector( '.tariff-order-rust-qr' ),
		qrPricePlace	= document.querySelector( '.tariff-order-col.qr-price span' )

	if( ! checkbox || ! qrPricePlace ) return

	checkbox.addEventListener( 'click', () => {
		const activePlan = document.querySelector( '.tariff-plan.active' )

		if( ! activePlan ) return

		if( checkbox.classList.contains( 'active' ) ){
			checkbox.classList.remove( 'active' )
			qrPricePlace.innerHTML = 0
		}else{
			checkbox.classList.add( 'active' )
			qrPricePlace.innerHTML = activePlan.dataset.qrMetalPrice
		}

		updateTotal()
	} )
}

/**
 * Calculate total price.
 *
 * @returns {number}	Total price.
 */
const calculateTotal = () => {
	const
		plan		= document.querySelector( '.tariff-plan.active' ),
		qrCheckbox	= document.querySelector( '.tariff-order-rust-qr.active' ),
		tenYears	= document.querySelector( '.tariff-duration .switch.active' )
	let qrPrice		= ( qrCheckbox && plan ) ? parseFloat( plan.dataset.qrMetalPrice.replace( ' ', '' ) ) : 0,
		planPrice

	if( ! plan ) return 0

	// Check if 10 years switch is ON.
	if( tenYears )
		planPrice = parseFloat( plan.dataset.priceTen.replace( ' ', '' ) ) * 10
	else
		planPrice = parseFloat( plan.dataset.price.replace( ' ', '' ) )

	return planPrice + qrPrice
}

/**
 * Set total price in HTML.
 */
const updateTotal = () => {
	const totalPlace = document.querySelector( '.tariff-order-col.total-price span' )

	if( ! totalPlace ) return

	totalPlace.innerHTML = formatNumber( calculateTotal() )
}

/**
 * Promo code form.
 */
const promo = () => {
	const
		form	= document.querySelector( '.tariff-order-promo' ),
		input	= document.querySelector( '#promo' )

	if( ! form || ! input ) return

	const btn = form.querySelector( '.btn[type="submit"]' )

	input.addEventListener( 'keyup', e => {
		const val = e.target.value

		if( btn && val ) btn.removeAttribute( 'disabled' )
		else btn.setAttribute( 'disabled', 'disabled' )
	} )
}