<?php

/**
 * Payment page template.
 * Step 0.
 *
 * @see Page Template: Payment -> Step 0
 *
 * @package WordPress
 * @subpackage inheart
 */
$title			= get_field( 'title_payment_step_0' );
$description	= get_field( 'description_payment_step_0' );
$description_sec = get_field( 'description_payment_step_0_sec' );
$acception_text = get_field( 'acception_text_payment_step_0' );
$email	= get_field( 'email_payment_step_0' );
$phone	= get_field( 'phone_payment_step_0' );
$button_text	= get_field( 'button_text_payment_step_0' ) ?: 'Замовити QR-код';
$class			= isset( $args['hidden'] ) && $args['hidden'] ? '' : ' active';
?>

<section id="new-memory-step-0" class="new-memory-step new-memory-step-0 direction-column<?php echo esc_attr( $class ) ?>">
	<div class="container direction-column">
		
		<form class="form-white payment-info-form custom-form" id="payment-step-0-form" onsubmit="return false;">
			<fieldset class="flex flex-wrap">

				<div class="title">
				<?php echo $title ?>
				</div>

				<div class="description">
					<?php echo $description ?></br>
					<?php echo $description_sec ?>
					
				</div>

				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'email',
					'label'			=> __( $email, 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Ваш Email', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'email',
					'required'		=> 1,
					'type'          => 'email',
					'extra_attrs'   => 'oninput="console.log(\'Native email input event\');"'
				] );
				
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'phone',
					'label'			=> __( $phone, 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( '+380-(00)-000-0000', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'tel',
					'required'		=> 1,
					'type'          => 'text',
					'extra_attrs'   => 'oninput="console.log(\'Native phone input event\');"'
				] );
				?>

				<div class="payment-terms-agreement full">
					<?php
					get_template_part( 'components/inputs/checkbox', null, [
						'name'			=> 'terms_agreement',
						'label'			=> __( $acception_text, 'inheart' ),
						'label_class'	=> 'label-checkbox-custom',
						'required'		=> 1,
						'extra_attrs'   => 'onchange="console.log(\'Native checkbox change event\');"'
					] );
					?>
				</div>

				<div class="payment-submit-container full flex justify-center">
					<button type="submit" class="btn lg primary payment-submit-btn" id="payment-step-0-submit">
						<?php echo esc_html( $button_text ) ?>
					</button>
				</div>
				
			</fieldset>
		</form>
	</div>
</section>

