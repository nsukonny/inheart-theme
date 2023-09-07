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

<section class="profile-memories">
	<div class="container">
		<div class="profile-memories-inner">
			<?php if( $title ) echo '<h1 class="profile-memories-title">' . esc_html( $title ) . '</h1>' ?>

			<div class="profile-memories-list flex flex-wrap">
				<?php
				foreach( $memory_pages as $page )
					get_template_part( 'template-parts/profile/memory-card', null, ['id' => $page->ID] );
				?>

				<div class="memory-card create flex direction-column">
					<a
						href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>"
						class="memory-card-create-text flex direction-column align-center justify-center"
						title="<?php esc_attr_e( "Створити нову сторінку пам'яті", 'inheart' ) ?>"
					>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_758_32180" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 7C11.45 7 11 7.45 11 8V11H8C7.45 11 7 11.45 7 12C7 12.55 7.45 13 8 13H11V16C11 16.55 11.45 17 12 17C12.55 17 13 16.55 13 16V13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H13V8C13 7.45 12.55 7 12 7ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_758_32180)">
								<rect width="24" height="24" fill="#F7B941"/>
							</g>
						</svg>
						<?php esc_html_e( "Створити нову сторінку пам'яті", 'inheart' ) ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

