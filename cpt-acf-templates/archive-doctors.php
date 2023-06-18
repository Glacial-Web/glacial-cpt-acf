<?php
/**
 *  Displays doctor filtering on the doctors archive page.
 *
 * @package Glacial_Cpt_Acf
 */

get_header();

if ( have_posts() ):

	$use_doctor_services_filter = get_field( 'use_doctor_services_filter', 'options' ) ?? true;
	$use_doctor_locations_filter = get_field( 'use_doctor_locations_filter', 'options' ) ?? true;

	$locations = get_posts(
		array(
			'post_type'      => 'locations',
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'asc',
		)
	);

	$services = get_posts(
		array(
			'post_type'      => 'page',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => - 1,
			'meta_key'       => 'glacial_page_type',
			'meta_value'     => 'service-page'
		)
	);

	$container_class     = 'filters-off';
	$mix_it_up_class     = 'mix-it-up-off';

	if ( $use_doctor_services_filter || $use_doctor_locations_filter ):
		$container_class = 'filters-on';
		$mix_it_up_class = 'mix'; ?>

        <form class="controls" id="Filters">
            <div class="search-field-div">

				<?php if ( $use_doctor_services_filter ): ?>

                    <fieldset>
                        <h2>Services</h2><br>
                        <select aria-label="Doctor Services Filter">
                            <option value="">All</option>

							<?php foreach ( $services as $service ): ?>
                                <option value="<?php echo '.' . $service->post_name; ?>"><?php echo $service->post_title; ?></option>
							<?php endforeach; ?>

                        </select>
                    </fieldset>

				<?php endif;

				if ( $use_doctor_locations_filter ): ?>

                    <fieldset>
                        <h2>Locations</h2>
                        <select aria-label="Doctor Location Filter">
                            <option value="">All</option>

							<?php foreach ( $locations as $location ): ?>
                                <option value="<?php echo '.' . $location->post_name; ?>"><?php echo $location->post_title; ?></option>
							<?php endforeach; ?>

                        </select>
                    </fieldset>

				<?php endif; ?>

                <button id="Reset" class="dr-clear-btn">Clear Filters</button>
            </div>
        </form>
        <div id="errorMessage"></div>

	<?php endif; ?>

    <div class="mix-holder <?php echo $container_class; ?>">
        <div id="Container" class="container">

			<?php
			$doctor_type_field_obj = get_field_object( 'doctor_type' );

			if ( ! empty( $doctor_type_field_obj['choices'] ) ) {
				$doctor_types = $doctor_type_field_obj['choices'];
			} else {
				$doctor_types = array( '' );
			}

			foreach ( $doctor_types as $doctor_type ):

				if ( $doctor_type ): ?>
                    <h2><?php echo $doctor_type; ?></h2>
				<?php endif; ?>

                <div class="cpt-grid">

					<?php while ( have_posts() ): the_post();

						$doctor_type_field    = get_field( 'doctor_type' );

						if ( in_array( $doctor_type, $doctor_type_field ) || empty( $doctor_type ) ):
							$doc_services = get_field( 'specialties' );
							$doc_locations    = get_field( 'location' );
							$location_classes = '';
							$service_classes  = '';
							$image            = get_field( 'headshot' );

							if ( $doc_locations ) {
								$location_names   = wp_list_pluck( $doc_locations, 'post_name' );
								$location_classes = implode( ' ', $location_names );
							}

							if ( $doc_services ) {
								$service_names   = wp_list_pluck( $doc_services, 'post_name' );
								$service_classes = implode( ' ', $service_names );
							}

							$doctor_classes = $location_classes . ' ' . $service_classes . ' ' . $mix_it_up_class;

							?>

                            <div class="cpt-doctor-image-link <?php echo $doctor_classes; ?>">

								<?php glacial_cpt_get_template_part( 'doctor-headshot-link' ); ?>

                            </div>

						<?php endif;

					endwhile; ?>

                </div>

			<?php endforeach; ?>

        </div>
    </div>

<?php else: ?>

    <h2>No Doctors Found</h2>

<?php endif;

get_footer(); ?>