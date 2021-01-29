<?php
/**
 * Plugin Name:     Glacial Custom Post Types with ACF
 * Plugin URI:
 * Description:     Contains Custom Post Types for Doctors and Locations with ACF.
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Text Domain:     glacial-cpt-acf
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Glacial_Cpt_Acf
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


register_activation_hook( __FILE__, 'glacial_plugin_activate' );
/**
 * Add a flag that will allow to flush the rewrite rules when needed.
 */
function glacial_plugin_activate() {
	if ( ! get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}
}


function doctor_post_type_register() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'               => _x( 'Doctors', 'Post Type General Name', 'glacial' ),
		'singular_name'      => _x( 'Doctor', 'Post Type Singular Name', 'glacial' ),
		'menu_name'          => __( 'Doctors', 'glacial' ),
		'parent_item_colon'  => __( 'Parent Doctor', 'glacial' ),
		'all_items'          => __( 'All Doctors', 'glacial' ),
		'view_item'          => __( 'View Doctor', 'glacial' ),
		'add_new_item'       => __( 'Add New Doctor', 'glacial' ),
		'add_new'            => __( 'Add New', 'glacial' ),
		'edit_item'          => __( 'Edit Doctor', 'glacial' ),
		'update_item'        => __( 'Update Doctor', 'glacial' ),
		'search_items'       => __( 'Search Doctor', 'glacial' ),
		'not_found'          => __( 'Not Found', 'glacial' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'glacial' ),
	);

// Set other options for Custom Post Type

	$args = array(
		'label'       => __( 'doctors', 'glacial' ),
		'description' => __( 'Doctor Bios and Specialties', 'glacial' ),
		'labels'      => $labels,
		// Features this CPT supports in Post Editor
		'supports'    => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'category', 'page-attributes' ),
		// You can associate this CPT with a taxonomy or custom taxonomy.
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
		'name'               => _x( 'Locations', 'Post Type General Name', 'glacial' ),
		'singular_name'      => _x( 'Locations', 'Post Type Singular Name', 'glacial' ),
		'menu_name'          => __( 'Locations', 'glacial' ),
		'parent_item_colon'  => __( 'Parent Location', 'glacial' ),
		'all_items'          => __( 'All Locations', 'glacial' ),
		'view_item'          => __( 'View Location', 'glacial' ),
		'add_new_item'       => __( 'Add New Location', 'glacial' ),
		'add_new'            => __( 'Add New', 'glacial' ),
		'edit_item'          => __( 'Edit Location', 'glacial' ),
		'update_item'        => __( 'Update Location', 'glacial' ),
		'search_items'       => __( 'Search Locations', 'glacial' ),
		'not_found'          => __( 'Not Found', 'glacial' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'glacial' ),
	);

// Set other options for Custom Post Type

	$args = array(
		'label'       => __( 'locations', 'glacial' ),
		'description' => __( 'Locations', 'glacial' ),
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


add_action( 'init', 'glacial_flush_rewrite_rules_test', 20 );
/**
 * Flush rewrite rules if the previously added flag exists,
 * and then remove the flag.
 */
function glacial_flush_rewrite_rules_test() {
	if ( get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		flush_rewrite_rules();
		delete_option( 'glacial_flush_rewrite_rules_flag' );
	}
}

add_filter( 'template_include', 'glacial_cpt_plugin_templates' );
function glacial_cpt_plugin_templates( $template ): string {

	$post_types = array('doctors', 'locations');

	if ( is_post_type_archive( $post_types ) || is_singular($post_types)) {
		$template = plugin_dir_path( __FILE__ ) . 'templates/doctor-location-wrapper.php';
	}

	return $template;
}

// Save Acf
add_filter( 'acf/settings/save_json', 'glacial_cpt_json_save_point' );
function glacial_cpt_json_save_point( $acf_json_path ): string {
	// update path
	$acf_json_path = plugin_dir_path( __FILE__ ) . '/acf-json';

	// return
	return $acf_json_path;
}

// Load Acf
add_filter( 'acf/settings/load_json', 'glacial_cpt_json_load_point' );
function glacial_cpt_json_load_point( $acf_json_path ) {
	// remove original path (optional)
	unset( $acf_json_path[0] );
	// append path
	$acf_json_path[] = plugin_dir_path( __FILE__ ) . '/acf-json';

	// return
	return $acf_json_path;
}


function glacial_cpt_register_styles() {

	wp_register_style( 'glacial-cpt',plugin_dir_url(__FILE__) . 'assets/css/glacial-cpt.css', false, time(), 'all' );
	wp_enqueue_style( 'glacial-cpt' );

	if ( is_post_type_archive( 'doctors' ) ) {
		wp_register_style( 'mixitup',plugin_dir_url(__FILE__) . 'assets/css/doc-mix-it-up.css', false, time(), 'all' );
		wp_enqueue_style( 'mixitup' );
	}
}

function glacial_cpt_register_scripts() {
	if ( is_post_type_archive( 'doctors' ) ) {

		wp_register_script( 'mixup', plugin_dir_url(__FILE__) . 'assets/js/doc-mix-it-up.js', array( 'jquery' ), null, false );
		wp_enqueue_script( 'mixup' );

		wp_register_script( 'mixitup', 'https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'mixitup' );

	}
}

add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_styles' );
add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_scripts' );


