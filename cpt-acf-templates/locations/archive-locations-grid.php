<?php
/**
 *
 * Displays all the locations in a grid layout
 *
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */ ?>

<div class="locations-grid">

	<?php
	$add_icons = get_field( 'add_icons', 'options' ) ?? true;

	while ( have_posts() ): the_post();
		$address = get_field( 'address' );
		$hours   = get_field( 'hours' );
		$iframe  = get_field( 'map_iframe' ); ?>

        <div class="cpt-location-card">
            <div>
                <h2><?php the_title(); ?></h2>

				<?php if ( $address ): ?>
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

            </div>
            <div>
                <a href="<?php the_permalink(); ?>" class="ui-button">
                    More About <?php the_title(); ?>
                </a>

				<?php if ( $iframe ): ?>
                    <div class="card-iframe">
						<?php echo $iframe; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>

	<?php endwhile; ?>

</div>
