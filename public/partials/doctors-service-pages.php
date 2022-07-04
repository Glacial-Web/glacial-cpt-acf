<?php
/**
 *
 * Template part for displaying doctors by chosen category
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

//Query the Doctors custom post type to see if any doctors are related to the page
$args = array(
  'numberposts' => - 1,
  'post_type'   => 'doctors',
  'orderby'     => 'menu_order',
  'order'       => 'ASC',
  'meta_query'  => array(
	'relation' => 'or',
	array(
	  'key'     => 'specialties',
	  'value'   => '"' . get_the_ID() . '"',
	  'compare' => 'LIKE'
	)
  )
);

$doctors = new WP_Query( $args );

if ( $doctors->have_posts() ): ?>

    <div class="doctors-section">
        <div class="doctors-container">
            <h2><?php the_title(); ?> Doctors</h2>
            <div class="flex-wrapper flex-start">

				<?php while ( $doctors->have_posts() ): $doctors->the_post(); ?>
                    <div class="cpt-doctor-image-link">
						<?php include plugin_dir_path( __DIR__ ) . 'partials/doctor-headshot-link.php'; ?>
                    </div>
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>

<?php endif; ?>
