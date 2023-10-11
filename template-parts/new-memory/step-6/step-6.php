<?php

/**
 * New Memory page template.
 * Step 6.
 *
 * @see Page Template: New Memory -> Step 6
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_page_id = $_SESSION['memory_page_id'] ?? null ) return;

$is_edit		= isset( $_GET['edit'] ) && $_GET['edit'] == 1;
$image			= get_field( 'page_created_img' );
$text			= get_field( 'page_created_text' );
$text_updated	= get_field( 'page_updated_text' );
?>

<section id="new-memory-step-6" class="new-memory-step new-memory-step-6 page-created direction-column justify-center">
	<div class="container direction-column align-center">
		<div class="page-created-tip flex justify-center align-center">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="12" cy="12" r="12" fill="#D0AD60"/>
				<path d="M17.6139 8.15706C17.8033 8.35802 17.7939 8.67446 17.5929 8.86386L9.66309 16.3378C9.42573 16.5615 9.05311 16.5546 8.82417 16.3222L6.14384 13.6019C5.95003 13.4052 5.95237 13.0886 6.14907 12.8948C6.34578 12.701 6.66235 12.7033 6.85616 12.9001L9.26192 15.3417L16.9071 8.13614C17.108 7.94674 17.4245 7.95611 17.6139 8.15706Z" fill="#011C1A"/>
			</svg>

			<?php
			if( $is_edit ) esc_html_e( "Сторінку пам’яті оновлено", 'inheart' );
			else esc_html_e( "Сторінку пам’яті створено", 'inheart' );
			?>
		</div>

		<div class="page-created-info flex direction-column align-center">
			<div class="page-created-thumb flex justify-center align-center">
				<div class="page-created-thumb-border"></div>
				<div class="page-created-thumb-img">
					<?php
					if( has_post_thumbnail( $memory_page_id ) )
						echo get_the_post_thumbnail( $memory_page_id, 'full' );
					?>
				</div>
			</div>

			<div class="page-created-fullname flex direction-column align-center">
				<div class="page-created-firstname"></div>
				<div class="page-created-lastname"></div>
			</div>

			<div class="page-created-dates"></div>
		</div>

		<?php
		if( $image )
			echo '<div class="page-created-image flex justify-center">', wp_get_attachment_image( $image['id'], 'ih-illustration' ), '</div>';

		if( $is_edit ){
			if( $text_updated ) echo '<div class="page-created-text">', $text_updated, '</div>';
		}else{
			if( $text ) echo '<div class="page-created-text">', $text, '</div>';
		}

		?>
	</div>
</section><!-- #new-memory-step-6 -->

