<?php

/**
 * Textarea default layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$placeholder	= $args['placeholder'] ?? '';
$value			= $args['value'] ?? '';
$required		= $args['required'] ?? '';
?>

<label for="<?php echo esc_attr( $name ) ?>" class="label <?php echo esc_attr( $label_class ) ?>">
	<?php echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' ) ?>

	<textarea
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		<?php echo ( $required ? 'required' : '' ) ?>
	><?php echo esc_attr( $value ) ?></textarea>

	<span class="hint"></span>
</label>

