<?php
/**
 * Displays the doctor headshot link
 *
 * @package Glacial_Cpt_Acf
 */

$image                  = get_field( 'headshot' );
$additional_specialties = get_field( 'additional_specialties' );

if ( $image ) {
	$image_url = $image['url'];
} else {
	$image_url = plugin_dir_url( __DIR__ ) . 'images/doc-placeholder.jpg';
} ?>

<a class="doctor-headshot-link" href="<?php the_permalink(); ?>">
    <img src="<?php echo $image_url ?>" alt="<?php the_title() ?>" loading="lazy">
    <div class="doctor-name"><?php the_title(); ?></div>

	<?php if ( $additional_specialties && is_post_type_archive('doctors') ): ?>
        <div class="doctor-additional-specialties"><?php echo $additional_specialties; ?></div>
	<?php endif; ?>

</a>