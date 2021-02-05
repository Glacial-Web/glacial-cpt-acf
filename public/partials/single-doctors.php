<?php
/**
 * The template for displaying single Doctor pages
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
		//ACF Vars
		$thumbnail_id           = get_post_thumbnail_id( $post->ID );
		$alt                    = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
		$url                    = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		$additional_specialties = get_field( 'additional_specialties' ); ?>

		<?php if ( $additional_specialties ): ?>
            <div class="doctor-additional-specialties">
				<?php echo $additional_specialties; ?>
            </div>
		<?php endif; ?>

        <img src="<?php echo $url; ?>" alt="<?php echo $alt; ?>" class="inner_img">

		<?php the_content(); ?>

		<?php
		//This section is for getting the post object of the related pages using ACF Relationship field
		//Vars using ACF Relationship fields
		$specialties = get_field( 'specialties' );
		$surgical    = get_field( 'surgical_procedures' );
		$locations   = get_field( 'location' ); ?>

        <div class="specialties_div">

			<?php if ( $specialties ): ?>
                <div class="specialties_wrapper">
                    <h3 class="inner_docs_headline _2">Specialties</h3>

					<?php foreach ( $specialties as $post ): ?>
                        <a href="<?php the_permalink(); ?>"
                           class="page_link"><?php the_title(); ?></a>
					<?php endforeach; ?>

                </div>

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

			<?php if ( $locations ): ?>
                <div class="specialties_wrapper">
                    <h3 class="inner_docs_headline _2">Locations</h3>
					<?php foreach ( $locations as $post ): ?>
                        <a href="<?php the_permalink(); ?>" class="page_link"><?php the_title(); ?></a>
					<?php endforeach; ?>
                </div>

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

        </div>

	<?php endwhile; //End of loop ?>

<?php endif; ?>

<?php get_footer(); ?>