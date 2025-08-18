<?php

/*
 * Options for layout are:
 * - list
 * - grid
 * - map
 * */
function glacial_cpt_location_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'layout' => 'list',
	), $atts );

	$location_archive_layout = $atts['layout'];

	ob_start();

		glacial_cpt_get_template_part( '/locations/archive-locations-' . $location_archive_layout );

	return ob_get_clean();
}

add_shortcode( 'gl_locations', 'glacial_cpt_location_shortcode' );

