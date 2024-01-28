<?php

/**
 * Order is created section layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$customer_id	= get_current_user_id();
$order_id		= ih_get_latest_invoice_id( $customer_id );
$invoice_id		= get_field( 'invoice_id', $order_id );
$ordered		= get_field( 'ordered', $order_id );
$city			= get_field( 'city', $order_id );
$department		= get_field( 'department', $order_id );
$lastname		= get_field( 'lastname', $order_id );
$firstname		= get_field( 'firstname', $order_id );
$fathername		= get_field( 'fathername', $order_id );
$email			= get_field( 'email', $order_id );
$phone			= get_field( 'phone', $order_id );
?>

<section class="order-created profile-body">
	<?php get_template_part( 'components/profile/order-created/title' ) ?>

	<h3 class="order-created-title">
		<?php printf( __( 'Замовлення %s', 'inheart' ), $invoice_id  ) ?>
	</h3>

	<div class="order-created-content">
		<div class="order-created-status">
			<?php printf( __( 'Статус Вашого замовлення: %s', 'inheart' ), ih_get_invoice_status() ) ?>
		</div>

		<div class="order-created-ordered">
			<?php printf( __( 'Вміст замовлення: %s', 'inheart' ), $ordered ) ?>
		</div>

		<div class="order-created-destination">
			<h4><?php _e( 'Нова Пошта:', 'inheart' ) ?></h4>
			<div class="order-created-city">
				<?php printf( __( 'Місто: %s', 'inheart' ), $city ) ?>
			</div>
			<div class="order-created-department">
				<?php printf( __( 'Відділення: %s', 'inheart' ), $department ) ?>
			</div>
		</div>

		<div class="order-created-customer">
			<h4><?php _e( 'Інфо замовника:', 'inheart' ) ?></h4>
			<div class="order-created-name">
				<?php printf( __( "Повне ім'я: %s", 'inheart' ), "$lastname $firstname $fathername" ) ?>
			</div>
			<div class="order-created-email">
				<?php printf( __( 'Email: %s', 'inheart' ), $email ) ?>
			</div>
			<div class="order-created-phone">
				<?php printf( __( 'Телефон: %s', 'inheart' ), $phone ) ?>
			</div>
		</div>
	</div>
</section>

