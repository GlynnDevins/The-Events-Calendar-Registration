<?php
/**
 * Plugin Name: The Events Calendar Gravity Forms Registration
 * Plugin URI:
 * Description: This plugin will integrate The Events Calendar and Gravity Forms for Event Registration
 * Version: 0.1
 * Author: GlynnDevins
 * Author URI: http://www.glynndevins.com
 * License:
 */


class EventsCalendarGravityFormsRegistration {

  public function __construct() {
    add_action( 'widgets_init', __CLASS__.'::register_events_sidebar' );
    add_action('admin_notices', __CLASS__.'::admin_notice');
    register_activation_hook( __FILE__, __CLASS__.'::activate' );
  }

  /**
   * Check to see if all necessary plugins are active
   *
   */
  public static function req_plugins_active() {

    $plugins = array();
    if ( !is_plugin_active( 'acf-field-date-time-picker/acf-date_time_picker.php' ) ) :
      $plugins[] = 'Advanced Custom Fields Date/Time Picker';
    endif;
    if ( !is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) :
      $plugins[] = 'The Events Calendar';
    endif;
    if ( !is_plugin_active( 'gravityforms/gravityforms.php' ) ) :
      $plugins[] = 'Gravity Forms';
    endif;
    if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) ) :
      $plugins[] = 'Advanced Custom Fields';
    endif;

    $issues = '';
    if (empty($plugins)) :
      return false;
    else:
      return $plugins;
    endif;
  }



  /**
   * Display Admin Notice if there are Issues with necessary plugins not being active
   *
   */
  public static function admin_notice() {
    if (static::req_plugins_active() !== false) :
      $issues = static::req_plugins_active();
      echo '<div class="error">';
      echo 'The following plugins are required for event registration: ';
      echo '<ul>';
      foreach($issues as $issue){
        echo '<li style="font-weight:bold;">'.$issue.'</li>';
      }
      echo '</ul>';
      echo '</div>';
    endif;
  }


  /**
   * Method called when this plugin is activated
   */
  public static function activate() {
    if(static::req_plugins_active() !== false) {
      add_option( 'ecgf_activation_error', 'true' );
      unset($_GET['action']);
      static::admin_notice();
      exit;
    }
  }

  /**
   * Disable required plugins from being deactivated via GUI
   */
  function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
    //add_filter( 'plugin_action_links', __CLASS__.'::disable_plugin_deactivation', 10, 4 );
    if(is_admin()) {
      return $actions;
    }
    // Remove edit link for all
    if ( array_key_exists( 'edit', $actions ) )
      unset( $actions['edit'] );
    // Remove deactivate link for crucial plugins
    if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
        'facebook-open-graph-meta-in-wordpress/fbogmeta.php',
        'wp-pagenavi/wp-pagenavi.php'
      )))
      unset( $actions['deactivate'] );
    return $actions;
  }


  /**
   * Register Events Sidebar for Template
   *
   */
  public static function register_events_sidebar() {
    register_sidebar( array(
      'name'          => __( 'Events Sidebar', 'events' ),
      'id'            => 'events',
      'description'   => __( 'Appears on the Events Page', 'events' ),
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ) );
  }




  /**
   * Check to see if a form exists in Gravity Forms
   *
   */
  public static function show_form($form_name){

    $forms = GFFormsModel::get_forms();
    foreach($forms as &$form){
      if ($form->title == $form_name) {
        gravity_form($form_name, false, false, false, '', false);
      }
    }
    return false;
  }

  /**
   * Conditionals to determine what and when items are displayed for registration
   *
   */
  public static function registration_conditionals(){
    //check to see if the sidebar is active
    if (is_active_sidebar('events')) :
      //check to see if the page is a single event
      if (is_singular('tribe_events')) :

        // set some dates and times for use
        $startdatetime = tribe_get_start_date($post->ID, false, 'U'); // get the start date and time set for the event
        $disableregdatetime = get_field('registration_expiration_date_and_time'); // get the date and time set for registration cutoff
        $disableregoffset = get_date_from_gmt(date('Y-m-d H:i:s', $disableregdatetime), 'U'); // get the registration cutoff time offset based on what is set in wordpres options
        // THIS IS UNUSED CODE //$startdate = date_i18n(get_option('date_format'), $startdatetime, true); //$starttime = date_i18n(get_option('time_format'), $startdatetime, true); //$disabledate = date_i18n(get_option('date_format'), $disableregdatetime, true); //$disabletime = date_i18n(get_option('time_format'), $disableregdatetime, true); //$gmt_offset_hours = get_option('gmt_offset'); //$gmt_offset_seconds = $gmt_offset_hours * -3600; //echo $disableregdatetime + $gmt_offset_seconds; //echo '<br />'.current_time( 'timestamp' );

        echo '<h3>' . get_field('registration_headline') . '</h3>'; // show the registration headline

        if (get_field('enable_online_registration')): //check to see if online registration is enabled
          if (current_time('timestamp') <= $disableregoffset): // check to see if the current time for the website is at or before the registration cutoff
            ?>
            <div id="event-registration-form">
            <?php
            // need to check to see if this function exists - else display "install plugin"
            static::show_form('Event Registration'); // display the event registration form **requires the Events Calendar / Granvity Forms integration plugin.
          else:
            // let the user know that registration time period has lapsed
            echo '<div class="notice">' . get_field('online_registration_expired_message') . '</div>';
          endif;
          ?>
          </div>
        <?php
        else:
          echo '<div class="notice">' . get_field('online_registration_disabled_message') . '</div>';
        endif;
        ?>
      <?php
      endif;

      if (!is_singular('tribe_events')) :
        // only display the events sidebar widgets on event pages pages that are not a single event.
        dynamic_sidebar('events');
      endif;

    endif;
  }
}

$ecgf = new EventsCalendarGravityFormsRegistration();