<?php
/**
 * Plugin Name:     Glacial Custom Post Types with ACF
 * Description:     Contains Custom Post Types for Doctors and Locations with ACF.
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Text Domain:     glacial-cpt-acf
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Glacial_Cpt_Acf
 */

/*
 * If this file is called directly, DIE!
 * */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
 * Admin notice if ACF is not installed
 * */
function glacial_cpt_acf_notice() { ?>
    <div class="notice notice-error">
        <h2>Glacial CPT Plugin</h2>
        <p>Please install and activate Advanced Custom Fields Pro, it is required by <b>Glacial Custom Post Types with
                ACF</b> plugin to work.</p>
    </div>
<?php }

/*
 * if ACF is not installed, show notice and don't load anything else
 * */
if ( ! function_exists( 'the_field' ) ) {
	if ( ! function_exists( 'the_field' ) ) {
		add_action( 'admin_notices', 'glacial_cpt_acf_notice' );
	}

} else {

	/**
	 * Register Custom Post Types: Doctors and Locations
	 * */
	include ( plugin_dir_path( __FILE__ ) ) . 'includes/post-types.php';

	/**
	 * Plugin activation hook
	 * */
	function glacial_cpt_plugin_activate() {
		// Add a flag that will allow to flush the rewrite rules when needed.
		if ( ! get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
			add_option( 'glacial_flush_rewrite_rules_flag', true );
		}
	}

	register_activation_hook( __FILE__, 'glacial_cpt_plugin_activate' );

	/**
	 * Plugin deactivate hook
	 *
	 * Currently not in use
	 *
	 * */
	function glacial_cpt_plugin_deactivate() {
	}

	register_deactivation_hook( __FILE__, 'glacial_cpt_plugin_deactivate' );

	/**
	 * Flush the rewrite rules. Uses option set above to prevent flush on every activation.
	 * */
	function glacial_cpt_flush_rewrite_rules() {
		if ( get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'glacial_flush_rewrite_rules_flag' );
		}
	}

	add_action( 'init', 'glacial_cpt_flush_rewrite_rules', 20 );

	/**
	 * ACF JSON save point
	 * */
	function glacial_cpt_json_save_point( $acf_json_path ): string {
		$acf_json_path = plugin_dir_path( __FILE__ ) . '/acf-json';

		return $acf_json_path;
	}

	add_filter( 'acf/settings/save_json', 'glacial_cpt_json_save_point' );

	/**
	 * ACF JSON load point
	 * */
	function glacial_cpt_json_load_point( $acf_json_path ) {
		$acf_json_path[] = plugin_dir_path( __FILE__ ) . '/acf-json';

		return $acf_json_path;
	}

	add_filter( 'acf/settings/load_json', 'glacial_cpt_json_load_point' );

	/**
	 * Enqueue styles
	 * */
	function glacial_cpt_register_styles() {
		wp_register_style( 'glacial-cpt', plugin_dir_url( __FILE__ ) . 'public/css/glacial-cpt.css', false, time(), 'all' );
		wp_enqueue_style( 'glacial-cpt' );
	}

	add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_styles' );

	/**
	 * Enqueue scripts
	 * */
	function glacial_cpt_register_scripts() {
		if ( is_post_type_archive( 'doctors' ) ) {

			wp_register_script( 'mixup', plugin_dir_url( __FILE__ ) . 'public/js/doc-mix-it-up.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'mixup' );

			wp_register_script( 'mixitup', 'https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'mixitup' );

		}
	}

	add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_scripts' );

	/**
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
	add_filter( 'acf/fields/relationship/query/name=location_specialties', 'service_pages_relationship_field', 10, 3 );

	/**
	 * Theme Hook: glacial_theme_template_parts
	 *
	 * Hook is in Glacial Theme index.php
	 * */
	function glacial_cpt_templates() {
		if ( in_array( get_post_type(), array( 'locations', 'doctors' ) ) ) {
			include ( plugin_dir_path( __FILE__ ) ) . 'public/templates/doctor-location-wrapper.php';
		}
	}

	add_action( 'glacial_theme_template_parts', 'glacial_cpt_templates' );

	/**
	 * Theme Hook: glacial_theme_before_footer
	 *
	 * Hook is in Glacial Theme footer.php
	 * */
	function glacial_cpt_theme_before_footer() {

		include ( plugin_dir_path( __FILE__ ) ) . 'public/partials/doctors-service-pages.php';

		if ( is_singular( 'doctors' ) ) {
			include ( plugin_dir_path( __FILE__ ) ) . 'public/partials/all-doctors.php';
		}
	}

	add_action( 'glacial_theme_before_footer', 'glacial_cpt_theme_before_footer' );

	function glacial_cpt_archive_titles() {
		if ( is_post_type_archive( 'doctors' ) ) {
			$title = 'Doctors';
		} elseif ( is_post_type_archive( 'locations' ) ) {
			$title = 'Locations';
		} else {
			$title = '';
		}

		return $title;
	}

	add_filter( 'get_the_archive_title', 'glacial_cpt_archive_titles' );

	/**
	 * Alter the query for the locations and doctors archive page
	 */
	function glacial_cpt_change_queries( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( is_post_type_archive( array( 'doctors', 'locations' ) ) ) {
				$query->set( 'posts_per_page', - 1 );
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
			}
		}

		return $query;
	}

	add_action( 'pre_get_posts', 'glacial_cpt_change_queries' );

}

