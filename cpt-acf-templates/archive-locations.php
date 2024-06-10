<?php
/**
 *
 * Displays all the locations
 *
 * @package Glacial_Cpt_Acf
 */

get_header();

if ( have_posts() ) {
	$location_archive_layout = get_field( 'location_archive_layout', 'options' ) ?? 'list';

//	glacial_cpt_get_template_part( '/locations/archive-locations-' . $location_archive_layout );
	echo '<h2>Map</h2>';
	glacial_cpt_get_template_part( '/locations/archive-locations-map');
	echo '<hr>';
	echo '<h2>Grid</h2>';
	glacial_cpt_get_template_part( '/locations/archive-locations-grid' );
	echo '<hr>';
	echo '<h2>List</h2>';
	glacial_cpt_get_template_part( '/locations/archive-locations-list' );

}

get_footer();
