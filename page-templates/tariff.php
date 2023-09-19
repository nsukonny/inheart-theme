<?php

/**
 * Template name: Tariff
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() || ! ( $memory_page_id = $_GET['id'] ?? null ) )
	wp_die( esc_html__( 'Тільки авторизовані користувачі', 'inheart' ) );

get_header();

wp_enqueue_style( 'tariff', THEME_URI . '/static/css/pages/tariff.min.css', [], THEME_VERSION );
wp_enqueue_script( 'tariff', THEME_URI . '/static/js/tariff/tariff.min.js', [], THEME_VERSION, true );
?>

<main class="main tariff flex direction-column">
	<section class="tariff-wrapper">
		<div class="container direction-column align-start">
			<div class="tariff-inner flex flex-wrap justify-between">
				<div class="tariff-left">
					<h1 class="h2 tariff-title">
						<?php the_title() ?>
					</h1>

					<div class="tariff-duration flex align-center">
						<span><?php esc_html_e( 'На один рік', 'inheart' ) ?></span>
						<span class="switch"></span>
						<span><?php esc_html_e( 'На десять років', 'inheart' ) ?></span>
					</div>

					<?php get_template_part( 'template-parts/tariff/plans', null, ['id' => $memory_page_id] ) ?>
				</div>

				<div class="tariff-right hidden">
					<h2 class="h2 tariff-title right">
						<?php _e( 'Ваше замовлення', 'inheart' ) ?>
					</h2>

					<?php get_template_part( 'template-parts/tariff/order', null, ['id' => $memory_page_id] ) ?>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();

