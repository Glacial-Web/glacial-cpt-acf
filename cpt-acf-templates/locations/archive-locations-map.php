<?php

$args = array(
	'post_type'      => 'locations',
	'posts_per_page' => - 1,
	'orderby'        => 'title',
	'order'          => 'ASC',
);

$locations = new WP_Query( $args ); ?>

<section>
    <div class="location-search-wrapper">
        <div class="location-search">
            <button id="getLocation" aria-label="Use your current location">
				<?php echo glacial_cpt_svg_icon( 'target' ); ?>
            </button>
            <div id="locationSearch"></div>
        </div>
    </div>
    <div class="find-location-container">
        <div class="location-holder-left" id="panel">
			<?php if ( $locations->have_posts() ):
				while ( $locations->have_posts() ): $locations->the_post();
					$google_map        = get_field( 'google_map' );
					if ( $google_map ):
						$lng = $google_map['lng'] ?? '';
						$lat           = $google_map['lat'] ?? '';
						$directions    = 'https://www.google.com/maps/dir/?api=1&destination=' . $lat . ',' . $lng;
						$phone_numbers = get_field( 'phone_numbers' );
						// rewrite the address to there is a check before each other array elements
						$address = ( $google_map['street_number'] ?? '' ) . ' ' .
						           ( $google_map['street_name_short'] ?? '' ) . '<br>' .
						           ( $google_map['city'] ?? '' ) . ', ' .
						           ( $google_map['state'] ?? '' ) . ' ' .
						           ( $google_map['post_code'] ?? '' ); ?>

                        <div>
                            <a href="#" class="open-info-window" data-locationid="<?php the_ID(); ?>">
                                <h3 class="location-title">
									<?php the_title(); ?>
                                </h3>
                            </a>
                            <div class="location-data-container">
								<?php if ( $address ): ?>
                                    <p>
										<?php echo $address ?>
                                    </p>
								<?php endif;
								glacial_cpt_get_template_part( '/locations/phone-numbers' ); ?>
                                <div class="location-info-buttons">
                                    <a href="<?php the_permalink(); ?>" class="map-link">More Information</a>
                                    <a href="<?php echo $directions; ?>" class="map-link" target="_blank">Get Directions</a>
                                </div>
                            </div>
                        </div>
					<?php endif;
				endwhile;
				wp_reset_postdata();
			endif; ?>
        </div>
        <div id="glacialMap" class="glacial-map-div"></div>
    </div>
</section>