<?php
/**
 * Displays single doctor
 *
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */
get_header();
?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post();
		// ACF Vars
		$additional_specialties = get_field( 'additional_specialties' ); ?>

		<?php if ( $additional_specialties ): ?>
            <div class="doctor-additional-specialties">
				<?php echo $additional_specialties; ?>
            </div>
		<?php endif; ?>

		<?php the_post_thumbnail( 'large', array( 'class' => 'doctor-featured-image' ) ); ?>

		<?php the_content(); ?>

		<?php
		//This section is for getting the post object of the related pages using ACF Relationship field
		//Vars using ACF Relationship fields
		$specialties = get_field( 'specialties' );
		$surgical    = get_field( 'surgical_procedures' );
		$locations   = get_field( 'location' ); ?>

        <div class="doctor-specialties-locations flex-wrapper">

			<?php if ( $specialties ): ?>
                <div class="specialties-wrapper">
                    <h3>Specialties</h3>
                    <ul>
						<?php foreach ( $specialties as $post ): ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"
                                   class="page_link"><?php the_title(); ?></a>
                            </li>
						<?php endforeach; ?>

						<?php wp_reset_postdata(); ?>

                    </ul>
                </div>
			<?php endif; ?>

			<?php if ( $locations ): ?>
                <div class="specialties-wrapper">
                    <h3>Locations</h3>
                    <ul>
						<?php foreach ( $locations as $post ): ?>
                            <li>
                                <a href="<?php the_permalink(); ?>" class="page_link"><?php the_title(); ?></a>
                            </li>
						<?php endforeach; ?>

                        <?php wp_reset_postdata(); ?>

                    </ul>
                </div>
			<?php endif; ?>

        </div>

	<?php endwhile; //End of loop ?>

<?php endif; ?>

<?php get_footer(); ?>