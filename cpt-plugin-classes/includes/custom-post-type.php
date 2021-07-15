<?php


class Custom_Post_Type {

	public function __construct() {
		add_action( 'init', array( $this, 'my_custom_student_type' ) );
		add_action( 'init', array( $this, 'my_student_taxonomies' ) );
		add_shortcode( 'student_shortcode', array( $this, 'student_shortcode_handler' ) );
		add_action( 'manage_students_posts_columns', array( $this, 'students_custom_column_head' ) );
		add_action( 'manage_students_posts_custom_column', array( $this, 'students_custom_column_data' ), 10, 2 ); // 10 is priority, 2 - num of params.
		add_action( 'wp_ajax_student_checkbox_handle', array( $this, 'student_checkbox_handle' ) );
	}
	/**  Function creating new Post Type & initializing it. */
	public function my_custom_student_type() {

	// Args are "settings" to the custom post type.
	$args = array(
		// Labels adds to menu.
		'labels'            => array(
			'name'          => 'Students',
			'singular_name' => 'Student',
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-welcome-learn-more',
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'category', 'custom-fields' ),
	);

		register_post_type( 'students', $args );
	}

	/**  Function creating Student taxonomies. */
	public function my_student_taxonomies() {
		$args = array(
			'labels'            => array(
				'name'          => 'Subjects',
				'singular_name' => 'Subject',
		),
			'public'       => true,
			'hierarchical' => true,
		);
		// The categories to the students.
		register_taxonomy( 'Subjects', array( 'students' ), $args );
	}

	/** Student shortcode - WP ONBOARDING DAY 11.
 	 *
 	 * @param object $atts An associative array of attributes.
	 */
	public function student_shortcode_handler( $atts ) {

		$attributes = shortcode_atts( [
			'id' => 1,
		], $atts );

		$s_id = intval( $attributes['id'] );
		if ( $s_id <= 0 ) {
			return '<h4 style="color:red;">Please enter a valid: a number bigger than 0</h4>';
		}

		// Creating query args -> post type  + post id.
		$post_args = array(
			'post_type' => 'students',
			'p'         => $s_id,
		);

		$my_query = new WP_Query( $post_args );

		if ( ! $my_query->have_posts() ) {
			return 'No such student found';
		};

		if ( $my_query->have_posts() ) :

			while ( $my_query->have_posts() ) :
				$my_query->the_post();
				?>
				<h2> This is <?php the_title(); ?> </h2>
				<?php the_post_thumbnail( 'large' ); ?>
				<h4> Grade: <?php echo esc_html( get_post_meta( get_the_ID(), '_student_grade_key' )[0] ); ?> </h4>
				<?php
			endwhile;

		endif;
	}
	/** Students - Active Custom column head
	 *
	 * @param object $columns - all existing columns.
	 */
	public function students_custom_column_head( $columns ) {
		// $columns contains all currently available columns (Default).
		$columns['enable_student'] = 'Active Student'; // Adding to all currently present.
		return $columns;
	}
	/** Students - Active Custom column head
	 *
	 * @param string $column - current column to add.
	 * @param int $post_id - id of the student.
	 */
	public function students_custom_column_data( $column, $post_id ) {
		$current_status = get_post_meta( $post_id, '_is_active_student', true );
		switch ( $column ) {
			case 'enable_student':
				?>
				<input type="checkbox" name="activeStudent" id="activeStudent_<?php echo $post_id; ?>" <?php if ( 'true' === $current_status ) {
					echo 'checked';
				} ?>>
				<?php
		}
	}
		/** Students - Active Custom column head*/
	public function student_checkbox_handle() {
		$checkbox_value = wp_unslash( sanitize_text_field( $_POST['isActive'] ) );
		$student_id = wp_unslash( sanitize_text_field( $_POST['student_id'] ) );
		update_post_meta( $student_id, '_is_active_student', $checkbox_value );
		wp_send_json_success( $checkbox_value, $student_id );
	}

}

$dx_custom_post_type = new Custom_Post_Type();

// Loads before headers on website.


