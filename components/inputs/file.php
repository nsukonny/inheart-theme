<?php

/**
 * Input file layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$value			= $args['value'] ?? '';
$required		= $args['required'] ?? '';
$icon_lead		= $args['icon_lead'] ?? '';	// Icon file name with extension. Must be in the folder /static/img/
$wrap_class		= $icon_lead ? ' icon-lead' : '';
?>

<div class="label-file-wrap">
	<label for="<?php echo esc_attr( $name ) ?>" class="label label-file <?php echo esc_attr( $label_class ) ?>">
		<?php
		if( $icon_lead ) echo '<span class="input-wrapper button outlined file' . esc_attr( $wrap_class ) . '">';

		if( $icon_lead ){
			echo '<span class="input-icon lead">';
			get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/' . esc_attr( $icon_lead )] );
			echo '</span>';
		}

		echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' );
		?>

		<input
			id="<?php echo esc_attr( $name ) ?>"
			name="<?php echo esc_attr( $name ) ?>"
			type="file"
			value="<?php echo esc_attr( $value ) ?>"
			<?php echo ( $required ? 'required' : '' ) ?>
		/>

		<?php if( $icon_lead ) echo '</span>' ?>

		<span class="hint"></span>
	</label>

	<div class="label-file-wrap-result hidden">
		<button class="button button-icon round danger" type="button">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_905_132" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="2" width="12" height="16">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M12.9165 3.33333H14.9998C15.4582 3.33333 15.8332 3.70833 15.8332 4.16667C15.8332 4.625 15.4582 5 14.9998 5H4.99984C4.5415 5 4.1665 4.625 4.1665 4.16667C4.1665 3.70833 4.5415 3.33333 4.99984 3.33333H7.08317L7.67484 2.74167C7.82484 2.59167 8.0415 2.5 8.25817 2.5H11.7415C11.9582 2.5 12.1748 2.59167 12.3248 2.74167L12.9165 3.33333ZM6.6665 17.5C5.74984 17.5 4.99984 16.75 4.99984 15.8333V7.5C4.99984 6.58333 5.74984 5.83333 6.6665 5.83333H13.3332C14.2498 5.83333 14.9998 6.58333 14.9998 7.5V15.8333C14.9998 16.75 14.2498 17.5 13.3332 17.5H6.6665Z" fill="black"/>
				</mask>
				<g mask="url(#mask0_905_132)">
					<rect width="20" height="20" fill="currentColor"/>
				</g>
			</svg>
		</button>
	</div>
</div>

