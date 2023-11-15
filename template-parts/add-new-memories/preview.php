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

<div class="memory-preview">
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
			if( $status === 'publish' && ! $is_rejected )
				echo '<button class="btn">' . __( 'Спогад опубліковано', 'inheart' ) . '</button>';

			if( $type === 'others' ){
				if( $is_rejected ){
					echo '<button class="btn">' . esc_html__( 'Відмовлено в публікації', 'inheart' ) . '</button>
					<button class="btn lg primary">' . esc_html__( "Додати на сторінку пам'яті", 'inheart' ) . '</button>';
				}elseif( $status === 'pending' ){
					echo '<button class="btn lg primary">' . esc_html__( "Додати на сторінку пам'яті", 'inheart' ) . '</button>';
				}
			}else{
				if( $is_rejected )
					echo '<button class="btn">' . esc_html__( 'Відмовлено в публікації', 'inheart' ) . '</button>';
				elseif( $status === 'pending' )
					echo '<button class="btn">' . esc_html__( 'На розгляді', 'inheart' ) . '</button>';
			}
            ?>

            <button class="btn icon delete">
                <img src="<?php echo THEME_URI ?>/static/img/delete.svg" alt="delete"/>
            </button>
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

