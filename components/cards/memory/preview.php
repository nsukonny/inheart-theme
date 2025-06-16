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
        <a href="<?php echo get_the_permalink( $page_id ) ?>" class="memory-preview-page flex align-center">
            <?php
            if( has_post_thumbnail( $page_id ) ) {
                echo '<div class="memory-preview-page-thumb">' . get_the_post_thumbnail( $page_id, 'ih-logo' ) . '</div>';
            } else {
                $default_thumb = get_field( 'default_memory_page_thumbnail', 'option' );
                if( $default_thumb ) {
                    echo '<div class="memory-preview-page-thumb">' . wp_get_attachment_image( $default_thumb['id'], 'ih-logo' ) . '</div>';
                }
            }
            ?>

            <div class="memory-preview-page-name">
                <?php echo ih_get_memory_page_name( $page_id ) ?>
            </div>
        </a>

        <div class="memory-preview-actions flex align-center">
            <?php if( $status === 'pending' ): ?>
                <div class="memory-preview-status flex align-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#D0AD60"/>
                        <path d="M17.6139 8.15706C17.8033 8.35802 17.7939 8.67446 17.5929 8.86386L9.66309 16.3378C9.42573 16.5615 9.05311 16.5546 8.82417 16.3222L6.14384 13.6019C5.95003 13.4052 5.95237 13.0886 6.14907 12.8948C6.34578 12.701 6.66235 12.7033 6.85616 12.9001L9.26192 15.3417L16.9071 8.13614C17.108 7.94674 17.4245 7.95611 17.6139 8.15706Z" fill="#011C1A"/>
                    </svg>

                    <?php _e( 'На модерації', 'inheart' ) ?>
                </div>
            <?php endif; ?>

            <?php if( $is_rejected ): ?>
                <div class="memory-preview-status flex align-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#D0AD60"/>
                        <path d="M17.6139 8.15706C17.8033 8.35802 17.7939 8.67446 17.5929 8.86386L9.66309 16.3378C9.42573 16.5615 9.05311 16.5546 8.82417 16.3222L6.14384 13.6019C5.95003 13.4052 5.95237 13.0886 6.14907 12.8948C6.34578 12.701 6.66235 12.7033 6.85616 12.9001L9.26192 15.3417L16.9071 8.13614C17.108 7.94674 17.4245 7.95611 17.6139 8.15706Z" fill="#011C1A"/>
                    </svg>

                    <?php _e( 'Відхилено', 'inheart' ) ?>
                </div>
            <?php endif; ?>

            <div class="memory-preview-delete flex align-center justify-center" data-id="<?php echo esc_attr( $id ) ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask_<?php echo esc_attr( $id ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
                    </mask>
                    <g mask="url(#mask_<?php echo esc_attr( $id ) ?>)">
                        <rect width="24" height="24" fill="currentColor"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <div class="memory-preview-body flex direction-column align-center">
        <?php
        if( has_post_thumbnail( $id ) ){
            ?>
            <div class="memory-preview-thumb" data-full="<?php echo get_the_post_thumbnail_url( $id, 'full' ) ?>">
                <?php echo get_the_post_thumbnail( $id, 'ih-logo' ) ?>
            </div>
            <?php
        }

        if( $content ) echo '<div class="memory-preview-text">' . $content . '</div>';

        if( $full_name ) echo '<div class="memory-preview-fullname">' . esc_html( $full_name ) . '</div>';

        if( $role ) echo '<div class="memory-preview-role">' . esc_html( $role ) . '</div>';
        ?>
    </div>
</div>

