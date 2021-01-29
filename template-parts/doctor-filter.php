<?php
/**
 *
 * The template part for displaying the doctor filtering on the doctors page.
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

// get all locations
$location_type_query = new WP_Query(
	array(
		'post_type'      => 'locations',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'asc',
		'post__not_in'   => array( 80 )
	)
);
// array of posts
$locations = $location_type_query->posts;

wp_reset_query();
wp_reset_postdata();
//Get Services pages by tag
$services_tag_query = new WP_Query(
	array(
		'post_type' => 'page',
		'tag'       => 'service',
		'orderby'   => 'title',
		'order'     => 'asc'
	)
);

//Same as query above
$services = $services_tag_query->posts;

wp_reset_query();
wp_reset_postdata();
//Get Advanced Procedure pages by tag
$procedures_tag_query = new WP_Query(
	array(
		'post_type' => 'page',
		'tag'       => 'advanced-procedure',
		'orderby'   => 'title',
		'order'     => 'asc'
	)
);
//Same as query above
$procedures = $procedures_tag_query->posts;

wp_reset_query();
wp_reset_postdata();
?>

<div class="inner_page_section">
    <div class="inner_wrapper">
        <div class="inner_content_div">

			<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			} ?>

            <h1 class="inner_main_headline"><?php post_type_archive_title(); ?></h1>

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
                        <h2>Procedures</h2><br>
                        <select>
                            <option value="">All</option>

							<?php foreach ( $procedures as $procedure ): ?>
                                <option value="<?php echo '.' . $procedure->post_name; ?>"><?php echo $procedure->post_title; ?></option>
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
				'orderby'        => 'wpse_last_word', //Custom orderby in functions.php
				'order'          => 'asc'
			);

			$the_query = new WP_Query( $args ); ?>

			<?php if ( $the_query->have_posts() ) : ?>

                <div class="mix-holder">
                    <div id="Container" class="container">
                        <div class="flex-wrapper flex-start">
							<?php while ( $the_query->have_posts() ) : $the_query->the_post();
								$image  = get_field( 'headshot' );
								$degree = get_field( 'degree' );

								//Related services, procedure and locations fields
								$doc_locations  = get_field( 'location' );
								$doc_services   = get_field( 'specialties' );
								$doc_procedures = get_field( 'surgical_procedures' );

								//$doc_array_merge = array_merge($doc_locations, $doc_services, $doc_procedures);
								//print_r(array($doc_locations, $doc_procedures, $doc_services));
								?>

                                <div class="mix w-20 tablet-50 large-25 mobile-w-full doctor-individual <?php if ( $doc_locations ) {
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
                                        <p class="text-center doc-name"><b><?php the_title(); ?></b>

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

        </div>
    </div>
</div>


