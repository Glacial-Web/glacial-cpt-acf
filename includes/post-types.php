<?php
/**
 * Register our Custom Post Types: doctors and locations.
 *
 * @package Glacial_Cpt_Acf
 */


/*
 * Doctors CPT
 * */
function doctor_post_type_register() {

	/**
	 * @since 1.1.0
	 *
	 * Add dynamic options via ACF Options page
	 * */
	$doctors_cpt_name = get_field( 'doctors_cpt_name', 'option' ) ?? 'Doctors';
	$doctors_cpt_slug = get_field( 'doctors_cpt_slug', 'option' ) ?? 'doctors';

	$labels = array(
	  'name'              => $doctors_cpt_name,
	  'menu_name'         => $doctors_cpt_name,
	  'all_items'         => 'All ' . $doctors_cpt_name,
	  'view_item'         => 'View ' . $doctors_cpt_name,
	  'add_new_item'      => 'Add New ' . $doctors_cpt_name,
	  'add_new'           => 'Add New ' . $doctors_cpt_name,
	  'edit_item'         => 'Edit ' . $doctors_cpt_name,
	  'update_item'       => 'Update ' . $doctors_cpt_name,
	  'search_items'      => 'Search ' . $doctors_cpt_name,
	);

	$args = array(
	  'label'               => 'doctors',
	  'description'         => 'Doctor Bios and Specialties',
	  'labels'              => $labels,
	  'supports'            => array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'revisions',
		'page-attributes'
	  ),
	  'hierarchical'        => false,
	  'public'              => true,
	  'show_ui'             => true,
	  'show_in_menu'        => true,
	  'show_in_nav_menus'   => true,
	  'show_in_admin_bar'   => true,
	  'menu_position'       => 5,
	  'can_export'          => true,
	  'has_archive'         => true,
	  'exclude_from_search' => false,
	  'publicly_queryable'  => true,
	  'capability_type'     => 'page',
	  'menu_icon'           => 'dashicons-buddicons-buddypress-logo',
	  'show_in_rest'        => true,
	  'rewrite'             => array(
		'slug'       => $doctors_cpt_slug, // Change this to change the doctors slug
		'with_front' => false
	  )
	);

	register_post_type( 'doctors', $args );

}

add_action( 'init', 'doctor_post_type_register', 0 );

/*
 * Location CPT
 * */
function location_post_type_register() {

	/**
	 * @since 1.1.0
	 *
	 * Add dynamic options via ACF Options page
	 * */
	$locations_cpt_name = get_field( 'locations_cpt_name', 'option' ) ?? 'Locations';
	$locations_cpt_slug = get_field( 'locations_cpt_slug', 'option' ) ?? 'locations';

	$labels = array(
	  'name'               => $locations_cpt_name,
	  'singular_name'      => $locations_cpt_name,
	  'menu_name'          => $locations_cpt_name,
	  'all_items'          => 'All ' . $locations_cpt_name,
	  'view_item'          => 'View ' . $locations_cpt_name,
	  'add_new_item'       => 'Add New ' . $locations_cpt_name,
	  'add_new'            => 'Add New ' . $locations_cpt_name,
	  'edit_item'          => 'Edit '. $locations_cpt_name,
	  'update_item'        => 'Update ' . $locations_cpt_name,
	  'search_items'       => 'Search ' . $locations_cpt_name,
	);

	$args = array(
	  'label'               => 'locations',
	  'description'         => 'Locations',
	  'labels'              => $labels,
	  'supports'            => array(
		'title',
		'editor',
		'author',
		'thumbnail',
		'revisions',
		'page-attributes'
	  ),
	  'menu_icon'           => 'dashicons-location',
	  'hierarchical'        => false,
	  'public'              => true,
	  'show_ui'             => true,
	  'show_in_menu'        => true,
	  'show_in_nav_menus'   => true,
	  'show_in_admin_bar'   => true,
	  'menu_position'       => 6,
	  'can_export'          => true,
	  'has_archive'         => true,
	  'exclude_from_search' => false,
	  'query_var'           => true,
	  'publicly_queryable'  => true,
	  'capability_type'     => 'page',
	  'show_in_rest'        => true,
	  'rewrite'             => array(
		'slug'       => $locations_cpt_slug, // Change this to change the locations slug
		'with_front' => false
	  )
	);

	register_post_type( 'locations', $args );
}

add_action( 'init', 'location_post_type_register', 0 );


