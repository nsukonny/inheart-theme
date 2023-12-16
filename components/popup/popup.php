<?php

/**
 * Popup layout.
 *
 * @package    WordPress
 * @subpackage inheart
 */

if( ! $text = $args['text'] ?? null ) return;

$class		= $args['class'] ?? '';
$label_yes	= $args['label_yes'] ?? __( 'Так', 'inheart' );
$label_no	= $args['label_no'] ?? __( 'Ні', 'inheart' );
?>

<div class="popup-confirm hidden <?php echo esc_attr( $class ) ?>">
	<div class="popup-confirm-text"><?php echo esc_html( $text ) ?></div>
	<div class="popup-confirm-buttons flex flex-wrap">
		<div class="button-wrap">
			<button class="button negative popup-confirm-yes" type="button"><?php echo esc_html( $label_yes ) ?></button>
		</div>
		<div class="button-wrap">
			<button class="button popup-confirm-no" type="button"><?php echo esc_html( $label_no ) ?></button>
		</div>
	</div>
</div>

