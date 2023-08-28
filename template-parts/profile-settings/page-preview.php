<?php

/**
 * Profile Settings page template.
 * Memory page preview.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $page_id = $args['id'] ?? null ) return;

$name		= get_field( 'first_name', $page_id ) . ' ' . get_field( 'middle_name', $page_id );
$last_name	= get_field( 'last_name', $page_id );
?>

<div class="profile-memory-preview flex flex-wrap">
	<?php
	if( has_post_thumbnail( $page_id ) ){
		?>
		<div class="profile-memory-preview-thumb">
			<?php echo get_the_post_thumbnail( $page_id, 'thumbnail' ) ?>
		</div>
		<?php
	}
	?>

	<div class="profile-memory-preview-info flex direction-column">
		<div class="profile-memory-preview-name flex direction-column">
			<div><?php echo esc_html( $name ) ?></div>
			<div><?php echo esc_html( $last_name ) ?></div>
		</div>
		<div class="profile-memory-preview-bottom flex flex-wrap">
			<div class="profile-memory-preview-type">
				<?php esc_html_e( "Коротка сторінка пам’яті", 'inheart' ) ?>
			</div>
			<button class="btn simple">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_1566_33503)">
						<path d="M15.1813 0.926893C14.0291 -0.284951 12.1047 -0.309226 10.9222 0.873168L1.54741 10.2475C1.21958 10.5754 0.992038 10.9899 0.891482 11.4424L0.0138652 15.3923C-0.0232157 15.5592 0.0275431 15.7335 0.148442 15.8544C0.26934 15.9753 0.443618 16.026 0.610502 15.9889L4.53689 15.1157C5.00432 15.0118 5.43243 14.7767 5.77103 14.4381L15.129 5.08003C16.27 3.939 16.2933 2.09631 15.1813 0.926893ZM11.6293 1.58029C12.4143 0.795384 13.6917 0.811498 14.4566 1.61596C15.1948 2.39225 15.1793 3.61548 14.4219 4.37293L13.7507 5.04418L10.958 2.25155L11.6293 1.58029ZM10.2509 2.95864L13.0436 5.7513L5.06391 13.731C4.85976 13.9352 4.60164 14.0769 4.31982 14.1396L1.1605 14.8421L1.86768 11.6593C1.92698 11.3924 2.06117 11.148 2.2545 10.9547L10.2509 2.95864Z" fill="#F7B941"/>
					</g>
					<defs>
						<clipPath id="clip0_1566_33503">
							<rect width="16" height="16" fill="white"/>
						</clipPath>
					</defs>
				</svg>
				<?php esc_html_e( 'Змінити', 'inheart' ) ?>
			</button>
		</div>
	</div>
</div><!-- .profile-settings-memory-preview -->

