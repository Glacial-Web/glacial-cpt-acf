# Change Log

All notable changes to this plugin will be documented in this file.

## [1.1.0] - 2023-01-19

### Added

* Options page to the plugin [Read more](https://github.com/Glacial-Web/glacial-cpt-acf/issues/2)
* Categorize doctors into MD, OD, etc. or anything you choose. Can be changed or turned off in ACF settings.
* Ability to add posts to single location pages (SEO request)

### Changed

* Doctor images now use aspect-ratio instead of fixed height. This allows for more flexibility in image sizes.

## [2.0.0] - 2023-06-16

#### Glacial theme v3.0.0 or later required

### Added

* All templates can now be overridden in theme.
* New JS library used for filtering doctors ([isotope.js v3.0.6](https://github.com/metafizzy/isotope)) .
* Ability to search doctors by text field.
* New custom post type for locations. This allows for more flexibility in the future.
* Ability to add doctors name and link to author of blog posts.
* If doctor has blog post(s) associated with them, the blog post(s) will be displayed on the doctor page with 3 layout
  options.
* If doctor has blog post(s) associated with them and the ACF excerpt field is not empty a card will be display on the
  associated blog posts.

### Changed

* Both CPT (Doctors and Locations) are now created in ACF settings. This makes it much easier to change things like
  slugs and labels.
* Links to doctor services and location now displayed as buttons.
* CSS: All doctors and related posts containers converted from flex-box to grid.

## [2.0.3] - 2023-12-7

### Fixed

* Doctors related to service pages were being displayed on search results page when last search had related doctors. A
  check of `is_page()` was added to prevent this. [Read more](https://github.com/Glacial-Web/glacial-cpt-acf/issues/6)

## [2.1.0] - 2025-09-04

### Added

* Ability to add icons to location info
* New grid layout for location
* Ability to add searchable map to location page
* Ability to add a phone number modal to header

## [2.1.1] - 2025-10-29

### Added

* Filters to modify doctors heading on service pages
* `glacial_cpt_doctors_service_pages_cpt_labels` - Allows modification of the "doctors" CPT label used in headings.
* `glacial_cpt_doctors_service_pages_heading` - Allows modification of the final heading string for the doctors service
  page.
* `glacial_cpt_doctors_service_pages_title` - Allows modification of the service page title (single default changes "
  Cataracts" to "Cataract").
* `glacial_cpt_doctors_service_pages_title_changes` - Allows modification of the array of title replacements (e.g., to
  add or change which titles are replaced and with what, default is `['Cataracts' => 'Cataract']`).

These filters provide hooks for theme to customize labels, headings, and title replacements for doctors service pages.