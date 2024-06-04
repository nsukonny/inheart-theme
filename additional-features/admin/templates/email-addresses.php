<?php

/**
 * Email Addresses Admin page template.
 *
 * @package    WordPress
 * @subpackage inheart
 */

date_default_timezone_set( 'Europe/Kiev' );
$date_start     = isset( $_POST['date-start'] ) && $_POST['date-start'] ? $_POST['date-start'] : '';
$date_end       = isset( $_POST['date-end'] ) && $_POST['date-end'] ? $_POST['date-end'] : date( 'Y-m-d' );
$mp_post_status = isset( $_POST['mp-status'] ) && $_POST['mp-status'] ? $_POST['mp-status'] : '';
$mp_is_expanded = isset( $_POST['mp-is-expanded'] ) && $_POST['mp-is-expanded'] ? $_POST['mp-is-expanded'] : '';

if( $date_start && $date_end ){
	// Reset dates if they are incorrect.
	if( strtotime( $date_start ) > strtotime( $date_end ) ){
		$date_start = $date_end = '';
		$users      = get_users();
	}else{    // Dates are OK.
		$users = get_users( [
			'date_query' => [
				[
					'after'     => "$date_start 00:00:00",
					'before'    => "$date_end 23:59:59",
					'inclusive' => true
				]
			]
		] );
	}
}else{
	$users = get_users();
}
?>

<section class="email-addresses">
	<form method="post" class="email-addresses-filters" action="">
		<fieldset>
			<legend>Дати створення акаунту:</legend>

			<div class="email-addresses-filter">
				<label for="date-start">Початок</label> <input
					type="date" id="date-start" name="date-start" value="<?php echo esc_attr( $date_start ) ?>"
				/>
			</div>
			<div class="email-addresses-filter">
				<label for="date-end">Кінець</label> <input
					type="date" id="date-end" name="date-end" value="<?php echo esc_attr( $date_end ) ?>"
				/>
			</div>
		</fieldset>

		<fieldset>
			<legend>Статус сторінок пам'яті:</legend>

			<div class="email-addresses-filter">
				<label for="mp-status">Статус</label> <select name="mp-status" id="mp-status">
					<option value=""<?php echo ! $mp_post_status ? ' selected' : '' ?>>---</option>
					<option value="draft"<?php echo $mp_post_status === 'draft' ? ' selected' : '' ?>>Чернетка</option>
					<option value="publish"<?php echo $mp_post_status === 'publish' ? ' selected' : '' ?>>Опублікована
					</option>
				</select>
			</div>
			<div class="email-addresses-filter">
				<label for="mp-is-expanded">Статус оплати</label> <select name="mp-is-expanded" id="mp-is-expanded">
					<option value=""<?php echo ! $mp_is_expanded ? ' selected' : '' ?>>---</option>
					<option value="free"<?php echo $mp_is_expanded === 'free' ? ' selected' : '' ?>>Безкоштовна</option>
					<option value="paid"<?php echo $mp_is_expanded === 'paid' ? ' selected' : '' ?>>Платна</option>
				</select>
			</div>
		</fieldset>

		<div class="email-addresses-submit">
			<button class="button-primary">Фільтр</button>
		</div>
	</form>

	<div class="email-addresses-list">
		<div class="email-addresses-row email-addresses-header">
			<div class="email-addresses-col">#</div>
			<div class="email-addresses-col">Пошта</div>
			<div class="email-addresses-col">Зареєстрований</div>
			<div class="email-addresses-col">Ім'я</div>
			<div class="email-addresses-col">Сторінка пам'яті</div>
			<div class="email-addresses-col">Статус</div>
		</div>

		<?php
		if( ! $users ){
			echo 'Користувачів не знайдено. Спробуйте змінити фільтри.';
		}else{
			$index = 1;

			foreach( $users as $user ){
				$user_id    = $user->ID;
				$user_email = $user->user_email;
				$user_name  = $user->display_name;
				$registered = $user->user_registered;
				$mp_args    = [
					'post_type'   => 'memory_page',
					'numberposts' => -1,
					'post_status' => $mp_post_status ?: 'any',
					'author__in'  => $user_id
				];

				if( $mp_is_expanded === 'paid' ){
					$mp_args['meta_key']   = 'is_expanded';
					$mp_args['meta_value'] = true;
				}else if( $mp_is_expanded === 'free' ){
					$mp_args['meta_query'] = [
						[
							'key'     => 'is_expanded',
							'value'   => true,
							'compare' => '!='
						],
						[
							'key'     => 'is_expanded',
							'compare' => 'NOT EXISTS'
						],
						'relation' => 'OR'
					];
				}

				$memory_pages = get_posts( $mp_args );

				if( empty( $memory_pages ) ) continue;

				foreach( $memory_pages as $key => $memory_page ){
					$mp_id     = $memory_page->ID;
					$mp_link   =
						'<a href="' . get_edit_post_link( $mp_id ) . '" target="_blank">' . $memory_page->post_title .
						'</a>';
					$is_paid   = get_field( 'is_expanded', $mp_id ) ? 'Платна' : 'Безкоштовна';
					$mp_status = ih_ukr_post_status( get_post_status( $mp_id ) ) . ', ' . $is_paid;
					ih_print_email_addresses_row( [
						$index,
						$key === 0 ? $user_email : '',
						$key === 0 ? date( 'd.m.Y', strtotime( $registered ) ) : '',
						$key === 0 ? $user_name : '',
						$mp_link,
						$mp_status
					] );
					$index++;
				}
			}
		}
		?>
	</div>

	<?php
	if( $users ){
		?>
		<form method="post" class="download-form" action="">
			<input type="submit" name="download-csv" class="button-primary" value="Скачати CSV"/>
		</form>
		<?php
	}
	?>
</section>

