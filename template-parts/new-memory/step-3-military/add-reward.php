<?php

/**
 * New Memory page template.
 * Step 3 (Rewards) - Add new reward.
 *
 * @see Page Template: New Memory -> Step 3 (Rewards)
 *
 * @package WordPress
 * @subpackage inheart
 */

$rewards_types = get_terms( ['taxonomy' => 'rewards', 'hide_empty' => true] );
?>

<div class="rewards-main-wrap form-white flex flex-wrap hidden">
	<aside class="rewards-sidebar">
		<div class="rewards-sidebar-breadcrumbs flex flex-wrap">
			<span class="active"><?php _e( 'Оберіть нагороду або відзнаку', 'inheart' ) ?></span>
			<span><?php _e( 'Додайте опис', 'inheart' ) ?></span>
		</div>

		<form class="rewards-search" role="search">
			<fieldset>
				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'search-reward',
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Пошук по нагородам', 'inheart' ),
					'required'		=> 0,
					'icon_lead'		=> 'search.svg'
				] );
				?>
			</fieldset>
		</form>

		<?php
		if( ! empty( $rewards_types ) ){
			echo '<div class="rewards-types">';

			foreach( $rewards_types as $type ){
				?>
				<button class="button xl fw button-icon-tail rewards-type-filter" data-slug="<?php echo esc_attr( $type->slug ) ?>">
					<span><?php echo esc_html( $type->name ) ?></span>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10.7826 7.33312L7.20663 3.75712L8.1493 2.81445L13.3346 7.99979L8.1493 13.1851L7.20663 12.2425L10.7826 8.66645H2.66797V7.33312H10.7826Z" fill="#F7B941"/>
					</svg>
				</button>
				<?php
			}

			echo '</div>';
		}
		?>

		<div class="rewards-not-found">
			<span><?php _e( 'Не знайшли відзнаку у списку?', 'inheart' ) ?></span>
			<button class="button lg button-icon-lead add-custom-reward">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.0013 4.66676C9.41579 4.66676 10.7723 5.22867 11.7725 6.22886C12.7727 7.22905 13.3346 8.58561 13.3346 10.0001C13.3346 11.4146 12.7727 12.7711 11.7725 13.7713C10.7723 14.7715 9.41579 15.3334 8.0013 15.3334C6.58681 15.3334 5.23026 14.7715 4.23007 13.7713C3.22987 12.7711 2.66797 11.4146 2.66797 10.0001C2.66797 8.58561 3.22987 7.22905 4.23007 6.22886C5.23026 5.22867 6.58681 4.66676 8.0013 4.66676V4.66676ZM8.0013 6.0001C6.94044 6.0001 5.92302 6.42152 5.17287 7.17167C4.42273 7.92182 4.0013 8.93923 4.0013 10.0001C4.0013 11.061 4.42273 12.0784 5.17287 12.8285C5.92302 13.5787 6.94044 14.0001 8.0013 14.0001C9.06217 14.0001 10.0796 13.5787 10.8297 12.8285C11.5799 12.0784 12.0013 11.061 12.0013 10.0001C12.0013 8.93923 11.5799 7.92182 10.8297 7.17167C10.0796 6.42152 9.06217 6.0001 8.0013 6.0001ZM8.0013 7.0001L8.8833 8.78676L10.8546 9.07343L9.42797 10.4634L9.76464 12.4274L8.0013 11.5001L6.23797 12.4268L6.57463 10.4634L5.14797 9.07276L7.1193 8.7861L8.0013 7.0001ZM12.0013 1.33343V3.33343L11.0926 4.0921C10.3386 3.69649 9.51534 3.44999 8.66797 3.3661V1.33343H12.0013ZM7.33464 1.33276V3.3661C6.48753 3.44986 5.66452 3.69613 4.91064 4.09143L4.0013 3.33343V1.33343L7.33464 1.33276Z" fill="#F7B941"/>
				</svg>
				<span><?php _e( 'Додати нагороду власноруч', 'inheart' ) ?></span>
			</button>
		</div>
	</aside>

	<div class="rewards-area">
		<?php
		if( $rewards_types && ! is_wp_error( $rewards_types ) ){
			foreach( $rewards_types as $type ){
				$rewards = get_posts( [
					'post_type'		=> 'reward',
					'numberposts'	=> -1,
					'post_status'	=> 'publish',
					'rewards'		=> $type->slug
				] );

				if( empty( $rewards ) ) continue;

				echo '<h4>', esc_html( $type->name ), '</h4><div class="rewards-list flex flex-wrap align-start">';

				foreach( $rewards as $reward )
					get_template_part( 'components/cards/reward/preview', null, ['id' => $reward->ID] );

				echo '</div>';
			}
		}
		?>
	</div>

	<?php
	get_template_part( 'template-parts/new-memory/step-3-military/reward-active-popup', null, [
		'img' => $args['img'] ?? null
	] );
	?>
</div>

