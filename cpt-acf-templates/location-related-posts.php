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
        <div class="cpt-grid">

			<?php foreach ( $related_blog_posts as $related_blog_post ) {
				glacial_cpt_get_template_part( 'related-posts-element', array( 'id' => $related_blog_post ) );
			} ?>

        </div>
    </div>

<?php endif; ?>