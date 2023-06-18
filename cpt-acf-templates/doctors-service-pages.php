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
	$cpt_object = get_post_type_object( 'doctors' );

	$cpt_labels                        = $cpt_object->labels->name;
	$related_doctors_alternate_heading = get_field( 'related_doctors_alternate_heading' );
	$default_heading                   = get_the_title() . ' ' . $cpt_labels;
	$heading                           = $related_doctors_alternate_heading ?: $default_heading; ?>

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
