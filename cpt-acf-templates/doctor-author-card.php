<?php
/**
 * Displays a doctor author card
 *
 *
 * @package    WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

$doc_id          = get_post_meta( get_the_ID(), 'change_author_link', true );

if ( $doc_id ):
	$doc_headshot = get_field( 'headshot', $doc_id );
	$doc_excerpt = get_field( 'doctor_excerpt', $doc_id ); ?>

    <div class="doctor-author-card">
        <div class="doctor-author-card__image">
            <img src="<?php echo $doc_headshot['url']; ?>" alt="<?php echo get_the_title( $doc_id ) ?>">
        </div>
        <div>
            <div class="doctor-author-card__written-wrap">
                <svg width="40px" height="31px" viewBox="0 0 400 312" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>checkmark</title>
                    <g id="checkmark" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect fill="#FFFFFF" opacity="0" x="0" y="0" width="40" height="31"></rect>
                        <path d="M355.416,1.736 C355.234,1.87 322.048,25.835 306.561,38.79 C257.438,79.89 215.173,127.653 175.04,177.371 C168.256,185.774 158.748,200.848 154.495,210.396 C139.448,192.577 132.532,182.216 113.074,167.384 C97.374,157.179 74.935,146.906 61.082,140.85 C55.48,138.4 48.702,135.745 43.104,136.756 C28.943,139.312 15.148,143.894 0,147.991 C3.635,150.093 6.076,151.507 8.521,152.919 C47.87,175.612 82.067,204.255 106.983,242.503 C119.194,261.249 128.909,281.638 139.473,301.433 C142.915,307.883 147.511,311.942 154.963,312.174 C162.659,312.413 168.004,308.76 171.354,301.966 C171.942,300.772 172.501,299.564 173.071,298.36 C200.205,241.116 232.959,187.268 271.265,136.84 C308.511,87.81 350.349,43.02 396.821,2.593 C397.874,1.679 398.932,0.753 400,-0.185 C400,-0.185 355.514,1.694 355.416,1.736"
                              id="Fill-1"></path>
                    </g>
                </svg>
                <span class="doctor-author-card__label">Written by</span>
            </div>
            <h3 class="doctor-author-card__title"><?php echo get_the_title( $doc_id ); ?></h3>
            <p class="doctor-author-card__excerpt"><?php echo $doc_excerpt; ?></p>
            <a class="ui-button" href="<?php echo esc_url( get_permalink( $doc_id ) ); ?>">Learn More</a>
        </div>
    </div>

<?php endif; ?>