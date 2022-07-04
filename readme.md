# Glacial Custom Post Types with ACF

This plugin will create custom post types for doctors and locations. It uses ACF Pro and makes use of the relationship
field as well as others. You MUST have ACF Pro installed to use this plugin.

## Usage

The plugin will create the post types Doctors and Locations on install. The ACF fields will be available for syncing in
Field Groups in ACF.

The main template file, doctor-location-wrapper.php, is in public/templates. This template serves as a wrapper. You
should change the outer div and h1 elements to match your design.

All locations and doctors on the archive pages are ordered according to menu order.

### Doctors

To make use of the Doctor filter, you need to add the service tag to service pages. Any page with that tag will populate
the "Services" dropdown menu. The plugin adds tag support for pages and creates the service tag on activation so it's
ready for use.

## Styling
There is base styling on this but you will need to add styling to match design of site.

## Other Notes


There is a template partial in public/partials/use-in-theme called doctors-service-pages.php. You can use this file as a
template part on service pages and it will pull in all doctors who have that service in their profile. Just copy it to your theme. I put it there for ease of copying

That's basically it.

