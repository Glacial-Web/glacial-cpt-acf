<?php
/**
 *
 * Displays all the locations in a list layout
 *
 * Because we use this template part in a shortcode, we need to query the post type again
 *
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

$args = array(
	'post_type'      => 'locations',
	'posts_per_page' => - 1,
	'orderby'        => 'title',
	'order'          => 'ASC',
);

$locations   = new WP_Query( $args );

$counter     = 1;
$found_posts = $locations->found_posts;
$add_icons   = get_field( 'add_icons', 'options' ) ?? true; ?>

<?php while ( $locations->have_posts() ): $locations->the_post();
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

endwhile;
wp_reset_postdata(); ?>
