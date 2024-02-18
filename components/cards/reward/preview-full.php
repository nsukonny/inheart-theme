<?php

/**
 * Reward preview (full).
 *
 * @package    WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$reward = $args['reward'] ?? null;
?>

<div class="reward-preview full flex direction-column align-center" data-id="<?php echo esc_attr( $id ) ?>">
	<?php
	if( has_post_thumbnail( $id ) ) echo '<div class="reward-preview-thumb">', get_the_post_thumbnail( $id, 'medium', [ 'loading' => 'lazy' ] ), '</div>';
	?>

	<div class="reward-preview-title"><?php echo get_the_title( $id ) ?></div>

	<div class="reward-preview-actions flex align-center">
		<button class="button button-icon round reward-preview-edit">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M6.414 16L16.556 5.85802L15.142 4.44402L5 14.586V16H6.414ZM7.243 18H3V13.757L14.435 2.32202C14.6225 2.13455 14.8768 2.02924 15.142 2.02924C15.4072 2.02924 15.6615 2.13455 15.849 2.32202L18.678 5.15102C18.8655 5.33855 18.9708 5.59286 18.9708 5.85802C18.9708 6.12319 18.8655 6.37749 18.678 6.56502L7.243 18ZM3 20H21V22H3V20Z"
					  fill="#96AAAE"/>
			</svg>
		</button>
		<button class="button button-icon round reward-preview-delete">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_905_13219" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="2" width="12"
					  height="16">
					<path fill-rule="evenodd" clip-rule="evenodd"
						  d="M12.9165 3.33333H14.9998C15.4582 3.33333 15.8332 3.70833 15.8332 4.16667C15.8332 4.625 15.4582 5 14.9998 5H4.99984C4.5415 5 4.1665 4.625 4.1665 4.16667C4.1665 3.70833 4.5415 3.33333 4.99984 3.33333H7.08317L7.67484 2.74167C7.82484 2.59167 8.0415 2.5 8.25817 2.5H11.7415C11.9582 2.5 12.1748 2.59167 12.3248 2.74167L12.9165 3.33333ZM6.6665 17.5C5.74984 17.5 4.99984 16.75 4.99984 15.8333V7.5C4.99984 6.58333 5.74984 5.83333 6.6665 5.83333H13.3332C14.2498 5.83333 14.9998 6.58333 14.9998 7.5V15.8333C14.9998 16.75 14.2498 17.5 13.3332 17.5H6.6665Z"
						  fill="black"/>
				</mask>
				<g mask="url(#mask0_905_13219)">
					<rect width="20" height="20" fill="#F74141"/>
				</g>
			</svg>
		</button>
	</div>

	<?php
	// Hidden fields for editing.
	if( $reward ){
		?>
		<div class="reward-preview-edict hidden"><?php echo esc_html( $reward['edict'] ) ?></div>
		<div class="reward-preview-number hidden"><?php echo esc_html( $reward['reward_number'] ) ?></div>
		<div class="reward-preview-date hidden"><?php echo esc_html( $reward['reward_date'] ) ?></div>
		<div class="reward-preview-for hidden"><?php echo esc_html( $reward['for_what'] ) ?></div>
		<div class="reward-preview-posthumously hidden"><?php echo esc_html( $reward['posthumously'] ) ?></div>
		<?php
	}
	?>
</div>

