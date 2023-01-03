<?php
/**
 * the meat of our plugin
 *
 * @package Glacial_Cpt_Acf
 *
 * */

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
	wp_register_style( 'glacial-cpt', GLACIAL_CPT_PLUGIN_URL . 'public/css/glacial-cpt.css', false, CPT_STYLE_VERSION, 'all' );
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
	$cpts      = array( 'doctors', 'locations' );
	$post_type = get_post_type();

	if ( is_singular( $cpts ) ) {
		$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/single-{$post_type}.php";
	}

	if ( is_post_type_archive( $cpts ) ) {
		$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/archive-{$post_type}.php";
	}

	if ($template_path) {
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
function glacial_cpt_get_template_part( $name ) {

	$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/{$name}.php";

	if ( locate_template( $template_path ) ) {
		$template = get_stylesheet_directory() . '/' . $template_path;
	} else {
		$template = GLACIAL_CPT_PLUGIN_DIR . '/' . $template_path;
	}

	include $template;
}


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
 * Check if Doctors or Locations slug changed and flush rewrite rules if it has.
 *
 * @see glacial_cpt_flush_rewrite_rules()
 *
 * @since 1.1.0
 * */
function glacial_cpt_check_slug_change() {

	$doctors_slug_acf   = 'field_631d3d7d03660';
	$locations_slug_acf = 'field_631d417092564';

	// Sanitize the slug and flush rewrite rules if it has changed
	if ( isset( $_POST['acf'][ $doctors_slug_acf ] ) ) {
		$_POST['acf'][ $doctors_slug_acf ] = sanitize_title( $_POST['acf'][ $doctors_slug_acf ] );

		/**
		 * Add our flush rewrite flag
		 * */
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}

	if ( isset( $_POST['acf'][ $locations_slug_acf ] ) ) {
		$_POST['acf'][ $locations_slug_acf ] = sanitize_title( $_POST['acf'][ $locations_slug_acf ] );

		/**
		 * Add our flush rewrite flag
		 * */
		add_option( 'glacial_flush_rewrite_rules_flag', true );
	}

}

add_action( 'acf/save_post', 'glacial_cpt_check_slug_change', 5 );
