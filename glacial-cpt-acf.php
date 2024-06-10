<?php
/**
 * Plugin Name:     Glacial Custom Post Types with ACF
 * Description:     Contains Custom Post Types for Doctors and Locations with ACF.
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Text Domain:     glacial-cpt-acf
 * Domain Path:     /languages
 * Version:         2.0.3
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

define( 'GLACIAL_CPT_VERSION', '2.0.3' );
define( 'GLACIAL_CPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GLACIAL_CPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GLACIAL_CPT_TEMPLATES_FOLDER_NAME', 'cpt-acf-templates' );
// get theme version
$theme         = wp_get_theme();
$theme_type_ok = $theme->get( 'Author' ) == 'Glacial Multimedia';
$theme_ver_ok  = version_compare( $theme->get( 'Version' ), '3.0.0', '>=' );

if ( get_template_directory() !== get_stylesheet_directory() ) {
	$theme_ver_ok = version_compare( $theme->parent()->get( 'Version' ), '3.0.0', '>=' );
}
$has_acf = function_exists( 'the_field' );

/*
 * If "Discourage search engines from indexing this site" is checked in Settings > Reading
 * then add time() to static resources to prevent browser caching
 * */
if ( get_option( 'blog_public' ) ) {
	define( 'CPT_STYLE_VERSION', GLACIAL_CPT_VERSION );
} else {
	define( 'CPT_STYLE_VERSION', time() );
}

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
function glacial_cpt_plugin_deactivate() {
}

register_deactivation_hook( __FILE__, 'glacial_cpt_plugin_deactivate' );

/**
 * if ACF is not installed or Glacial Theme not >= v3.0.0, show notice and don't load anything else
 *
 * @since 1.0.0
 * */
if ( $has_acf && $theme_ver_ok && $theme_type_ok ) {
	/**
	 * Grab the main functions of our plugin
	 *
	 * @since 1.0.0
	 * */
	require_once GLACIAL_CPT_PLUGIN_DIR . 'includes/glacial-cpt-acf-main.php';

} else {
	add_action( 'admin_notices', function () use ( $has_acf, $theme_ver_ok, $theme_type_ok ) { ?>
        <div class="notice notice-error">
            <h2>Glacial CPT Plugin</h2>
			<?php if ( !$has_acf ) {
				echo '<h3>ACF Not Activated</h3>';
				echo '<p>Please install and activate Advanced Custom Fields Pro, it is required by <b>Glacial Custom Post Types with ACF</b> plugin to work.</p>';
			}
			if ( !$theme_type_ok ) {
				echo '<h3>Glacial Theme Not Activated</h3>';
				echo '<p>Please install Glacial Theme, it is required by <b>Glacial Custom Post Types with ACF</b> plugin to work.</p>';
			}
			if ( !$theme_ver_ok ) {
				echo '<h3>Incorrect Version of Glacial Theme</h3>';
				echo '<p>Please update to Glacial Theme v3.0.0+, it is required by <b>Glacial Custom Post Types with ACF</b> plugin to work.</p>';
			} ?>
        </div>
		<?php
	} );
}
