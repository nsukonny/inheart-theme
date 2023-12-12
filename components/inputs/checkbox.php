<?php

/**
 * Input checkbox layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? '';
$value			= $args['value'] ?? '';
$required		= $args['required'] ?? '';
?>

<div class="checkbox-wrapper">
	<input
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		type="checkbox"
		<?php echo ( $value ? 'checked' : '' ) ?>
		<?php echo ( $required ? 'required' : '' ) ?>
	/>
	<label for="<?php echo esc_attr( $name ) ?>" class="label-checkbox <?php echo esc_attr( $label_class ) ?>">
		<?php echo $label ?>
	</label>
</div>

