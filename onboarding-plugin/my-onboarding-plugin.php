<?php
/**
 * Plugin Name: My Onboarding Plugin
 * Description: This plugin is part of Day 5 WordPress Onboarding at DevriX
 * Author: Tanya_Devrix
 * Version: 1.0.0
 * Text Domain: my-onboarding-plugin
 *
 * @package onboarding-plugin
 */

// Used for url path protection.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Adding the plugin if the option is set to "enable".

if ( get_option( 'onboarding' ) == 1 ) {
	// Using wp_head hook and function onboard_plugin.
	add_action( 'wp_head', 'onboard_plugin' );


}
	/** Displaying text before header. */
	function onboard_plugin() {
	$is_post = is_single();
	$is_type = get_post_type();
	if ( $is_post && 'students' === $is_type ) {
		$prepend_str = 'Onboarding Filter: by Tanya@Devrix';
		echo esc_attr( $prepend_str );
		add_filter( 'wp_nav_menu_items', 'logged_user_add_menu' );
	}
}
	// Adding user settings nav element using the wp_nav_meni_items and a function which takes all nav items as params.
	/** Checking if user is logged in.
	 *
	 * @param array $items Existing navigation menu items.
	 */
function logged_user_add_menu( $items ) {

	if ( ! is_user_logged_in() ) {
		return $items;
	} elseif ( is_user_logged_in() ) {
		$profile_page_link = admin_url( 'profile.php' );
		return $items .= '<li><a href="' . $profile_page_link . '" class = "menu-item" >Profile Settings</a></li>';
	};
}
		// Adding a hidden div after first paragraph.
	add_filter( 'the_content', 'add_html_tags', 9999 );
	/** Adding some html to content.
	 *
	 * @param array|string $content Using page content.
	 */
function add_html_tags( $content ) {
		$content = str_replace( '</p>', '</p><div style="display:none;">Nice lil div</div>', $content );
		return $content;
};
	// Adding a paragraph after content and adding a div to it.
	add_filter( 'the_content', 'add_new_para', 9999 );
	/** Adding some html to content Pt2.
	 *
	 * @param object $content Receiving the page content.
	 */
function add_new_para( $content ) {
		$content = $content . '<p>An added paragraph </p>';
		$content = str_replace( '<p>An added paragraph </p>', '<p> An added paragraph containing a hidden DIV <div style="display:none;">Nice lil div</div></p>', $content );

		return $content;
};


// Adding user update action - email to admin.
	add_action( 'profile_update', 'profile_update_notify' );
/** Send email on profile change. */
function profile_update_notify() {
			$my_user_info = wp_get_current_user();
			wp_mail(
				get_bloginfo( 'admin_email' ), // To.
				'User updated profile', // Subject.
				'Greetings, Admin! User profile of ' . $my_user_info->user_login . ' has been modified.', // Body.
			);
}

add_action( 'admin_menu', 'my_new_submenu' );
/**  Adding a submenu/menu to the Settings menu using admin_menu hook. */
function my_new_submenu() {
	// Slug of main menu, page title, title, permissions, slug, action controller.
	// Add_submenu_page('options-general.php','My Onboarding','Onboarding Filters','administrator', 'my-onboarding-filters', 'onboarding_filter_enabling' ).
	add_menu_page( 'My Onboarding', 'My Onboarding', 'administrator', 'my-onboarding-filters', 'onboarding_filter_enabling', 'dashicons-filter', 4 );
}
/** Enabling the plugin in the admin workspace*/
function onboarding_filter_enabling() {
	$state = get_option( 'onboarding' );
	$checked = ( 1 == $state ) ? 'checked' : '';

	$my_form = '<h1>Filters Enable/Disable</h1>
      <label> Check to Enable Filters
      <input type="checkbox" name="onboarding" id="enableFilters" ' . $checked;
	'>
      </label>';
	echo $my_form;
}

?>

<?php

add_action( 'admin_enqueue_scripts', 'add_onboarding_script' );

/** Script loading. */
function add_onboarding_script() {

	wp_enqueue_script( 'onboarding_script', plugins_url( 'onboarding_script.js', __FILE__ ), array( 'jquery' ), false, true );

	wp_localize_script( 'onboarding_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}


add_action( 'wp_ajax_filter_value', 'filter_value' );

/** Updating enable/disable value. */
function filter_value() {

	$current_value = sanitize_text_field( $_POST['filterValue'] );
	$saved_option = '';
	if ( 'true' === $current_value ) {
		$saved_option = 1;
	};

	update_option( 'onboarding', $saved_option );
	wp_die();
}
