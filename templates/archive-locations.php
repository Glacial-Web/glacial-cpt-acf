<?php
/**
 *
 * Template for displaying all the locations
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */


$args = array(
	'post_type'      => 'locations',
	'posts_per_page' => - 1,
	'orderby'        => 'menu_order',
	'order'          => 'asc'
);

$the_query = new WP_Query( $args ); ?>

<?php if ( $the_query->have_posts() ) :
	$counter = 1;
	$all_fields_count = $the_query->post_count; ?>


                <h1 class="inner_main_headline"><?php echo post_type_archive_title(); ?></h1>

				<?php while ( $the_query->have_posts() ) : $the_query->the_post();
					$address = get_field( 'address' );
					$hours   = get_field( 'hours' );
					$iframe  = get_field( 'map_iframe' );
					?>

                    <div class="cpt-location-info">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <h2><?php the_title(); ?></h2>
                        </a>
                        <div class="flex-wrapper">
                            <div>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">

                                    <?php if ( $address ) : ?>
                                        <p><?php echo $address; ?></p>
                                    <?php endif; ?>

                                </a>

								<?php include( plugin_dir_path (__DIR__) . 'template-parts/location-phone-numbers.php' ); ?>

                                <p>
                                <?php echo $hours; ?>
                                </p>

                                <a href="<?php the_permalink(); ?>" class="ui-button" title="Learn more about <?php the_title(); ?>">Learn More</a>
                            </div>
                            <div>

								<?php if ( $iframe ) : ?>
                                    <div class="embed-container location-page">
										<?php echo $iframe; ?>
                                    </div>
								<?php endif; ?>

                            </div>
                        </div>
                    </div>

					<?php
					// no <hr> on last location
					if ( $counter != $all_fields_count ):?>

                        <hr class="mb-4">

					<?php endif; ?>

					<?php $counter ++; ?>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>


<?php endif; ?>
