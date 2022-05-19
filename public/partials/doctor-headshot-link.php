<?php
/**
 * Displays the doctor headshot link
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

$image = get_field( 'headshot' );

if ( $image ) {
	$image_url = $image['url'];
} else {
	$image_url = plugin_dir_url( __DIR__ ) . 'images/doc-placeholder.jpg';
} ?>

<a href="<?php the_permalink(); ?>">
    <img src="<?php echo $image_url ?>" alt="<?php the_title() ?>">
    <div class="doctor-name"><?php the_title(); ?></div>
</a>