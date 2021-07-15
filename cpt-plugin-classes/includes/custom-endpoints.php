<?php

/** REST API - Custom endpoints. */
class Rest_Api_Custom {

	public function __construct() {
		add_action( 'rest_api_init', function () {
			register_rest_route( '/onboard/v1', 'allstudents/page/(?P<page>\d+)', [
				'methods'  => [
					'GET',
				],
				'callback' => array( $this, 'get_students_cpt' ),
			] );
		} );

		add_action( 'rest_api_init', function () {

			register_rest_route( '/onboard/v1', '/students/(?P<id>\d+)', [
				'methods'  => [ 'GET' ],
				'callback' => array( $this, 'get_student_by_id_cpt' ),
			] );
		} );

		add_action( 'rest_api_init', function () {

			register_rest_route( '/onboard/v1', '/students/edit/(?P<id>\d+)', [
				'methods'  => [ 'POST' ],
				'callback' => array( $this, 'update_cpt_student' ),
				'permission_callback' => array( $this, 'permission_rest_student_cpt' ),
			] );
		} );

		add_action( 'rest_api_init', function () {

			register_rest_route('/onboard/v1', '/students/delete/(?P<id>\d+)', [
				'methods'             => [ 'Delete' ],
				'callback'            => array( $this, 'delete_cpt_student' ),
				'permission_callback' => array( $this, 'permission_rest_student_cpt' ),
			] );
		} );

		add_action( 'rest_api_init', function () {

			register_rest_route( '/onboard/v1', '/allstudents', [
				'methods'             => [ 'POST' ],
				'callback'            => array( $this, 'add_new_cpt_student' ),
				'permission_callback' => array( $this, 'permission_rest_student_cpt' ),
			] );
		} );
	}

	/** Get all students. */
	public function get_students_cpt( $params ) {
		$args = [
			'post_type'      => 'students',
			'posts_per_page' => 5,
			'paged'          => $params['page'],
		];

		$query = new WP_Query( $args );
		// if no posts found return.
		if ( empty( $query->posts ) ) {
			return new WP_Error( '404', __('No posts found'), [ 'These are not the students you are looking for' ] );
		}
			// set max number of pages and total num of posts.
			$max_pages = $query->max_num_pages;
			$total     = $query->found_posts;
			$response  = $query->posts;
			// set headers and return response.
			$response = new WP_REST_Response( $response, 200 );

			$response->header( 'X-WP-Total', $total );
			$response->header( 'X-WP-TotalPages', $max_pages );
			// previous_posts_link( '<<<= Previous Page' );
			// next_posts_link( 'Next Page =>>>', $max_pages );

			return [ $response , $params['page'] ];

	}

		/** Get student by ID.
		 *
		 * @param int $params Id from route.
		 */
	public function get_student_by_id_cpt( $params ) {
		$args = [
					'post_type' => 'students',
							'p' => $params['id'],
					];
		$query = new WP_Query( $args );
		if ( empty( $query->posts ) ) {
			return new WP_Error( '404', __( 'No post found' ), [ 'No data' ] );
		} else {
			$response = $query->posts;
			$response = new WP_REST_Response( $response, 200 );
			return $response;
		}
	}

	/** UPDATE: Edit student by Id.
	 *
	 * @param int $params Id from route.
	 */
	public function update_cpt_student( $params ) {
		$post_id = sanitize_text_field( $params['id'] );
		if ( get_post( $post_id ) ) {
		$data = array(
			'ID'           => $post_id,
			'post_title'   => sanitize_text_field( $_POST['post_title'] ),
			'post_content' => sanitize_text_field( $_POST['post_content'] ),
			'post_excerpt' => sanitize_text_field( $_POST['post_excerpt'] ),
			'post_status'  => 'publish',
			'post_type'    => 'students',
		);
		}
		if ( wp_update_post( $data, true ) ) {
			return 'Post updated successfully.';
		} else {
			return 'Post could not be edited';
		}

	}
	/** Deleting a student
	 *
	 * @param object $params Received the id of student that should be deleted.
	 */
	public function delete_cpt_student( $params ) {

		$post_id = sanitize_text_field( $params['id'] );
		if ( get_post( $post_id ) ) {
			if ( wp_delete_post( $post_id ) ) {
				return 'Post deleted successfully';
			};
		} else {
			return 'Error deleting';
		}

	}
	/**  Post. */
	/** Adding a new student */
	public function add_new_cpt_student() {
		// Create post object.
		if (! isset( $_POST['post_title'] ) || ! isset( $_POST['post_content'] ) || ! isset( $_POST['post_excerpt'] ) ) {
			return 'Title, Content and Excerpt are mandatory!';
		}
		if ( '' === $_POST['post_title'] || '' === $_POST['post_content']  || '' === ( $_POST['post_excerpt'] ) ) {
			return 'Title, Content and Excerpt cannot be empty strings!';
		}
		$my_post = array(
			'post_title'   => sanitize_text_field( $_POST['post_title'] ),
			'post_content' => sanitize_text_field( $_POST['post_content'] ),
			'post_excerpt' => sanitize_text_field( $_POST['post_excerpt'] ),
			'post_status'  => 'publish',
			'post_type'    => 'students',
		);

		if ( wp_insert_post( $my_post) ) {
			return [ 'Post created successfully', $my_post ];
		} else {
			return 'Error creating post!';
		}

	}

	/** Admin Authentication */
	public function permission_rest_student_cpt() {
		if ( current_user_can( 'administrator' ) ) {
			return current_user_can( 'administrator' );
		} else {
			return false;
		}
	}

}

$rest_api_endpoints = new Rest_Api_Custom();
