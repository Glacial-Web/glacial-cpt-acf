<?php
/**
 * This is a wrapper that will pull in all of our parts
 *
 * @package Glacial_Cpt_Acf
 */

if ( is_post_type_archive( array( 'locations', 'doctors' ) ) ) {
	include( GLACIAL_CPT_PLUGIN_DIR . 'public/partials/archive-' . get_post_type() . '.php' );
}

if ( is_singular( array( 'locations', 'doctors' ) ) ) {
	include( GLACIAL_CPT_PLUGIN_DIR . 'public/partials/single-' . get_post_type() . '.php' );
}

