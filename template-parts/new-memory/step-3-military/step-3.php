<?php

/**
 * New Memory page template.
 * Step 3 (Rewards).
 *
 * @see Page Template: New Memory -> Step 3 (Rewards)
 *
 * @package WordPress
 * @subpackage inheart
 */

$title				= get_field( 'title_3_military' );
$desc				= get_field( 'desc_3_military' );
$no_rewards_img		= get_field( 'no_rewards_img' );
$no_rewards_text	= get_field( 'no_rewards_text' );
$memory_page_id		= $_SESSION['memory_page_id'];
$rewards			= get_field( 'rewards', $memory_page_id );
$rewards_class		= empty( $rewards ) ? ' hidden' : '';
$no_rewards_class	= empty( $rewards ) ? '' : ' hidden';
?>

<section id="new-memory-step-3-military" class="new-memory-step new-memory-step-3-military direction-column">
	<div class="container direction-column">
		<div class="new-memory-step-suptitle">
			<?php _e( 'Крок 3', 'inheart' ) ?>
		</div>

		<?php
		if( $title ){
			?>
			<div class="new-memory-step-title flex align-center">
				<?php echo $title ?>

				<button class="button tetriary sm button-icon-lead add-reward<?php echo ( empty( $rewards ) ? ' hidden' : '' ) ?>">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8.0013 4.66676C9.41579 4.66676 10.7723 5.22867 11.7725 6.22886C12.7727 7.22905 13.3346 8.58561 13.3346 10.0001C13.3346 11.4146 12.7727 12.7711 11.7725 13.7713C10.7723 14.7715 9.41579 15.3334 8.0013 15.3334C6.58681 15.3334 5.23026 14.7715 4.23007 13.7713C3.22987 12.7711 2.66797 11.4146 2.66797 10.0001C2.66797 8.58561 3.22987 7.22905 4.23007 6.22886C5.23026 5.22867 6.58681 4.66676 8.0013 4.66676V4.66676ZM8.0013 6.0001C6.94044 6.0001 5.92302 6.42152 5.17287 7.17167C4.42273 7.92182 4.0013 8.93923 4.0013 10.0001C4.0013 11.061 4.42273 12.0784 5.17287 12.8285C5.92302 13.5787 6.94044 14.0001 8.0013 14.0001C9.06217 14.0001 10.0796 13.5787 10.8297 12.8285C11.5799 12.0784 12.0013 11.061 12.0013 10.0001C12.0013 8.93923 11.5799 7.92182 10.8297 7.17167C10.0796 6.42152 9.06217 6.0001 8.0013 6.0001ZM8.0013 7.0001L8.8833 8.78676L10.8546 9.07343L9.42797 10.4634L9.76464 12.4274L8.0013 11.5001L6.23797 12.4268L6.57463 10.4634L5.14797 9.07276L7.1193 8.7861L8.0013 7.0001ZM12.0013 1.33343V3.33343L11.0926 4.0921C10.3386 3.69649 9.51534 3.44999 8.66797 3.3661V1.33343H12.0013ZM7.33464 1.33276V3.3661C6.48753 3.44986 5.66452 3.69613 4.91064 4.09143L4.0013 3.33343V1.33343L7.33464 1.33276Z" fill="#F7B941"/>
					</svg>
					<span><?php _e( 'Додати нагороду', 'inheart' ) ?></span>
				</button>
			</div>
			<?php
		}

		if( $desc ){
			?>
			<div class="new-memory-step-desc">
				<?php echo $desc ?>
			</div>
			<?php
		}
		?>

		<div class="has-rewards form-white flex flex-wrap align-start<?php echo $rewards_class ?>">
			<?php
			if( ! empty( $rewards ) ){
				foreach( $rewards as $reward ){
					$part_name = $reward['reward_custom'] ? 'custom' : 'full';

					get_template_part( 'components/cards/reward/preview', $part_name, [
						'id'		=> $reward['reward_id'],
						'reward'	=> $reward
					] );
				}
			}
			?>
		</div>

		<?php
		get_template_part( 'components/popup/popup', null, [
			'text'		=> __( 'Дійсно видалити нагороду?', 'inheart' ),
			'class'		=> 'delete',
			'label_yes'	=> __( 'Видалити', 'inheart' ),
			'label_no'	=> __( 'Залишити', 'inheart' )
		] );
		?>

		<div class="no-rewards-body flex direction-column align-center justify-center<?php echo $no_rewards_class ?>">
			<?php
			if( $no_rewards_img )
				echo '<div class="no-rewards-img">',
					wp_get_attachment_image( $no_rewards_img, 'ih-profile-media' ),
				'</div>';

			if( $no_rewards_text )
				echo '<p class="no-rewards-text">', esc_html( $no_rewards_text ), '</p>';
			?>

			<button class="button tetriary sm button-icon-lead add-reward">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.0013 4.66676C9.41579 4.66676 10.7723 5.22867 11.7725 6.22886C12.7727 7.22905 13.3346 8.58561 13.3346 10.0001C13.3346 11.4146 12.7727 12.7711 11.7725 13.7713C10.7723 14.7715 9.41579 15.3334 8.0013 15.3334C6.58681 15.3334 5.23026 14.7715 4.23007 13.7713C3.22987 12.7711 2.66797 11.4146 2.66797 10.0001C2.66797 8.58561 3.22987 7.22905 4.23007 6.22886C5.23026 5.22867 6.58681 4.66676 8.0013 4.66676V4.66676ZM8.0013 6.0001C6.94044 6.0001 5.92302 6.42152 5.17287 7.17167C4.42273 7.92182 4.0013 8.93923 4.0013 10.0001C4.0013 11.061 4.42273 12.0784 5.17287 12.8285C5.92302 13.5787 6.94044 14.0001 8.0013 14.0001C9.06217 14.0001 10.0796 13.5787 10.8297 12.8285C11.5799 12.0784 12.0013 11.061 12.0013 10.0001C12.0013 8.93923 11.5799 7.92182 10.8297 7.17167C10.0796 6.42152 9.06217 6.0001 8.0013 6.0001ZM8.0013 7.0001L8.8833 8.78676L10.8546 9.07343L9.42797 10.4634L9.76464 12.4274L8.0013 11.5001L6.23797 12.4268L6.57463 10.4634L5.14797 9.07276L7.1193 8.7861L8.0013 7.0001ZM12.0013 1.33343V3.33343L11.0926 4.0921C10.3386 3.69649 9.51534 3.44999 8.66797 3.3661V1.33343H12.0013ZM7.33464 1.33276V3.3661C6.48753 3.44986 5.66452 3.69613 4.91064 4.09143L4.0013 3.33343V1.33343L7.33464 1.33276Z" fill="#F7B941"/>
				</svg>
				<span><?php _e( 'Додати нагороду', 'inheart' ) ?></span>
			</button>
		</div>

		<?php get_template_part( 'template-parts/new-memory/step-3-military/add-reward' ) ?>
</section><!-- #new-memory-step-3-military -->

