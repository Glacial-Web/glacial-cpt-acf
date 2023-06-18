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

if ( $doctors->have_posts() ):
	$cpt_object = get_post_type_object( 'doctors' );

	$cpt_name = $cpt_object->labels->name;
	$heading  = 'Our ' . $cpt_name; ?>

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

