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
import { closeDropdown, openDropdown } from './step-2-military'

const
	addRewardButtons	= document.querySelectorAll( 'button.add-reward' ),
	titleButton			= document.querySelector( '#new-memory-step-3-military .new-memory-step-title .add-reward' ),
	noRewardsBody		= document.querySelector( '.no-rewards-body' ),
	hasRewardsBody		= document.querySelector( '.has-rewards' ),
	rewardsMainWrap		= document.querySelector( '.rewards-main-wrap' ),
	rewardsArea			= document.querySelector( '.rewards-area' ),
	searchForm			= document.querySelector( 'form.rewards-search' ),
	search				= document.querySelector( '#search-reward' ),
	rewardForm			= document.querySelector( '.reward-popup-form' ),
	decline				= document.querySelector( '.reward-popup-form-decline' ),
	rewardPopupText		= document.querySelector( '.reward-popup-text' )

// Reward popup.
const
	popup						= rewardsMainWrap.querySelector( '.reward-popup' ),
	popupReward					= popup.querySelector( '.reward-preview' ),
	popupNoRewardThumb			= popupReward.querySelector( '.reward-preview-no-reward-thumb' ),
	popupRewardThumb			= popupReward.querySelector( '.reward-preview-thumb' ),
	popupRewardTitle			= popupReward.querySelector( '.reward-preview-title' ),
	rewardPopupTextEdict		= rewardPopupText.querySelector( '.reward-popup-text-edict' ),
	rewardPopupTextNumber		= rewardPopupText.querySelector( '.reward-popup-text-number' ),
	rewardPopupTextDate			= rewardPopupText.querySelector( '.reward-popup-text-date' ),
	rewardPopupTextFor			= rewardPopupText.querySelector( '.reward-popup-text-for' ),
	rewardPopupTextPosthumously	= rewardPopupText.querySelector( '.reward-popup-text-posthumously' ),
	textAfterNumber				= rewardPopupText.querySelector( '.reward-popup-text-number-after' ),
	textAfterDate				= rewardPopupText.querySelector( '.reward-popup-text-date-after' ),
	rewardPopupCustomText		= popup.querySelector( '.reward-popup-custom' )

// Reward popup inputs.
const
	customLabel		= popup.querySelector( '.label-reward-custom' ),
	customInput		= popup.querySelector( '#reward-custom' ),
	edictInput		= popup.querySelector( '#edict' ),
	numberInput		= popup.querySelector( '#reward-number' ),
	dateInput		= popup.querySelector( '#reward-date' ),
	forInput		= popup.querySelector( '#reward-for-what' ),
	posthumously	= popup.querySelector( '#posthumously' )

