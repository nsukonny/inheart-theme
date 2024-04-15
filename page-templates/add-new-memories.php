<?php

/**
 * Template name: Add New Memories
 *
 * @package WordPress
 * @subpackage inheart
 */

$memory_page = $_GET['mp'] ?? null;

if( ! is_user_logged_in() ){
	$_SESSION['redirect_to_mp'] = get_the_permalink( $memory_page );
	wp_redirect( get_the_permalink( pll_get_post( ih_get_login_page_id() ) ) );
	exit;
}

// No destination memory page ID - redirect to Profile Memories.
if( ! $memory_page ){
	wp_redirect( get_the_permalink( pll_get_post( ih_get_profile_memories_page_id() ) ) );
	exit;
}

get_template_part( 'components/header/profile' );

wp_enqueue_style( 'add-new-memories', THEME_URI . '/static/css/pages/add-new-memories.min.css', [], THEME_VERSION );
wp_enqueue_script( 'add-new-memories', THEME_URI . '/static/js/add-new-memories/add-new-memories.min.js', [], THEME_VERSION, true );

$bottom_text = get_field( 'memory_created_bottom' );
?>

<main class="main add-new-memories flex flex-wrap">
	<?php get_template_part( 'components/sidebar/sidebar' ) ?>

	<section class="profile-body add-new-memories-inner flex flex-wrap align-start">
		<?php
		get_template_part( 'components/profile/create-memory/title' );
		get_template_part( 'components/profile/memories/add-new-memory-form', null, ['memory_page' => $memory_page] );
		?>
		<div class="add-new-memory-info">
			<?php
			get_template_part( 'components/cards/memory-page/card', null, [
				'id'			=> $memory_page,
				'front'			=> 1,
				'date_format'	=> 'lang',
				'mobile_dates'	=> 1
			] );
			?>
		</div>

		<?php
		if( $bottom_text ){
			?>
			<div class="add-new-memory-bottom flex align-center justify-between hidden">
				<div><?php echo $bottom_text ?></div>
				<a href="<?php echo get_the_permalink( pll_get_post( ih_get_memory_creation_page_id() ) ) ?>" class="button lg primary">
					<?php _e( 'Створити сторінку спогадів', 'inheart' ) ?>
				</a>
			</div>
			<?php
		}
		?>
	</section>
</main>

<?php
get_footer();

