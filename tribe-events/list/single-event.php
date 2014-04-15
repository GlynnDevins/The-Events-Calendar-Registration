<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */
if (!defined('ABSPATH')) {
    die('-1');
}
?>

<?php
// Setup an array of venue details for use later in the template
$venue_details = array();

if ($venue_name = tribe_get_meta('tribe_event_venue_name')) {
    $venue_details[] = $venue_name;
}

if ($venue_address = tribe_get_meta('tribe_event_venue_address')) {
    $venue_details[] = $venue_address;
}
// Venue microformats
$has_venue = ( $venue_details ) ? ' vcard' : '';
$has_venue_address = ( $venue_address ) ? ' location' : '';
?>

<!-- Event Cost -->
<?php /* if ( tribe_get_cost() ) : ?> 
  <div class="tribe-events-event-cost">
  <span><?php echo tribe_get_cost( null, true ); ?></span>
  </div>
  <?php endif; */ ?>


<!-- Event Head -->
<div class="event-head">
    <!-- Event Title -->
    <?php echo tribe_event_featured_image(null, 'thumbnail') ?>
    <?php do_action('tribe_events_before_the_event_title') ?>
    <h3 class="tribe-events-list-event-title summary">
        <a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
            <?php the_title() ?>
        </a>
    </h3>
    <?php do_action('tribe_events_after_the_event_title') ?>
</div>

<!-- Event Content -->
<div class="event-desc">
    <?php do_action('tribe_events_before_the_content') ?>
    <div class="tribe-events-list-event-description tribe-events-content description entry-summary">
        <?php the_excerpt() ?>
    </div><!-- .tribe-events-list-event-description -->
    <?php do_action('tribe_events_after_the_content') ?>
</div>

<!-- Event Meta Custom-->

<div class="tribe-events-event-meta <?php echo $has_venue . $has_venue_address; ?>">
<?php do_action('tribe_events_before_the_meta') ?>
    <dl>
        <dt>Location:</dt>
        <dd>
            <span class="event-address"><?php echo tribe_get_address(); ?></span>
            <span class="event-city"><?php echo tribe_get_city(); ?></span>, <span class="event-state"><?php echo tribe_get_state(); ?></span><span class="event-zip"><?php echo tribe_get_zip(); ?></span>
        </dd>
        <dt>Date:</dt>
        <dd><?php echo tribe_get_start_date(null, false, 'l, F j, Y');?></dd>
        <dt>Time:</dt>
        <dd><?php echo tribe_get_start_date(null, false, 'g:i a ') . ' - ' . tribe_get_end_date(null, false, 'g:i a ') . ' ' . tribe_get_start_date(null, false, 'T') ;?></dd>
    </dl>
<?php do_action('tribe_events_after_the_meta') ?>
</div>

<a href="<?php echo tribe_get_event_link() ?>" class="tribe-events-read-more" rel="bookmark"><?php _e('Find out more', 'tribe-events-calendar') ?> &raquo;</a>