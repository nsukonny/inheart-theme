<?php

/**
 * Profile - no memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

$class = (isset($args['hide']) && $args['hide']) ? ' hidden' : '';
$img   = get_field('no_memory_pages_img');
$title = get_field('no_memory_pages_title');
$note = get_field('no_memory_pages_note');
$first_name		= get_field( 'first_name', $memory_page_id );
$last_name		= get_field( 'last_name', $memory_page_id );
$middle_name	= get_field( 'middle_name', $memory_page_id );
?>

<section class="profile-memories none profile-body<?php echo esc_attr( $class ) ?>">
	<?php get_template_part( 'components/profile/memory-pages/title-without-text', null, [ 'draft' => $draft_memory_page ] ) ?>

	<div class="memories-form-page">
		<div class="profile-memories-inner flex direction-column align-center">
			<div class="create-new-memory-container">
				<?php if ( $img ): ?>
					<div class="profile-memories-img">
						<?= wp_get_attachment_image( $img['id'], 'ih-profile-media' ) ?>
					</div>
				<?php endif; ?>

				<?php if ( $title ): ?>
					<h1 class="profile-memories-title"><?= esc_html( $title ) ?></h1>
				<?php endif; ?>

				<?php if ( $note ): ?>
					<div class="profile-memories-note"><?= esc_html( $note ) ?></div>
				<?php endif; ?>

				<form class="form-white memory-form">
					<fieldset class="flex flex-wrap">
						<?php
						get_template_part( 'components/inputs/default', null, [
							'name'          => 'lastname',
							'label'         => __( 'Прізвище', 'inheart' ),
							'label_class'   => 'full',
							'placeholder'   => __( 'Прізвище Померлого', 'inheart' ),
							'value'         => $last_name,
							'autocomplete'  => 'family-name',
							'required'      => 1
						] );
						?>
						<div class="bottom-form-block">
							<?php
							get_template_part( 'components/inputs/default', null, [
								'name'          => 'firstname',
								'label'         => __( "Ім'я", 'inheart' ),
								'label_class'   => 'full',
								'placeholder'   => __( "Ім'я Померлого", 'inheart' ),
								'value'         => $first_name,
								'autocomplete'  => 'given-name',
								'required'      => 1
							] );
							get_template_part( 'components/inputs/default', null, [
								'name'          => 'fathername',
								'label'         => __( 'По батькові', 'inheart' ),
								'label_class'   => 'full',
								'placeholder'   => __( 'По батькові Померлого', 'inheart' ),
								'value'         => $middle_name,
								'autocomplete'  => 'additional-name',
								'required'      => 1
							] );
							?>
						</div>
					</fieldset>
				</form>

				<a
					href="<?php echo get_the_permalink( pll_get_post( ih_get_memory_creation_page_id() ) ) ?>"
					class="memory-form-btn button primary lg"
				>
					<?php esc_html_e( "Далi", 'inheart' ) ?>
				</a>
			</div>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
	const button = document.querySelector('.memory-form-btn');
    const lastnameInput = document.querySelector('input[name="lastname"]');
    const firstnameInput = document.querySelector('input[name="firstname"]');
    const fathernameInput = document.querySelector('input[name="fathername"]');

    lastnameInput.value = '';
    firstnameInput.value = '';
    fathernameInput.value = '';


	if (button) {
		button.addEventListener('click', () => {

            const lastname = lastnameInput ? lastnameInput.value : '';
            const firstname = firstnameInput ? firstnameInput.value : '';
            const fathername = fathernameInput ? fathernameInput.value : '';

			localStorage.setItem('memoryFormData', JSON.stringify({
				lastname,
				firstname,
				fathername
			}));
		});
	}
});
</script>