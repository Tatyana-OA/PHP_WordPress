<?php
/**
 * Plugin Name: My Onboarding Plugin
 * Description: This plugin is part of Day 5 WordPress Onboarding at DevriX
 * Author: Tanya_Devrix
 * Version: 1.0.0
 * Text Domain: my-onboarding-plugin
 */
 
 // used for url path protection
 if(!defined('ABSPATH')) {
     exit;
 }
 

 //adding the plugin if the option is set to "enable"
 if (get_option('onboarding')==1) {
    //using wp_head hook and function onboard_plugin
  add_action("wp_head", "onboard_plugin");
 
  //displaying text before header
  function onboard_plugin() {
  $prependStr = 'Onboarding Filter: by Tanya@Devrix';
  echo $prependStr;
 
  //adding user settings nav element using the wp_nav_meni_items and a function which takes all nav items as params
  add_filter('wp_nav_menu_items','logged_user_add_menu');
 
  function logged_user_add_menu($items) {
      if (!is_user_logged_in()) {
          return $items;
      }
        else if (is_user_logged_in()) {
        $profilePageLink = admin_url( 'profile.php' );
        return $items .= '<li><a href="'.$profilePageLink.'" class = "menu-item" >Profile Settings</a></li>';
 
 
       };
  }
 
  } 
 
   // adding user update action - email to admin
   add_action( 'profile_update', 'profile_update_notify'); 
 
   function profile_update_notify() {
            $myUser = get_currentuserinfo();
            var_dump($myUser->user_login);
            wp_mail(
                'tasenova@devrix.com', //to
                'User updated profile', //subject
                'Greetings, Admin! User profile of ' . $myUser->user_login . ' has been modified.', //body
                array('Content-Type: text/html; charset=UTF-8') //headers
            );   
   }
  //adding a hidden div after first paragraph
  add_filter('the_content', 'add_html_tags');
  function add_html_tags($content) { 
      $content = str_replace('</p>', '</p><div style="display:none;">Nice lil div</div>', $content);
     echo $content;
  };
  //adding a paragraph after content and adding a div to it
  add_filter('the_content', 'add_new_para');
  function add_new_para($content) {
    $content= $content.'<p>An added paragraph </p>';
    $content = str_replace('<p>An added paragraph </p>', '<p> An added paragraph containing a hidden DIV <div style="display:none;">Nice lil div</div></p>', $content);
 
    echo $content;
 
  };
 }
 

// adding a submenu/menu to the Settings menu using admin_menu hook
  add_action('admin_menu', 'my_new_submenu');
  function my_new_submenu(){
      // (slug of main menu, page title, title, permissions, slug, action controller)
     // add_submenu_page('options-general.php','My Onboarding','Onboarding Filters','administrator', 'my-onboarding-filters', 'onboarding_filter_enabling' );
     add_menu_page('My Onboarding','My Onboarding','administrator','my-onboarding-filters', 'onboarding_filter_enabling', 'dashicons-filter',4 );
    }
  function onboarding_filter_enabling() {
    $state = get_option('onboarding');
    $checked = ($state ==1) ? "checked" : " ";
 
      $myForm = '<h1>Filters Enable/Disable</h1> 
      <label> Check to Enable Filters
      <input type="checkbox" name="onboarding" id="enableFilters" '.$checked; '>
      </label>';
      echo $myForm;
 
 
 
  }
 
?>
 
<?php
 
//script loading
function add_onboarding_script() {
 
 
wp_enqueue_script('onboarding_script', plugins_url('onboarding_script.js', __FILE__), array('jquery'),false, true);
 
wp_localize_script( 'onboarding_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )  ) );
 
 
}
 
add_action( 'admin_enqueue_scripts', 'add_onboarding_script' );  
add_action( 'wp_ajax_filter_value', 'filter_value' );
 
 
function filter_value() {
 
    $currentValue =  $_POST['filterValue'];
   $savedOption = '';
   if ($currentValue=='true') {
       $savedOption = 1;
   } else {
    $savedOption = 0;
   }
 
    update_option('onboarding',$savedOption);
    wp_die();
 
}
 
?>
