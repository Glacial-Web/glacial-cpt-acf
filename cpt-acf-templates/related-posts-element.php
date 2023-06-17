<?php
/**
 * The template partial for displaying the related posts element
 *
 * @package Glacial_Cpt_Acf
 */

$post_id = $args['id']; // $args comes from glacial_cpt_get_template_part()

if ( has_post_thumbnail( $post_id ) ) {
	$image_url   = get_the_post_thumbnail_url( $post_id, 'medium_large' );
	$image_class = 'has-featured';
} else {
	$image_url   = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
	$image_class = 'no-featured';
} ?>

<div class="cpt-related-post">
    <a href="<?php echo get_the_permalink( $post_id ); ?>" class="location-related-post-link">
        <img src="<?php echo $image_url; ?>" class="<?php echo $image_class; ?>"
             alt="<?php echo get_the_title( $post_id ); ?>"
             loading="lazy">
        <div class="related-post-title"><?php echo get_the_title( $post_id ); ?></div>
    </a>
</div>

