<?php
/*
Plugin Name: Snappy List Builder Practice
Plugin URI: localhost
Description: The ultimate email list building plugin for WordPress. Capture new subscribers. Reward subscribers with a custom download upon opt-in. Build unlimited lists. Import and export subscribers easily with .csv
Version: 1.0
Author: Ted
Author URI: https://www.linkedin.com/in/edgar-daniel-moso-340814100/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/* 0. TABLE OF CONTENTS */

/*

1. HOOKS
  1.1 - registers all our custom shortcodes
  1.2 - register custom admin column headers
  1.3 - register custom admin column data

2. SHORTCODES
  2.1 - sl_register_shortcodes()
  2.2 - sl_form_shortcode()

3. FILTERS
  3.1 - hm_sl_subscriber_column_headers()
  3.2 - hm_sl_subscriber_column_data()
  3.2.2 - hm_sl_register_custom_admin_titles()
  3.2.3 - hm_sl_custom_admin_titles()
  3.3 - hm_sl_list_column_headers()
  3.4 - hm_sl_list_column_data()

4. EXTERNAL SCRIPTS

5. ACTIONS

6. HELPERS

7. CUSTOM POST TYPES

8. ADMIN PAGES

9. SETTINGS

10. MISC.

*/

/* 1. HOOKS */

// 1.1
// hint: registers all our custom shortcodes on init
// This hook is going to inject our register shortcode into a wordpress event (init)
// So when wordpress initiates it triggers an event called 'init' and when 'init'
// happens, we're going to say 'inject this code and run it'
// add_action( string $tag/name of event, callback $function_to_add, int $priority, int $accepted_args)
add_action('init', 'hm_sl_register_shortcodes');

// 1.2
// hint: register custom admin column headers
// manage_edit-{$post_type}_columns filter
// add_filter( 'manage_edit-*post-type*_columns', '*function you want to run*' )
// The data we're manipulating here is the default column header settings for this particular post_type (hm_sl_subscriber)
// One accepted argument: $columns
add_filter( 'manage_edit-hm_sl_subscriber_columns', 'hm_sl_subscriber_column_headers' );
add_filter( 'manage_edit-hm_sl_list_columns', 'hm_sl_list_column_headers' );

// 1.3
// hint: register custom admin column data
// manage_{$post_type}_posts_custom_column filter
// add_filter( 'manage_*post-type*_posts_custom_column', '' )
// Two accepted arguments: $column, $post_id
add_filter( 'manage_hm_sl_subscriber_posts_custom_column', 'hm_sl_subscriber_column_data', 1, 2 );
// admin_head-edit.php, this action hook is triggered within the <head></head>
// section of a specific plugin-generated page.
add_action( 'admin_head-edit.php', 'hm_sl_register_custom_admin_titles' );
add_filter( 'manage_hm_sl_list_posts_custom_column', 'hm_sl_list_column_data', 1, 2 );

/* 2. SHORTCODES */
// 2.1
// hint: registers all our custom shortcodes
function hm_sl_register_shortcodes() {

  add_shortcode('hm_sl_form', 'hm_sl_form_shortcode');

}

// 2.2
// hint: returns a html string for a email capture form
// $content="" - if none are provided on the second parameter, then it would
// just return empty string. '=""' will just help avoid errors incase you try
// to manipulate content later on.
function hm_sl_form_shortcode( $args, $content="" ) {

  // get the list id
  $list_id = 0;
  if ( isset($args['id']) ) $list_id = (int)$args['id'];

  // setup our output variable - the form html
  // **note** Wordpress has a built in Ajax handler (/wp-admin/admin-ajax.php)
  // and what we do is submit all of our Ajax posts to this particular file.
  // And then we tell what action you want to run (basically, your function) when
  // run this file.
  $output = '

  <div class="hm-sl">

  <form id="hm-sl-form" name="hm_sl_form" class="hm-sl-form" action="/wp-admin/admin-ajax.php?action=hm_sl_save_subscription" method="post">

  <input type="hidden" name="hm_sl_target_list" value"' . $list_id . '">

  <p class="hm-sl-input-container">
  <label>Your Name</label><br>
  <input type="text" name="hm_sl_fname" placeholder="First Name"/>
  <input type="text" name="hm_sl_lname" placeholder="Last Name"/>
  </p>

  <p class="hm-sl-input-container">
  <label>Your Email</label><br>
  <input type="email" name="hm_sl_email" placeholder="ex. you@email.com"/>
  </p>';

  /*
  including content in our form html if content is passed into the
  function.

  if the strlen() return a 1 or greater it results to true, but if it
  return a 0, then false.
  */
  if ( strlen($content) ):

    // wpautop() automatically wraps content with an html p tag
    $output .= '<div class="hm-sl-content">' . wpautop($content) . '</div>';

  endif;

  // completing our form html
  $output .= '<p class="hm-sl-input-container">
  <input type="submit" name="hm_sl_submit" value="Sign Me Up!"/>
  </p>

  </form>

  </div>

  ';

  return $output;
}

