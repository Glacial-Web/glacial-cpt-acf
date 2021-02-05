<?php
/**
 * @package Glacial_Cpt_Acf
 */


function doctor_post_type_register() {
// Set UI labels for Custom Post Type
	$labels = array(
		'name'               => 'Doctors',
		'singular_name'      => 'Doctor',
		'menu_name'          => 'Doctors',
		'parent_item_colon'  => 'Parent Doctor',
		'all_items'          => 'All Doctors',
		'view_item'          => 'View Doctor',
		'add_new_item'       => 'Add New Doctor',
		'add_new'            => 'Add New',
		'edit_item'          => 'Edit Doctor',
		'update_item'        => 'Update Doctor',
		'search_items'       => 'Search Doctor'
	);

// Set other options for Custom Post Type

	$args = array(
		'label'       => 'doctors',
		'description' => 'Doctor Bios and Specialties',
		'labels'      => $labels,
		// Features this CPT supports in Post Editor
		'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'category', 'page-attributes' ),
		'taxonomies'  => array( 'post_tag', 'category' ),
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


	);

	// Register Custom Post Type
	register_post_type( 'doctors', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/
add_action( 'init', 'doctor_post_type_register', 0 );

//Create Location custom post types
function location_post_type_register() {

// Set UI labels for Custom Post Type
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

// Set other options for Custom Post Type

	$args = array(
		'label'       => 'locations',
		'description' => 'Locations',
		'labels'      => $labels,
		// Features this CPT supports in Post Editor
		'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'category', 'page-attributes' ),
		'menu_icon' => 'dashicons-location',
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
	);

	// Register Custom Post Type
	register_post_type( 'locations', $args );
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/
add_action( 'init', 'location_post_type_register', 0 );


