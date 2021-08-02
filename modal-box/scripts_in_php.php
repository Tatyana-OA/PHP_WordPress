/** Scripts for Calendar -> Interactive adding task/project/opportunity etc. from calendar day */
	function wpcrm_system_add_dashboard_dynamic_script() {

		wp_enqueue_script( 'wp-crm-dynamic-calendar-add', WP_CRM_SYSTEM_PLUGIN_URL . '/assets/dist/scripts/wp-crm-dynamic-calendar-add.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false);

		wp_localize_script( 'wp-crm-dynamic-calendar-add', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	add_action( 'wp_ajax_wpcrm_system_handle_quick_create_jquery', 'wpcrm_system_handle_quick_create_jquery' );


add_action( 'admin_enqueue_scripts', 'wpcrm_system_add_dashboard_dynamic_script');

function wpcrm_system_handle_quick_create_jquery() {
	$wpcrm_fast_value =  $_POST['name'];
	$wpcrm_fast_type = sanitize_text_field( $_POST['type'] );
	if ( is_array( $wpcrm_fast_value ) ){
		$post_arr = array(
			'post_title'   => implode(' ', $wpcrm_fast_value),
			'post_type' => $wpcrm_fast_type,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
		);
		$contact_name_first = $wpcrm_fast_value[0];
		$contact_name_last = $wpcrm_fast_value[1];
		$post_id =  wp_insert_post($post_arr);

		add_post_meta( $post_id, '_wpcrm_contact-first-name', $contact_name_first, true );
	    add_post_meta( $post_id, '_wpcrm_contact-last-name', $contact_name_last, true );
	} else {
		$post_arr = array(
			'post_title'   => $wpcrm_fast_value,
			'post_type' => $wpcrm_fast_type,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
		);
		$post_id =  wp_insert_post($post_arr);
	}

	
	//  _wpcrm_contact-first-name
	//  _wpcrm_contact-last-name


	wp_send_json_success( $wpcrm_fast_value);
}
