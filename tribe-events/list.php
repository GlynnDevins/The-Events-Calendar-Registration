<?php
/**
 * List View Template
 * The wrapper template for a list of events. This includes the Past Events and Upcoming Events views 
 * as well as those same views filtered to a specific category.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
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
?>



    <?php
// get the_content from the events page. This will be editable via CMS for the events page.
    $page = get_posts(
            array(
                'name' => 'events',
                'post_type' => 'page'
            )
    );

    if ($page) {
        echo $page[0]->post_content;
    }
    ?>

<?php do_action('tribe_events_before_template'); ?>

        <!-- Tribe Bar -->
<?php //tribe_get_template_part('modules/bar'); ?>

        <!-- Main Events Content -->
<?php tribe_get_template_part('list/content'); ?>

        <div class="tribe-clear"></div>

<?php do_action('tribe_events_after_template') ?>