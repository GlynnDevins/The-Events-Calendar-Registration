<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * THIS IS THE OVERRIDE FILE THAT SHOULD EXIST IN THE /themes/{your theme}/tribe-events/ directory
 * 
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */
if (!defined('ABSPATH')) {
    die('-1');
}

$event_id = get_the_ID();
?>

<div id="tribe-events-content" class="tribe-events-single">

    <p class="tribe-events-back">
        <a href="<?php echo tribe_get_events_link() ?>">
            <?php
            // BACK TO ALL EVENTS LINK
            _e('&lArr; Back To All Events', 'tribe-events-calendar');
            ?>
        </a>
    </p>

    <!-- Notices -->
    <?php tribe_events_the_notices() ?>

    <?php
    // EVENT TITLE
    the_title('<h2 class="tribe-events-single-event-title summary">', '</h2>');
    ?>

    <!-- Event header -->
    <div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
        <!-- Navigation -->
        <h3 class="tribe-events-visuallyhidden"><?php _e('Event Navigation', 'tribe-events-calendar') ?></h3>
        <?php /* ?>
          <ul class="tribe-events-sub-nav">
          <li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link('&laquo; %title%') ?></li>
          <li class="tribe-events-nav-next"><?php tribe_the_next_event_link('%title% &raquo;') ?></li>
          </ul><!-- .tribe-events-sub-nav -->
          <?php */ ?>
    </div><!-- #tribe-events-header -->

    <?php while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('vevent'); ?>>

            <?php
            // FEATURED IMAGE
            //echo tribe_event_featured_image(null, 'medium');
            
            if ( has_post_thumbnail()) : 
                the_post_thumbnail('medium', array('class' => 'alignleft'));
            endif;
            
            ?>
            
    <div class="tribe-events-single-event-meta <?php echo $has_venue . $has_venue_address; ?>">
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


            <?php do_action('tribe_events_single_event_before_the_content') ?>

            <div class="tribe-events-single-event-description tribe-events-content entry-content description">
                <?php
                // EVENT DESCRIPTION
                the_content();
                ?>
            </div>

            <?php do_action('tribe_events_single_event_after_the_content') ?>

            <!-- Event meta -->
            <?php do_action('tribe_events_single_event_before_the_meta') ?>
            <?php
            // EVENT META BOX - DATE / TIME / VENUE NAME & ADDRESS / GOOGLE MAP
            //echo tribe_events_single_event_meta()
            ?>
            <?php do_action('tribe_events_single_event_after_the_meta') ?>
            <?php if ( dynamic_sidebar('ecgf_single_widget') ) : else : endif; ?>
            <div class="event-googlemap">
                <?php
                    if (tribe_embed_google_map() == 'true') :
                        echo tribe_get_embedded_map(null, false);
                    else:
                        echo 'There is no map available for this event.';
                    endif;
                ?>
                
                <?php
                    if (tribe_show_google_map_link() == 'true') :
                        echo '<a href="' . tribe_get_map_link() . '" target="_blank" class="google-maps-link tribe-events-read-more">Open with Google Maps</a>';
                    endif;
                ?>
            </div>
        </div><!-- .hentry .vevent -->
        <?php if (get_post_type() == TribeEvents::POSTTYPE && tribe_get_option('showComments', false)) comments_template() ?>
    <?php endwhile; ?>

    <!-- Event footer -->
    <div id="tribe-events-footer">
        <h3 class="tribe-events-visuallyhidden"><?php _e('Event Navigation', 'tribe-events-calendar') ?></h3>
        <?php /* ?>
          <ul class="tribe-events-sub-nav">
          <li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link('&laquo; %title%') ?></li>
          <li class="tribe-events-nav-next"><?php tribe_the_next_event_link('%title% &raquo;') ?></li>
          </ul><!-- .tribe-events-sub-nav -->
          <?php */ ?>

    </div><!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
