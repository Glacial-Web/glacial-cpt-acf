<?php
/**
 *  Displays doctor filtering on the doctors archive page.
 *
 * @package Glacial_Cpt_Acf
 */

// Get all locations
$location_type_query = new WP_Query(
	array(
		'post_type'      => 'locations',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'asc',
	)
);
// Array of locations
$locations = $location_type_query->posts;

wp_reset_postdata();

// Get Services pages by tag
$services_tag_query = new WP_Query(
	array(
		'post_type' => 'page',
		'tag'       => 'service',
		'orderby'   => 'title',
		'order'     => 'asc'
	)
);

// Array of service pages
$services = $services_tag_query->posts;

wp_reset_postdata();
?>

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

    <button id="Reset" class="dr-clear-btn ui-button">Clear Filters</button>
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
					// ACF Vars
					$image          = get_field( 'headshot' );
					$degree         = get_field( 'degree' );
					$doc_locations  = get_field( 'location' );
					$doc_services   = get_field( 'specialties' );
					$doc_procedures = get_field( 'surgical_procedures' );

					?>

                    <div class="mix cpt-doctor-image-link doctor-individual <?php if ( $doc_locations ) {
						if ( is_array( $doc_locations ) ) {
							foreach ( $doc_locations as $location ) {
								echo ' ' . $location->post_name;
							}
						} else {
							echo ' ' . $doc_locations->post_name;
						}
					}

					if ( $doc_services ) {
						if ( is_array( $doc_services ) ) {
							foreach ( $doc_services as $service ) {
								echo ' ' . $service->post_name;
							}

						} else {
							echo ' ' . $doc_services->post_name;
						}
					}

					if ( $doc_procedures ) {
						if ( is_array( $doc_procedures ) ) {
							foreach ( $doc_procedures as $procedure ) {
								echo ' ' . $procedure->post_name;
							}
						} else {
							echo ' ' . $doc_procedures->post_name;
						}

					} ?>">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                            <p class="doctor-name"><b><?php the_title(); ?></b>

								<?php if ( $degree ): ?>
                                    <br>
									<?php echo $degree;
								endif;
								?>
                            </p>
                        </a>
                    </div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>

<?php endif; ?>
