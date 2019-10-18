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
// add_action('init', 'slb_register_shortcodes');
/* 2. SHORTCODES */
// 2.1
// hint: registers all our custom shortcodes
function slb_register_shortcodes() {

  add_shortcode('slb_form', 'slb_form_shortcode');

}

// 2.2
// hint: returns a html string for a email capture form
// $content="" - if none are provided on the second parameter, then it would
// just return empty string. '=""' will just help avoid errors incase you try
// to manipulate content later on.
function slb_form_shortcode( $args, $content="" ) {
  // setup our output variable - the form html
  $output = '

  <div class="slb">

  <form id="slb-form" name="slb_form" class="slb-form" method="post">

  <p class="slb-input-container">
  <label>Your Name</label><br>
  <input type="text" name="slb_fname" placeholder="First Name"/>
  <input type="text" name="slb_lname" placeholder="Last Name"/>
  </p>

  <p class="slb-input-container">
  <label>Your Email</label><br>
  <input type="email" name="slb_email" placeholder="ex. you@email.com"/>
  </p>';

  /*
  including content in our form html if content is passed into the
  function.

  if the strlen() return a 1 or greater it results to true, but if it
  return a 0, then false.
  */
  if ( strlen($content) ):

    // wpautop() automatically wraps content with an html p tag
    $output .= '<div class="sl-content">' . wpautop($content) . '</div>';

  endif;

  // completing our form html
  $output .= '<p class="slb-input-container">
  <input type="submit" name="slb_submit" value="Sign Me Up!"/>
  </p>

  </form>

  </div>

  ';

  return $output;
}

/* 3. FILTERS */

/* 4. EXTERNAL SCRIPTS */

/* 5. ACTIONS */

/* 6. HELPERS */

/* 7. CUSTOM POST TYPES */

/* 8. ADMIN PAGES */

/* 9. SETTINGS */

/* 10. MISC. */

function slb_add_subscriber_metaboxes( $post ) {

  // add_meta_box() - built in wordpress function for adding meta boxes
  // takes a few 'arguments'
  add_meta_box(
    'slb-subscriber-details', // $id (string)
    'Subscriber Details', // $title (string)
    'slb_subscriber_metabox', // $callback function (string)
    'slb_subscriber', // $screen or post type prefix that you created
    'normal', // $context
    'default', // $priority
  );

}
// add_action('add_meta_boxes_{custom_post_type_slug}', '{function_name}')
// hooking to a unique wordpress action that wordpress creates for every custom
// post type
add_action( 'add_meta_boxes_slb_subscriber', 'slb_add_subscriber_metaboxes' );

function slb_subscriber_metabox() {

  global $post;

  $post_id = $post->ID;

  // wp_nonce_field() function generates a unique id.
  // It outputs two hidden input fields, and one of them contains a unique id.
  // Doing it this way you know that you're not getting data posted from malicious script
  // wp_nonce_field( mixed $action, string $name, bool $referer, bool $echo );
  // First parameter: basename( __FILE__ ) - get the page that we're in. __FILE__ -> variable that pulls in the current file that we're on.
  // Second parameter: 'slb_subscriber_nonce'
  // Third parameter: optional
  // Fourth parameter: optional
  wp_nonce_field( basename( __FILE__ ), 'slb_subscriber_nonce' );

  // get_post_meta( int $post_id, string $key, bool $single )
  $first_name = ( !empty( get_post_meta( $post_id, 'slb_first_name', true ) ) ) ? get_post_meta( $post_id, 'slb_first_name', true ) : '';
  $last_name = ( !empty( get_post_meta( $post_id, 'slb_last_name', true ) ) ) ? get_post_meta( $post_id, 'slb_last_name', true ) : '';
  $email = ( !empty( get_post_meta( $post_id, 'slb_email', true ) ) ) ? get_post_meta( $post_id, 'slb_email', true ) : '';
  $lists = ( !empty( get_post_meta( $post_id, 'slb_list' ) ) ) ? get_post_meta( $post_id, 'slb_list' ) : [];

  // echo '<br>' . $first_name;
  // echo '<br>' . $last_name;
  // echo '<br>' . $email . '<br>';
  // echo '<br>' . var_dump($lists);
  // exit;

  ?>

  <style>
  .slb-field-row {
    display: flex;
    flex-flow: row nowrap;
    flex: 1 1;
  }
  .slb-field-container {
    position: relative;
    flex: 1 1;
    margin-right: 1em;
  }
  .slb-field-container label {
    font-weight: bold;
  }
  .slb-field-container label span {
    color: red;
  }
  .slb-field-container ul {
    list-style: none;
    margin-top: 0;
  }
  .slb-field-container ul label {
    font-weight: normal;
  }
  </style>

  <div class="slb-field-row">
    <div class="slb-field-container">
      <p>
        <label>First Name <span>*</span></label>
        <input type="text" name="slb_first_name" required="require" class="widefat" value="<?php echo $first_name ?>" />
      </p>
    </div>
    <div class="slb-field-container">
      <p>
        <label>Last Name <span>*</span></label>
        <input type="text" name="slb_last_name" required="require" class="widefat" value="<?php echo $last_name ?>" />
      </p>
    </div>
  </div>
  <div class="slb-field-row">
    <div class="slb-field-container">
      <p>
        <label>Email <span>*</span></label>
        <input type="email" name="slb_email" required="require" class="widefat" value="<?php echo $email ?>" />
      </p>
    </div>
  </div>
  <div class="slb-field-row">
    <div class="slb-field-container">
      <label>Lists</label>
      <ul>
        <?php
          // pull wordpress database object by calling 'global' object type
          global $wpdb;

          // you can use php code as long as you wrap it around with {}
          /*
            $wpdb->posts will dynamically pull in the Posts table
          */
          $list_query = $wpdb->get_results("SELECT ID,post_title FROM {$wpdb->posts} WHERE post_type = 'slb_list' AND post_status IN ( 'draft', 'publish' )");

          if ( !is_null($list_query) ) {
            foreach ($list_query as $list) {
              // code...

              // variable ($checked) to check each list
              // in_array( mixed $needle, array $haystack ) - needle in a haystack. Get it?
              $checked = ( in_array( $list->ID, $lists ) ) ? 'checked="checked"' : '';

              echo '<li><label><input type="checkbox" name="slb_list[]" value="' . $list->ID . '" ' . $checked . '/>' . $list->post_title . '</label></</li>';
            }
          }

        ?>
        <!--
        php will treat the value of the name attribute as an array.
        So, it's going to look for a field with the same name and it's going
        to add each value that's checked into that array
      -->
      <!-- <li><label><input type="checkbox" name="slb_list[]" value="1"/> List 1</label></</li>
      <li><label><input type="checkbox" name="slb_list[]" value="2"/> List 2</label></</li>
      <li><label><input type="checkbox" name="slb_list[]" value="3"/> List 3</label></</li> -->
    </ul>
  </div>
</div>
<?php
}

