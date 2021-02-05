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

// If this file is called directly, DIE!
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function glacial_plugin_activate() {
	// Add a flag that will allow to flush the rewrite rules when needed.
	if ( ! get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}

	// Create service tag if it doesn't exists
	if ( ! term_exists('service') ) {
		wp_insert_term( 'Service', 'post_tag' );
	}
}
register_activation_hook( __FILE__, 'glacial_plugin_activate' );

function glacial_plugin_deactivate() {
// Do some stuff when deactivating the plugin
}
register_deactivation_hook( __FILE__, 'glacial_plugin_deactivate' );

include (plugin_dir_path(__FILE__)) . 'includes/post-types.php';

// Rewrite permalinks with our new CPTs
function glacial_flush_rewrite_rules() {
	if ( get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		flush_rewrite_rules();
		delete_option( 'glacial_flush_rewrite_rules_flag' );
	}
}
add_action( 'init', 'glacial_flush_rewrite_rules', 20 );

// Tag support for pages
function tags_support_all() {
	register_taxonomy_for_object_type( 'post_tag', 'page' );
}
add_action( 'init', 'tags_support_all' );

// Ensure all tags are included in queries
function tags_support_query( $wp_query ) {
	if ( $wp_query->get( 'tag' ) ) {
		$wp_query->set( 'post_type', 'any' );
	}
}
add_action( 'pre_get_posts', 'tags_support_query' );

// Change hierarchy to use template in this plugin
function glacial_cpt_plugin_templates( $template ): string {
	$post_types = array( 'doctors', 'locations' );

	if ( is_post_type_archive( $post_types ) || is_singular( $post_types ) ) {
		$template = plugin_dir_path( __FILE__ ) . 'public/templates/doctor-location-wrapper.php';
	}
	return $template;
}
add_filter( 'template_include', 'glacial_cpt_plugin_templates' );

// Save ACF in new location
function glacial_cpt_json_save_point( $acf_json_path ): string {
	// update path
	$acf_json_path = plugin_dir_path( __FILE__ ) . '/acf-json';

	// return
	return $acf_json_path;
}
add_filter( 'acf/settings/save_json', 'glacial_cpt_json_save_point' );

// Load ACF
function glacial_cpt_json_load_point( $acf_json_path ) {
	// remove original path (optional)
	unset( $acf_json_path[0] );
	// append path
	$acf_json_path[] = plugin_dir_path( __FILE__ ) . '/acf-json';
	// return
	return $acf_json_path;
}
add_filter( 'acf/settings/load_json', 'glacial_cpt_json_load_point' );

// Our styles
function glacial_cpt_register_styles() {
	wp_register_style( 'glacial-cpt', plugin_dir_url( __FILE__ ) . 'public/css/glacial-cpt.css', false, null, 'all' );
	wp_enqueue_style( 'glacial-cpt' );

	if ( is_post_type_archive( 'doctors' ) ) {
		wp_register_style( 'mixitup', plugin_dir_url( __FILE__ ) . 'public/css/doc-mix-it-up.css', false, null, 'all' );
		wp_enqueue_style( 'mixitup' );
	}
}
add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_styles' );

// Our JS
function glacial_cpt_register_scripts() {
	// Register only if post type archive is Doctors
	if ( is_post_type_archive( 'doctors' ) ) {

		wp_register_script( 'mixup', plugin_dir_url( __FILE__ ) . 'public/js/doc-mix-it-up.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'mixup' );

		wp_register_script( 'mixitup', 'https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'mixitup' );

	}
}
add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_scripts' );



