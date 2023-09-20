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

				<div id="tariff-right" class="tariff-right hidden">
					<h2 class="h2 tariff-title right">
						<button class="tariff-back">
							<svg class="hide-after-lg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
								<path d="M9.15898 16.3666C9.36292 16.5528 9.67918 16.5384 9.86536 16.3345C10.0515 16.1305 10.0371 15.8143 9.8332 15.6281L3.66535 9.99736H17.4961C17.7722 9.99736 17.9961 9.7735 17.9961 9.49736C17.9961 9.22122 17.7722 8.99736 17.4961 8.99736H3.66824L9.8332 3.36927C10.0371 3.18309 10.0515 2.86684 9.86536 2.66289C9.67918 2.45895 9.36292 2.44456 9.15898 2.63074L2.24263 8.94478C2.10268 9.07254 2.02285 9.24008 2.00314 9.41323C1.99851 9.44058 1.99609 9.46869 1.99609 9.49736C1.99609 9.52423 1.99821 9.55061 2.00229 9.57633C2.02047 9.75224 2.10058 9.9229 2.24263 10.0526L9.15898 16.3666Z" fill="#F7B941"/>
							</svg>
						</button>
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

