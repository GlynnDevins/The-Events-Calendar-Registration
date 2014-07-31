<?php
/**
 * Plugin Name: The Events Calendar Gravity Forms Registration
 * Plugin URI:
 * Description: This plugin will integrate The Events Calendar and Gravity Forms for Event Registration
 * Version: 1.2
 * Author: GlynnDevins
 * Author URI: http://www.glynndevins.com
 * License: GPLv2 or later
 */

class EventsCalendarGravityFormsRegistration {

  public static $formTitle = 'Event Registration';

  public function __construct() {

    add_action('admin_notices',     __CLASS__.'::admin_notice');
    add_action('admin_menu',        __CLASS__.'::admin_menu');
    add_action("gform_pre_render",  __CLASS__."::form_render_date_fields", 10, 3);
    add_action('post_row_actions',  __CLASS__.'::post_row_actions');

    register_activation_hook( __FILE__, __CLASS__.'::activate' );

    add_shortcode( 'ecgf_form', __CLASS__.'::short_code_render' );

    include_once( 'acf.php' );

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

  public static function admin_menu() {

    $tribe_settings = TribeSettings::instance();

    // Export entries
    if($_REQUEST['page'] === 'tribe-events-calendar-registration-export' and !is_null($_REQUEST['id'])) {

      $post_id = $_REQUEST['id'];
      $forms = GFFormsModel::get_forms();
      foreach($forms as $form){
        if(strtolower($form->title) == strtolower(self::$formTitle)) {
          $form_id = $form->id;

          $entries = GFAPI::get_entries(
            $form_id,
              array(
              'field_filters' => array(
                array(
                  'key'     => '7',
                  'value'   => $post_id
                )
              )
            ),
            null,
            array('offset' => '0', 'page_size' => '1000')
          );

          header("Content-type: text/csv");
          header("Content-Disposition: attachment; filename=" . sanitize_title_with_dashes($entries[0]['6']) . ".csv");
          header("Pragma: no-cache");
          header("Expires: 0");

          echo $entries[0]['6'] . "\n";
          echo "Date Created, First Name, Last Name, Email, Phone Number, Number of Participants\n";
          foreach($entries as $entry) {
            echo $entry['date_created'] . ',';
            echo $entry['1'] . ',';
            echo $entry['2'] . ',';
            echo $entry['3'] . ',';
            echo $entry['4'] . ',';
            echo $entry['5'] . "\n";
          }

          die();
        }
      }


    }

  }

  /**
   * Display Admin Notice if there are Issues with necessary plugins not being active
   *
   */
  public static function admin_notice() {
    if (self::req_plugins_active() !== false) :
      $issues = self::req_plugins_active();
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
    if(self::req_plugins_active() !== false) {
//      unset($_GET['action']);
      self::admin_notice();
      exit;
    }
    self::generatePages();
    self::generateForm();
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
  public static function render_registration_form($returnOutput = false){
    if($returnOutput) {
      ob_start();
    }

      //check to see if the page is a single event
      if (is_singular('tribe_events')) :

        // set some dates and times for use
        $startdatetime = tribe_get_start_date($post->ID, false, 'U'); // get the start date and time set for the event
        $registrationExpirationEnabled = get_field('enable_registration_expiration'); // get the date and time set for registration cutoff
        $disableregdatetime = get_field('registration_expiration_date_and_time'); // get the date and time set for registration cutoff
        if($disableregdatetime !== false) {
          $disableregoffset = get_date_from_gmt(date('Y-m-d H:i:s', $disableregdatetime), 'U'); // get the registration cutoff time offset based on what is set in wordpress options
        }




        echo '<h3>' . get_field('registration_headline') . '</h3>'; // show the registration headline

        if (get_field('enable_online_registration')): //check to see if online registration is enabled
          if (($registrationExpirationEnabled === false) or ($disableregdatetime == false or (isset($disableregoffset) and time() <= $disableregoffset))): // check to see if the current time for the website is at or before the registration cutoff
            ?>
            <div id="event-registration-form">
            <?php
            // need to check to see if this function exists - else display "install plugin"
            self::show_form('Event Registration'); // display the event registration form **requires the Events Calendar / Gravity Forms integration plugin.
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

    if($returnOutput) {
      return ob_get_clean();
    }

  }

  // Dyamically Generate events page and thank you page
  public static function generatePages() {
    $page = get_page_by_path('events', OBJECT, 'page');
    if(null === $page) {
      $eventsPageID = wp_insert_post(array(
        'name'        => 'events',
        'post_title'  => 'Events',
        'post_status' => 'publish',
        'post_type'   => 'page'
      ));
    }
    if(isset($eventsPageID)) {
      $parent_id = $eventsPageID;
    } else {
      $parent_id = $page->ID;
    }
    $page2 = get_page_by_path('events/thank-you', OBJECT, 'page');
    if(null === $page2) {
      wp_insert_post(array(
        'name'        => 'events/thank-you',
        'post_title'  => 'Thank you',
        'post_status' => 'publish',
        'post_type'   => 'page',
        'post_parent' => $parent_id
      ));
    }
  }
  public static function generateForm() {

    if(class_exists('GFAPI')) {

      $thankyouPage = get_page_by_path('events/thank-you', OBJECT, 'page');

      $form = array(
        'labelPlacement'          => 'top_label',
        'useCurrentUserAsAuthor'  => '1',
        'title'                   => self::$formTitle,
        'descriptionPlacement'    => 'below',
        'button'                  => array(
          'type'  => 'text',
          'text'  => 'Submit'
        ),
        'fields' => array(
          array(
            'id'          => '1',
            'isRequired'  => '1',
            'size'        => 'medium',
            'type'        => 'name',
            'nameFormat'  => 'simple',
            'label'       => 'First Name'
          ),
          array(
            'id'          => '2',
            'isRequired'  => '1',
            'size'        => 'medium',
            'type'        => 'name',
            'nameFormat'  => 'simple',
            'label'       => 'Last Name'
          ),
          array(
            'id'          => '3',
            'isRequired'  => '1',
            'size'        => 'medium',
            'type'        => 'email',
            'label'       => 'Email'
          ),
          array(
            'id'          => '4',
            'isRequired'  => '1',
            'size'        => 'medium',
            'type'        => 'phone',
            'phoneFormat' => 'standard',
            'label'       => 'Phone'
          ),
          array(
            'id'          => '5',
            'isRequired'  => '1',
            'size'        => 'medium',
            'type'        => 'select',
            'label'       => 'Number Attending',
            'choices'     => array(
              array(
                'text'  => '1',
                'value' => '1'
              ),
              array(
                'text'  => '2',
                'value' => '2'
              ),
              array(
                'text'  => '3',
                'value' => '3'
              ),
              array(
                'text'  => '4',
                'value' => '4'
              ),
              array(
                'text'  => '5',
                'value' => '5'
              ),
              array(
                'text'  => '6',
                'value' => '6'
              ),
              array(
                'text'  => '7',
                'value' => '7'
              ),
              array(
                'text'  => '8',
                'value' => '8'
              )
            )
          ),
          array(
            'id'          => '6',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{embed_post:post_title}',
            'label'       => 'Event Name'
          ),
          array(
            'id'          => '7',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{embed_post:ID}',
            'label'       => 'Event Post ID'
          ),
          array(
            'id'          => '8',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{custom_field:_EventStartDate}',
            'label'       => 'Event Start Date'
          ),
          array(
            'id'          => '9',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{custom_field:_EventEndDate}',
            'label'       => 'Event End Date'
          ),
          array(
            'id'          => '10',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{custom_field:_EventRecurrence}',
            'label'       => 'Event Recurrence'
          ),
          array(
            'id'          => '11',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '{custom_field:_EventAllDay}',
            'label'       => 'All Day Event'
          ),
          array(
            'id'          => '90',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '',
            'label'       => 'Event Formatted Date'
          ),
          array(
            'id'          => '91',
            'size'        => 'medium',
            'type'        => 'hidden',
            'defaultValue'=> '',
            'label'       => 'Event Formatted Time'
          )
        ),
        'cssClass'        => 'contact-form-gfec-form',
        'enableHoneypot'  => '1',
        'confirmations'    => array(
          array(
            'id'          => '5316355c6c8c1',
            'isDefault'   => '1',
            'type'        => 'page',
            'name'        => 'Default Confirmation',
            'pageId'      => $thankyouPage->ID,
            'queryString' => 'eventID={post_id:7}'
          )
        ),
        'notifications'    => array(
          array(
            'id'      => '53163750d13d3',
            'to'      => '3',
            'name'    => 'RSVP',
            'event'   => 'form_submission',
            'toType'  => 'field',
            'subject' => 'Thank You for Registering for (Event Name:5} - {Event Date:6}',
            'message' => '{all_fields}',
            'from'    => '{admin_email}',
            'fromName'=> get_bloginfo('name')
          )
        )
      );

      if(RGFormsModel::is_unique_title($form['title'])) {

        $form_id = RGFormsModel::insert_form($form['title']);

        $form["id"] = $form_id;

        GFFormsModel::trim_form_meta_values($form);

        if(isset($form['confirmations'])) {
          $form['confirmations'] = GFExport::set_property_as_key($form['confirmations'], 'id');
          $form['confirmations'] = GFFormsModel::trim_conditional_logic_values($form['confirmations'], $form);
          GFFormsModel::update_form_meta($form_id, $form['confirmations'], 'confirmations');
          unset($form['confirmations']);
        }

        if(isset($form['notifications'])) {
          $form['notifications'] = GFExport::set_property_as_key($form['notifications'], 'id');
          $form['notifications'] = GFFormsModel::trim_conditional_logic_values($form['notifications'], $form);
          GFFormsModel::update_form_meta($form_id, $form['notifications'], 'notifications');
          unset($form['notifications']);
        }

        RGFormsModel::update_form_meta($form_id, $form);

      }


    }

  }

  public static function post_row_actions($arg= '') {
    $asdf = $arg;
    global $wp_the_query;
    if($wp_the_query->query_vars['post_type'] == "tribe_events") {
      global $post;
      $arg[] = '<a href="' . admin_url('edit.php?post_type=tribe_events&page=tribe-events-calendar-registration-export&id=' . $post->ID) . '" title="Export Entries">Export Entries</a>';
    }
    return $arg;
  }

  public static function form_render_date_fields($form = '', $arg2 = '') {

    if( ( $form['title'] !== 'Event Registration' ) ) {
      return $form;
    }

    foreach($form['fields'] as &$field){

      $field_ids = array("90", "91");
      if( ! in_array($field['id'], $field_ids) ) {
        continue;
      }

      global $post;

      if($field['id'] === "90") {
        // Event Formatted Date
        $field['defaultValue'] = tribe_get_start_date($post, false, 'l, F j, Y');
      }
      if($field['id'] === "91") {
        // Event Formatted Time
        $field['defaultValue'] = tribe_get_start_date($post, false, 'g:i a ') . ' ' . tribe_get_start_date($post, false, 'T');
      }

    }
    return $form;
  }

  function short_code_render() {
    return self::render_registration_form(true);
  }


}



class ECGF_Widget extends WP_Widget {

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {

    parent::__construct(
      'widget_ecgf',
      __('Events Registration', 'text_domain'),
      array( 'description' => __( 'Events Registration Widget', 'text_domain' ), )
    );

  }

  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    // outputs the content of the widget
    extract($args);
    ?>
    <?php echo $before_widget; ?>
    <?php
    echo $before_title . $after_title;
    ?>
    <?=EventsCalendarGravityFormsRegistration::render_registration_form(); ?>
    <?php echo $after_widget; ?>
  <?php
  }

  /**
   * Ouputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    // outputs the options form on admin
  }

  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   */
  public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved
  }
}


function ECGF_Widget_init() {
  register_widget( 'ECGF_Widget' );
}

add_action( 'widgets_init', 'ECGF_Widget_init');





$ecgf = new EventsCalendarGravityFormsRegistration();
