<?php
/**
 *
 * Template part for displaying doctors by chosen category
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */
?>
<?php
//Query the Doctors custom post type to see if any doctors are related to the page
$doctors = get_posts( array(
	'numberposts' => - 1,
	'post_type'   => 'doctors',
	'orderby'     => 'menu_order',
	'order'       => 'ASC',
	'meta_query'  => array(
		'relation' => 'or',
		array(
			'key'     => 'specialties',
			// name of custom field
			'value'   => '"' . get_the_ID() . '"',
			// matches exactly "123", not just 123. This prevents a match for "1234"
			'compare' => 'LIKE'
		),
		/*array(
			'key'     => 'surgical_procedures',
			// name of custom field
			'value'   => '"' . get_the_ID() . '"',
			// matches exactly "123", not just 123. This prevents a match for "1234"
			'compare' => 'LIKE'
		)*/
	)
) );

if ( $doctors ): ?>

    <div class="lasik_doctors_section doctors_section">
        <div class="wrapper_div-2">
            <h2 class="inner_docs_headline"><?php the_title(); ?> Doctors </h2>
            <div class="docs_side_div">
				<?php foreach ( $doctors as $doctor ):
					$degree = get_field( 'degree', $doctor->ID );
					$img = get_field( 'headshot', $doctor->ID );
					?>

                    <a href="<?php echo get_permalink( $doctor->ID ); ?>"
                       class="doctor_btn_inner-2 w-inline-block"
                       title="<?php echo get_the_title( $doctor->ID ); ?>">
                        <div class="doc_btn_text"><b><?php echo get_the_title( $doctor->ID ) . ' ' . $degree; ?></b>
                        </div>
                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>" class="doc_btn_img">
                    </a>

				<?php endforeach; ?>

            </div>
        </div>
    </div>

<?php endif; ?>
