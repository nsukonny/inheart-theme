<?php

/**
 * Profile memory preview.
 *
 * @package    WordPress
 * @subpackage inheart
 */

$id = $args['id'] ?? null;

if( ! $id ) return;

$type			= $args['type'] ?? 'others';
$page_id		= ( int ) get_field( 'memory_page', $id );
$status			= get_post_status( $id );
$full_name		= get_field( 'full_name', $id );
$is_rejected	= get_field( 'is_rejected', $id );
$role			= get_field( 'role', $id );
$content		= get_field( 'content', $id );
?>

<div class="memory-preview" data-id="<?php echo esc_attr( $id ) ?>">
    <div class="memory-preview-top flex flex-wrap justify-between align-center">
        <div class="memory-preview-page flex align-center">
            <?php
			if( has_post_thumbnail( $page_id ) )
				echo '<div class="memory-preview-page-thumb">' . get_the_post_thumbnail( $page_id, 'ih-logo' ) . '</div>';
			?>

            <div class="memory-preview-page-name">
                <?php echo ih_get_memory_page_name( $page_id ) ?>
            </div>
        </div>

        <div class="memory-preview-actions flex align-center">
            <?php
			if( $type === 'others' ){
				if( $status === 'publish' && ! $is_rejected )
					echo '<span class="button info">' . __( 'Спогад опубліковано', 'inheart' ) . '</span>';

				if( $status === 'pending' ){
					?>
					<button class="button lg primary memory-preview-publish">
						<?php _e( "Додати на сторінку пам'яті", 'inheart' ) ?>
					</button>
					<?php
				}
				?>
				<button class="button button-icon lg danger memory-preview-delete" data-type="others">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<mask id="mask0_905_13219" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="2" width="12" height="16">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M12.9165 3.33333H14.9998C15.4582 3.33333 15.8332 3.70833 15.8332 4.16667C15.8332 4.625 15.4582 5 14.9998 5H4.99984C4.5415 5 4.1665 4.625 4.1665 4.16667C4.1665 3.70833 4.5415 3.33333 4.99984 3.33333H7.08317L7.67484 2.74167C7.82484 2.59167 8.0415 2.5 8.25817 2.5H11.7415C11.9582 2.5 12.1748 2.59167 12.3248 2.74167L12.9165 3.33333ZM6.6665 17.5C5.74984 17.5 4.99984 16.75 4.99984 15.8333V7.5C4.99984 6.58333 5.74984 5.83333 6.6665 5.83333H13.3332C14.2498 5.83333 14.9998 6.58333 14.9998 7.5V15.8333C14.9998 16.75 14.2498 17.5 13.3332 17.5H6.6665Z" fill="black"/>
						</mask>
						<g mask="url(#mask0_905_13219)">
							<rect width="20" height="20" fill="currentColor"/>
						</g>
					</svg>
				</button>
				<?php
			}else{
				if( $status === 'publish' && ! $is_rejected )
					echo '<span class="button info">' . __( 'Спогад опубліковано', 'inheart' ) . '</span>';

				if( $is_rejected ){
					?>
					<button class="button negative no-events"><?php esc_html_e( 'Відмовлено в публікації', 'inheart' ) ?></button>
					<button class="button md outlined memory-preview-rejected-submit" data-type="yours">OK</button>
					<?php
				}elseif( $status === 'pending' ){
					?>
					<button class="button no-events">
						<?php _e( 'На модерації', 'inheart' ) ?>
					</button>
					<button class="button button-icon lg danger memory-preview-delete" data-type="yours">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_905_13220" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="2" width="12" height="16">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M12.9165 3.33333H14.9998C15.4582 3.33333 15.8332 3.70833 15.8332 4.16667C15.8332 4.625 15.4582 5 14.9998 5H4.99984C4.5415 5 4.1665 4.625 4.1665 4.16667C4.1665 3.70833 4.5415 3.33333 4.99984 3.33333H7.08317L7.67484 2.74167C7.82484 2.59167 8.0415 2.5 8.25817 2.5H11.7415C11.9582 2.5 12.1748 2.59167 12.3248 2.74167L12.9165 3.33333ZM6.6665 17.5C5.74984 17.5 4.99984 16.75 4.99984 15.8333V7.5C4.99984 6.58333 5.74984 5.83333 6.6665 5.83333H13.3332C14.2498 5.83333 14.9998 6.58333 14.9998 7.5V15.8333C14.9998 16.75 14.2498 17.5 13.3332 17.5H6.6665Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_905_13220)">
								<rect width="20" height="20" fill="currentColor"/>
							</g>
						</svg>
					</button>
					<?php
				}elseif( $status === 'trash' ){}
			}
            ?>
        </div>
    </div>

    <div class="memory-preview-body flex direction-column align-center">
        <?php
        if( has_post_thumbnail( $id ) ) echo '<div class="memory-preview-thumb">' . get_the_post_thumbnail( $id, 'ih-logo' ) . '</div>';

        if( $content ) echo '<div class="memory-preview-text">' . $content . '</div>';

        if( $full_name ) echo '<div class="memory-preview-fullname">' . esc_html( $full_name ) . '</div>';

        if( $role ) echo '<div class="memory-preview-role">' . esc_html( $role ) . '</div>';
        ?>
    </div>
</div>

