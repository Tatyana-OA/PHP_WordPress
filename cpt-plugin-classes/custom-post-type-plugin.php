<?php
/**
 * Plugin Name: Custom Post Type Plugin
 * Description: This plugin is part of Day 9 WordPress Onboarding at DevriX
 * Author: Tanya_Devrix
 * Version: 1.0.0
 * Text Domain: custom-post-type-plugin
 *
 * @package custom-post-type-plugin
 */

/**
 * Custom Widget required from includes.
 */
require WP_PLUGIN_DIR . '/custom-post-type-plugin/includes/custom-widget.php';
// // require ;
require WP_PLUGIN_DIR . '/custom-post-type-plugin/includes/custom-post-type.php';

require WP_PLUGIN_DIR . '/custom-post-type-plugin/includes/custom-endpoints.php';

require WP_PLUGIN_DIR . '/custom-post-type-plugin/includes/cpt-metabox-class.php';




// Used for url path protection.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/** Refactoring Custom Post Type Plugin -> OOP. */
class Custom_Post_Type_Plugin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'add_custompost_script') );

	}
	public function add_custompost_script() {

		wp_enqueue_script( 'custom-post-type-plugin-script', plugins_url( 'custom-post-type-plugin-script.js', __FILE__ ), array( 'jquery' ), false, true );
		wp_localize_script( 'custom-post-type-plugin-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

}

$dx_custom_post_type_plugin = new Custom_Post_Type_Plugin();