/* 3. FILTERS */

// 3.1
// $columns argument is an associate of array that's going to contain the information
// about the column headers that are going to be displayed
// or in other words
// $columns contains the default settings
function hm_sl_subscriber_column_headers( $columns ) {

  // $columns (default settings) is being overridden here
  // creating custom column header data
  $columns = array(
    'cb' => '<input type="checkbox"', // 'cb' stands for 'checkbox'
    'title' => __('Subscriber Name'),
    'email' => __('Email Address'),
  );

  // returning new columns
  return $columns;

}

// 3.2
function hm_sl_subscriber_column_data( $column, $post_id ) {

  // setup our return next
  $output = '';

  switch( $column ) {
    case 'title':
      // get the custom name data
      $fname = get_field( 'hm_sl_fname', $post_id );
      $lname = get_field( 'hm_sl_lname', $post_id );
      $output .= $fname . ' ' . $lname;
      break;
    case 'email':
      // get the custom email data
      $email = get_field( 'hm_sl_email', $post_id );
      $output .= $email;
      break;
  }

  // echo the output
  echo $output;

}

// 3.2.2
// hint: registers special custom admin title columns
function hm_sl_register_custom_admin_titles() {
  add_filter( 'the_title', 'hm_sl_custom_admin_titles', 99, 2 );
}

// 3.2.3
// hint: handles custom admin title "title" column data for post types without titles
function hm_sl_custom_admin_titles( $title, $post_id ) {

  global $post;

  $output = $title;

  if ( isset( $post->post_type ) ) :

    switch( $post->post_type ) {
      case 'hm_sl_subscriber':
        $fname = get_field( 'hm_sl_fname', $post_id );
        $lname = get_field( 'hm_sl_lname', $post_id );
        $output = $fname . ' ' . $lname; // not appending, but replacing the current title (Auto Draft)
        break;
    }

  endif;

  return $output;

}

// 3.3
// hint: funtion to hook to manage_edit_columns to change column headers on
// 'hm_sl_list' post type
function hm_sl_list_column_headers( $columns ) {

  // $columns (default settings) is being overridden here
  // creating custom column header data
  $columns = array(
    'cb' => '<input type="checkbox"', // 'cb' stands for 'checkbox'
    'title' => __('List Name'),
  );

  // returning new columns
  return $columns;

}

// 3.4
// hint: function to hook to filter, manage_custom_column to change
function hm_sl_list_column_data( $column, $post_id ) {

  // setup our return next
  $output = '';

  switch( $column ) {
    case 'example':
      // get the custom name data
      // $fname = get_field( 'hm_sl_fname', $post_id );
      // $lname = get_field( 'hm_sl_lname', $post_id );
      // $output .= $fname . ' ' . $lname;
      break;
  }

  // echo the output
  echo $output;

}


/* 4. EXTERNAL SCRIPTS -------------------------------------------------------*/

/* 5. ACTIONS ----------------------------------------------------------------*/