function slb_save_slb_subscriber_meta( $post_id, $post ) {

  // !wp_verify_nonce( string $nonce, mixed $action )

  // Verify nonce for security reasons
  if ( !isset($_POST['slb_subscriber_nonce']) || !wp_verify_nonce( $_POST['slb_subscriber_nonce'], basename( __FILE__ ) ) ) {
    return $post_id;
  }

  // get the post type object
  $post_type = get_post_type_object( $post->post_type );

  // check if te current user has permission to edit the post
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
    return $post_id;
  }

  // Get the posted data and sanitize it
  $first_name = ( isset($_POST['slb_first_name'])) ? sanitize_text_field( $_POST['slb_first_name'] ) : '';
  $last_name = ( isset($_POST['slb_last_name'])) ? sanitize_text_field( $_POST['slb_last_name'] ) : '';
  $email = ( isset($_POST['slb_email'])) ? sanitize_text_field( $_POST['slb_email'] ) : '';
  // this one is going to be a little bit different because you're getting an array of values
  // emptry array could either be: [] or array();
  $lists = ( isset($_POST['slb_list']) && is_array($_POST['slb_list']) ) ? (array) $_POST['slb_list'] : [];

  // echo '<div class="test-results">';
  // echo '<br>' . $first_name;
  // echo '<br>' . $last_name;
  // echo '<br>' . $email . '<br>';
  // echo '<br>' . var_dump($lists);
  // echo '</div>';
  // exit;

  // update post meta
  // update_post_meta( int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value )
  //  --- int $post_id (required)
  //  --- string $meta_key (required)
  //  --- mixed $meta_value (required)
  update_post_meta($post_id, 'slb_first_name', $first_name );
  update_post_meta($post_id, 'slb_last_name', $last_name );
  update_post_meta($post_id, 'slb_email', $email );
  // update_post_meta($post_id, 'slb_first_name', $first_name );

  // delete the existing list meta for this post
  // delete_post_meta( int $post_id, string $meta_key, mixed $meta_value )
  delete_post_meta( $post_id, 'slb_list' );

  // add new list meta
  // 'if' statment to check if the $lists is empty
  if ( !empty($lists) ) {
    foreach( $lists as $index => $list_id ) {

      // add list relational meta value
      // add_post_meta( int $post_id, string $meta_key, mixed $meta_value, bool $unique )
      add_post_meta( $post_id, 'slb_list', $list_id, false ); // NOT unique meta key

    }
  }

}
add_action( 'save_post', 'slb_save_slb_subscriber_meta', 10, 2 );

function slb_edit_post_change_title() {

  global $post;

  if( $post->post_type == 'slb_subscriber' ) {

    add_filter(
      'the_title',
      'slb_subscriber_title',
      100,
      2
    );

  }

}
add_action( 'admin_head-edit.php', 'slb_edit_post_change_title' );

// this function is being called inside slb_edit_post_change_title function
function slb_subscriber_title( $title, $post_id ) {

  $title = get_post_meta( $post_id, 'slb_first_name', true ) . ' ' . get_post_meta( $post_id, 'slb_last_name', true );

  return $title;

}
