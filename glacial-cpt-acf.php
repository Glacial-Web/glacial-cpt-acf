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

function glacial_cpt_acf_notice() { ?>
    <div class="notice notice-error">
        <p>Please install and activate Advanced Custom Fields Pro, it is required <b>Glacial Custom Post Types with
                ACF</b> plugin to work.</p>
    </div>
<?php }

if ( ! function_exists( 'the_field' ) ) {
	add_action( 'admin_notices', 'glacial_cpt_acf_notice' );

} else {

	include ( plugin_dir_path( __FILE__ ) ) . 'includes/post-types.php';

	function glacial_cpt_plugin_activate() {
		// Add a flag that will allow to flush the rewrite rules when needed.
		if ( ! get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
			add_option( 'glacial_flush_rewrite_rules_flag', true );
		}

	}

	register_activation_hook( __FILE__, 'glacial_cpt_plugin_activate' );

	function glacial_cpt_plugin_deactivate() {
// Do some stuff when deactivating the plugin
	}

	register_deactivation_hook( __FILE__, 'glacial_cpt_plugin_deactivate' );


// Rewrite permalinks with our new CPTs
	function glacial_cpt_lush_rewrite_rules() {
		if ( get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'glacial_flush_rewrite_rules_flag' );
		}
	}

	add_action( 'init', 'glacial_cpt_lush_rewrite_rules', 20 );

// Change hierarchy to use template in this plugin
	function glacial_cpt_plugin_templates( $template ): string {
		$post_types = array( 'doctors', 'locations' );
		if ( is_post_type_archive( $post_types ) || is_singular( $post_types ) ) {
			$template = plugin_dir_path( __FILE__ ) . 'public/templates/doctor-location-wrapper.php';
		}

		return $template;
	}

	//add_filter( 'template_include', 'glacial_cpt_plugin_templates' );

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

	/*
	 * This will populate our ACF field with service pages from our theme
	 * */
	function service_pages_relationship_field( $args, $field, $post_id ) {

		$args['meta_query'] = array(
		  array(
			'key'   => 'glacial_page_type',
			'value' => 'service-page',
		  )
		);

		return $args;
	}

	add_filter( 'acf/fields/relationship/query/name=specialties', 'service_pages_relationship_field', 10, 3 );
}


function glacial_cpt_templates() {
	include ( plugin_dir_path( __FILE__ ) ) . 'public/templates/doctor-location-wrapper.php';
}

add_action( 'glacial_theme_template_parts', 'glacial_cpt_templates' );

function glacial_cpt_theme_after_content() {
		include ( plugin_dir_path( __FILE__ ) ) . 'public/partials/doctors-service-pages.php';
}

add_action( 'glacial_theme_before_footer', 'glacial_cpt_theme_after_content' );

