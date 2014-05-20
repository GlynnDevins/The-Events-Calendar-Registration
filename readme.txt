=== Plugin Name ===
Contributors: Fastmover
Tags: gravityforms, events, the events calendar, advanced custom fields, registration, rsvp
Requires at least: 3.7
Tested up to: 3.9
Stable tag: 1.1
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

This plugin does require you widgetize your events theme files, if you haven't already made a tribe-events folder in your theme, you will want to follow these directions: http://tri.be/support/documentation/events-calendar-themers-guide/

Once you've copied out the template files you need, you need to add a widget area to them. To learn more about this, please see readme at: https://github.com/GlynnDevins/The-Events-Calendar-Registration


== Installation ==

1. Upload `the-events-calendar-registration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable the form for an event on the event edit page.
4. Widgetize your events template files, if not already.  For further instructions reference: https://github.com/GlynnDevins/The-Events-Calendar-Registration
