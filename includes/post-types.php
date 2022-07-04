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
	$labels = array(
	  'name'              => 'Doctors',
	  'singular_name'     => 'Doctor',
	  'menu_name'         => 'Doctors',
	  'parent_item_colon' => 'Parent Doctor',
	  'all_items'         => 'All Doctors',
	  'view_item'         => 'View Doctor',
	  'add_new_item'      => 'Add New Doctor',
	  'add_new'           => 'Add New',
	  'edit_item'         => 'Edit Doctor',
	  'update_item'       => 'Update Doctor',
	  'search_items'      => 'Search Doctor'
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
		'category',
		'page-attributes'
	  ),
	  'taxonomies'          => array( 'post_tag', 'category' ),
	  'hierarchical'        => true,
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
		'slug'       => 'doctors', // Change this to change the doctors slug
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
	$labels = array(
	  'name'               => 'Locations',
	  'singular_name'      => 'Locations',
	  'menu_name'          => 'Locations',
	  'parent_item_colon'  => 'Parent Location',
	  'all_items'          => 'All Locations',
	  'view_item'          => 'View Location',
	  'add_new_item'       => 'Add New Location',
	  'add_new'            => 'Add New Location',
	  'edit_item'          => 'Edit Location',
	  'update_item'        => 'Update Location',
	  'search_items'       => 'Search Locations',
	  'not_found'          => 'Not Found',
	  'not_found_in_trash' => 'Not found in Trash',
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
		'category',
		'page-attributes'
	  ),
	  'menu_icon'           => 'dashicons-location',
	  'hierarchical'        => true,
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
		'slug'       => 'locations', // Change this to change the locations slug
		'with_front' => false
	  )
	);

	register_post_type( 'locations', $args );
}

add_action( 'init', 'location_post_type_register', 0 );


