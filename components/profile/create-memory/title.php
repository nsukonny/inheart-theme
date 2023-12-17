<?php

/**
 * Profile memory pages.
 * Title part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$title			= $args['title'] ?? esc_html__( 'Створення спогаду', 'inheart' );
$memory_page_id	= $_GET['mp'] ? ( int ) ih_clean( $_GET['mp'] ) : null;
?>

<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
	<span class="profile-memories-title-text flex direction-column align-start">
		<a href="<?php echo get_the_permalink( pll_get_post( 1361 ) ) ?>" class="button button-return flex align-center">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
				<path d="M11.828 11.9992L14.657 14.8272L13.243 16.2422L9 11.9992L13.243 7.75619L14.657 9.17119L11.828 11.9992Z" fill="currentColor"></path>
			</svg>
			<span><?php _e( 'Повернутись', 'inheart' ) ?></span>
		</a>
		<?php echo esc_html( $title ) ?>
	</span>

	<span class="profile-memories-title-buttons flex flex-wrap align-center justify-between">
		<button class="menu-button profile-menu-button flex align-center">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
				<line y1="1" x2="14" y2="1" stroke="currentColor" />
				<line y1="13" x2="14" y2="13" stroke="currentColor" />
				<line y1="7" x2="14" y2="7" stroke="currentColor" />
			</svg>
			<span><?php _e( 'Меню', 'inheart' ) ?></span>
		</button>
		<button class="button lg primary min-width save-changes" form="add-new-memory-form" type="submit">
			<?php esc_html_e( 'Далі', 'inheart' ) ?>
		</button>

		<?php
		if( $memory_page_id ){
			?>
			<a href="<?php echo get_the_permalink( $memory_page_id ) ?>" class="button lg primary min-width see-memory-page hidden">
				<?php esc_html_e( "Перейти до сторінки пам’яті", 'inheart' ) ?>
			</a>
			<?php
		}
		?>
	</span>
</h1>

