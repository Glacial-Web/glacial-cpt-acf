<?php
/**
 *  Displays doctor filtering on the doctors archive page.
 *
 * @package Glacial_Cpt_Acf
 */

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
); ?>

<form class="controls" id="Filters">
    <div class="search-field-div">
        <fieldset>
            <h2>Services</h2><br>
            <select>
                <option value="">All</option>

				<?php foreach ( $services as $service ): ?>
                    <option value="<?php echo '.' . $service->post_name; ?>"><?php echo $service->post_title; ?></option>
				<?php endforeach; ?>

            </select>
        </fieldset>
        <fieldset>
            <h2>Locations</h2>
            <select>
                <option value="">All</option>
				<?php foreach ( $locations as $location ): ?>
                    <option value="<?php echo '.' . $location->post_name; ?>"><?php echo $location->post_title; ?></option>
				<?php endforeach; ?>
            </select>
        </fieldset>

    </div>

    <button id="Reset" class="dr-clear-btn">Clear Filters</button>
</form>
<div id="errorMessage"></div>


<?php
//Get the Doctors
$args = array(
  'post_type'      => 'doctors',
  'posts_per_page' => '-1',
  'orderby'        => 'menu_order',
  'order'          => 'asc'
);

$doctor_query = new WP_Query( $args ); ?>

<?php if ( $doctor_query->have_posts() ) : ?>

    <div class="mix-holder">
        <div id="Container" class="container">
            <div class="flex-wrapper flex-start">

				<?php while ( $doctor_query->have_posts() ) : $doctor_query->the_post();

					$image            = get_field( 'headshot' );
					$doc_locations    = get_field( 'location' );
					$doc_services     = get_field( 'specialties' );
					$location_classes = '';
					$service_classes  = '';

					if ( $doc_locations ) {
						$location_names   = wp_list_pluck( $doc_locations, 'post_name' );
						$location_classes = implode( ' ', $location_names );
					}

					if ( $doc_services ) {
						$service_names   = wp_list_pluck( $doc_services, 'post_name' );
						$service_classes = implode( ' ', $service_names );
					}

					$doctor_classes = $location_classes . ' ' . $service_classes; ?>

                    <div class="mix cpt-doctor-image-link <?php echo $doctor_classes; ?>">

						<?php include plugin_dir_path( __DIR__ ) . 'partials/doctor-headshot-link.php'; ?>

                    </div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>

<?php endif; ?>
