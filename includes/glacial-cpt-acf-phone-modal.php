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

/**
 * Adds a button to the header that triggers the phone modal.
 *
 * This button is displayed in the header after the navigation menu.
 * It uses the `glacial_after_nav` action hook to insert the button.
 *
 * @return void
 * @since 2.1.0
 */
function glacial_cpt_add_button_to_header(): void {

	$html = '<button id="phoneModalButton" class="phone-modal-button" data-micromodal-trigger="phoneModal" aria-label="Open phone popup" aria-haspopup="true">';
	$html .= glacial_cpt_svg_icon( 'phone' );
	$html .= '</button>';

	echo $html;

}


/**
 * @return void
 */
function glacial_cpt_add_modal_to_footer(): void {

	glacial_cpt_get_template_part( 'phone-modal' );
}

if ( glacial_cpt_is_phone_modal_enabled() ) {
	add_action( 'wp_footer', 'glacial_cpt_add_modal_to_footer', 100 );
	add_action( 'glacial_after_nav', 'glacial_cpt_add_button_to_header', 1 );
}

