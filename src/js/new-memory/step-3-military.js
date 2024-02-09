import {
	addLoader,
	checkAjaxWorkingStatus,
	customDebounce,
	formatDate,
	ihAjaxRequest,
	removeLoader,
	setAjaxWorkingStatus,
	showNotification
} from '../common/global'
import { openDropdown, closeDropdown } from './step-2-military'

const
	stepData = localStorage.getItem( 'ih-step-3-military' ) ?
		JSON.parse( localStorage.getItem( 'ih-step-3-military' ) ) : {}

export const addReward = () => {
	const
		addRewardBtn	= document.querySelector( 'button.add-reward' ),
		noRewardsBody	= document.querySelector( '.no-rewards-body' ),
		rewardsMainWrap	= document.querySelector( '.rewards-main-wrap' ),
		rewardsArea		= document.querySelector( '.rewards-area' ),
		searchForm		= document.querySelector( 'form.rewards-search' ),
		search			= document.querySelector( '#search-reward' ),
		rewardForm		= document.querySelector( '.reward-popup-form' ),
		decline			= document.querySelector( '.reward-popup-form-decline' ),
		rewardPopupText	= document.querySelector( '.reward-popup-text' )

	if(
		! addRewardBtn || ! noRewardsBody || ! rewardsMainWrap ||
		! rewardsArea || ! searchForm || ! search
	) return

	// Show all rewards wrap on button click.
	addRewardBtn.addEventListener( 'click', e => {
		e.preventDefault()
		noRewardsBody.classList.add( 'hidden' )
		rewardsMainWrap.classList.remove( 'hidden' )
	} )

	// Click on a reward.
	rewardsMainWrap.addEventListener( 'click', e => {
		const
			target	= e.target,
			preview	= target.closest( '.reward-preview' ),
			popup	= rewardsMainWrap.querySelector( '.reward-popup' )

		if( ! preview || ! popup ) return

		const
			activePreview		= document.querySelector( '.reward-preview.active' ),
			breadcrumbs			= document.querySelector( '.rewards-sidebar-breadcrumbs' ),
			previewThumb		= preview.querySelector( '.reward-preview-thumb img' ).cloneNode(),
			previewTitle		= preview.querySelector( '.reward-preview-title' ).innerText,
			popupRewardThumb	= popup.querySelector( '.reward-preview-thumb' ),
			popupRewardTitle	= popup.querySelector( '.reward-preview-title' )

		if( activePreview ) activePreview.classList.remove( 'active' )

		popupRewardThumb.innerHTML = ''
		popupRewardThumb.appendChild( previewThumb )
		popupRewardTitle.innerText = previewTitle
		preview.classList.add( 'active' )
		popup.classList.remove( 'hidden' )

		if( breadcrumbs ){
			breadcrumbs.querySelector( '.active' ).classList.remove( 'active' )
			breadcrumbs.querySelector( 'span:last-child' ).classList.add( 'active' )
		}
	} )

	// Close reward popup.
	if( decline ){
		decline.addEventListener( 'click', e => {
			e.preventDefault()

			const
				activePreview	= document.querySelector( '.reward-preview.active' ),
				breadcrumbs		= document.querySelector( '.rewards-sidebar-breadcrumbs' ),
				popup			= e.target.closest( '.reward-popup' )

			if( activePreview ) activePreview.classList.remove( 'active' )

			if( breadcrumbs ){
				breadcrumbs.querySelector( '.active' ).classList.remove( 'active' )
				breadcrumbs.querySelector( 'span:first-child' ).classList.add( 'active' )
			}

			popup.classList.add( 'hidden' )
		} )
	}

	// Filter callback.
	const filter = () => {
		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const
			formData	= new FormData(),
			activeBtn	= document.querySelector( '.rewards-type-filter.active' )

		formData.append( 'action', 'ih_ajax_filter_rewards' )
		formData.append( 'slug', activeBtn ? activeBtn.dataset.slug : '' )
		formData.append( 's', search.value )

		addLoader( rewardsArea )

		ihAjaxRequest( formData ).then( res => {
			removeLoader( rewardsArea )

			if( res ){
				switch( res.success ){
					case true:
						rewardsArea.innerHTML = res.data.structure
						break

					case false:
						showNotification( res.data.msg, 'error' )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	}

	// Click on a rewards type button to filter.
	const rewardsTypes = document.querySelectorAll( '.rewards-type-filter' )

	if( rewardsTypes.length ){
		rewardsTypes.forEach( btn => {
			btn.addEventListener( 'click', e => {
				e.preventDefault()

				// This button is already active - remove this filter.
				if( btn.classList.contains( 'active' ) ){
					btn.classList.remove( 'active' )
				}else{
					const activeBtn = document.querySelector( '.rewards-type-filter.active' )

					if( activeBtn ) activeBtn.classList.remove( 'active' )

					btn.classList.add( 'active' )
				}

				filter()
				btn.blur()
			} )
		} )
	}

	// Filter rewards when typing in a search field.
	search.addEventListener( 'input', customDebounce( filter ) )

	// Search form submit - filter rewards.
	searchForm.addEventListener( 'submit', e => {
		e.preventDefault()
		filter()
	} )

	// Reward popup inputs.
	const
		edictInput		= document.querySelector( '#edict' ),
		numberInput		= document.querySelector( '#reward-number' ),
		dateInput		= document.querySelector( '#reward-date' ),
		forInput		= document.querySelector( '#reward-for-what' ),
		posthumously	= document.querySelector( '#posthumously' )

	if( edictInput ){
		edictInput.addEventListener( 'focus', openDropdown )
		edictInput.addEventListener( 'click', openDropdown )

		const
			label	= edictInput.closest( '.label' ),
			options	= label.querySelectorAll( '.option' )

		if( options.length ){
			options.forEach( option => {
				option.addEventListener( 'click', () => {
					const text = option.innerText

					edictInput.value = text
					setTimeout( () => closeDropdown(), 10 )

					if( ! rewardPopupText ) return

					rewardPopupText.querySelector( '.reward-popup-text-edict' ).innerText = `${ text }`
				} )
			} )
		}
	}

	document.addEventListener( 'click', e => {
		const target = e.target

		if( ! target.closest( '.label' ) ) closeDropdown()
	} )

	if( numberInput ){
		numberInput.addEventListener( 'input', () => {
			const
				val				= numberInput.value,
				textAfterNumber	= rewardPopupText.querySelector( '.reward-popup-text-number-after' )

			if( ! val ){
				textAfterNumber.classList.add( 'hidden' )
				return
			}

			rewardPopupText.querySelector( '.reward-popup-text-number' ).innerText = `№ ${ val }`
			textAfterNumber.classList.remove( 'hidden' )
		} )
	}

	if( dateInput ){
		dateInput.addEventListener( 'input', () => {
			const
				val				= dateInput.value,
				textAfterDate	= rewardPopupText.querySelector( '.reward-popup-text-date-after' )

			if( ! val ){
				textAfterDate.classList.add( 'hidden' )
				return
			}

			rewardPopupText.querySelector( '.reward-popup-text-date' ).innerText = formatDate( val )
			textAfterDate.classList.remove( 'hidden' )
		} )
	}

	if( forInput ){
		forInput.addEventListener( 'input', () => {
			const val = forInput.value

			if( ! val ) return

			rewardPopupText.querySelector( '.reward-popup-text-for' ).innerText = val
		} )
	}

	if( posthumously ){
		posthumously.addEventListener( 'change', () => {
			if( posthumously.checked )
				rewardPopupText.querySelector( '.reward-popup-text-posthumously' ).classList.remove( 'hidden' )
			else
				rewardPopupText.querySelector( '.reward-popup-text-posthumously' ).classList.add( 'hidden' )
		} )
	}

	if( rewardForm ){
		rewardForm.addEventListener( 'submit', e => {
			e.preventDefault()

			if( checkAjaxWorkingStatus() ) return

			setAjaxWorkingStatus( true )

			const
				formData	= new FormData( rewardForm ),
				rewardId	= document.querySelector( '.reward-preview.active' ).dataset.id

			formData.append( 'action', 'ih_ajax_add_reward' )
			formData.append( 'id', rewardId )
			addLoader( rewardForm )

			ihAjaxRequest( formData ).then( res => {
				removeLoader( rewardForm )

				if( res ){
					switch( res.success ){
						case true:
							showNotification( res.data.msg )
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
}