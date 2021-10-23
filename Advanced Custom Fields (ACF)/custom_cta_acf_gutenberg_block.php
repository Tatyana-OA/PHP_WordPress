add_action( 'acf/init', 'my_acf_init_block_types' );
function my_acf_init_block_types() {

    // Check function exists.
    if( function_exists( 'acf_register_block_type' ) ) {
acf_register_block_type(
			array(
            'name'            => 'call-to-action',
            'title'           => __( 'Call to Action' ),
            'description'     => __('A custom call-to-action block.' ),
            'render_template' => 'template-parts/blocks/cta.php',
            'category'        => 'formatting',
            'icon'            => 'bell',
            'keywords'        => array( 'cta', 'block' ),
			'enqueue_style'   => get_template_directory_uri() . '/assets/dist/css/master.css',
        )
		);
}
}
