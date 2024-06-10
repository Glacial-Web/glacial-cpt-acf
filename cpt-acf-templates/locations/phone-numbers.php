<?php
/**
 * Displays the phone numbers used in single and archive location templates
 *
 * @package Glacial_Cpt_Acf
 */

if ( have_rows( 'phone_numbers' ) ):
	$add_icons = get_field( 'add_icons', 'options' ) ?? true; ?>


    <ul class="location-phone-list">

		<?php while ( have_rows( 'phone_numbers' ) ): the_row();

			$label  = get_sub_field( 'label' );
			$number = get_sub_field( 'number' );
			$link   = get_sub_field( 'link' );
			$note   = get_sub_field( 'note' );

			$link = $link == 'yes';

			$label_icon_html = '';
			$icon            = '';

			$icon = $link ? 'phone' : 'fax';

			if ( $add_icons ) {
				$label_icon_html = glacial_cpt_svg_icon( $icon );
			} else {
				$label_icon_html = $label . ': ';
			}

			if ( $link ):
				$href_number = preg_replace( '/\D/', '', $number ); ?>

                <li>
					<?php echo $label_icon_html; ?>
                    <a href="tel:+1<?php echo $href_number; ?>"
                       class="location-phone"><?php echo $number; ?></a>
					<?php if ( $note ) {
						echo '<br><span class="phone-note">' . $note . '</span>';
					} ?>
                </li>

			<?php else: ?>

                <li>
					<?php echo $label_icon_html ?>
                    <span class="nowrap location-fax"><?php echo $number; ?></span>
					<?php if ( $note ) {
						echo '<br><span class="phone-note">' . $note . '</span>';
					} ?>
                </li>

			<?php endif;

		endwhile; ?>

    </ul>

<?php endif; ?>