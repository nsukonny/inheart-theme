<?php

/**
 * Email Addresses Admin page template.
 *
 * @package    WordPress
 * @subpackage inheart
 */

if( ! $users = get_users() ) return;
?>

<section class="email-addresses">
	<div class="email-addresses-list">
		<div class="email-addresses-row">
			<div class="email-addresses-col">Пошта</div>
			<div class="email-addresses-col">Ім'я</div>
			<div class="email-addresses-col">Сторінка пам'яті</div>
			<div class="email-addresses-col">Статус</div>
		</div>

		<?php
		foreach( $users as $user ){
			$user_id      = $user->ID;
			$user_email   = $user->user_email;
			$user_name    = $user->display_name;
			$memory_pages = get_posts( [
				'post_type'   => 'memory_page',
				'numberposts' => -1,
				'post_status' => 'any',
				'author__in'  => $user_id
			] );

			if( empty( $memory_pages ) ) continue;

			foreach( $memory_pages as $key => $memory_page ){
				$mp_id     = $memory_page->ID;
				$mp_link   =
					'<a href="' . get_edit_post_link( $mp_id ) . '" target="_blank">' . $memory_page->post_title .
					'</a>';
				$is_paid   = get_field( 'is_expanded', $mp_id ) ? 'Платна' : 'Безкоштовна';
				$mp_status = ih_ukr_post_status( get_post_status( $mp_id ) ) . ', ' . $is_paid;
				ih_print_email_addresses_row( [
					$key === 0 ? $user_email : '',
					$key === 0 ? $user_name : '',
					$mp_link,
					$mp_status
				] );
			}
		}
		?>
	</div>

	<form method="post" class="download-form" action="">
		<input type="submit" name="download-csv" class="button-primary" value="Скачати CSV" />
	</form>
</section>

