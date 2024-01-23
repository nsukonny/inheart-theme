<?php

/**
 * Order is created section layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$customer_id = get_current_user_id();
?>

<section class="order-created profile-body">
	<?php get_template_part( 'components/profile/order-created/title' ) ?>

	<div class="order-created-info">
		<?php printf( esc_html__( 'Статус Вашого замовлення: %s', 'inheart' ), ih_get_invoice_status()  ) ?>
	</div>

	<div class="order-created-content">
		<?php the_content() ?>
	</div>
</section>

