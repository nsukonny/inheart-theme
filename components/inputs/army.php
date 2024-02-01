<?php

/**
 * Input for army type layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $types = $args['types'] ?? null ) return;

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$type			= $args['type'] ?? 'text';
$placeholder	= $args['placeholder'] ?? '';
$value			= $args['value'] ?? '';
$required		= $args['required'] ?? '';
$icon_lead		= $args['icon_lead'] ?? '';	// Icon file name with extension. Must be in the folder /static/img/
$icon_tail		= $args['icon_tail'] ?? '';	// ^ Same here.
$wrap_class		= $icon_lead ? ' select icon-lead' : ' select';
$wrap_class		.= $icon_tail ? ' icon-tail' : '';
?>

<label for="<?php echo esc_attr( $name ) ?>" class="label <?php echo esc_attr( $label_class ) ?>">
	<?php
	echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' );

	if( $icon_lead || $icon_tail ) echo '<span class="input-wrapper ' . esc_attr( $type ) . esc_attr( $wrap_class ) . '">';

	if( $icon_lead ){
		echo '<span class="input-icon lead">';
		get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/' . esc_attr( $icon_lead )] );
		echo '</span>';
	}
	?>

	<input
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		type="text"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		value="<?php echo esc_attr( $value ) ?>"
		<?php echo ( $required ? 'required' : '' ) ?>
	/>

	<?php
	if( $icon_tail ){
		echo '<span class="input-icon tail">';
			get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/' . esc_attr( $icon_tail )] );
		echo '</span>';
	}

	if( $icon_lead || $icon_tail ) echo '</span>';

	if( $types ){
		echo '<span class="options direction-column">';

		foreach( $types as $type )
			echo '<span class="option">', esc_html( $type['name'] ), '</span>';

		echo '</span>';
	}
	?>

	<span class="hint"></span>
</label>

