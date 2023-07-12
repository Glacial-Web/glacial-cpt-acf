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
	$use_doctor_text_search_filter = get_field( 'use_doctor_text_search_filter', 'options' )?? true;
	$doctor_archive_page_layout = get_field( 'doctor_archive_page_layout', 'options' );

	if ( $use_doctor_services_filter || $use_doctor_locations_filter || $use_doctor_text_search_filter ) {
		$filters_on        = true;
		$container_class   = 'filters-on';
		$filter_item_class = 'doc-item';
	} else {
		$filters_on        = false;
		$container_class   = 'filters-off';
		$filter_item_class = 'doc-item-filters-off';
	}

	$container_class .= ' ' . $doctor_archive_page_layout;

	$locations = get_posts( array(
		'post_type'      => 'locations',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	$services = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_key'       => 'glacial_page_type',
		'meta_value'     => 'service-page'
	) );

	if ( $filters_on ): ?>

        <div class="doctor-filter-controls">
            <div class="search-field-div">

				<?php if ( $use_doctor_text_search_filter ): ?>

                    <div>
                        <h2>Name</h2>
                        <div class="doc-search-field">
                            <input id="textSearch" type="search" placeholder="Name Search"
                                   aria-label="Search Doctor Name">
                            <button id="docSearchReset">&#x2715;</button>
                        </div>
                    </div>

				<?php endif;

				if ( $use_doctor_services_filter ): ?>

                    <div>
                        <h2>Services</h2><br>
                        <select class="select-filter" aria-label="Doctor Services Filter" data-group="services">
                            <option value="*">All</option>

							<?php foreach ( $services as $service ): ?>
                                <option value="<?php echo '.' . $service->post_name; ?>"><?php echo $service->post_title; ?></option>
							<?php endforeach; ?>

                        </select>
                    </div>

				<?php endif;

				if ( $use_doctor_locations_filter ): ?>

                    <div>
                        <h2>Locations</h2>
                        <select class="select-filter" aria-label="Doctor Location Filter" data-group="locations">
                            <option value="*">All</option>

							<?php foreach ( $locations as $location ): ?>
                                <option value="<?php echo '.' . $location->post_name; ?>"><?php echo $location->post_title; ?></option>
							<?php endforeach; ?>

                        </select>
                    </div>

				<?php endif; ?>

            </div>
            <button id="reset" class="dr-clear-btn">Clear Filters</button>
            <div id="errorMessage"></div>
        </div>

	<?php endif; ?>

    <div class="<?php echo $container_class; ?>">
        <div class="doctor-archive-container">

			<?php

			$doctor_type_field_obj = get_field_object( 'doctor_type' );

			if ( ! empty( $doctor_type_field_obj['choices'] ) ) {
				$doctor_types = $doctor_type_field_obj['choices'];
			} else {
				$doctor_types = array( '' );
			}

			foreach ( $doctor_types as $doctor_type ): ?>

                <div>

					<?php if ( $doctor_type ):

						$heading_id = sanitize_title_with_dashes( $doctor_type ); ?>

                        <h2 class="doctor-type-heading" id="<?php echo $heading_id; ?>"><?php echo $doctor_type; ?></h2>

					<?php endif; ?>

                    <div class="doctor-filter-grid">

						<?php while ( have_posts() ): the_post();

							$doctor_type_field    = get_field( 'doctor_type' ) ?? array();

							if ( ! is_array( $doctor_type_field ) ) {
								$doctor_type_field = array( $doctor_type_field );
							}

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

								$doctor_classes = $location_classes . ' ' . $service_classes . ' ' . $filter_item_class;
								$doctor_classes = trim( $doctor_classes ); ?>

                                <div class="cpt-doctor-image-link <?php echo $doctor_classes; ?>">

									<?php glacial_cpt_get_template_part( 'doctor-headshot-link' ); ?>

                                </div>

							<?php endif;
						endwhile; ?>

                    </div>
                </div>

			<?php endforeach; ?>

        </div>
    </div>

<?php endif;

get_footer(); ?>