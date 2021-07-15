<?php

// if ( class_exists( 'CPT_Metabox_Custom' ) ) {
// 	return;
// }
class CPT_Metabox_Custom {

	public function __construct( $id, $label, $key, $html, $field_name ) {
		$this->id         = $id;
		$this->label      = $label;
		$this->key        = $key;
		$this->html       = $html;
		$this->field_name = $field_name;
		add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ) );
	}

	public function create_meta_box() {
		add_meta_box( $this->id, $this->label, array( $this, 'metabox_html' ), [ 'students'] );
	}
	public function metabox_html( $post ) {
		$value = get_post_meta( $post->ID, $this->key, true );

		// make sure str_contains works.

		if ( ! function_exists('str_contains')) {
			function str_contains( string $haystack, string $needle ): bool
			{
				return '' === $needle || false !== strpos( $haystack, $needle );
			}
		}
		// handle html for input type text -> populate with value.
		if ( str_contains( $this->html, 'insert' ) ) {
			$this->html = str_replace( 'insert', esc_attr( $value ), $this->html );
		}
		// handle html for options -> populate with value.
		if ( str_contains( $this->html, $value ) && str_contains( $this->html, 'option') ) {
			$replace      = $value . '"';
			$this->html   = str_replace( $replace, $value . '" selected . ', $this->html );
		}

		echo $this->html;

	}
	public function save_metabox( $post_id ) {
		if ( isset( $_POST[ $this->field_name ] ) ) {
			$meta_value = sanitize_text_field( $_POST[ $this->field_name ] );
			update_post_meta( $post_id, $this->key, $meta_value );

		}

	}

}
$grade_html = '	<label> Grade <select name="grade" id="grade">
<option value="poor">Poor 2.00 - 2.49</option>
<option value="fair"> Fair 2.50 - 3.49</option>
<option value="good">Good 3.50 - 4.49</option>
<option value="distinguish">Very Good 4.50 - 5.49</option>
<option value="excellent">Excellent 5.50+</option>
</select> </label>';

new CPT_Metabox_Custom( 'student_lives_in', 'Lives in', '_student_livesIn_key', '<label> Student Residence <input type="text" id="livesIn" name="livesIn" placeholder="Country, City" value="insert"> </label>', 'livesIn' );
new CPT_Metabox_Custom( 'student_address', 'Address', '_student_address_key', '<label> Address <input type="text" id="address" name="address" placeholder="Address" value="insert"> </label>', 'address' );
new CPT_Metabox_Custom( 'student_birthdate', 'Birthdate', '_student_birthdate_key', '<label> Birthdate <input type="date" id="birthdate" name="birthdate" value="insert"> </label>', 'birthdate' );
new CPT_Metabox_Custom( 'student_grade', 'Grade', '_student_grade_key', $grade_html, 'grade' );


