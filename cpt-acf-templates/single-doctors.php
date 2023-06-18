<?php
/**
 * Displays single doctor
 *
 * @package Glacial_Cpt_Acf
 */

get_header();

$doctor_blog_post_layout = get_field( 'doctor_blog_post_layout', 'options' );

/*
 * Get the posts that have this doctor as the change_author_link
 * */
$args = array(
	'post_type'      => 'post',
	'meta_key'       => 'change_author_link',
	'meta_value'     => get_the_ID(),
	'posts_per_page' => 3,
);

$related_blog_posts = get_posts( $args );

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

				<?php endif;

				if ( $specialties || $locations || $related_blog_posts ): ?>

                    <div class="doctor-specialties-locations">

						<?php if ( $specialties ): ?>

                            <div>
                                <h3>Specialties</h3>
                                <ul>

									<?php foreach ( $specialties as $specialty ):

										$alternate_page_title = get_field( 'alternate_page_title', $specialty->ID );
										$specialty_title = $alternate_page_title ?: get_the_title( $specialty->ID ); ?>

                                        <li>
                                            <a href="<?php echo get_the_permalink( $specialty->ID ); ?>"
                                               class="ui-button"><?php echo $specialty_title; ?></a>
                                        </li>

									<?php endforeach; ?>

                                </ul>
                            </div>

						<?php endif;

						if ( $locations ): ?>

                            <div>
                                <h3>Locations</h3>
                                <ul>

									<?php foreach ( $locations as $location ): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink( $location->ID ) ?>"
                                               class="ui-button"><?php echo get_the_title( $location->ID ) ?></a>
                                        </li>
									<?php endforeach; ?>

                                </ul>
                            </div>

						<?php endif;

						/*
						 * Doctor Blog Post Layout 1 : Text links in sidebar
						 * */
						if ( $related_blog_posts && $doctor_blog_post_layout == 1 ): ?>

                            <div class="doctor-posts-list">
                                <h3>Posts by <?php the_title(); ?></h3>
                                <ul>

									<?php foreach ( $related_blog_posts as $related_blog_post ): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink( $related_blog_post->ID ) ?>"><?php echo get_the_title( $related_blog_post->ID ) ?></a>
                                        </li>
									<?php endforeach; ?>

                                </ul>
                            </div>

						<?php endif; ?>

                    </div>

				<?php endif; ?>

            </div>
            <div class="single-doctor-bio">

				<?php the_content();

				/*
				 * Doctor Blog Post Layout 2 : Post card link in right panel directly under content
				 * */
				if ( $related_blog_posts && $doctor_blog_post_layout == 2 ): ?>

                    <h3>Posts by <?php the_title(); ?></h3>
                    <div class="related-post-grid">

						<?php foreach ( $related_blog_posts as $related_blog_post ) {

							$args = array(
								'id'           => $related_blog_post->ID,
								'num_of_posts' => 3,
							);

							glacial_cpt_get_template_part( 'related-posts-element', array( 'id' => $related_blog_post->ID ) );
						} ?>
                    </div>

				<?php endif; ?>

            </div>

		<?php endwhile; ?>

    </div>

<?php endif;

/*
 * Doctor blog post layout 3 : Post card link at bottom of page
 * */
if ( $related_blog_posts && $doctor_blog_post_layout == 3 ): ?>

    <h3>Posts by <?php the_title(); ?></h3>
    <div class="related-post-grid">

		<?php foreach ( $related_blog_posts as $related_blog_post ) {

			$args = array(
				'id'           => $related_blog_post->ID,
				'num_of_posts' => 3,
			);

			glacial_cpt_get_template_part( 'related-posts-element', array( 'id' => $related_blog_post->ID ) );
		} ?>

    </div>

<?php endif;

get_footer(); ?>
