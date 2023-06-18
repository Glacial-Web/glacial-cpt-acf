<?php
/**
 * Displays single locations
 *
 * @package Glacial_Cpt_Acf
 */

get_header();

if ( have_posts() ):

	while ( have_posts() ): the_post();
		$address = get_field( 'address' );
		$hours   = get_field( 'hours' );
		$iframe  = get_field( 'map_iframe' ); ?>

        <div class="single-cpt-wrapper">
            <div class="single-location-info">

				<?php if ( $address ): ?>
                    <p><?php echo $address; ?></p>
				<?php endif;

				glacial_cpt_get_template_part( 'phone-numbers' );

				if ( $iframe ): ?>
                    <div class="embed-container location-page">
						<?php echo $iframe; ?>
                    </div>
				<?php endif;

				if ( $hours ): ?>
                    <p><?php echo $hours; ?></p>
				<?php endif;

				if ( have_rows( 'additional_buttons' ) ): ?>

                    <div class="single-location-buttons">

						<?php while ( have_rows( 'additional_buttons' ) ): the_row();
							$link = get_sub_field( 'link' ); ?>

                            <a href="<?php echo $link['url']; ?>" class="ui-button"
                               target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>

						<?php endwhile; ?>

                    </div>

				<?php endif;

				/*
				 * This section is for getting the post object of the related pages using
				 * ACF Relationship field Vars using ACF Relationship fields
				 * */
				$specialties = get_field( 'location_specialties' );

				if ( $specialties ): ?>

                    <div>
                        <h3>Services at <?php the_title(); ?></h3>
                        <ul>

							<?php foreach ( $specialties as $specialty ): ?>
                                <li>
                                    <a href="<?php echo get_the_permalink( $specialty->ID ) ?>"
                                       class="page_link"><?php echo get_the_title( $specialty->ID ); ?></a>
                                </li>
							<?php endforeach; ?>

                        </ul>

                    </div>

				<?php endif; ?>

            </div>
            <div class="single-location-content">

				<?php the_content();

				/*
				 * Query the Doctors custom post type to see if any doctors are
				 * related to this location
				 * */
				$args = array(
					'posts_per_page' => - 1,
					'post_type'      => 'doctors',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'meta_query'     => array(
						array(
							'key'     => 'location',
							'value'   => '"' . get_the_ID() . '"',
							'compare' => 'LIKE'
						)
					)
				);

				$doctors = new WP_Query( $args );

				if ( $doctors->have_posts() ):
					$cpt_object = get_post_type_object( 'doctors' );

					$heading = $cpt_object->labels->name . ' at ' . get_the_title(); ?>

                    <div class="single-location-doctors">
                        <h2><?php echo $heading; ?></h2>
                        <div class="cpt-grid">

							<?php while ( $doctors->have_posts() ): $doctors->the_post(); ?>

                                <div class="cpt-doctor-image-link">

									<?php glacial_cpt_get_template_part( 'doctor-headshot-link' ); ?>

                                </div>

							<?php endwhile;

							wp_reset_postdata(); ?>

                        </div>
                    </div>

				<?php endif;

				glacial_cpt_get_template_part( 'location-related-posts' ); ?>

            </div>
        </div>

	<?php endwhile;

endif;

get_footer(); ?>

