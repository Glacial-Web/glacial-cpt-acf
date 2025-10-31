# Glacial Custom Post Types with ACF

This plugin will create custom post types for doctors and locations. You MUST have ACF Pro installed to use this plugin
and the glacial theme v3.0.0 or later.

## Usage

Download and install the plugin.

This will create everything you need to use the Doctors and Locations custom post types.

The ACF fields will available immediately in the Doctor and Location pages. To change the fields just sync them in ACF
admin.

## Changing the templates

To change any of the templates you can copy the template file from the plugin directory to your theme directory.

Create a folder in the root of your theme named `cpt-acf-templates` and copy the template file you want to change to
that folder.

All locations and doctors on the archive pages are ordered according to menu order.

## Styling

There is base styling on this but you will need to add styling to match design of site.

## Filters

The plugin exposes several filters to customize headings, CPT labels and automatic title replacements used on doctors
service pages.

- `glacial_cpt_doctors_service_pages_cpt_labels(array $labels)`  
  Modify the CPT labels used in headings (same structure as register_post_type labels).

  Example — change "Doctors" to "Providers":
  ```php
  add_filter( 'glacial_cpt_doctors_service_pages_cpt_labels', function( string $cpt_label ) {
     $cpt_label = 'Providers';
      return $labels;
  } );
  ```

- `glacial_cpt_doctors_service_pages_heading (string $heading, string $service_title)`  
  Modify the final heading string shown on service pages.

  Example — prepend a prefix:
  ```php
  add_filter( 'glacial_cpt_doctors_service_pages_heading', function( $heading, $service_title ) {
      return 'Our Top ' . $heading; // e.g. "Our Top Providers for Cataracts"
  }, 10, 2 );
  ```

- `glacial_cpt_doctors_service_pages_title(string $title)`  
  Modify the computed service page title (used for singular/plural tweaks).

  Example — force a specific title:
  ```php
  add_filter( 'glacial_cpt_doctors_service_pages_title', function( $title ) {
      if ( 'Cataracts' === $title ) {
          return 'Cataract Surgery';
      }
      return $title;
  } );
  ```

- `glacial_cpt_doctors_service_pages_title_changes(array $replacements)`  
  Adjust the array of title replacements (key => replacement). Useful to add or change automatic string swaps.

  Example — add a replacement rule:
  ```php
  add_filter( 'glacial_cpt_doctors_service_pages_title_changes', function( $replacements ) {
      $replacements['Glaucoma'] = 'Glaucoma Treatment';
      return $replacements;
  } );
  ```
