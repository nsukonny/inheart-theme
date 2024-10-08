<?php

/**
 * New Memory page template.
 * Step 2. Section in the sidebar.
 *
 * @see Page Template: New Memory -> Step 2
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $section = $args['section'] ?? null ) return;

$memory_page_id		= $_SESSION['memory_page_id'] ?? 0;
$hide_for_military	= $section['hide_for_military'] ?? null;
$class				= '';

// If this is military theme and section is hidden for military.
if( $memory_page_id && $hide_for_military ) $class = ' hide-for-military';

$key			= $args['key'] ?? 0;
$ready_sections	= $args['ready_sections'] ?? null;
$sec_title		= $section['title'];

// No title - exit.
if( ! $sec_title ) return;

// If this section is already added - exit.
if( ! empty( $ready_sections ) ){
	foreach( $ready_sections as $ready_section ){
		if( $ready_section['index'] == $key ) return;
	}
}

$is_custom_title	= $section['is_custom_title'] ? ' custom' : '';
$thumb				= ( $is_custom_title && $section['thumb'] )
					? ' data-thumb="' . esc_url( $section['thumb'] ) . '"' : '';

// Custom section exists - exit.
if( $is_custom_title && ! empty( $ready_sections ) ){
	foreach( $ready_sections as $i => $ready_section ){
		if( $ready_section['own_title'] ) return;
	}
}
?>

<div
	class="section flex align-center<?php echo esc_attr( $is_custom_title ), esc_attr( $class ) ?>"
	data-order="<?php echo esc_attr( $key ) ?>"
	data-id="<?php echo esc_attr( $key ) ?>"
	data-title="<?php echo esc_attr( $sec_title ) ?>"
	<?php echo $thumb ?>
>
	<div class="section-label">
		<?php echo esc_html( $sec_title ) ?>
	</div>
	<button
		class="section-add"
		aria-label="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
		title="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="mask-add-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.48 6.48 2 12 2C17.52 2 22 6.48 22 12C22 17.52 17.52 22 12 22C6.48 22 2 17.52 2 12ZM13 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H13V8C13 7.45 12.55 7 12 7C11.45 7 11 7.45 11 8V11H8C7.45 11 7 11.45 7 12C7 12.55 7.45 13 8 13H11V16C11 16.55 11.45 17 12 17C12.55 17 13 16.55 13 16V13Z" fill="black"/>
			</mask>
			<g mask="url(#mask-add-<?php echo esc_attr( $key ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>
	<button
		class="section-remove"
		aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
		title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="mask-remove-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
			</mask>
			<g mask="url(#mask-remove-<?php echo esc_attr( $key ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>
	<button
		class="section-drag"
		aria-label="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
		title="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="mask-drag-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="4" width="10" height="16">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9 4C7.9 4 7 4.9 7 6C7 7.1 7.9 8 9 8C10.1 8 11 7.1 11 6C11 4.9 10.1 4 9 4ZM7 12C7 10.9 7.9 10 9 10C10.1 10 11 10.9 11 12C11 13.1 10.1 14 9 14C7.9 14 7 13.1 7 12ZM9 20C10.1 20 11 19.1 11 18C11 16.9 10.1 16 9 16C7.9 16 7 16.9 7 18C7 19.1 7.9 20 9 20ZM17 6C17 7.1 16.1 8 15 8C13.9 8 13 7.1 13 6C13 4.9 13.9 4 15 4C16.1 4 17 4.9 17 6ZM15 10C13.9 10 13 10.9 13 12C13 13.1 13.9 14 15 14C16.1 14 17 13.1 17 12C17 10.9 16.1 10 15 10ZM13 18C13 16.9 13.9 16 15 16C16.1 16 17 16.9 17 18C17 19.1 16.1 20 15 20C13.9 20 13 19.1 13 18Z" fill="black"/>
			</mask>
			<g mask="url(#mask-drag-<?php echo esc_attr( $key ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>
</div><!-- .section -->

