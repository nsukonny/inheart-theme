<?php

/**
 * Profile memory single page card.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$firstname	= get_field( 'first_name', $id );
$middlename	= get_field( 'middle_name', $id );
$lastname	= get_field( 'last_name', $id );
$born_at	= get_field( 'born_at', $id );
$died_at	= get_field( 'died_at', $id );
?>

<div class="memory-card">
	<div class="memory-card-top page-created-info flex direction-column align-center">
		<div class="page-created-thumb flex justify-center align-center">
			<div class="page-created-thumb-border">
				<span><?php esc_html_e( 'In memory of', 'inheart' ) ?></span>
			</div>
			<div class="page-created-thumb-img">
				<?php if( has_post_thumbnail( $id ) ) echo get_the_post_thumbnail( $id, 'full' ) ?>
			</div>
		</div>

		<div class="page-created-fullname flex direction-column align-center">
			<div class="page-created-firstname"><?php echo esc_html( "$firstname $middlename" ) ?></div>
			<div class="page-created-lastname flex">
				<div class="memory-card-date born"><?php echo esc_attr( str_replace( '/', '.', $born_at ) ) ?></div>
				<span><?php echo esc_html( $lastname ) ?></span>
				<div class="memory-card-date died"><?php echo esc_attr( str_replace( '/', '.', $died_at ) ) ?></div>
			</div>
		</div>
	</div><!-- .memory-card-top -->

	<div class="memory-card-bottom">
		<div class="memory-card-title">
			<?php esc_html_e( "Коротка сторінка пам'яті", 'inheart' ) ?>
		</div>
		<div class="memory-card-actions flex flex-wrap align-center">
			<button class="btn lg primary outlined border-gold">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_1566_33476)">
						<path d="M7.10433 0.899231C7.47114 0.155983 8.53099 0.155988 8.8978 0.899232L10.8282 4.81073L15.1448 5.43797C15.9651 5.55715 16.2926 6.56513 15.699 7.14366L12.5755 10.1883L13.3129 14.4875C13.453 15.3044 12.5956 15.9274 11.8619 15.5417L8.00106 13.5119L4.14018 15.5417C3.40655 15.9274 2.54913 15.3044 2.68924 14.4875L3.4266 10.1883L0.303081 7.14366C-0.290438 6.56512 0.0370772 5.55715 0.857295 5.43797L5.17389 4.81073L7.10433 0.899231Z" fill="#F7B941"/>
					</g>
					<defs>
						<clipPath id="clip0_1566_33476">
							<rect width="16" height="16" fill="white"/>
						</clipPath>
					</defs>
				</svg>
				<?php esc_html_e( 'Розширити до Повної?', 'inheart' ) ?>
			</button>
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
				<?php esc_html_e( 'Редагувати', 'inheart' ) ?>
			</button>
		</div>
	</div><!-- .memory-card-bottom -->
</div>

