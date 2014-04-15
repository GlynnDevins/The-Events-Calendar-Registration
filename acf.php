<?php
/**
 * Created by PhpStorm.
 * User: skohlmeyer
 * Date: 4/14/14
 * Time: 4:53 PM
 */


if(function_exists("register_field_group"))
{
  register_field_group(array (
    'id' => 'acf_events-options',
    'title' => 'Events Options',
    'fields' => array (
      array (
        'key' => 'field_53179bc291768',
        'label' => 'Enable Online Registration',
        'name' => 'enable_online_registration',
        'type' => 'true_false',
        'instructions' => 'This will display the RSVP Form on the event detail page.',
        'message' => '',
        'default_value' => 1,
      ),
      array (
        'key' => 'field_5318b7275be56',
        'label' => 'Online Registration Disabled Message',
        'name' => 'online_registration_disabled_message',
        'type' => 'text',
        'instructions' => 'If online registration is disabled, please enter a message to display.',
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_53179bc291768',
              'operator' => '!=',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'default_value' => 'Online Registration is not available for this event.',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_5317a26898ac9',
        'label' => 'Registration Headline',
        'name' => 'registration_headline',
        'type' => 'text',
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_53179bc291768',
              'operator' => '==',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'default_value' => 'Event Registration',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_5329ef8d99c88',
        'label' => 'Enable Registration Expiration',
        'name' => 'enable_registration_expiration',
        'type' => 'true_false',
        'instructions' => 'To enable a registration expiration / cutoff date, check this box.',
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_53179bc291768',
              'operator' => '==',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'message' => '',
        'default_value' => 0,
      ),
      array (
        'key' => 'field_5318afc263f1c',
        'label' => 'Registration Expiration Date and Time',
        'name' => 'registration_expiration_date_and_time',
        'type' => 'date_time_picker',
        'instructions' => 'Please select the date and time in which you wish to end event registration.

	<strong><em>** Important - this uses the timezone as set in the website to disallow event registration.</em></strong>',
        'required' => 1,
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5329ef8d99c88',
              'operator' => '==',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'show_date' => 'true',
        'date_format' => 'mm/dd/yy',
        'time_format' => 'h:mm tt',
        'show_week_number' => 'false',
        'picker' => 'slider',
        'save_as_timestamp' => 'true',
        'get_as_timestamp' => 'true',
      ),
      array (
        'key' => 'field_53299fe02d250',
        'label' => 'Online Registration Expired Message',
        'name' => 'online_registration_expired_message',
        'type' => 'textarea',
        'instructions' => 'Please input the message you would like displayed when online registration has expired.',
        'required' => 1,
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5329ef8d99c88',
              'operator' => '==',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'default_value' => 'Online registration has expired.',
        'placeholder' => '',
        'maxlength' => '',
        'formatting' => 'br',
        'rows' => '',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'tribe_events',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'side',
      'layout' => 'default',
      'hide_on_screen' => array (
        0 => 'custom_fields',
        1 => 'discussion',
        2 => 'comments',
        3 => 'revisions',
        4 => 'slug',
        5 => 'author',
        6 => 'format',
        7 => 'categories',
        8 => 'tags',
        9 => 'send-trackbacks',
      ),
    ),
    'menu_order' => 0,
  ));
}
