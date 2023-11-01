<?php

/**
 * Profile memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_pages = $args['pages'] ?? null ) return;

$title = get_field( 'memory_pages_title' );
?>

<section class="profile-memories profile-body">
	<?php
	if( $title ){
		?>
		<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
			<?php echo esc_html( $title ) ?>
			<a class="btn lg primary" href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>">
				<?php esc_html_e( "Створити сторінку пам'яті", 'inheart' ) ?>
			</a>
		</h1>
		<?php
	}
	?>

	<div class="profile-memories-list flex flex-wrap">
		<?php
		foreach( $memory_pages as $page )
			get_template_part( 'template-parts/profile/memory-card', null, ['id' => $page->ID] );
		?>
	</div>
</section>

