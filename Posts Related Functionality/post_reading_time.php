function post_reading_time( $post ) {
	$content          = get_post_field( 'post_content', $post );
	$decode_content   = html_entity_decode( $content );
	$filter_shortcode = do_shortcode( $decode_content );
	$strip_tags       = wp_strip_all_tags( $filter_shortcode, true );
	$post_word_count       = str_word_count( $strip_tags );
	$reading_time      = ceil( $word_count / 200 );
	$timer            = ' min';
	$totalreadingtime = $readingtime . $timer;
	return $totalreadingtime;
};
