<?php

/**
 * Memory single page.
 * Memories section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$lang		= $args['lang'] ?? 'uk';
$memories	= get_field( 'memories', $id );
?>

<section class="single-memory-memories">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php echo pll_translate_string( 'Спогади рiдних i близьких', $lang ) ?>
		</h2>

		<?php
		if( ! empty( $memories ) ){
			?>
			<div class="single-memory-memories-cols">
				<?php
				foreach( $memories as $memory ){
					$thumb	= $memory['thumb'];
					$text	= $memory['text'];
					$name	= $memory['name'];
					$role	= $memory['role'];

					// No content - go to the next memory.
					if( ! $thumb && ! $text ) continue;
					?>
					<div class="single-memory-memories-item"></div>
					<?php
				}
				?>
			</div>
			<?php
		}else{
			?>
			<div class="single-memory-no-memories flex direction-column align-center">
				<?php
				if( $no_memories_img = get_field( 'no_memories_image', 'option' ) ){
					?>
					<div class="single-memory-no-memories-img">
						<?php echo wp_get_attachment_image( $no_memories_img['id'], 'ih-illustration', null, ['loading' => 'lazy'] ) ?>
					</div>
					<?php
				}
				?>

				<div class="single-memory-no-memories-title">
					<?php echo pll_translate_string( 'Наразі немає жодного спогаду', $lang ) ?>
				</div>
				<div class="single-memory-no-memories-desc">
					<?php echo pll_translate_string( 'Додайте перший спогад про близьку людину', $lang ) ?>
				</div>
				<a
					href="#"
					class="btn xl secondary outlined single-memory-no-memories-btn"
					title="<?php echo pll_translate_string( 'Додати спогад про людину', $lang ) ?>"
				>
					<?php
//					echo pll_translate_string( 'Додати спогад про людину', $lang );
//					echo pll_translate_string( 'Функція скоро буде доступна', $lang );
					esc_html_e( 'Функція скоро буде доступна', 'inheart' );
					?>
				</a>
			</div><!-- .single-memory-no-memories -->
			<?php
		}
		?>
	</div>
</section>