export const addReward = () => {
	if(
		! addRewardButtons.length || ! hasRewardsBody || ! noRewardsBody || ! rewardsMainWrap ||
		! rewardsArea || ! searchForm || ! search
	) return

	// Show all rewards wrap on button click.
	addRewardButtons.forEach( btn => {
		btn.addEventListener( 'click', e => {
			e.preventDefault()
			hasRewardsBody.classList.add( 'hidden' )
			noRewardsBody.classList.add( 'hidden' )
			rewardsMainWrap.classList.remove( 'hidden' )
		} )
	} )

	// Click on a reward.
	rewardsMainWrap.addEventListener( 'click', e => {
		const
			target	= e.target,
			preview	= target.closest( '.reward-preview' )

		if( ! preview || ! popup ) return

		const
			activePreview		= document.querySelector( '.reward-preview.active' ),
			breadcrumbs			= document.querySelector( '.rewards-sidebar-breadcrumbs' ),
			previewThumb		= preview.querySelector( '.reward-preview-thumb img' ).cloneNode(),
			previewTitle		= preview.querySelector( '.reward-preview-title' ).innerText

		if( activePreview ) activePreview.classList.remove( 'active' )

		popupReward.setAttribute( 'data-id', preview.dataset.id )
		popupRewardThumb.innerHTML = ''
		popupNoRewardThumb.classList.add( 'hidden' )
		popupRewardThumb.classList.remove( 'hidden' )
		popupRewardThumb.appendChild( previewThumb )
		popupRewardTitle.classList.remove( 'hidden' )
		popupRewardTitle.innerText = previewTitle
		preview.classList.add( 'active' )
		popup.classList.remove( 'hidden' )

		if( breadcrumbs ){
			breadcrumbs.querySelector( '.active' ).classList.remove( 'active' )
			breadcrumbs.querySelector( 'span:last-child' ).classList.add( 'active' )
		}

		window.scrollTo( { top: 0, left: 0 } )
	} )

	// Close reward popup.
	if( decline ){
		decline.addEventListener( 'click', e => {
			e.preventDefault()

			const
				activePreview	= document.querySelector( '.reward-preview.active' ),
				breadcrumbs		= document.querySelector( '.rewards-sidebar-breadcrumbs' )

			if( activePreview ) activePreview.classList.remove( 'active' )

			if( breadcrumbs ){
				breadcrumbs.querySelector( '.active' ).classList.remove( 'active' )
				breadcrumbs.querySelector( 'span:first-child' ).classList.add( 'active' )
			}

			cleanRewardPopup()
			rewardsMainWrap.classList.add( 'hidden' )

			if( ! hasRewardsBody.querySelectorAll( '.reward-preview' ).length ) noRewardsBody.classList.remove( 'hidden' )
			else hasRewardsBody.classList.remove( 'hidden' )
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
						window.scrollTo( {
							top: rewardsArea.getBoundingClientRect().top + window.scrollY,
							behavior: 'smooth'
						} )
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

	const onEdictInputUpdate = e => {
		const value = e.target.value

		if( ! rewardPopupTextEdict ) return

		rewardPopupTextEdict.innerText = value
	}

	if( edictInput ){
		edictInput.addEventListener( 'focus', openDropdown )
		edictInput.addEventListener( 'click', openDropdown )
		edictInput.addEventListener( 'input', onEdictInputUpdate )

		const
			label	= edictInput.closest( '.label' ),
			options	= label.querySelectorAll( '.option' )

		if( options.length ){
			options.forEach( option => {
				option.addEventListener( 'click', () => {
					edictInput.value = option.innerText
					setTimeout( () => closeDropdown(), 10 )
					edictInput.dispatchEvent( new Event( 'input' ) )
				} )
			} )
		}
	}

	document.addEventListener( 'click', e => {
		const target = e.target

		if( ! target.closest( '.label' ) ) closeDropdown()
	} )

	numberInput.addEventListener( 'input', () => {
		const val = numberInput.value

		if( ! val ){
			textAfterNumber.classList.add( 'hidden' )
			return
		}

		rewardPopupTextNumber.innerText = `№ ${ val }`
		textAfterNumber.classList.remove( 'hidden' )
	} )

	dateInput.addEventListener( 'input', () => {
		const val = dateInput.value

		if( ! val ){
			textAfterDate.classList.add( 'hidden' )
			return
		}

		rewardPopupTextDate.innerText = formatDate( val )
		textAfterDate.classList.remove( 'hidden' )
	} )

	forInput.addEventListener( 'input', () => {
		const val = forInput.value

		if( ! val ) return

		rewardPopupTextFor.innerText = val
	} )

	posthumously.addEventListener( 'change', () => {
		if( posthumously.checked ) rewardPopupTextPosthumously.classList.remove( 'hidden' )
		else rewardPopupTextPosthumously.classList.add( 'hidden' )
	} )

	rewardForm.addEventListener( 'submit', e => {
		e.preventDefault()

		if( checkAjaxWorkingStatus() ) return

		setAjaxWorkingStatus( true )

		const
			formData	= new FormData( rewardForm ),
			rewardId	= popupReward.dataset.id

		formData.append( 'action', 'ih_ajax_add_reward' )
		formData.append( 'id', rewardId )
		addLoader( rewardForm )

		ihAjaxRequest( formData ).then( res => {
			removeLoader( rewardForm )

			if( res ){
				switch( res.success ){
					case true:
						showNotification( res.data.msg )
						hasRewardsBody.innerHTML = res.data.rewards
						hasRewardsBody.classList.remove( 'hidden' )
						rewardsMainWrap.classList.add( 'hidden' )
						titleButton.classList.remove( 'hidden' )
						cleanRewardPopup()
						break

					case false:
						showNotification( res.data.msg, 'error' )
						break
				}
			}

			setAjaxWorkingStatus( false )
		} )
	} )

	addCustomReward()
	deleteReward()
	editReward()
}

const addCustomReward = () => {
	const btn = document.querySelector( 'button.add-custom-reward' )

	if( ! btn ) return

	btn.addEventListener( 'click', () => {
		popupReward.setAttribute( 'data-id', '' )
		popupRewardThumb.classList.add( 'hidden' )
		popupNoRewardThumb.classList.remove( 'hidden' )
		popupRewardTitle.classList.add( 'hidden' )
		popup.classList.remove( 'hidden' )
		customLabel.classList.remove( 'hidden' )

		if( rewardPopupCustomText ) rewardPopupCustomText.classList.remove( 'hidden' )

		window.scrollTo( { top: 0, left: 0 } )
		btn.blur()
	} )
}

/**
 * Delete reward.
 */
const deleteReward = () => {
	const popupConfirm = document.querySelector( '.popup-confirm.delete' )

	if( ! hasRewardsBody || ! popupConfirm ) return

	hasRewardsBody.addEventListener( 'click', e => {
		const
			target	= e.target,
			clientX	= e.clientX < 200 ? 200 : e.clientX,
			clientY	= e.clientY

		if(
			! target.className ||
			! target.classList.contains( 'reward-preview-delete' ) ||
			! target.closest( '.reward-preview-delete' )
		) return

		// If we are here - delete button was clicked. Show popup.
		const buttonsWrap = target.closest( '.reward-preview-actions' )

		// If popup already exists - exit.
		if( buttonsWrap.classList.contains( 'active' ) ) return

		const popupConfirmClone = popupConfirm.cloneNode( true )

		buttonsWrap.classList.add( 'active' )
		buttonsWrap.appendChild( popupConfirmClone )
		popupConfirmClone.style.left = `${ clientX }px`
		popupConfirmClone.style.top = `${ clientY }px`
		popupConfirmClone.classList.remove( 'hidden' )
		document.body.classList.add( 'overflow-hidden' )

		popupConfirmClone.querySelector( '.popup-confirm-no' ).addEventListener( 'click', () => {
			popupConfirmClone.remove()
			document.body.classList.remove( 'overflow-hidden' )
			buttonsWrap.classList.remove( 'active' )
		} )
		popupConfirmClone.querySelector( '.popup-confirm-yes' ).addEventListener( 'click', () => {
			if( checkAjaxWorkingStatus() ) return

			const
				reward		= target.closest( '.reward-preview' ),
				memoryId	= reward.dataset.id || '',
				formData	= new FormData()

			formData.append( 'action', 'ih_ajax_delete_reward' )
			formData.append( 'id', memoryId )
			setAjaxWorkingStatus( true )

			ihAjaxRequest( formData ).then( res => {
				buttonsWrap.classList.remove( 'active' )

				if( res ){
					switch( res.success ){
						case true:
							showNotification( res.data.msg )
							reward.remove()

							// If there are no more rewards - show No Rewards screen.
							if( ! hasRewardsBody.querySelectorAll( '.reward-preview' ).length ){
								hasRewardsBody.classList.add( 'hidden' )
								titleButton.classList.add( 'hidden' )
								document.querySelector( '.no-rewards-body' ).classList.remove( 'hidden' )
							}
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
 * Edit reward.
 */
const editReward = () => {
	if( ! hasRewardsBody ) return

	hasRewardsBody.addEventListener( 'click', e => {
		const target = e.target

		if(
			! target.className ||
			! target.classList.contains( 'reward-preview-edit' ) ||
			! target.closest( '.reward-preview-edit' )
		) return

		const
			preview				= target.closest( '.reward-preview' ),
			isCustom			= preview.classList.contains( 'custom' ),
			previewThumb		= ! isCustom ? preview.querySelector( '.reward-preview-thumb img' ).cloneNode() : '',
			previewTitle		= preview.querySelector( '.reward-preview-title' ).innerText,
			hiddenEdict			= preview.querySelector( '.reward-preview-edict' ).innerText,
			hiddenNumber		= preview.querySelector( '.reward-preview-number' ).innerText,
			hiddenDate			= preview.querySelector( '.reward-preview-date' ).innerText,
			hiddenFor			= preview.querySelector( '.reward-preview-for' ).innerText,
			hiddenPosthumously	= preview.querySelector( '.reward-preview-posthumously' ).innerText

		// Normal reward.
		if( ! isCustom ){
			popupRewardThumb.innerHTML = ''
			popupNoRewardThumb.classList.add( 'hidden' )
			popupRewardThumb.classList.remove( 'hidden' )
			popupRewardThumb.appendChild( previewThumb )
		}else{
			popupRewardThumb.classList.add( 'hidden' )
			popupNoRewardThumb.classList.remove( 'hidden' )
			rewardPopupCustomText.classList.remove( 'hidden' )
			customLabel.classList.remove( 'hidden' )
			customInput.value = previewTitle
		}

		popupReward.setAttribute( 'data-id', preview.dataset.id )
		popupRewardTitle.classList.remove( 'hidden' )
		popupRewardTitle.innerText = previewTitle

		// Pre-fill popup fields.
		edictInput.value 	= hiddenEdict
		numberInput.value 	= hiddenNumber
		dateInput.value 	= hiddenDate
		forInput.value 		= hiddenFor

		// Pre-fill popup texts.
		rewardPopupTextEdict.innerText	= hiddenEdict
		rewardPopupTextNumber.innerText	= `№ ${ hiddenNumber }`
		rewardPopupTextDate.innerText	= formatDate( hiddenDate )
		rewardPopupTextFor.innerText	= hiddenFor

		if( hiddenPosthumously ){
			posthumously.checked = true
			rewardPopupTextPosthumously.classList.remove( 'hidden' )
		}

		textAfterNumber.classList.remove( 'hidden' )
		textAfterDate.classList.remove( 'hidden' )

		hasRewardsBody.classList.add( 'hidden' )
		rewardsMainWrap.classList.remove( 'hidden' )
		popup.classList.remove( 'hidden' )

		window.scrollTo( { top: 0, left: 0 } )
	} )
}

/**
 * Clean reward popup form and texts.
 */
const cleanRewardPopup = () => {
	popup.classList.add( 'hidden' )
	rewardForm.reset()

	customInput.innerText 			= ''
	rewardPopupTextEdict.innerText 	= ''
	rewardPopupTextNumber.innerText = ''
	rewardPopupTextDate.innerText 	= ''
	rewardPopupTextFor.innerText 	= ''

	rewardPopupCustomText.classList.add( 'hidden' )
	customLabel.classList.add( 'hidden' )
	rewardPopupTextPosthumously.classList.add( 'hidden' )
	textAfterNumber.classList.add( 'hidden' )
	textAfterDate.classList.add( 'hidden' )
}

export const checkStep3Military = () => true