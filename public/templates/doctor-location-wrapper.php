<?php
/**
 * This is a wrapper that will pull in all of our parts
 * You can change the outer divs and h1 to match your design
 *
 * @package Glacial_Cpt_Acf
 */

get_header(); ?>


<style>
    /*
	You can get rid of all of this. Just for basic formatting
	*/
    h1 {
        margin-bottom: 1em;
    }

    .inner_page_section {
        background: #fff;
    }

    .inner_wrapper {
        width: 90%;
        margin: auto;
        max-width: 1200px;
        padding: 50px 0;
    }

</style>

<div class="inner_page_section">
    <div class="inner_wrapper">

		<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} ?>

		<?php if ( is_post_type_archive( array( 'locations', 'doctors' ) ) ): ?>

            <h1 class="inner_main_headline"><?php echo post_type_archive_title(); ?></h1>

			<?php include( plugin_dir_path( __DIR__ ) . 'partials/archive-' . get_post_type() . '.php' ); ?>

		<?php endif; ?>

		<?php if ( is_singular( array( 'locations', 'doctors' ) ) ): ?>

			<?php
			$degree = get_field( 'degree' );
			if ( get_post_type() == 'doctors' && $degree ) {
				$title = get_the_title() . ', ' . $degree;
			} else {
				$title = get_the_title();
			} ?>

            <h1 class="inner_main_headline"><?php echo $title; ?></h1>

			<?php include( plugin_dir_path( __DIR__ ) . 'partials/single-' . get_post_type() . '.php' ); ?>

		<?php endif; ?>


    </div>
</div>


<?php get_footer(); ?>

