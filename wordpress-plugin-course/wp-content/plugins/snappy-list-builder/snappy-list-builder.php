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

2. SHORTCODES
2.1 - slb_register_shortcodes()
2.2 - slb_form_shortcode()

3. FILTERS

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

// 1.3
// hint: register custom admin column data
// manage_{$post_type}_posts_custom_column filter
// add_filter( 'manage_*post-type*_posts_custom_column', '' )
// Two accepted arguments: $column, $post_id
add_filter( 'manage_hm_sl_subscriber_posts_custom_column', 'hm_sl_subscriber_column_data', 1, 2 );

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
  // setup our output variable - the form html
  $output = '

  <div class="hm-sl">

  <form id="hm-sl-form" name="hm_sl_form" class="hm-sl-form" method="post">

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

/* 4. EXTERNAL SCRIPTS */

/* 5. ACTIONS */

/* 6. HELPERS */

/* 7. CUSTOM POST TYPES */

/* 8. ADMIN PAGES */

/* 9. SETTINGS */

/* 10. MISC. */
