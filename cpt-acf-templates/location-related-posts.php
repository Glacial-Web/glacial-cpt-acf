<?php
/**
 * The template partial for displaying related posts on the single location page
 *
 * @package Glacial_Cpt_Acf
 */

$related_blog_posts = get_field( 'related_blog_posts' );

if ( $related_blog_posts ): ?>

    <div class="related-posts-wrapper">
        <h3>Related Posts</h3>
        <div class="flex-wrapper flex-start">

			<?php foreach ( $related_blog_posts as $related_blog_post ):

				if ( has_post_thumbnail( $related_blog_post ) ) {
					$image_url = get_the_post_thumbnail_url( $related_blog_post, 'medium_large' );
                    $image_class = 'has-featured';
				} else {
					$image_url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
                    $image_class = 'no-featured';
				} ?>

                <div class="location-related-post">
                    <a href="<?php echo get_the_permalink( $related_blog_post ); ?>" class="location-related-post-link">
                        <img src="<?php echo $image_url; ?>" class="<?php echo $image_class; ?>" alt="<?php echo get_the_title( $related_blog_post ); ?>"
                             loading="lazy">
                        <div class="related-post-title"><?php echo get_the_title( $related_blog_post ); ?></div>
                    </a>
                </div>

			<?php endforeach; ?>

        </div>
    </div>

<?php endif; ?>