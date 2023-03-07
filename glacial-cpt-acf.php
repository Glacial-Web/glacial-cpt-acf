<?php
/**
 * Plugin Name:     Glacial Custom Post Types with ACF
 * Description:     Contains Custom Post Types for Doctors and Locations with ACF.
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Text Domain:     glacial-cpt-acf
 * Domain Path:     /languages
 * Version:         2.0.0
 *
 * GitHub Plugin URI: https://github.com/Glacial-Web/glacial-cpt-acf
 *
 * @package         Glacial_Cpt_Acf
 **/

/*
 * If this file is called directly, DIE!
 * */
if ( !defined( 'ABSPATH' ) ) {
	die;
}

define( 'GLACIAL_CPT_VERSION', '2.0.0' );
define( 'GLACIAL_CPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GLACIAL_CPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GLACIAL_CPT_TEMPLATES_FOLDER_NAME', 'cpt-acf-templates' );


/*
 * If "Discourage search engines from indexing this site" is checked in Settings > Reading
 * then add time() to static resources to prevent caching
 * */
if ( get_option( 'blog_public' ) ) {
	define( 'CPT_STYLE_VERSION', GLACIAL_CPT_VERSION );
} else {
	define( 'CPT_STYLE_VERSION', time() );
}

/**
 * Admin notice if ACF is not installed
 *
 * @since 1.0.0
 * */
function glacial_cpt_acf_notice() { ?>
    <div class="notice notice-error">
        <h2>Glacial CPT Plugin</h2>
        <p>Please install and activate Advanced Custom Fields Pro, it is required by <b>Glacial Custom Post Types with
                ACF</b> plugin to work.</p>
    </div>
<?php }

/**
 * Plugin activation hook
 *
 * Add a flag that will allow to flush the rewrite rules when needed.
 *
 * @since 1.0.0
 * */
function glacial_cpt_plugin_activate() {
	//
	if ( !get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}
}

register_activation_hook( __FILE__, 'glacial_cpt_plugin_activate' );

/**
 * Plugin deactivate hook
 *
 * Currently not in use
 *
 * @since 1.0.0
 * */
function glacial_cpt_plugin_deactivate() {}

register_deactivation_hook( __FILE__, 'glacial_cpt_plugin_deactivate' );

/**
 * if ACF is not installed, show notice and don't load anything else
 *
 * @since 1.0.0
 * */
if ( !function_exists( 'the_field' ) ) {
	add_action( 'admin_notices', 'glacial_cpt_acf_notice' );

} else {

	/**
	 * Register Custom Post Types: Doctors and Locations
	 *
	 * @since 1.0.0
	 * */
	require_once ( plugin_dir_path( __FILE__ ) ) . 'includes/post-types.php';

	/**
	 * Grab the main functions of our plugin
	 *
	 * @since 1.0.0
	 * */

	require_once ( plugin_dir_path( __FILE__ ) ) . 'includes/glacial-cpt-acf-main.php';

}
