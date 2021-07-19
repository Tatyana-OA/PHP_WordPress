<?php
/**
 * Plugin Name:       Gutenberg Block by Tanya@Devrix 
 * Description:       Example block written with ESNext standard and JSX support – build step required.
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ob-test-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function create_block_ob_test_block_block_init() {
	register_block_type_from_metadata( __DIR__ , [
		'render_callback' => 'gutenberg_examples_dynamic_render_callback',
		'attributes' => [
			"studentsCount" => [
				'type' => 'string',
				'default' => '5'
			],
			"studentType" => [
				'type'=> 'string',
				'default' => 'true'
			]
	] 
			]);
		}
add_action( 'init', 'create_block_ob_test_block_block_init' );


function gutenberg_examples_dynamic_render_callback( $attributes, $content ) {
	if ( isset($attributes['studentsCount'] ) ) {
		$count = intval( $attributes['studentsCount'] );
	}
	else {
		$count = 5;
	}
	if ( isset($attributes['studentType'] ) ) {
		$type = $attributes['studentType'];
	}
	else {
		$type = 'true';
	}
	$students_array = array(
        'numberposts'      => $count,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'meta_key'         => '_is_active_student',
        'meta_value'       => $type,
        'post_type'        => 'students',
    );
     $recent_posts = get_posts($students_array);
 	//echo '<div style="width: 300px;">Currently selected students: </div>';
     if ( count( $recent_posts ) === 0 ) {
         return 'No students corresponding to the selection';
     }
	 $bob = '';
	 for ( $i=0; $i<count( $recent_posts ); $i++) {
		$post = $recent_posts[$i];
		  $post_link = get_permalink( $post );
		  $post_thumbnail = get_the_post_thumbnail( $post );
		  $post_name = get_the_title( $post) ;
	
		  $post_string = '<div style="width:50%; background-color:#E4FFFD; border-radius:30px; text-align:center;">
		  <a href="' . $post_link . '">
			  <h3>' . $post_name . '</h3>
			  <div style="width:100%;">' . $post_thumbnail . '</div>
		  </a>
	  </div>';
		$bob .= $post_string;

	   };
	return $bob;
};
