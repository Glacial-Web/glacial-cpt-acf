<?php
/**
 *
 * Displays single locations
 *
 * @package Glacial_Cpt_Acf
 */
?>

<?php get_header(); ?>

<?php if ( have_posts() ): ?>

	<?php while ( have_posts() ): the_post();
		//Vars
		$address = get_field( 'address' );
		$hours   = get_field( 'hours' );
		$iframe  = get_field( 'map_iframe' ); ?>

        <div class="cpt-location-info">
            <div class="flex-wrapper">
                <div>

					<?php if ( $address ): ?>
                        <p><?php echo $address; ?></p>
					<?php endif; ?>

					<?php include( plugin_dir_path( __FILE__ ) . 'phone-numbers.php' ); ?>

					<?php if ( $hours ): ?>
                        <p><?php echo $hours; ?></p>
					<?php endif; ?>

                    <div class="location-content">

						<?php the_content(); ?>

                    </div>
                </div>
                <div>
                    <div class="embed-container location-page">

						<?php echo $iframe; ?>

                    </div>
                </div>
            </div>

			<?php
			// This section is for getting the post object
			// of the related pages using ACF Relationship field
			// Vars using ACF Relationship fields
			$specialties = get_field( 'location_specialties' );

			if ( $specialties ): ?>

                <div class="specialties_div locations-specialties">
                    <div class="specialties_wrapper">
                        <h3 class="inner_docs_headline _2">Services at <?php the_title(); ?></h3>

						<?php foreach ( $specialties as $post ): ?>
                            <a href="<?php the_permalink(); ?>"
                               title="More about <?php the_title(); ?>"
                               class="page_link"><?php the_title(); ?></a>
						<?php endforeach; ?>

					<?php wp_reset_postdata(); ?>

                    </div>
                </div>

			<?php endif; ?>

        </div>

		<?php
		//Query the Doctors custom post type to see if any doctors are related to this location
		$doctors = get_posts( array(
			'numberposts' => - 1,
			'post_type'   => 'doctors',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'meta_query'  => array(
				array(
					'key'     => 'location',
					// name of custom field
					'value'   => '"' . get_the_ID() . '"',
					// matches exactly "123", not just 123. This prevents a match for "1234"
					'compare' => 'LIKE'
				)
			)
		) );

		if ( $doctors ): ?>

            <div class="doctors-section-location">
                <div class="wrapper_div-2">
                    <h2 class="inner_docs_headline">Doctors at <?php the_title(); ?> </h2>
                    <div class="flex-wrapper flex-start">

						<?php foreach ( $doctors as $doctor ):
							$degree = get_field( 'degree', $doctor->ID );
							$img = get_field( 'headshot', $doctor->ID ); ?>

                            <div class="cpt-doctor-image-link">
                                <a href="<?php echo get_permalink( $doctor->ID ); ?>"
                                   title="<?php echo get_the_title( $doctor->ID ); ?>">
                                    <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"
                                         class="doc_btn_img">
                                    <p class="text-center doc-name"><b><?php echo get_the_title( $doctor->ID ) ?></b>

										<?php if ( $degree ): ?>
                                            <br>
											<?php echo $degree;
										endif; ?>
                                    </p>
                                </a>
                            </div>

						<?php endforeach; ?>

						<?php wp_reset_postdata(); ?>

                    </div>
                </div>
            </div>

		<?php endif; ?>

	<?php endwhile; // end of our query loop ?>

<?php endif; ?>

<?php get_footer(); ?>

