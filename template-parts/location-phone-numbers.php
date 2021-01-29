<?php
/**
 *
 * The template part for displaying the phone numbers for the locations
 *
 *
 * @package WordPress
 * @subpackage glacial
 * Author: Glacial Multimedia, Inc.
 * Author URL: https://www.glacial.com/
 */

if ( have_rows( 'phone_numbers' ) ): ?>

    <ul class="location-phone-list">

		<?php while ( have_rows( 'phone_numbers' ) ): the_row(); ?>
			<?php //vars
			$label  = get_sub_field( 'label' );
			$number = get_sub_field( 'number' );
			$link   = get_sub_field( 'link' );
			$note   = get_sub_field( 'note' );

			if ( $link ):
				$href_number = preg_replace( '/\D/', '', $number );
				?>

                <li><?php echo $label . ': '; ?>
                    <span class="nowrap"><a href="tel:+1<?php echo $href_number; ?>"
                                            class="location_phone"><?php echo $number; ?></a></span>
                        <?php if ( $note ) {
	                        echo '<br><em>' . $note . '</em>';
                        } ?>
                </li>

			<?php else: ?>

                <li><?php echo $label . ': ' . '<span class="nowrap fax_text">' . $number . '</span>'; ?></li>

			<?php endif; ?>

		<?php endwhile; ?>

    </ul>

<?php endif; ?>
