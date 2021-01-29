<?php
/**
 *
 */

get_header(); ?>
<div class="inner_page_section">
    <div class="inner_wrapper">

            <?php
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
			}
			if ( is_post_type_archive( 'locations' ) ) {

				include( plugin_dir_path( __FILE__ ) . 'archive-locations.php' );
			}

			if ( is_post_type_archive( 'doctors' ) ) {
				include( plugin_dir_path( __FILE__ ) . 'archive-doctors.php' );
			}

			if ( is_singular( 'locations' ) ) {
				include( plugin_dir_path( __FILE__ ) . 'single-locations.php' );
			}

			if ( is_singular( 'doctors' ) ) {
				include( plugin_dir_path( __FILE__ ) . 'single-doctors.php' );
			}
			?>

        </div>
    </div>

<style>

    .inner_page_section {
        background: #fff;
    }

    .inner_wrapper {
        width: 90%;
        margin: auto;
        max-width: 1200px;
        padding: 100px 0;
    }

</style>

<?php get_footer(); ?>