// 5.1
// hint: saves subscription data to an existing or new subscriber
function hm_sl_save_subscription() {

  // setup default result data
  $result = array(
    'status' => 0,
    'message' => 'Subscription was not saved. ',
  );

  // array for storing errors
  $errors = array();

  try {

    // get list_id
    $list_id = (int)$_POST['hm-sl-target-list'];

    // prepare subscriber data
    $subscriber_data = array(
      // esc_attr() - escape attribute function
      // this function is built into Wordpress to clean up the data in there and
      // to make sure that it's safe.
      // Text passed to esc_attr() is stripped of invalid or special characters
      // before output.
      'fname' => esc_attr( $_POST['hm_sl_fname'] ),
      'lname' => esc_attr( $_POST['hm_sl_lname'] ),
      'email' => esc_attr( $_POST['hm_sl_email'] ),
    );

    // attempt to create/save subscriber
    $subscriber_id = hm_sl_save_subscriber( $subscriber_data );

    // IF subscriber was saved successfully $subscriber_id will be greater than 0
    if ( $subscriber_id ):

      // IF subscriber already has this subscription
      // helper function
      if ( hm_sl_subscriber_has_subscription( $subscriber_id, $list_id ) ):

        // get list object
        $list = get_post( $list_id );

        // return detailed error
        $result['message'] .= esc_attr( $subscriber_data['email'] . ' is already subscribed to ' . $list-> post_title . '.');

      else:

        // save new subscriptioin
        $subscription_saved = hm_sl_add_subscription( $subscriber_id, $list_id );

        // IF subscription was saved successfully
        if ( $subscription_saved ):

          // subscription saved!
          $result['status'] = 1;
          $result['message'] = 'Subscription saved';

        endif;

      endif;

    endif;
  } catch ( Exception $e ) {

    // a php error occurred
    $result['error'] = 'Caught exception: ' . $e->getMessage();

  }

  // return result as json
  // we're returning it as a json string because we're going to be using
  // javascript to this ajax post
  hm_sl_return_json( $result );
}

// 5.2
// hint: creates a new subscriber or update an existing one
function hm_sl_save_subscriber( $subscriber_data ) {

  // setup default subscriber id
  // 0 means the subscriber was not saved
  $subscriber_id = 0;

  try {

    // the variable will hold a number to be used
    $subscriber_id = hm_sl_get_subscriber_id( $subscriber_data['email'] );

    // IF the subscriber does not already exists...
    if ( !$subscriber_id ):

      // add new subscriber to database
      // wp_insert_post( array $postarr, bool $wp_error = false )
      // - Wordpress' built-in function for insert or update a post
      $subscriber_id = wp_insert_post(
        array(
          'post_type' => 'hm_sl_subscriber',
          'post_title' => $subscriber_data['fname'] . . $subscriber_data['lname'],
          'post_status' => 'publish',
        ),
        true
      );

    endif;

    // add/update custom meta data
    update_field( hm_sl_get_acf_key( 'hm_sl_fname' ), $subscriber_data['fname'], $subscriber_id );
    update_field( hm_sl_get_acf_key( 'hm_sl_lname' ), $subscriber_data['lname'], $subscriber_id );
    update_field( hm_sl_get_acf_key( 'hm_sl_email' ), $subscriber_data['email'], $subscriber_id );
  } catch ( Exception $e ) {

    // a php error occurred

  }

  // return subscriber_id
  return $subscriber_id;
}

/*----------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------*/

/* 6. HELPERS */

// 6.1
// hint: returns true or false
function hm_sl_subscriber_has_subscription( $subscriber_id, $list_id ) {

  // setup default return value
  $has_subscription = false;

  // get subscriber
  $subscriber = get_post( $subscriber_id );

  // get subscriptions
  // function hm_sl_get_subscriptions() returns an array
  $subscriptions = hm_sl_get_subscriptions( $subscriber_id );

  // check subscriptions for $list_id
  if ( in_array( $list_id, $subscriptions ) ):

    // found the $list_id in $subscriptions
    // this subscribers is already subscribed to this list
    $has_subscription = true;

  else:

    // did not find $list_id in $subscriptions
    // this subscriber is not yet subscribed to this list

  endif;

  return $has_subscription;

}

// 6.2
// hint: retrieves a subscriber_id from an email address
function hm_sl_get_subscriber_id( $email ) {

  $subscriber_id = 0;

  try {

    // check if subscriber already exists
    $subscriber_query = new WP_Query(
      array(
        'post_type' => 'hm_sl_subscriber',
        'posts_per_page' => '1',
        'meta_key' => 'hm_sl_email',
        'meta_query' => array(
          array(
            'key' => 'hm_sl_email',
            'value' => $email, // or whatever it is you're using here
            'compare' => '='
          ),
        )
      )
    );

  } catch ( Exception $e ) {

    // a php error occurred

  }

  return (int)$subscriber_id;
}

/* 7. CUSTOM POST TYPES */

/* 8. ADMIN PAGES */

/* 9. SETTINGS */

/* 10. MISC. */
