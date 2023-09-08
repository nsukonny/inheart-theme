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

$language	= $args['language'] ?? get_locale();
$lang_uk	= ( ! $language || $language === 'uk' ) ? ' active' : '';
?>

<div class="new-memory-langs inline-flex flex-wrap">
	<a href="<?php echo get_the_permalink( get_page_by_path( 'stvorennya-storinki-pamyati' )->ID ) ?>" class="new-memory-lang<?php echo $lang_uk ?>" data-lang="uk">
		<?php esc_html_e( 'Українська', 'inheart' ) ?>
	</a>
	<a href="<?php echo get_the_permalink( get_page_by_path( 'sozdanie-straniczy-pamyati' )->ID ) ?>" class="new-memory-lang<?php echo ( $language === 'ru_RU' ? ' active' : '' ) ?>" data-lang="ru_RU">
		<?php esc_html_e( 'Російська', 'inheart' ) ?>
	</a>
	<a href="<?php echo get_the_permalink( get_page_by_path( 'creating-a-new-memory-page' )->ID ) ?>" class="new-memory-lang<?php echo ( $language === 'en_US' ? ' active' : '' ) ?>" data-lang="en_US">
		<?php esc_html_e( 'Англійська', 'inheart' ) ?>
	</a>
	<div class="new-memory-lang-bg"></div>
</div>

