<?php
/**
 *
 * Template part for displaying doctors related to a service page.
 *
 * @package Glacial_Cpt_Acf
 */

$args = array(
	'posts_per_page' => - 1,
	'post_type'      => 'doctors',
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		'relation' => 'or',
		array(
			'key'     => 'specialties',
			'value'   => '"' . get_the_ID() . '"',
			'compare' => 'LIKE'
		)
	)
);

$doctors = new WP_Query( $args );

if ( $doctors->have_posts() ):
	// Get the number of doctors found. Used for singular/plural in heading.
	$number_of_doctors = $doctors->found_posts;
	// You can change or remove this prefix as needed, just leave as empty string '' if not needed.
	$optional_prefix = 'Our';

	/**
	 * @see glacial_get_doctors_service_page_heading() in includes/glacial-cpt-acf-helpers-filters.php
	 * */
	$heading = glacial_get_doctors_service_page_heading( $number_of_doctors, $optional_prefix ); ?>

    <div class="doctors-section">
        <div class="doctors-container">
            <h2><?php echo $heading; ?></h2>
            <div class="cpt-grid">

				<?php while ( $doctors->have_posts() ): $doctors->the_post(); ?>

                    <div class="cpt-doctor-image-link">

						<?php glacial_cpt_get_template_part( 'doctor-headshot-link' ); ?>

                    </div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>

<?php endif; ?>
