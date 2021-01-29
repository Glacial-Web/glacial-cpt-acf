<?php
/**
 *
 * The template for displaying Glaucoma page
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */
?>

<?php get_header(); ?>

<div class="inner_page_section location">
    <div class="inner_wrapper location-page">
        <div class="inner_content_div" id="location-<?php the_ID(); ?>">

			<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			} ?>

			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) :
			the_post();

			//Vars
			$address = get_field( 'address' );
			$hours   = get_field( 'hours' );
			$iframe  = get_field( 'map_iframe' );
			?>

            <h1 class="inner_main_headline"><?php the_title(); ?></h1>

            <div class="cpt-location-info">
                <div class="flex-wrapper">
                    <div>
                        <div class="location-content">

							<?php the_content(); ?>

                        </div>
                        <p><?php echo $address; ?></p>

						<?php include( plugin_dir_path( __DIR__ ) . 'template-parts/location-phone-numbers.php' ); ?>

                        <p><?php echo $hours; ?></p>

                    </div>
                    <div>
                        <div class="embed-container location-page">

							<?php echo $iframe; ?>

                        </div>
                    </div>
                </div>
            </div>

			<?php
			// This section is for getting the post object
			// of the related pages using ACF Relationship field
			// Vars using ACF Relationship fields
			$specialties = get_field( 'location_specialties' );
			$surgical    = get_field( 'location_surgical_procedures' );

			if ( $specialties || $surgical ): ?>

                <div class="specialties_div locations-specialties">

					<?php if ( $specialties ): ?>
                        <div class="specialties_wrapper">
                            <h3 class="inner_docs_headline _2"><?php the_title(); ?><br>Specialties</h3>

							<?php foreach ( $specialties as $post ): ?>
                                <a href="<?php the_permalink(); ?>"
                                   title="More about <?php the_title(); ?>"
                                   class="page_link"><?php the_title(); ?></a>
							<?php endforeach; ?>

                        </div>

						<?php wp_reset_postdata(); ?>

					<?php endif; ?>

					<?php if ( $surgical ): ?>

                        <div class="specialties_wrapper">
                            <h3 class="inner_docs_headline _2"><?php the_title(); ?><br>Surgical Procedures</h3>

							<?php foreach ( $surgical as $post ): ?>
                                <a href="<?php the_permalink(); ?>" class="page_link"><?php the_title(); ?></a>
							<?php endforeach; ?>

                        </div>

						<?php wp_reset_postdata(); ?>

					<?php endif; ?>

                </div>

			<?php endif; ?>

        </div>
    </div>

	<?php
	//Query the Doctors custom post type to see if any doctors are related to this location
	$doctors = get_posts( array(
		'numberposts' => - 1,
		'post_type'   => 'doctors',
		'orderby'     => 'mune_order',
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
						$img = get_field( 'headshot', $doctor->ID );
						?>

                        <div class="cpt-doctor-image-link">
                            <a href="<?php echo get_permalink( $doctor->ID ); ?>"
                               title="<?php echo get_the_title( $doctor->ID ); ?>">

                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"
                                     class="doc_btn_img">
                                <p class="text-center doc-name"><b><?php echo get_the_title( $doctor->ID ) ?></b>

									<?php if ( $degree ): ?>
                                        <br>
										<?php echo $degree;
									endif;
									?>
                                </p>
                            </a>
                        </div>

					<?php endforeach; ?>

                </div>
            </div>
        </div>

	<?php endif; ?>

</div>

<?php endwhile; // end of the loop. ?>

<?php wp_reset_postdata(); ?>

<?php endif; ?>

<?php get_footer(); ?>

