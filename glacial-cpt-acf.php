<?php
/**
 * Plugin Name:     Glacial Custom Post Types with ACF
 * Description:     Contains Custom Post Types for Doctors and Locations with ACF.
 * Author:          Glacial Multimedia
 * Author URI:      https://glacial.com
 * Text Domain:     glacial-cpt-acf
 * Version:         2.1.0
 *
 * GitHub Plugin URI: https://github.com/Glacial-Web/glacial-cpt-acf
 *
 * @package         Glacial_Cpt_Acf
 **/

if ( !defined( 'ABSPATH' ) ) {
	die;
}

// --- Constants ---
define( 'GLACIAL_CPT_VERSION', '2.0.3' );
define( 'GLACIAL_CPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GLACIAL_CPT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GLACIAL_CPT_TEMPLATES_FOLDER_NAME', 'cpt-acf-templates' );

// --- Versioning ---
define( 'CPT_STYLE_VERSION', get_option( 'blog_public' ) ? GLACIAL_CPT_VERSION : time() );

// --- Theme/ACF Checks ---
/**
 * Checks if the active theme is Glacial and version >= 3.0.0.
 *
 * @return bool
 */
function glacial_theme_is_valid(): bool {
	$theme      = wp_get_theme();
	$is_glacial = $theme->get( 'Author' ) === 'Glacial Multimedia';
	$version_ok = version_compare( $theme->get( 'Version' ), '3.0.0', '>=' );

	if ( get_template_directory() !== get_stylesheet_directory() ) {
		$version_ok = version_compare( $theme->parent()->get( 'Version' ), '3.0.0', '>=' );
	}

	return $is_glacial && $version_ok;
}

/**
 * Checks if ACF is active.
 *
 * @return bool
 */
function glacial_acf_is_active(): bool {
	return function_exists( 'the_field' );
}

// --- Activation/Deactivation ---
/**
 * Plugin activation hook.
 */
function glacial_cpt_plugin_activate(): void {
	if ( !get_option( 'glacial_flush_rewrite_rules_flag' ) ) {
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}
}

register_activation_hook( __FILE__, 'glacial_cpt_plugin_activate' );

function glacial_cpt_plugin_deactivate(): void {
}

register_deactivation_hook( __FILE__, 'glacial_cpt_plugin_deactivate' );

// --- Admin Notices ---
/**
 * Displays admin notices for missing requirements.
 */
function glacial_cpt_admin_notices(): void {
	$acf         = glacial_acf_is_active();
	$theme_valid = glacial_theme_is_valid();
	$theme       = wp_get_theme();
	$is_glacial  = $theme->get( 'Author' ) === 'Glacial Multimedia';

	?>
    <div class="notice notice-error">
        <h2>Glacial CPT Plugin</h2>
		<?php if ( !$acf ) : ?>
            <h3>ACF Not Activated</h3>
            <p>Please install and activate Advanced Custom Fields Pro, required by <b>Glacial Custom Post Types with
                    ACF</b>.</p>
		<?php endif; ?>
		<?php if ( !$is_glacial ) : ?>
            <h3>Glacial Theme Not Activated</h3>
            <p>Please install Glacial Theme, required by <b>Glacial Custom Post Types with ACF</b>.</p>
		<?php endif; ?>
		<?php if ( !$theme_valid ) : ?>
            <h3>Incorrect Version of Glacial Theme</h3>
            <p>Please update to Glacial Theme v3.0.0+, required by <b>Glacial Custom Post Types with ACF</b>.</p>
		<?php endif; ?>
    </div>
	<?php
}

// --- Main Loader ---
if ( glacial_acf_is_active() && glacial_theme_is_valid() ) {
	require GLACIAL_CPT_PLUGIN_DIR . 'includes/glacial-cpt-acf-main.php';
	require GLACIAL_CPT_PLUGIN_DIR . 'shortcodes/locations.php';
	require GLACIAL_CPT_PLUGIN_DIR . 'includes/glacial-cpt-acf-phone-modal.php';
} else {
	add_action( 'admin_notices', 'glacial_cpt_admin_notices' );
}