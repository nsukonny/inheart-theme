<?php

/**
 * Memory page single.
 * Memories section, add new memory button.
 *
 * @package WordPress
 * @subpackage inheart
 */

$url	= $args['url'] ?? '#';
$lang	= $args['url'] ?? 'uk';
$class	= isset( $args['class'] ) ? " {$args['class']}" : '';
?>

<div class="single-memory-memories-add flex justify-center<?php echo esc_attr( $class ) ?>">
	<a
		href="<?php echo esc_url( $url ) ?>"
		class="btn xl secondary outlined single-memory-no-memories-btn"
		title="<?php echo pll_translate_string( 'Додати спогад про людину', $lang ) ?>"
	>
		<?php echo pll_translate_string( 'Додати спогад про людину', $lang ) ?>
	</a>
</div>

