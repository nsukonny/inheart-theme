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
$born_at	= ih_convert_input_date( get_field( 'born_at', $page_id ), 'dots' );
$died_at	= ih_convert_input_date( get_field( 'died_at', $page_id ), 'dots' );
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
		<div class="profile-memory-preview-dates flex align-center">
			<span class="profile-memory-preview-date born"><?php echo esc_attr( $born_at ) ?></span>
			<span>–</span>
			<span class="profile-memory-preview-date died"><?php echo esc_attr( $died_at ) ?></span>
		</div>
		<div class="profile-memory-preview-bottom flex flex-wrap align-center">
			<span class="profile-memory-preview-type">
				<?php esc_html_e( "Коротка сторінка пам’яті", 'inheart' ) ?>
			</span>
			<button class="btn simple">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
					<path d="M4.82867 12.4997H2V9.67106L9.62333 2.04773C9.74835 1.92275 9.91789 1.85254 10.0947 1.85254C10.2714 1.85254 10.441 1.92275 10.566 2.04773L12.452 3.93373C12.577 4.05875 12.6472 4.22829 12.6472 4.40506C12.6472 4.58184 12.577 4.75138 12.452 4.8764L4.82867 12.4997ZM2 13.8331H14V15.1664H2V13.8331Z" fill="#F7B941"/>
				</svg>
				<?php esc_html_e( 'Змінити', 'inheart' ) ?>
			</button>
		</div>
	</div>
</div><!-- .profile-settings-memory-preview -->

