<?php
/**
 * the meat of our plugin
 *
 * @package Glacial_Cpt_Acf
 *
 * */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Flush the rewrite rules. Uses option set above to prevent flush on every activation.
 *
 * @since 1.0.0
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
 *
 * @since 1.0.0
 * */
function glacial_cpt_json_save_point( $acf_json_path ) {
	$acf_json_path = GLACIAL_CPT_PLUGIN_DIR . '/cpt-acf-json';

	return $acf_json_path;
}

add_filter( 'acf/settings/save_json', 'glacial_cpt_json_save_point' );

/**
 * ACF JSON load point
 *
 * @since 1.0.0
 * */
function glacial_cpt_json_load_point( $acf_json_path ) {
	$acf_json_path[] = GLACIAL_CPT_PLUGIN_DIR . '/cpt-acf-json';

	return $acf_json_path;
}

add_filter( 'acf/settings/load_json', 'glacial_cpt_json_load_point' );

/**
 * Enqueue styles
 *
 * @since 1.0.0
 * */
function glacial_cpt_register_styles() {
	wp_register_style( 'glacial-cpt', GLACIAL_CPT_PLUGIN_URL . 'public/css/glacial-cpt.css', array(), CPT_STYLE_VERSION, 'all' );
	wp_enqueue_style( 'glacial-cpt' );
}

add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_styles' );

/**
 * Enqueue scripts
 *
 * @since 1.0.0
 * */
function glacial_cpt_register_scripts() {

	$use_doctor_services_filter  = get_field( 'use_doctor_services_filter', 'options' ) ?? true;
	$use_doctor_locations_filter = get_field( 'use_doctor_locations_filter', 'options' ) ?? true;


	if ( is_post_type_archive( 'doctors' ) ) {

		/*
		 * Only enqueue the scripts if the archive page is using the filter
		 * */
		if ( $use_doctor_services_filter || $use_doctor_locations_filter ) {

			wp_register_script( 'mixup', GLACIAL_CPT_PLUGIN_URL . 'public/js/doc-mix-it-up.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'mixup' );

			wp_register_script( 'mixitup', 'https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'mixitup' );
		}

	}
}

add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_scripts' );

/**
 * Template loader.
 *
 * @since 1.1.0
 *
 * */
function glacial_cpt_template_include( $template ) {
	$template_path = '';
	$cpts          = array( 'doctors', 'locations' );
	$post_type     = get_post_type();

	if ( is_singular( $cpts ) ) {
		$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/single-{$post_type}.php";
	}

	if ( is_post_type_archive( $cpts ) ) {
		$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/archive-{$post_type}.php";
	}

	if ( $template_path ) {
		if ( locate_template( $template_path ) ) {
			$template = get_stylesheet_directory() . '/' . $template_path;
		} else {
			$template = GLACIAL_CPT_PLUGIN_DIR . $template_path;
		}
	}

	return $template;
}

add_filter( 'template_include', 'glacial_cpt_template_include', 99 );

/**
 * Ability to override template files in the theme
 *
 * @since 1.1.0
 *
 * */
function glacial_cpt_get_template_part( $name, $args = array() ) {

	$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/{$name}.php";

	if ( locate_template( $template_path ) ) {
		$template = get_stylesheet_directory() . '/' . $template_path;
	} else {
		$template = GLACIAL_CPT_PLUGIN_DIR . '/' . $template_path;
	}

	load_template( $template, false, $args );
}

/**
 * The titles on the archive pages using ACF
 *
 * @since 1.1.0
 * */
function glacial_cpt_archive_titles( $title, $original_title ) {

	if ( is_post_type_archive( 'doctors' ) ) {
		return $original_title;
	}

	if ( is_post_type_archive( 'locations' ) ) {
		return $original_title;
	}

	return $title;

}

add_filter( 'get_the_archive_title', 'glacial_cpt_archive_titles', 10, 2 );

/**
 * Alter the query for the locations and doctors archive page
 *
 * @since 1.0.0
 * */
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

/**
 * Add ACF options page
 *
 * @since 1.1.0
 * */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page( array(
		'page_title'  => 'Doctors and Locations Options',
		'menu_title'  => 'Doctors and Locations Options',
		'menu_slug'   => 'doctors-locations-options',
		'parent_slug' => 'options-general.php',
		'capability'  => 'edit_posts',
		'position'    => 10.2,
		'autoload'    => true,
	) );

}

/**
 * Add a choice to the page type ACF location rule
 *
 * @since 2.0.0
 * */
function glacial_acf_location_rule_page_type( $choices ) {
	$choices['service-page'] = 'Glacial Service Page';

	return $choices;
}

add_filter( 'acf/location/rule_values/page_type', 'glacial_acf_location_rule_page_type' );

/**
 * Match the new page type choice to the page type ACF location rule
 *
 * @since 2.0.0
 * */
function glacial_acf_location_rule_match_page_type( $match ) {

	$post_meta = get_post_meta( get_the_ID(), 'glacial_page_type', true );

	if ( $post_meta == 'service-page' ) {
		$match = true;
	}

	return $match;

}

add_filter( 'acf/location/rule_match/page_type', 'glacial_acf_location_rule_match_page_type', 10, 1 );

/**
 * Theme Hook: glacial_theme_before_footer
 *
 * Hook is in Glacial Theme footer.php
 *
 * @since 1.0.0
 * */
function glacial_cpt_theme_before_footer() {

	$add_doctors_to_service_pages           = get_field( 'add_doctors_to_service_pages', 'option' ) ?? true;
	$add_all_doctors_to_single_doctor_pages = get_field( 'add_all_doctors_to_single_doctor_pages', 'option' ) ?? true;

	if ( $add_doctors_to_service_pages ) {
		glacial_cpt_get_template_part( 'doctors-service-pages' );
	}

	if ( is_singular( 'doctors' ) && $add_all_doctors_to_single_doctor_pages ) {
		glacial_cpt_get_template_part( 'all-doctors' );
	}
}

add_action( 'glacial_theme_before_footer', 'glacial_cpt_theme_before_footer' );

/**
 * Add content to glacial_theme_after_content hook
 *
 * @since 2.0.0
 * */

function glacial_cpt_theme_after_content() {
	if ( is_singular( 'post' ) ) {
		glacial_cpt_get_template_part( 'doctor-author-card' );
	}

}

add_action( 'glacial_theme_after_content', 'glacial_cpt_theme_after_content' );

/**
 * Fix for ACF field widths in Doctors Gutenberg editor
 *
 * @since 2.0.0
 * */
function glacial_cpt_admin_print_styles() {
	echo '<style>
				.edit-post-layout__metaboxes #acf-group_5dc1ba22dfffc .acf-fields {
    			display: flex;
    			flex-wrap: wrap;
			}
			</style>';

}

add_action( 'admin_print_styles', 'glacial_cpt_admin_print_styles' );


