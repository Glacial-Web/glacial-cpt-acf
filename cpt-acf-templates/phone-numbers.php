<?php
/**
 * Displays the phone numbers used in single and archive location templates
 *
 * @package Glacial_Cpt_Acf
 */

if ( have_rows( 'phone_numbers' ) ): ?>

    <ul class="location-phone-list">

		<?php while ( have_rows( 'phone_numbers' ) ): the_row(); ?>

			<?php
			$label  = get_sub_field( 'label' );
			$number = get_sub_field( 'number' );
			$link   = get_sub_field( 'link' );
			$note   = get_sub_field( 'note' );

			if ( $link ):
				$href_number = preg_replace( '/\D/', '', $number ); ?>

                <li>
					<?php if ( $label ) {
						echo $label . ': ';
					} ?>
                    <span class="nowrap">
                        <a href="tel:+1<?php echo $href_number; ?>"
                           class="location_phone"><?php echo $number; ?></a>
                    </span>

					<?php if ( $note ) {
						echo '<br><span class="phone-note">' . $note . '</span>';
					} ?>

                </li>

			<?php else: ?>

                <li><?php if ( $label ) {
						echo $label . ': ';
					} ?>
                    <span class="nowrap fax_text"><?php echo $number; ?></span>
					<?php if ( $note ) {
						echo '<br><span class="phone-note">' . $note . '</span>';
					} ?>
                </li>

			<?php endif; ?>

		<?php endwhile; ?>

    </ul>

<?php endif; ?>