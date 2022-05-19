<?php
/**
 *
 * Displays all the locations
 *
 * @package Glacial_Cpt_Acf
 */


$args = array(
  'post_type'      => 'locations',
  'posts_per_page' => - 1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC'
);

$locations_query = new WP_Query( $args ); ?>

<?php if ( $locations_query->have_posts() ): ?>

	<?php
// Compare these at the end of each iteration, add an <hr> tag to all but the last iteration
	$counter     = 1;
	$found_posts = $locations_query->found_posts;
	?>

	<?php while ( $locations_query->have_posts() ): $locations_query->the_post();
		// ACF vars
		$address = get_field( 'address' );
		$hours   = get_field( 'hours' );
		$iframe  = get_field( 'map_iframe' ); ?>

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

					<?php include( plugin_dir_path( __FILE__ ) . 'phone-numbers.php' ); ?>

					<?php if ( $hours ): ?>
                        <p><?php echo $hours; ?></p>
					<?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="ui-button"
                       title="Learn more about <?php the_title(); ?>">
                        More About <?php the_title(); ?>
                    </a>
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
		if ( $counter != $found_posts ) {
			echo '<hr>';
		}

		$counter ++; ?>

	<?php endwhile; ?>

	<?php wp_reset_postdata(); ?>

<?php endif; ?>
