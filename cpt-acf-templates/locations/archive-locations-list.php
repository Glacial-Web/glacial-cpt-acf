<?php
/**
 *
 * Displays all the locations in a list layout
 *
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

/*
 * Compare these at the end of each iteration
 * add an <hr> tag to all but the last one
 * */

//global $wp_query;
$counter     = 1;
$found_posts = $wp_query->found_posts;
$add_icons   = get_field( 'add_icons', 'options' ) ?? true; ?>

<?php while ( have_posts() ): the_post();
	$address = get_field( 'address' );
	$hours   = get_field( 'hours' );
	$iframe  = get_field( 'map_iframe' ); ?>

    <div class="cpt-location-info">
        <h2><?php the_title(); ?></h2>
        <div class="flex-wrapper">
            <div>

				<?php if ( $address ) : ?>
                    <div class="location-icon-wrap address">
						<?php if ( $add_icons ) {
							echo glacial_cpt_svg_icon( 'address' );
						} ?>
                        <p><?php echo $address; ?></p>
                    </div>
				<?php endif; ?>

	            <?php glacial_cpt_get_template_part( '/locations/phone-numbers' ); ?>

				<?php if ( $hours ): ?>
                    <div class="location-icon-wrap hours">
						<?php if ( $add_icons ) {
							echo glacial_cpt_svg_icon( 'hours' );
						} ?>
                        <p><?php echo $hours; ?></p>
                    </div>
				<?php endif; ?>

                <a href="<?php the_permalink(); ?>" class="ui-button">
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
	/*
	 * no <hr> on last location
	 * */
	if ( $counter != $found_posts ) {
		echo '<hr>';
	}

	$counter ++;

endwhile; ?>
