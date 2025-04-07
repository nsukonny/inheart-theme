<?php

/**
 * New Memory page template.
 * Step 2. Section content, military, last fight.
 *
 * @see Page Template: New Memory -> Step 2
 *
 * @package WordPress
 * @subpackage inheart
 */

if( $section = $args['section'] ?? null ){
	$text		= $section['text'] ?? '';
	$location	= $section['location'] ?? '';
	$index		= $section['index'] ?? 0;
}else{
	$text	= $location = '';
	$index	= 100500;
}

$class = isset( $args['class'] ) ? " {$args['class']}" : '';
?>

<div
	class="section-content section-content-last-fight<?php echo esc_attr( $class ) ?>"
	data-id="<?php echo esc_attr( $index ) ?>"
>
	<div class="section-content-title"><?php _e( 'Останній бій', 'inheart' ) ?></div>
	<textarea
		class="section-content-text"
		placeholder="<?php esc_attr_e( 'Напишіть детальну біографію', 'inheart' ) ?>"
	><?php echo esc_html( $text ) ?></textarea>

	<button
			class="section-remove"
			aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
			title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="content-remove-<?php echo esc_attr( $index ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
			</mask>
			<g mask="url(#content-remove-<?php echo esc_attr( $index ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>

	<form enctype="multipart/form-data" class="section-content-form section-content-form-np">
		<fieldset>
			<button class="button tetriary button-icon-lead last-fight-show-field<?php echo ( $location ? ' hidden' : '' ) ?>" type="button">
				<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_location_marker)">
						<path d="M8 16.3188L3.75734 12.0761C2.91823 11.237 2.34679 10.1679 2.11529 9.00401C1.88378 7.84013 2.0026 6.63373 2.45673 5.53738C2.91086 4.44103 3.6799 3.50396 4.66659 2.84467C5.65328 2.18539 6.81332 1.8335 8 1.8335C9.18669 1.8335 10.3467 2.18539 11.3334 2.84467C12.3201 3.50396 13.0891 4.44103 13.5433 5.53738C13.9974 6.63373 14.1162 7.84013 13.8847 9.00401C13.6532 10.1679 13.0818 11.237 12.2427 12.0761L8 16.3188ZM11.3 11.1334C11.9526 10.4808 12.397 9.64926 12.577 8.74403C12.7571 7.83879 12.6646 6.90051 12.3114 6.04781C11.9582 5.19512 11.36 4.46632 10.5926 3.95356C9.82519 3.4408 8.92296 3.16711 8 3.16711C7.07704 3.16711 6.17481 3.4408 5.40739 3.95356C4.63997 4.46632 4.04183 5.19512 3.68861 6.04781C3.33539 6.90051 3.24294 7.83879 3.42297 8.74403C3.603 9.64926 4.04741 10.4808 4.7 11.1334L8 14.4334L11.3 11.1334ZM8 9.16678C7.64638 9.16678 7.30724 9.0263 7.05719 8.77625C6.80715 8.5262 6.66667 8.18707 6.66667 7.83344C6.66667 7.47982 6.80715 7.14068 7.05719 6.89064C7.30724 6.64059 7.64638 6.50011 8 6.50011C8.35362 6.50011 8.69276 6.64059 8.94281 6.89064C9.19286 7.14068 9.33334 7.47982 9.33334 7.83344C9.33334 8.18707 9.19286 8.5262 8.94281 8.77625C8.69276 9.0263 8.35362 9.16678 8 9.16678Z" fill="#F7B941"/>
					</g>
					<defs>
						<clipPath id="clip0_location_marker">
							<rect width="16" height="16" fill="white" transform="translate(0 0.5)"/>
						</clipPath>
					</defs>
				</svg>
				<span class="label-button-text">
					<?php _e( 'Додати місце останнього бою', 'inheart' ) ?>
				</span>
			</button>

			<?php
			get_template_part( 'components/inputs/cities', null, [
				'name'			=> 'city',
				'label'			=> __( 'Додати місце останнього бою', 'inheart' ),
				'label_class'	=> 'full section-content-np' . ( $location ? '' : ' hidden' ),
				'placeholder'	=> __( 'Почніть вводити назву', 'inheart' ),
				'required'		=> 0,
				'value'			=> $location
			] );
			?>
		</fieldset>
	</form>
</div><!-- .section-content -->

