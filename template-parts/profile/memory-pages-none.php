<?php

/**
 * Profile - no memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

$img	= get_field( 'no_memory_pages_img' );
$title	= get_field( 'no_memory_pages_title' );
?>

<section class="profile-memories none profile-body">
	<?php get_template_part( 'components/profile/memory-pages/title' ) ?>

	<div class="profile-memories-inner flex direction-column align-center">
		<?php
		if( $img )
			echo '<div class="profile-memories-img">' . wp_get_attachment_image( $img['id'], 'ih-profile-media' ) . '</div>';

		if( $title )
			echo '<h1 class="profile-memories-title">' . esc_html( $title ) . '</h1>';
		?>

		<a href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>" class="button primary lg">
			<?php esc_html_e( "Створити сторінку пам'яті", 'inheart' ) ?>
		</a>
	</div>
</section>

