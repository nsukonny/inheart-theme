<?php

/**
 * Profile Memories page template.
 * Switcher part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$case = $_GET['case'] ?? 'others';
?>

<div class="profile-memories-switcher switcher inline-flex flex-wrap align-center" data-page="<?php echo get_the_ID() ?>">
	<button class="btn tab<?php echo ( $case === 'others' ? ' active' : '' ) ?>" data-type="others">
		<?php esc_html_e( 'Вам', 'inheart' ) ?>
	</button>
	<button class="btn tab<?php echo ( $case === 'yours' ? ' active' : '' ) ?>" data-type="yours">
		<?php esc_html_e( 'Від вас', 'inheart' ) ?>
	</button>
	<div class="switcher-bg"></div>
</div>

