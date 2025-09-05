<?php
/**
 * the meat of our plugin
 *
 * @package Glacial_Cpt_Acf
 *
 * */

if ( !defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Check to see if phone modal is enabled
 *
 * @since 2.1.0
 * */
function glacial_cpt_is_phone_modal_enabled(): bool {
	return get_field( 'add_phone_number_modal', 'options' ) ?? false;
}

/**
 * Check if we have a Google Maps API key set in the ACF options page
 *
 * @since 2.1.0
 * */
function glacial_cpt_get_maps_api_key(): bool|string {
	return get_field( 'google_maps_api', 'options' ) ?? false;
}

/**
 * Set the Google Maps API key
 *
 * @since 2.1.0
 * */
function glacial_cpt_set_maps_api_key(): void {
	$maps_api_key = glacial_cpt_get_maps_api_key();
	if ( $maps_api_key ) {
		define( 'GOOGLE_MAPS_EMBED_API_KEY', $maps_api_key );
	} else {
		define( 'GOOGLE_MAPS_EMBED_API_KEY', '' );
	}
}

add_action( 'init', 'glacial_cpt_set_maps_api_key' );

/**
 * Flush the rewrite rules. Uses option set above to prevent flush on every activation.
 *
 * @since 1.0.0
 * */
function glacial_cpt_flush_rewrite_rules(): void {
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
function glacial_cpt_json_save_point( $acf_json_path ): string {
	return GLACIAL_CPT_PLUGIN_DIR . '/cpt-acf-json';
}

// Only save ACF JSON in local and development environments
if ( WP_ENVIRONMENT_TYPE === 'local' || WP_ENVIRONMENT_TYPE === 'development' ) {
	add_filter( 'acf/settings/save_json', 'glacial_cpt_json_save_point' );
}

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
function glacial_cpt_register_styles(): void {
	wp_register_style( 'glacial-cpt', GLACIAL_CPT_PLUGIN_URL . 'public/css/glacial-cpt.css', array(), CPT_STYLE_VERSION, 'all' );
	wp_enqueue_style( 'glacial-cpt' );

	/*
	 * If the search modal in the header is not enabled in the theme options we don't need to load
	 * the micromodal script because it will already be loaded by the theme.
	 * */
	if ( !glacial_theme_options( 'enable_search_modal' ) && glacial_cpt_is_phone_modal_enabled() ) {
		wp_register_script( 'micromodal', 'https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js', array(), null, [
			'strategy'  => 'defer',
			'in_footer' => true
		] );
		wp_enqueue_script( 'micromodal' );

		wp_register_style( 'glacial-cpt-phone-modal', GLACIAL_CPT_PLUGIN_URL . 'public/css/glacial-cpt-phone-modal.css', array( 'glacial-style' ), CPT_STYLE_VERSION, 'all' );
		wp_enqueue_style( 'glacial-cpt-phone-modal' );
	}


	wp_register_style( 'glacial-maps', GLACIAL_CPT_PLUGIN_URL . 'public/css/map-module.css', array(), CPT_STYLE_VERSION, 'all' );
	if ( glacial_cpt_get_maps_api_key() ) {
		wp_enqueue_style( 'glacial-maps' );
	}


}

add_action( 'wp_enqueue_scripts', 'glacial_cpt_register_styles' );

/**
 * Enqueue scripts
 *
 * @since 1.0.0
 * */
function glacial_cpt_register_scripts(): void {

	$use_doctor_services_filter    = get_field( 'use_doctor_services_filter', 'options' ) ?? true;
	$use_doctor_locations_filter   = get_field( 'use_doctor_locations_filter', 'options' ) ?? true;
	$use_doctor_text_search_filter = get_field( 'use_doctor_text_search_filter', 'options' ) ?? true;

	if ( is_post_type_archive( 'doctors' ) ) {

		/*
		 * Only enqueue the scripts if the archive page is using the filter
		 * */
		if ( $use_doctor_services_filter || $use_doctor_locations_filter || $use_doctor_text_search_filter ) {

			wp_register_script( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'isotope' );

			wp_register_script( 'doctor-filter', GLACIAL_CPT_PLUGIN_URL . 'public/js/doctor-filter.js', array(
				'jquery',
				'isotope'
			), null, true );
			wp_enqueue_script( 'doctor-filter' );
		}
	}

	/*
	 * Load the Google Maps API and the g-maps.js file if the API key is set
	 * */
	if ( glacial_cpt_get_maps_api_key() ) {
		wp_register_script( 'g-maps', GLACIAL_CPT_PLUGIN_URL . 'public/js/g-maps.js', array(), CPT_STYLE_VERSION, true );
		/*
		 * Add some urls to the g-maps.js file to read with localize
		 * */
		wp_localize_script( 'g-maps', 'gmaps', array(
			'api_key'    => GOOGLE_MAPS_EMBED_API_KEY,
			'site_url'   => get_site_url(),
			'plugin_url' => GLACIAL_CPT_PLUGIN_URL
		) );

		$g_maps_args = array(
			'strategy'  => 'async',
			'in_footer' => false
		);

		/*
		 * Calls the Google Maps API with the key and the places library.
		 * The callback is the initMap function in the g-maps.js file
		 * */
		wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_EMBED_API_KEY . '&libraries=places&loading=async&callback=initMap', array( 'g-maps' ), null, $g_maps_args );

		wp_enqueue_script( 'g-maps' );
		wp_enqueue_script( 'google-maps' );
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
function glacial_cpt_get_template_part( $name, $args = array() ): void {

	$template_path = GLACIAL_CPT_TEMPLATES_FOLDER_NAME . "/{$name}.php";

	if ( locate_template( $template_path ) ) {
		$template = get_stylesheet_directory() . '/' . $template_path;
	} else {
		$template = GLACIAL_CPT_PLUGIN_DIR . '/' . $template_path;
	}

	// if file not found, return a php warning
	if ( !file_exists( $template ) ) {
		trigger_error( "Template file not found: $template", E_USER_WARNING );

		return;
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
	if ( !is_admin() && $query->is_main_query() ) {
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
 *
 * @removed 2.0.3
 * Options page now added via acf json
 * */

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
function glacial_cpt_theme_before_footer(): void {

	$add_doctors_to_service_pages           = get_field( 'add_doctors_to_service_pages', 'option' ) ?? true;
	$add_all_doctors_to_single_doctor_pages = get_field( 'add_all_doctors_to_single_doctor_pages', 'option' ) ?? true;

	if ( $add_doctors_to_service_pages && is_page() ) {
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
function glacial_cpt_admin_print_styles(): void {
	echo '<style>
				.edit-post-layout__metaboxes #acf-group_5dc1ba22dfffc .acf-fields {
    			display: flex;
    			flex-wrap: wrap;
			}
			</style>';

}

add_action( 'admin_print_styles', 'glacial_cpt_admin_print_styles' );

/**
 * Utility function to output SVGs icons
 *
 * @since 2.1.0
 * */
function glacial_cpt_svg_icon( $icon_name = '', $width = '25', $height = '25' ): string {
	$icon = match ( $icon_name ) {
		'address' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,256,256" width="' . $width . 'px" height="' . $height . 'px" fill-rule="nonzero" class="glacial-svg-icon"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,1c-8.83984,0 -16,7.16016 -16,16c0,7.30859 3.85938,15.16406 7.65625,21.25c3.79688,6.08594 7.59375,10.40625 7.59375,10.40625c0.19141,0.21484 0.46484,0.33984 0.75,0.33984c0.28516,0 0.55859,-0.125 0.75,-0.33984c0,0 3.80078,-4.41016 7.59375,-10.53125c3.79297,-6.12109 7.65625,-13.95703 7.65625,-21.125c0,-8.83984 -7.16016,-16 -16,-16zM25,3c7.76172,0 14,6.23828 14,14c0,6.43359 -3.63672,14.08203 -7.34375,20.0625c-3.10547,5.01172 -5.73437,8.23828 -6.65625,9.34375c-0.92969,-1.09766 -3.55859,-4.25391 -6.65625,-9.21875c-3.70312,-5.9375 -7.34375,-13.59766 -7.34375,-20.1875c0,-7.76172 6.23828,-14 14,-14zM25,11c-3.85547,0 -7,3.14453 -7,7c0,3.85547 3.14453,7 7,7c3.85547,0 7,-3.14453 7,-7c0,-3.85547 -3.14453,-7 -7,-7zM25,13c2.77344,0 5,2.22656 5,5c0,2.77344 -2.22656,5 -5,5c-2.77344,0 -5,-2.22656 -5,-5c0,-2.77344 2.22656,-5 5,-5z"></path></g></g></svg>',
		'fax' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,256,256" width="' . $width . 'px" height="' . $height . 'px" fill-rule="nonzero" class="glacial-svg-icon"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M21,3v10h-2c-1.64453,0 -3,1.35547 -3,3v22c0,1.64453 1.35547,3 3,3h1.96875c-0.26172,3.32031 -3.08984,6 -6.46875,6c-3.37891,0 -6.20703,-2.67969 -6.46875,-6h2.96875c1.64453,0 3,-1.35547 3,-3v-28c0,-1.64453 -1.35547,-3 -3,-3h-8c-1.64453,0 -3,1.35547 -3,3v28c0,1.64453 1.35547,3 3,3h3.03125c0.26172,4.42578 3.98047,8 8.46875,8c4.48828,0 8.20703,-3.57422 8.46875,-8h24.03125c1.64453,0 3,-1.35547 3,-3v-22c0,-1.64453 -1.35547,-3 -3,-3h-2v-10zM23,5h20v12h-20zM3,9h8c0.55469,0 1,0.44531 1,1v28c0,0.55469 -0.44531,1 -1,1h-8c-0.55469,0 -1,-0.44531 -1,-1v-28c0,-0.55469 0.44531,-1 1,-1zM19,15h2v4h24v-4h2c0.55469,0 1,0.44531 1,1v22c0,0.55469 -0.44531,1 -1,1h-28c-0.55469,0 -1,-0.44531 -1,-1v-22c0,-0.55469 0.44531,-1 1,-1zM27,21c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM33,21c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM39,21c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM27,27c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM33,27c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM39,27c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM27,33c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM33,33c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2zM39,33c-1.10547,0 -2,0.89453 -2,2c0,1.10547 0.89453,2 2,2c1.10547,0 2,-0.89453 2,-2c0,-1.10547 -0.89453,-2 -2,-2z"></path></g></g></svg>',
		'phone' => '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0,0,256,256"  width="' . $width . 'px" height="' . $height . 'px" fill-rule="nonzero" class="glacial-svg-icon"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M11.83984,2.98828c-0.76953,-0.0625 -1.625,0.16016 -2.41406,0.71484c-0.69531,0.48438 -2.19531,1.67578 -3.59766,3.02344c-0.69922,0.67188 -1.36719,1.37109 -1.88281,2.05859c-0.51953,0.6875 -0.97266,1.31641 -0.94531,2.23047c0.02734,0.82031 0.10938,3.24609 1.85547,6.96484c1.74609,3.71484 5.13281,8.8125 11.73828,15.42188c6.60938,6.60938 11.70703,9.99609 15.42188,11.74219c3.71484,1.74609 6.14453,1.82813 6.96484,1.85547c0.91016,0.02734 1.53906,-0.42578 2.22656,-0.94531c0.6875,-0.51953 1.38672,-1.18359 2.05859,-1.88281c1.34375,-1.40234 2.53516,-2.90234 3.01953,-3.59766c1.10547,-1.57422 0.92188,-3.43359 -0.30859,-4.29687c-0.77344,-0.54297 -7.88672,-5.27734 -8.95703,-5.93359c-1.08594,-0.66406 -2.33594,-0.36328 -3.45312,0.22656c-0.87891,0.46484 -3.25781,1.82813 -3.9375,2.21875c-0.51172,-0.32422 -2.45312,-1.61719 -6.62891,-5.79297c-4.17969,-4.17578 -5.46875,-6.11719 -5.79297,-6.62891c0.39063,-0.67969 1.75,-3.04687 2.21875,-3.94141c0.58594,-1.11328 0.91406,-2.375 0.21484,-3.46875c-0.29297,-0.46484 -1.625,-2.49219 -2.96875,-4.52734c-1.34766,-2.03516 -2.625,-3.96484 -2.95703,-4.42578v-0.00391c-0.43359,-0.59766 -1.10937,-0.94922 -1.875,-1.01172zM11.65625,5.03125c0.27344,0.03516 0.4375,0.14453 0.4375,0.14453c0.16016,0.22266 1.5625,2.32422 2.90625,4.35547c1.34375,2.03516 2.71484,4.12109 2.95313,4.5c0.03906,0.05859 0.09375,0.72266 -0.29687,1.46094v0.00391c-0.44141,0.83984 -2.5,4.4375 -2.5,4.4375l-0.28516,0.50391l0.29297,0.5c0,0 1.53516,2.58984 6.41797,7.47266c4.88672,4.88281 7.47656,6.42188 7.47656,6.42188l0.5,0.29297l0.50391,-0.28516c0,0 3.58984,-2.05469 4.4375,-2.5c0.73828,-0.38672 1.40234,-0.33594 1.48047,-0.28906c0.69141,0.42578 8.375,5.53125 8.84766,5.86328c0.01563,0.01172 0.43359,0.64453 -0.17578,1.51172h-0.00391c-0.36719,0.52734 -1.57031,2.05469 -2.82422,3.35938c-0.62891,0.65234 -1.27344,1.26172 -1.82031,1.67188c-0.54687,0.41016 -1.03516,0.53906 -0.95703,0.54297c-0.85156,-0.02734 -2.73047,-0.04687 -6.17969,-1.66797c-3.44922,-1.61719 -8.37109,-4.85547 -14.85937,-11.34766c-6.48437,-6.48437 -9.72266,-11.40625 -11.34375,-14.85937c-1.61719,-3.44922 -1.63672,-5.32812 -1.66406,-6.17578c0.00391,0.07813 0.13281,-0.41406 0.54297,-0.96094c0.41016,-0.54687 1.01563,-1.19531 1.66797,-1.82422c1.30859,-1.25391 2.83203,-2.45703 3.35938,-2.82422v0.00391c0.43359,-0.30469 0.8125,-0.34375 1.08594,-0.3125z"></path></g></g></svg>',
		'hours' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="' . $width . 'px" height="' . $height . 'px" class="glacial-svg-icon"><circle style="fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" cx="25" cy="25" r="22"/><circle style="fill:currentColor" cx="25" cy="25" r="3"/><polyline style="fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" points="17,33 25,25 25,8 "/></svg>',
		'target' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0,0,256,256" width="' . $width . 'px" height="' . $height . 'px"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M23,0v4.0957c-9.9813,0.949 -17.9531,8.92279 -18.90234,18.9043h-4.09766v4h4.09766c0.94924,9.98151 8.92105,17.9553 18.90234,18.9043v4.0957h4v-4.09766c9.98122,-0.94921 17.95313,-8.92112 18.90234,-18.90234h4.09766v-4h-4.09766c-0.94921,-9.98122 -8.92112,-17.95313 -18.90234,-18.90234v-4.09766zM27,8.12695c7.806,0.90996 13.96308,7.06705 14.87305,14.87305h-2.87305v4h2.87305c-0.90996,7.806 -7.06705,13.96308 -14.87305,14.87305v-2.87305h-4v2.87109c-7.80363,-0.91119 -13.96316,-7.06574 -14.87305,-14.87109h2.87305v-4h-2.87305c0.90989,-7.80535 7.06942,-13.9599 14.87305,-14.87109v2.87109h4zM25,18c-3.86599,0 -7,3.13401 -7,7c0,3.86599 3.13401,7 7,7c3.86599,0 7,-3.13401 7,-7c0,-3.86599 -3.13401,-7 -7,-7z"/></g></g></svg>',
		default => 'Icon not found',
	};

	return $icon;
}

/**
 * This is the callback function for the custom REST API route
 *
 * @since 2.1.0
 * */
function glacial_cpt_locations_json_rest(): array {
	$args      = array(
		'post_type'      => 'locations',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);
	$locations = new WP_Query( $args );

	$features = array();
	if ( $locations->have_posts() ) {
		$features = [];

		while ( $locations->have_posts() ) {
			$locations->the_post();

			$google_map = get_field( 'google_map' );
			if ( !empty( $google_map ) ) {
				$lat = (float) $google_map['lat'];

				$lng = (float) $google_map['lng'];

				$phone_numbers = get_field( 'phone_numbers' );

				$address = ( $google_map['street_number'] ?? '' ) . ' ' .
				           ( $google_map['street_name_short'] ?? '' ) . '<br>' .
				           ( $google_map['city'] ?? '' ) . ', ' .
				           ( $google_map['state'] ?? '' ) . ' ' .
				           ( $google_map['post_code'] ?? '' );

				$directions = 'https://www.google.com/maps/dir/?api=1&destination=' . $lat . ',' . $lng;


				$feature    = array(
					'geometry'   => array(
						'type'        => 'Point',
						'coordinates' => array( $lng, $lat ),
					),
					'type'       => 'Feature',
					'properties' => array(
						'category'        => 'location',
						'address'         => $address,
						'place'           => $google_map,
						'name'            => get_the_title(),
						'phone'           => $phone_numbers[0]['number'] ?? 'No phone number',
						'link'            => get_the_permalink(),
						'directions_link' => $directions,
						'locationid'      => get_the_ID()
					),
				);
				$features[] = $feature;
			}
		}
	}

	if ( $features ) {

		$locations_json = array(
			'type'     => 'FeatureCollection',
			'features' => $features,
		);

		return $locations_json;
	}

	return array();
}

/**
 * Register a custom REST API route to fetch locations.
 *
 * This is used to get our locations on the custom Google Map.
 *
 * @since 2.1.0
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'glacial/v1', '/locations', array(
		'methods'             => 'GET', // The HTTP method for this route is GET.
		'callback'            => 'glacial_cpt_locations_json_rest', // The callback function when this route is hit.
		'permission_callback' => '__return_true', // This route is accessible to all users.
	) );
} );

/**
 * Add a Google Maps API key to the ACF Google Map field
 *
 * @since 2.1.0
 * */
function glacial_cpt_acf_google_map_api( $api ) {
	$api['key'] = GOOGLE_MAPS_EMBED_API_KEY;

	return $api;
}

add_filter( 'acf/fields/google_map/api', 'glacial_cpt_acf_google_map_api' );


/**
 * Hide the Google Map field if the API key is not set
 *
 * @since 2.1.0
 * */
function glacial_acf_load_field( $field ): array {

	if ( !glacial_cpt_get_maps_api_key() ) {
		$field['wrapper']['class'] .= ' hidden';
	}

	return $field;
}

add_filter( 'acf/load_field/name=google_map', 'glacial_acf_load_field' );
