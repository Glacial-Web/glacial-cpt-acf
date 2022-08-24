# Glacial Custom Post Types with ACF

This plugin will create custom post types for doctors and locations. You MUST have ACF Pro installed to use this plugin
and the glacial theme v2.2 or later.

## Usage

Download and install the plugin.

This will create everything you need to use the Doctors and Locations custom post types.

The ACF fields will available immediately in the Doctor and Location pages. To change the fields just sync them in ACF
admin.

The main template file, doctor-location-wrapper.php, is in public/templates. To change these you can either edit the
plugin or copy all templates to your theme. There are a couple of place you'll need to change to get_template_part() if
you copy the files over to your theme (working on changing this is subsequent releases. Might be easiest to edit the
plugin files right now.

All locations and doctors on the archive pages are ordered according to menu order.

## Styling

There is base styling on this but you will need to add styling to match design of site.
