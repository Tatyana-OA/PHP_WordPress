function tackle_lg_likes() {
	$previously_liked = get_option('liked_images');
	// if ( isset($previously_liked ) 

	$lg_liked_photo = $_POST['clickedPhoto'];
	//$arr[]= [sth => sthelse]
	if (gettype($previously_liked)!='array') {
		$liked_images[$lg_liked_photo] = ['likes' => 1];
		update_option('liked_images', $liked_images);
	 }
	else {
		$is_present = false;
		foreach ( $previously_liked as $key=>$value ) {
			if ($key == $lg_liked_photo) {
				$is_present=true;
			}
		}
		if ($is_present==false) {
			 $previously_liked[$lg_liked_photo] = ['likes' => 0];
			 update_option('liked_images', $previously_liked);
		}
		//wp_send_json_success($is_present);
		// wp_send_json_success($previously_liked[$lg_liked_photo]['likes']);
		$new_likes = $previously_liked[$lg_liked_photo]['likes'] + 1;
		$previously_liked[$lg_liked_photo] = ['likes' => $new_likes];
		update_option('liked_images', $previously_liked);

	}

	wp_send_json_success( get_option('liked_images') );
}
