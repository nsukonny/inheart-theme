<?php

/**
 * Profile Memories - no memories yet.
 *
 * @package WordPress
 * @subpackage inheart
 */

$id		= $args['id'] ?? null;
$type	= $args['type'] ?? null;
?>

<section class="profile-memories-list none">
	<div class="profile-memories-list-inner flex direction-column align-center">
		<?php get_template_part( 'components/profile/memories/no-memories', 'body', ['id' => $id, 'type' => $type] ) ?>
	</div>
</section>

