
<div class="modal micromodal-slide phone-numbers-modal" id="phoneModal" aria-hidden="true">
	<div class="modal__overlay" tabindex="-1" data-micromodal-close>
		<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
			<button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
			<main class="modal__content" id="searchModalContent">
				<div class="phone-list-top-div">

					<?php $content = get_field( 'phone_modal_content', 'options' );
					echo $content;

					$locations = get_posts( array(
						'post_type'      => 'locations',
						'posts_per_page' => - 1,
						'orderby'        => 'title',
						'order'          => 'ASC',
					) ); ?>

					<div class="phone-list-div">
						<?php foreach ( $locations as $location ):
							$phone_numbers = get_field( 'phone_numbers', $location->ID );
							if ( ! $phone_numbers ) {
								continue; // Skip if no phone numbers are set
							}
							$number = $phone_numbers[0]['number'] ?? '';
							$href   = 'tel:' . preg_replace( '/\D/', '', $number );
							?>
							<a href="<?php echo $href; ?>" class="phone-link w-inline-block">
								<?php echo glacial_cpt_svg_icon('phone'); ?>
								<div><?php echo get_the_title( $location->ID ); ?> |
									<strong><?php echo $number; ?></strong></div>
							</a>
						<?php endforeach; ?>

					</div>
				</div>

				<button data-micromodal-close>Close</button>
			</main>
		</div>
	</div>
</div>