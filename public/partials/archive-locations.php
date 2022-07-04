<?php
/**
 *
 * Displays all the locations
 *
 * @package Glacial_Cpt_Acf
 */

if ( have_posts() ): ?>

	<?php
	// Compare these at the end of each iteration,
	// add an <hr> tag to all but the last iteration
	global $wp_query;
	$counter     = 1;
	$found_posts = $wp_query->found_posts; ?>

	<?php while ( have_posts() ): the_post();
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

<?php endif; ?>
