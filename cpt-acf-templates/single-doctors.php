<?php
/**
 * Displays single doctor
 *
 * @package Glacial_Cpt_Acf
 */

get_header();

if ( have_posts() ): ?>

    <div class="single-cpt-wrapper">

		<?php while ( have_posts() ): the_post();

			$additional_specialties = get_field( 'additional_specialties' );
			$specialties            = get_field( 'specialties' );
			$locations              = get_field( 'location' ); ?>

            <div class="single-doctor-img-info">

				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large', array( 'class' => 'doctor-featured-image' ) );
				} else {
					echo '<img src="' . GLACIAL_CPT_PLUGIN_URL . 'public/images/doc-placeholder.jpg" alt="' . get_the_title() . '" class="doctor-featured-image">';
				} ?>

				<?php if ( $additional_specialties ): ?>

                    <h3 class="doctor-additional-specialties"><?php echo $additional_specialties; ?></h3>

				<?php endif; ?>

				<?php if ( $specialties || $locations ): ?>

                    <div class="doctor-specialties-locations">

						<?php if ( $specialties ): ?>

                            <div>
                                <h3>Specialties</h3>
                                <ul>

									<?php foreach ( $specialties as $specialty ): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink( $specialty->ID ); ?>"
                                               class="page_link"><?php echo get_the_title( $specialty->ID ) ?></a>
                                        </li>
									<?php endforeach; ?>

                                </ul>
                            </div>

						<?php endif; ?>

						<?php if ( $locations ): ?>

                            <div>
                                <h3>Locations</h3>
                                <ul>

									<?php foreach ( $locations as $location ): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink( $location->ID ) ?>"
                                               class="page_link"><?php echo get_the_title( $location->ID ) ?></a>
                                        </li>
									<?php endforeach; ?>

                                </ul>
                            </div>

						<?php endif; ?>

                    </div>

				<?php endif; ?>

            </div>
            <div class="single-doctor-bio">

				<?php the_content(); ?>

            </div>

		<?php endwhile; ?>

    </div>

<?php endif; ?>

<?php get_footer(); ?>
