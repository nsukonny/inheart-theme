<?php

/**
 * Input checkbox layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$size			= $args['size'] ?? 'sm';
$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? '';
$value			= $args['value'] ?? '';
$required		= $args['required'] ?? '';
$extra_attrs    = $args['extra_attrs'] ?? '';
?>

<div class="checkbox-wrapper <?php echo esc_attr( $size ) ?>">
	<input
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		type="checkbox"
		<?php echo ( $value ? 'checked' : '' ) ?>
		<?php echo ( $required ? 'required' : '' ) ?>
		<?php echo $extra_attrs ?>
		class="checkbox-field"
	/>
	<label for="<?php echo esc_attr( $name ) ?>" class="label-checkbox <?php echo esc_attr( $label_class ) ?>">
		<?php echo $label ?>
	</label>
</div>

