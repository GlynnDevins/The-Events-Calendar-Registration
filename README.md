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

This plugin does require you widgetize your events theme files, if you haven't already made a tribe-events folder in your theme, you will want to follow these directions: http://tri.be/support/documentation/events-calendar-themers-guide/

Once you've copied out the template files you need, you need to add a widget area to them.  This is as simple as the following code:

in your theme/functions.php file:
`
function events_single_widgets_init() {

  register_sidebar( array(
    'name' => 'Events right sidebar',
    'id' => 'events_side_1',
    'before_widget' => '<div>',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="rounded">',
    'after_title' => '</h2>',
  ) );
}


add_action( 'widgets_init', 'events_single_widgets_init' );
`

Then in the template file of your choice, add:

`<?php if ( dynamic_sidebar('events_side_1') ) : else : endif; ?>`

The easiest example may be to copy the file plugins/the-events-calendar/views/default-template.php to /your-theme/tribe-events/default-template.php

Then add the above line after the events output, like so:

`<div id="tribe-events-pg-template">
 	<?php tribe_events_before_html(); ?>
 	<?php tribe_get_view(); ?>
 	<?php tribe_events_after_html(); ?>
   <?php if ( dynamic_sidebar('events_side_1') ) : else : endif; ?>
 </div> <!-- #tribe-events-pg-template -->
 <?php get_footer(); ?>`