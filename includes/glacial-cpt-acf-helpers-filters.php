<?php
/**
 * Some helpers and filters for Glacial CPT ACF templates.
 *
 * @package Glacial_Cpt_Acf
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the doctors service page heading.
 *
 * @param int|null $post_id The post ID. Defaults to current post.
 * @param string $prefix An optional prefix for the heading.
 *
 * @return string The heading for the doctors service page.
 *
 * @since 2.1.1
 * */
function glacial_get_doctors_service_page_heading( $number_of_docs, string $prefix = '', int $post_id = null ): string {

	if ( !$post_id ) {
		$post_id = get_the_ID();
	}

	$cpt_object = get_post_type_object( 'doctors' );
	$cpt_labels = $cpt_object->labels;

	$cpt_labels = apply_filters( 'glacial_cpt_doctors_service_pages_cpt_labels', $cpt_labels );

	$cpt_label = $cpt_labels->name;

	if ( $number_of_docs === 1 ) {
		$cpt_label = $cpt_labels->singular_name;
	}


	// Get alternate heading if set
	$alternate_heading = get_field( 'related_doctors_alternate_heading', $post_id );

	/**
	 * Get the service page title
	 *
	 * Here's where you can modify specific service page titles.
	 * A default is set to change "Cataracts" to "Cataract".
	 *
	 * @see glacial_cpt_doctors_service_pages_title() in includes/glacial-cpt-acf-helpers-filters.php
	 * */

	$service_page_title = apply_filters( 'glacial_cpt_doctors_service_pages_title', get_the_title( $post_id ) );

	if ( $prefix ) {
		$service_page_title = $prefix . ' ' . $service_page_title;
	}

	$default_heading = $service_page_title . ' ' . $cpt_label;

	// Use alternate heading if available, otherwise default
	$heading = $alternate_heading ?: $default_heading;

	// Allow whole heading to be filtered
	return apply_filters( 'glacial_cpt_doctors_service_pages_heading', $heading );
}

/**
 * Filter the doctors service pages CPT labels.
 *
 * Input is an object containing all labels for the CPT.
 *
 * @param object $cpt_labels
 *
 * @return object
 *
 * @since 2.1.1
 */
function glacial_cpt_doctors_service_pages_cpt_labels( object $cpt_labels ): object {

	/*
	 * set our custom labels here ex:
	 *
	  if (is_page('some-page-slug')) {
	    $cpt_labels->name          = 'Physicians';
	    $cpt_labels->singular_name = 'Physician';
	   }
	*/

	return $cpt_labels;
}

add_filter( 'glacial_cpt_doctors_service_pages_cpt_labels', 'glacial_cpt_doctors_service_pages_cpt_labels' );

/**
 * Filter the doctors service pages heading.
 *
 * @param string $heading The heading.
 *
 * @return string
 */
function glacial_cpt_doctors_service_pages_heading( string $heading ): string {
	return $heading;
}

add_filter( 'glacial_cpt_doctors_service_pages_heading', 'glacial_cpt_doctors_service_pages_heading' );

/**
 * Filter the doctors service pages title.
 *
 * @param string $title The title.
 *
 * @return string
 *
 * @since 2.1.1
 */
function glacial_cpt_doctors_service_pages_title( string $title ): string {
	// our default title changes
	$pages_to_change = array(
		'Cataracts' => 'Cataract',
	);

	$pages_to_change = apply_filters( 'glacial_cpt_doctors_service_pages_title_changes', $pages_to_change );

	if ( array_key_exists( $title, $pages_to_change ) ) {
		$title = $pages_to_change[ $title ];
	}

	return $title;
}

add_filter( 'glacial_cpt_doctors_service_pages_title', 'glacial_cpt_doctors_service_pages_title' );