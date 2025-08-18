<?php
/**
 * Glacial CPT ACF Phone Modal
 *
 * Adds a button to the header that opens a modal with phone numbers.
 * The modal is populated with phone numbers from the 'locations' custom post type.
 * The button and modal are only displayed if the 'phone_modal_content' field is set in the options page.
 *
 * @package Glacial_Cpt_Acf
 * @since 2.1.0
 */

function glacial_cpt_add_button_to_header() {

	$html = '<button id="searchButton" class="top_phone" data-micromodal-trigger="phoneModal"aria-label="Open search popup" aria-haspopup="true">';
	$html .= glacial_cpt_svg_icon( 'phone' );
	$html .= '<span class="top_phone_text">Phone Numbers</span>';
	$html .= '</button>';

	echo $html;

}

add_action( 'glacial_after_nav', 'glacial_cpt_add_button_to_header', 1 );

function glacial_cpt_add_modal_to_footer() {

	glacial_cpt_get_template_part( 'phone-modal' );
}

add_action( 'wp_footer', 'glacial_cpt_add_modal_to_footer', 100 );

