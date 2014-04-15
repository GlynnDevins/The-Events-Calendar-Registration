=== Plugin Name ===
Contributors: Fastmover
Tags: gravityforms, events, the events calendar, advanced custom fields, registration, rsvp
Requires at least: 3.7
Tested up to: 3.8.3
Stable tag: 1.0
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
- - This custom field will be visible on the events add/edit page 
- Pages
- - Events
- - - Thank You

With this plugin enabled, you can easily export the entries on a per event basis by going to the edit events page, and using the Export Entries action when you mouse over the row.

There is a plugin option page underneath Events > Registration settings, which allow you to dynamically move the registration form from the right sidebar to the main content just above the map (if there is one). This logic is set in the template files.

There are tribe-events template files included with this that the plugin will use unless they're copied to the current active theme directory.



== Installation ==

1. Upload `the-events-calendar-registration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable the form for an event on the event edit page.
4. Style as needed copying the directory tribe-events out of the plugin folder into your active theme.
