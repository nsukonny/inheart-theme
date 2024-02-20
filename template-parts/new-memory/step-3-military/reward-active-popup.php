<?php

/**
 * New Memory page template.
 * Step 3 (Rewards) - Reward is active, popup.
 *
 * @see Page Template: New Memory -> Step 3 (Rewards)
 *
 * @package WordPress
 * @subpackage inheart
 */

$custom_reward_text = get_field( 'add_custom_reward_text' );
?>

<div class="reward-popup hidden">
	<div class="reward-popup-inner flex flex-wrap">
		<div class="reward-popup-preview">
			<div class="reward-preview flex direction-column align-center">
				<div class="reward-preview-no-reward-thumb hidden">
					<img src="<?php echo THEME_URI ?>/static/img/no-rewards-min.png" alt="" />
				</div>

				<div class="reward-preview-thumb"></div>
				<div class="reward-preview-title"></div>
			</div>

			<?php
			if( $custom_reward_text )
				echo '<div class="reward-popup-custom hidden">', esc_html( $custom_reward_text ), '</div>';
			?>
		</div>

		<form class="reward-popup-form flex direction-column">
			<fieldset class="flex flex-wrap justify-between">
				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'reward-custom',
					'label'			=> __( 'Назва нагороди*', 'inheart' ),
					'label_class'	=> 'full label-reward-custom hidden',
					'placeholder'	=> __( 'Назва нагороди', 'inheart' ),
				] );
				get_template_part( 'components/inputs/army', null, [
					'name'			=> 'edict',
					'label'			=> __( 'Наказ*', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Наказ', 'inheart' ),
					'required'		=> 1,
					'icon_tail'		=> 'arrow-down-s-line.svg',
					'types'			=> get_field( 'edicts' )
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'reward-number',
					'label'			=> __( 'Номер нагороди*', 'inheart' ),
					'placeholder'	=> __( 'Номер нагороди', 'inheart' ),
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/date', null, [
					'name'			=> 'reward-date',
					'label'			=> __( 'Дата видачі*', 'inheart' ),
					'placeholder'	=> __( 'Дата видачі', 'inheart' ),
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'reward-for-what',
					'label'			=> __( 'За що', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'За що', 'inheart' )
				] );
				get_template_part( 'components/inputs/checkbox', null, [
					'size'			=> 'lg',
					'name'			=> 'posthumously',
					'label'			=> __( 'Посмертно', 'inheart' )
				] );
				?>

				<div class="reward-popup-form-buttons flex flex-wrap align-center">
					<button class="button primary lg reward-popup-form-submit" type="submit">
						<?php _e( 'Додати', 'inheart' ) ?>
					</button>
					<button class="button secondary md reward-popup-form-decline" type="button">
						<?php _e( 'Скасувати', 'inheart' ) ?>
					</button>
				</div>
			</fieldset>
		</form>

		<div class="reward-popup-text">
			<div class="reward-popup-text-edict"></div>
			<span class="reward-popup-text-number"></span>
			<span class="reward-popup-text-number-after hidden"> <?php _e( 'від' ) ?> </span>
			<span class="reward-popup-text-date"></span><span class="reward-popup-text-date-after hidden">, </span>
			<span class="reward-popup-text-for"></span>
			<span class="reward-popup-text-posthumously hidden"> (<?php _e( 'Посмертно', 'inheart' ) ?>) </span>
		</div>
	</div>
</div>

