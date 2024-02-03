const
	stepData = localStorage.getItem( 'ih-step-3-military' ) ?
		JSON.parse( localStorage.getItem( 'ih-step-3-military' ) ) : {}

export const addReward = () => {
	const
		addRewardBtn	= document.querySelector( 'button.add-reward' ),
		noRewardsBody	= document.querySelector( '.no-rewards-body' ),
		rewardsMainWrap	= document.querySelector( '.rewards-main-wrap' )

	if( ! addRewardBtn || ! noRewardsBody || ! rewardsMainWrap ) return

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
			preview	= target.closest( '.reward-preview' )

		if( ! preview ) return

		const
			activePreview	= document.querySelector( '.reward-preview.active' ),
			breadcrumbs		= document.querySelector( '.rewards-sidebar-breadcrumbs' )

		if( activePreview ) activePreview.classList.remove( 'active' )

		preview.classList.add( 'active' )

		if( breadcrumbs ){
			breadcrumbs.querySelector( '.active' ).classList.remove( 'active' )
			breadcrumbs.querySelector( 'span:last-child' ).classList.add( 'active' )
		}
	} )
}