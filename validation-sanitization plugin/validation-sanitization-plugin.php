<?php
/**
 * Plugin Name: Validation-Sanitization Day 7 WP Onboarding
 * Description: This plugin is part of Day 7 WordPress Onboarding at DevriX
 * Author: Tanya_Devrix
 * Version: 1.0.0
 * Text Domain: validation-sanitization-plugin
 */

/** Used for url path protection.  */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/** Enqueuing ONLY to admin environment. */
add_action( 'admin_enqueue_scripts', 'add_plugin_script' );
/**
 * Enqueuing scripts
 *
 * @return void
 */
function add_plugin_script() {
	wp_enqueue_script( 'validation-sanitization-plugin', plugins_url( 'validation-sanitization-plugin.js', __FILE__ ), array( 'jquery' ), false, true );

	wp_localize_script( 'validation-sanitization-plugin', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}


add_action( 'admin_menu', 'my_links_menu' );

/** Adding a submenu/menu to the Settings menu using admin_menu hook */
function my_links_menu() {
	// Slug of main menu, page title, title, permissions, slug, action controller.

	add_menu_page( 'Links Plugin', 'Links Plugin', 'administrator', 'links-plugin', 'my_links_plugin', 'dashicons-admin-links', 4 );
}

/** Function which renders the html fields for submission and selection of duration */
function my_links_plugin() {
	// Detting transient if it exists.
	ob_start();
	?>
	<div class="plugin-body" style="margin-top: 20px;">
		<h1> Enter a URL to see its content!</h1>
		<input type="text" id="URL" name="URL" />
		<input type="submit" id="submit" name="submit" value="SUBMIT" />
		<div class='caching-duration'>
			<label>I want my URL cached for:</label>
			<select name="duration" id="duration">
				<option value="60">a minute</option>
				<option value="3600">one hour</option>
				<option value="86400">one day</option>
				<option value="604800">one week</option>
			</select>
		</div>
		<div class="retrieved_data" style="width:1300px; margin-top:50px;"> <?php echo get_transient( 'retrieved_web_data' ); ?>
		</div>

	</div>
	<?php

	// Echo and stop buffering hmtl.
	echo ob_get_clean();
}


add_action( 'wp_ajax_link_submission', 'link_submission' );
/** Dunction that takes care of AJAX */
function link_submission() {

	// cache duration logic.
	$transient_duration = sanitize_text_field( ( $_POST['cache_duration'] ) );
	if ( '60' === $transient_duration ) {
		$cache_duration = MINUTE_IN_SECONDS;
	} elseif ( '3600' === $transient_duration ) {
		$cache_duration = HOUR_IN_SECONDS;
	} elseif ( '86400' === $transient_duration ) {
		$cache_duration = DAY_IN_SECONDS;
	} elseif ( '604800' === $transient_duration ) {
		$cache_duration = WEEK_IN_SECONDS;
	}

	// sanitze the url link.
	$input_link = esc_url_raw( ( $_POST['inputLink'] ) );
	$external_data  = wp_remote_retrieve_body( wp_safe_remote_get( $input_link ) );
	if ( ! $external_data ) {
		wp_send_json_error();
		return;
	}

	set_transient( 'retrieved_web_data', $external_data, $cache_duration );

	wp_send_json_success( $external_data );

}

?>
