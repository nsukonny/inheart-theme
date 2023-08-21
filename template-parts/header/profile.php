<?php

/**
 * Header profile block.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( is_user_logged_in() ){
	$meta		= get_user_meta( get_current_user_id() );
	$fullname	= "{$meta['first_name'][0]} {$meta['last_name'][0]}";
}else{
	$fullname = '';
}
?>

<div class="header-profile flex align-center">
	<span class="header-profile-icon">
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0.5 13.4922C0.5 13.0822 0.667765 12.6902 0.964286 12.4071L1.44048 11.9526C5.11151 8.44842 10.8885 8.44842 14.5595 11.9526L15.0357 12.4071C15.3322 12.6902 15.5 13.0822 15.5 13.4922V14C15.5 14.8284 14.8284 15.5 14 15.5H2C1.17157 15.5 0.5 14.8284 0.5 14V13.4922Z" stroke="#F7B941"/>
			<circle cx="8" cy="4" r="3.5" stroke="#F7B941"/>
		</svg>
	</span>
	<span class="header-profile-name"><?php echo esc_html( $fullname ) ?></span>
	<button class="header-profile-button" type="button" aria-label="<?php esc_attr_e( 'Відкрити меню профілю', 'inheart' ) ?>">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g id="Navigation / arrow drop_up">
				<mask id="mask0_1564_33234" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="8" y="9" width="8" height="6">
					<g id="Icon Mask">
						<path id="Round" d="M15.2934 11.4134L12.7034 14.0034C12.3134 14.3934 11.6834 14.3934 11.2934 14.0034L8.70337 11.4134C8.07337 10.7834 8.52337 9.7034 9.41337 9.7034L14.5934 9.7034C15.4834 9.7034 15.9234 10.7834 15.2934 11.4134Z" fill="black"/>
					</g>
				</mask>
				<g mask="url(#mask0_1564_33234)">
					<rect id="Color Fill" x="24" y="24" width="24" height="24" transform="rotate(-180 24 24)" fill="#011C1A"/>
				</g>
			</g>
		</svg>
	</button>
</div>

