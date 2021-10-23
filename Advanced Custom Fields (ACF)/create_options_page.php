if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Sample Options Page Name',
		'menu_title'	=> 'Options Page',
		'menu_slug' 	=> 'options-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
};
