<?php

/**
 * Input date layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$placeholder	= $args['placeholder'] ?? '';
$value			= $args['value'] ?? '';
$autocomplete	= $args['autocomplete'] ?? '';
$required		= $args['required'] ?? '';
?>

<label for="<?php echo esc_attr( $name ) ?>" class="label <?php echo esc_attr( $label_class ) ?>">
	<?php
	echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' );

	echo '<span class="input-wrapper date">';
	?>

	<input
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		type="text"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		value="<?php echo esc_attr( $value ) ?>"
		<?php echo ( $autocomplete ? 'autocomplete="' . esc_attr( $autocomplete ) . '"' : '' ) ?>
		<?php echo ( $required ? 'required' : '' ) ?>
		onfocus="this.type='date';this.showPicker()"
		onclick="this.type='date'"
		onblur="(!this.value ? this.type='text' : this.type='date')"
	/>
	<span class="input-icon tail">
		<img src="<?php echo THEME_URI ?>/static/img/calendar.svg" alt="" />
	</span>
	<span class="hint"></span>
</label>

