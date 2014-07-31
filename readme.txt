=== Plugin Name ===
Contributors: Fastmover
Tags: gravityforms, events, the events calendar, advanced custom fields, registration, rsvp
Requires at least: 3.7
Tested up to: 3.9.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates a registration form for events created using The Events Calendar plugin.

== Description ==

This plugin requires the following plugins be installed and enabled:

- The Events Calendar plugin
- Gravity Forms
- Advanced Custom Fields
- Advanced Custom Fields Date/Time Picker

When enabled, this plugin auto generates:

- A gravity form titled "Event Registration"
- An advanced custom field group on the post type tribe_events
- - These custom fields will be visible on the events add/edit page
- Pages
  - Events (top level)
    - Thank You (nested under events)

With this plugin enabled, you can easily export the entries on a per event basis by going to the edit events page, and using the Export Entries action when you mouse over the row.

To display the events form, you can use the shortcode: `[ecgf_form]`

The registration form will only show on a single event page, this means it will not display on an archive page of events.

== Installation ==

1. Upload `the-events-calendar-registration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Copy and paste shortcode into your event or event's single template: `[ecgf_form]`

== Screenshots ==

1. Add's export functionality to the admin view of all events.
2. Front end facing form (obviously won't be styled the same as styling is up to you).
3. Edit event widget, which allows disabling event registration on a per event basis, as well as specifying when event registration will end and the message to be shown when the registration period has ended.

== Changelog ==

= 1.2 =
* Updated readme to reflect current version
* Added screenshots
