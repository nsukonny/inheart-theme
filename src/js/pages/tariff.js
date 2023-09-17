document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	switchDuration()
	planSelection()
} )

const switchDuration = () => {
	const
		switcher	= document.querySelector( '.switch' ),
		plans		= document.querySelectorAll( '.tariff-plan' )

	if( ! switcher || ! plans.length ) return

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
	} )
}

const planSelection = () => {
	const
		plans			= document.querySelectorAll( '.tariff-plan' ),
		qrMetalCheckbox	= document.querySelector( '.tariff-order-rust-qr' ),
		qrMetalPriceEl	= document.querySelector( '.tariff-order-rust-qr-price' )

	if( ! plans.length ) return

	plans.forEach( plan => {
		plan.addEventListener( 'click', () => {
			const
				activePlan		= document.querySelector( '.tariff-plan.active' ),
				qrMetalPrice	= plan.dataset.qrMetalPrice || ''

			if( activePlan ) activePlan.classList.remove( 'active' )

			plan.classList.add( 'active' )
			qrMetalPriceEl.innerHTML = qrMetalPrice

			if( qrMetalPrice === '0' ) qrMetalCheckbox.classList.add( 'active' )
			else qrMetalCheckbox.classList.remove( 'active' )
		} )
	} )
}