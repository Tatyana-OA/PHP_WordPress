<?php

/**  Creating the widget */
class Student_Widget extends WP_Widget {

	/**  Constructor function for new widget */
	public function __construct() {
		parent::__construct(
			// Base s_id of your widget.
			'Student_Widget',
			// Widget name will appear in UI.
			__( 'Students Widget', 'Student_Widget_domain' ),
			// Widget description.
			array( 'description' => __( 'This is an onboaring students widget', 'Student_Widget_domain' ) )
		);
	}

	/**  Creating the widget front-end
	 *
	 * @param object $args Used for before/after title.
	 * @param object $instance Current instance of widget class.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		// Before and after widget arguments are defined by themes.
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where the code is run and output is displayed.
		echo __( '<h4>These are the queried students: </h4>', 'Student_Widget_domain' );

		// Getting data from $instance (which is the backend part of the widget & querying it).
		$posts = esc_attr( $instance['posts_per_page'] );
		if ( 'Active' === $instance['dropdown_active'] ) {
			$status = 'true';
		} else {
			$status = 'false';
		}
		echo '<p>Selection: ' . esc_html( $posts ) . ' posts. </p>';
		echo '<p>Category: ' . esc_html( $instance['dropdown_active'] ) . ' students. </p>';
		$loop = new WP_Query(array(
			'post_type'      => 'students',
			'posts_per_page' => $posts,
			'meta_key'       => '_is_active_student',
			'meta_value'     => $status
		) );
		while ( $loop->have_posts() ) :
			$loop->the_post();
			?>
			<div style="background-color:#E4FFFD; border-radius:30px; text-align:center;">
				<a href="<?php echo esc_url( get_the_permalink() ); ?>">
					<?php the_post_thumbnail( 'medium' ); ?>
					<h3><?php the_title(); ?></h3>
					<h6><?php the_excerpt(); ?> </h6>
					<h6>Average grade: <?php echo esc_html( get_post_meta( get_the_ID(), '_student_grade_key', true ) ); ?> </h6>
				</a>
			</div>
		<?php
		endwhile;

		// Built-in.
		echo $args['after_widget'];
	}

	/**  Creating the widget back-end
	 *
	 * @param object $instance The current instance of the class Widget.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Students Widget', 'Student_Widget_domain' );
		}
		if ( isset( $instance['posts_per_page'] ) ) {
			$posts_per_page = $instance['posts_per_page'];
		} else {
			$posts_per_page = __( '1', 'Student_Widget_domain' );
		}
		if ( isset( $instance['dropdown_active'] ) ) {
			$dropdown_option = $instance['dropdown_active'];
		} else {
			$dropdown_option = __( 'Active', 'Student_Widget_domain' );
		}
		// Widget admin form.
		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widg-option" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name = "<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e( 'Posts per page:' ); ?></label>
			<input class="widg-option" id="<?php echo esc_html( $this->get_field_id( 'posts_per_page' ) ); ?>" name = "<?php echo esc_html( $this->get_field_name( 'posts_per_page' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'dropdown_active' ) ); ?>"> Active/Inactive:
				<select class='widg-option' id="<?php echo esc_html( $this->get_field_id( 'dropdown_active' ) ); ?>" name = "<?php echo esc_html( $this->get_field_name( 'dropdown_active' ) ); ?>" type="text">
					<option value='Active' <?php echo ( 'Active' === $dropdown_option ) ? 'selected' : ''; ?>>
						Active
					</option>
					<option value='Inactive' <?php echo ( 'Inactive' === $dropdown_option ) ? 'selected' : ''; ?>>
						Inactive
					</option>
				</select>
			</label>
		</p>

		<?php
	}

	/** Updating widget replacing old instances with new.
	 *
	 * @param object $new_instance Edited values.
	 * @param object $old_instance Previous values.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = ( ! empty( $new_instance['posts_per_page'] ) ) ? wp_strip_all_tags( $new_instance['posts_per_page'] ) : '';
		$instance['dropdown_active'] = ( ! empty( $new_instance['dropdown_active'] ) ) ? wp_strip_all_tags( $new_instance['dropdown_active'] ) : '';
		return $instance;
	}

	// Class Student_Widget ends here.
}


/** Registering new widget. */
function wpb_load_widget() {
	register_widget( 'Student_Widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

/** Creating custom sidebar. */
function custom_sidebar_wp_onboarding() {
	register_sidebar(
		array(
			'id'            => 'devrix',
			'name'          => 'DevriX Sidebar',
			'description'   => 'Custom Sidebar by Tanya@DevriX (WP Onboarding Day 12)',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}


add_action( 'widgets_init', 'custom_sidebar_wp_onboarding' );
