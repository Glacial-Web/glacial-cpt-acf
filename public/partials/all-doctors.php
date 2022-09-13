<?php
/**
 * The template partial for displaying all doctors except on
 * single doctor pages. Does not show current doctor.
 *
 *
 * @package Glacial_Cpt_Acf
 */


$args = array(
  'posts_per_page' => - 1,
  'post__not_in'   => array( get_the_ID() ),
  'post_type'      => 'doctors',
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
);

$doctors = new WP_Query( $args );

if ( $doctors->have_posts() ): ?>

    <div class="doctors-section">
        <div class="doctors-container">
            <h2>Our Doctors</h2>
            <div class="flex-wrapper flex-start">

				<?php while ( $doctors->have_posts() ): $doctors->the_post(); ?>

                    <div class="cpt-doctor-image-link">
						<?php include( GLACIAL_CPT_PLUGIN_DIR . 'public/partials/doctor-headshot-link.php' ); ?>
                    </div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>

<?php endif; ?>

