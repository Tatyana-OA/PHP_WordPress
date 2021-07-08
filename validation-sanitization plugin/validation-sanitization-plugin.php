<?php
/**
 * Plugin Name: Validation-Sanitization Day 7 WP Onboarding
 * Description: This plugin is part of Day 7 WordPress Onboarding at DevriX
 * Author: Tanya_Devrix
 * Version: 1.0.0
 * Text Domain: validation-sanitization-plugin
 */
 
 // used for url path protection
 if(!defined('ABSPATH')) {
     exit;
 }
//script loading
function add_plugin_script() {
 
 
    wp_enqueue_script('validation-sanitization-plugin', plugins_url('validation-sanitization-plugin.js', __FILE__), array('jquery'),false, true);
    
    wp_localize_script( 'validation-sanitization-plugin', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
       
     
    }
     //enqueuing ONLY to admin environment
    add_action( 'admin_enqueue_scripts', 'add_plugin_script' );  

   // adding a submenu/menu to the Settings menu using admin_menu hook
    add_action('admin_menu', 'my_links_menu');
   function my_links_menu(){
      // (slug of main menu, page title, title, permissions, slug, action controller)

     add_menu_page('Links Plugin','Links Plugin','administrator','links-plugin', 'my_links_plugin', 'dashicons-admin-links',4 );
    }
  
    //function which renders the html fields for submission and selection of duration
    function my_links_plugin() {
        //getting transient if it exists
        $webData = get_transient('retrieved_web_data');
        ob_start();
        ?>
        <div class="plugin-body" style="margin-top: 20px;">
             <h1> Enter a URL to see its content!</h1>
            <input type="text" id="URL" name="URL" />
            <input type="submit" id="submit" name="submit" value="SUBMIT" />
            <div class='caching-duration'>
            <label>I want my URL cached for a/an:</label>
                <select name="duration" id="duration">
                <option value="hour">hour</option>
                <option value="day">day</option>
                <option value="week">week</option>
                <option value="month">month</option>
                </select>
            </div>
            <div class="retrieved_data" style="width:1300px; margin-top:50px;"> <?php echo get_transient('retrieved_web_data') ?> 
            </div>

        </div>
        <?php
    
        //echo and stop buffering hmtl
        echo ob_get_clean();
 
    }

      // function that takes care of AJAX
    add_action( 'wp_ajax_link_submission', 'link_submission' );

    function link_submission() {
        
        $cleaned = sanitize_url(($_POST['inputLink']));
        $selectedDuration = sanitize_text_field($_POST['cacheDuration']);
        
        if ($selectedDuration=='hour') {
            $cacheDuration = HOUR_IN_SECONDS;
        }else if($selectedDuration=='day') {
            $cacheDuration = DAY_IN_SECONDS;
        }else if ($selectedDuration=='week') {
            $cacheDuration = WEEK_IN_SECONDS;
        }else if($selectedDuration=='month') {
            $cacheDuration = MONTH_IN_SECONDS;
        }

        //gets the body of a remote URL;
        $webData = wp_remote_retrieve_body(wp_remote_get($cleaned));

        set_transient('retrieved_web_data', $webData, $cacheDuration);
        
        // info retrieved from link is sent to js file
        wp_send_json_success($webData);
       
    }



 ?>
