<?php

/**
 * Template name: New Memory
 *
 * @package WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'new-memory', THEME_URI . '/static/css/pages/new-memory.min.css', [], THEME_VERSION );
wp_enqueue_script( 'new-memory', THEME_URI . '/static/js/new-memory/new-memory.min.js', [], THEME_VERSION, true );

ih_create_new_memory_page();

$lang_changed = isset( $_GET['langchanged'] ) && $_GET['langchanged'] == 1;
?>

<style>
    .new-memory .popup {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        padding: 100px 20px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 100;
        background-color: rgba(0, 0, 0, 0.81);
        cursor: pointer;
        overflow-y: auto
    }
</style>

<main class="main new-memory flex direction-column" data-initial-step="<?php echo ( $lang_changed ? 1 : 0 ) ?>">
	<div class="popup popup-loader" style="background-color: #fff">
		<div class="tmp-loader"></div>
	</div>

	<?php
	get_template_part( 'template-parts/new-memory/step-0/step', '0', ['hidden' => $lang_changed] );
	get_template_part( 'template-parts/new-memory/step-1/step', '1', ['active' => $lang_changed] );
	get_template_part( 'template-parts/new-memory/step-2-military/step', '2' );
	get_template_part( 'template-parts/new-memory/step-3-military/step', '3' );
	get_template_part( 'template-parts/new-memory/step-2/step', '2' );
	get_template_part( 'template-parts/new-memory/step-3/step', '3' );
	get_template_part( 'template-parts/new-memory/step-4/step', '4' );
	get_template_part( 'template-parts/new-memory/step-5/step', '5' );
	get_template_part( 'template-parts/new-memory/step-6/step', '6' );
	get_template_part( 'template-parts/new-memory/step-6/step', '6-military' );
	?>
</main>

<?php
get_template_part( 'template-parts/new-memory/order-now-popup' );
get_template_part( 'template-parts/new-memory/footer', null, ['active_back' => $lang_changed] );

