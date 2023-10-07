<?php

/**
 * New Memory page template.
 * Step 4, external resources links. Single link layout.
 *
 * @see Page Template: New Memory -> Step 4
 *
 * @package WordPress
 * @subpackage inheart
 */

if( $link = $args['link'] ?? null ){
	$link_url		= $link['url'];
	$link_title		= $link['title'];
	$wrapper_class	= ' filled';
}else{
	$link_url		= $link_title = '';
	$wrapper_class	= '';
}

$key = $args['key'] ?? 0;
?>

<div class="step-media-link flex flex-wrap<?php echo esc_attr( $wrapper_class ) ?>" data-id="<?php echo esc_attr( $key ) ?>">
	<label for="media-link-<?php echo esc_attr( $key ) ?>" class="label dark half">
		<span class="label-text"><?php esc_html_e( 'Посилання', 'inheart' ) ?></span>
		<input
			id="media-link-<?php echo esc_attr( $key ) ?>"
			name="media-link-<?php echo esc_attr( $key ) ?>"
			type="text"
			data-type="url"
			placeholder="<?php esc_attr_e( 'Додати посилання', 'inheart' ) ?>"
			value="<?php echo esc_attr( $link_url ) ?>"
			required
		/>
	</label>
	<label for="media-name-link-<?php echo esc_attr( $key ) ?>" class="label dark half end">
		<span class="label-text"><?php esc_html_e( 'Назва посилання', 'inheart' ) ?></span>
		<input
			id="media-name-link-<?php echo esc_attr( $key ) ?>"
			name="media-name-link-<?php echo esc_attr( $key ) ?>"
			type="text"
			data-type="title"
			placeholder="<?php esc_attr_e( 'Назва посилання', 'inheart' ) ?>"
			value="<?php echo esc_attr( $link_title ) ?>"
			required
		/>
	</label>
	<button
		class="media-link-delete"
		title="<?php esc_attr_e( 'Видалити посилання', 'inheart' ) ?>"
		type="button"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="mask_link_<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
			</mask>
			<g mask="url(#mask_link_<?php echo esc_attr( $key ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>
</div><!-- .step-media-link -->

