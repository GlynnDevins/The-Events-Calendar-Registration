##The-Events-Calendar-Registration
================================

This plugin requires the following plugins be installed and enabled:
  - The Events Calendar plugin
  - Gravity Forms
  - Advanced Custom Fields
  - Advanced Custom Fields Date/Time Picker

When enabled, this plugin auto generates:
  - A gravity form titled "Event Registration"
  - An advanced custom field group on the post type tribe_events
    - These custom fields will be visible on the events add/edit page
  - Pages:
    - Events (top level)
      - Thank You (nested under events)

With this plugin enabled, you can easily export the entries on a per event basis by going to the edit events page, and using the Export Entries action when you mouse over the row.

To display the events form, you can use the shortcode: `[ecgf_form]`

The registration form will only show on a single event page, this means it will not display on an archive page of events.

