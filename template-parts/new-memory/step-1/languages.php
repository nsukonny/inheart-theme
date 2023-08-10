<?php

/**
 * New Memory page template.
 * Step 1. Languages.
 *
 * @see Page Template: New Memory -> Step 1
 *
 * @package WordPress
 * @subpackage inheart
 */

$language	= $args['language'] ?? '';
$lang_uk	= ( ! $language || $language === 'uk' ) ? ' active' : '';
?>

<div class="new-memory-langs inline-flex flex-wrap">
	<button class="new-memory-lang<?php echo $lang_uk ?>" data-lang="uk">
		<?php esc_html_e( 'Українська', 'inheart' ) ?>
	</button>
	<button class="new-memory-lang<?php echo ( $language === 'ru-RU' ? ' active' : '' ) ?>" data-lang="ru-RU">
		<?php esc_html_e( 'Російська', 'inheart' ) ?>
	</button>
	<button class="new-memory-lang<?php echo ( $language === 'en-US' ? ' active' : '' ) ?>" data-lang="en-US">
		<?php esc_html_e( 'Англійська', 'inheart' ) ?>
	</button>
	<div class="new-memory-lang-bg"></div>
</div>

