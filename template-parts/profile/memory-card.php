<?php

/**
 * Profile memory single page card.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$only_front	= $args['front'] ?? null;
$theme		= get_field( 'theme', $id );
$firstname	= get_field( 'first_name', $id );
$middlename	= get_field( 'middle_name', $id );
$lastname	= get_field( 'last_name', $id );
$born_at	= ih_convert_input_date( get_field( 'born_at', $id ), 'dots' );
$died_at	= ih_convert_input_date( get_field( 'died_at', $id ), 'dots' );

if( ! $firstname && ! $lastname ) return;
?>

<div class="memory-card <?php echo esc_attr( $theme ) ?>">
	<div class="memory-card-top page-created-info flex direction-column align-center">
		<?php get_template_part( 'template-parts/single/memory/thumb', null, ['id' => $id] ) ?>

		<div class="page-created-fullname flex direction-column align-center">
			<div class="page-created-firstname"><?php echo esc_html( "$firstname $middlename" ) ?></div>
			<div class="page-created-lastname">
				<?php echo esc_html( $lastname ) ?>
			</div>
		</div>

		<div class="page-created-dates flex align-center justify-center">
			<div class="memory-card-date born"><?php echo esc_attr( $born_at ) ?></div>
			<span>–</span>
			<div class="memory-card-date died"><?php echo esc_attr( $died_at ) ?></div>
		</div>
	</div><!-- .memory-card-top -->

	<?php
	if( ! $only_front ){
		?>
		<div class="memory-card-bottom flex direction-column align-center justify-center">
			<div class="memory-card-watch flex justify-center align-center">
				<a class="btn simple" href="<?php echo get_the_permalink( $id ) ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
						<path d="M7.99978 2.5C11.5944 2.5 14.5851 5.08667 15.2124 8.5C14.5858 11.9133 11.5944 14.5 7.99978 14.5C4.40511 14.5 1.41444 11.9133 0.787109 8.5C1.41378 5.08667 4.40511 2.5 7.99978 2.5ZM7.99978 13.1667C9.35942 13.1664 10.6787 12.7045 11.7417 11.8568C12.8047 11.009 13.5484 9.82552 13.8511 8.5C13.5473 7.17554 12.8031 5.99334 11.7402 5.14668C10.6773 4.30003 9.35865 3.83902 7.99978 3.83902C6.64091 3.83902 5.32224 4.30003 4.25936 5.14668C3.19648 5.99334 2.45229 7.17554 2.14844 8.5C2.45117 9.82552 3.19489 11.009 4.25787 11.8568C5.32085 12.7045 6.64013 13.1664 7.99978 13.1667ZM7.99978 11.5C7.20413 11.5 6.44107 11.1839 5.87846 10.6213C5.31585 10.0587 4.99978 9.29565 4.99978 8.5C4.99978 7.70435 5.31585 6.94129 5.87846 6.37868C6.44107 5.81607 7.20413 5.5 7.99978 5.5C8.79543 5.5 9.55849 5.81607 10.1211 6.37868C10.6837 6.94129 10.9998 7.70435 10.9998 8.5C10.9998 9.29565 10.6837 10.0587 10.1211 10.6213C9.55849 11.1839 8.79543 11.5 7.99978 11.5ZM7.99978 10.1667C8.4418 10.1667 8.86573 9.99107 9.17829 9.67851C9.49085 9.36595 9.66644 8.94203 9.66644 8.5C9.66644 8.05797 9.49085 7.63405 9.17829 7.32149C8.86573 7.00893 8.4418 6.83333 7.99978 6.83333C7.55775 6.83333 7.13383 7.00893 6.82127 7.32149C6.5087 7.63405 6.33311 8.05797 6.33311 8.5C6.33311 8.94203 6.5087 9.36595 6.82127 9.67851C7.13383 9.99107 7.55775 10.1667 7.99978 10.1667Z" fill="#F7B941"/>
					</svg>
					<?php esc_html_e( 'Переглянути сторінку', 'inheart' ) ?>
				</a>
			</div>
			<div class="memory-card-actions flex direction-column align-center">
				<button class="btn simple edit-memory" data-id="<?php echo esc_attr( $id ) ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
						<path d="M4.82867 12.4997H2V9.67106L9.62333 2.04773C9.74835 1.92275 9.91789 1.85254 10.0947 1.85254C10.2714 1.85254 10.441 1.92275 10.566 2.04773L12.452 3.93373C12.577 4.05875 12.6472 4.22829 12.6472 4.40506C12.6472 4.58184 12.577 4.75138 12.452 4.8764L4.82867 12.4997ZM2 13.8331H14V15.1664H2V13.8331Z" fill="#F7B941"/>
					</svg>
					<span><?php esc_html_e( 'Редагувати', 'inheart' ) ?></span>
				</button>
				<button class="btn simple expand-to-full" data-id="<?php echo esc_attr( $id ) ?>" disabled>
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
						<path d="M8.50016 11.8337L4.58149 14.227L5.64682 9.76033L2.16016 6.77366L6.73682 6.40699L8.50016 2.16699L10.2635 6.40699L14.8408 6.77366L11.3535 9.76033L12.4188 14.227L8.50016 11.8337Z" fill="#C3DAE2" fill-opacity="0.24"/>
					</svg>
					<?php esc_html_e( 'Розширити до Повної? (скоро)', 'inheart' ) ?>
				</button>
				<button class="btn simple order-qr" data-id="<?php echo esc_attr( $id ) ?>" disabled>
					<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
						<path d="M11.1667 11.8333V11.1667H9.16667V9.16667H11.1667V10.5H12.5V11.8333H11.8333V13.1667H10.5V14.5H9.16667V12.5H10.5V11.8333H11.1667ZM14.5 14.5H11.8333V13.1667H13.1667V11.8333H14.5V14.5ZM2.5 2.5H7.83333V7.83333H2.5V2.5ZM9.16667 2.5H14.5V7.83333H9.16667V2.5ZM2.5 9.16667H7.83333V14.5H2.5V9.16667ZM12.5 9.16667H14.5V10.5H12.5V9.16667ZM4.5 4.5V5.83333H5.83333V4.5H4.5ZM4.5 11.1667V12.5H5.83333V11.1667H4.5ZM11.1667 4.5V5.83333H12.5V4.5H11.1667Z" fill="#C3DAE2" fill-opacity="0.24"/>
					</svg>
					<?php esc_html_e( 'Замовити qr-код (скоро)', 'inheart' ) ?>
				</button>
			</div>
		</div><!-- .memory-card-bottom -->
		<?php
	}
	?>
</div>

