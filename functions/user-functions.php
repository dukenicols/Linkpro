<?php


/* Checks if a user is logged in */
	function linkpro_is_logged_in(){
		if (is_user_logged_in()):
			return true;
		endif;

		return false;
	}

	function linkpro_has_facebook_id($user_id) {
 
 global $linkpro;

 $user_facebook_id = get_user_meta($user_id, 'linkpro_facebook_id', true);
 if(!empty($user_facebook_id)):
     return 1;
 endif;
 return 0; 
 
}

function do_user_need_change_pass($user_id) {
		global $linkpro;

		$user_need = get_user_meta($user_id, 'linkpro_need_change_pass', true);
		if(empty($user_need)):
			return true;
		endif;

		return false;
}

/* Update user profile from facebook */
	function linkpro_update_profile_via_facebook($user_id, $array) {
		global $linkpro;
		
		$id = (isset($array['id'])) ? $array['id'] : 0;
		$first_name = (isset($array['first_name'])) ? $array['first_name'] : 0;
		$last_name = (isset($array['last_name'])) ? $array['last_name'] : 0;
		$gender = (isset($array['gender'])) ? $array['gender'] : 0;
		$link = (isset($array['link'])) ? $array['link'] : 0;
		$email = (isset($array['email'])) ? $array['email'] : 0;
		$username = (isset($array['username'])) ? $array['username'] : 0;
		
		if ( linkpro_is_logged_in() && ( $user_id != get_current_user_id() ) && !current_user_can('manage_options') )
			die();
		
		if ($id && $id != 'undefined') { update_user_meta($user_id, 'linkpro_facebook_id', $id); }
		
		if ($first_name && $first_name != 'undefined'){ update_user_meta($user_id, 'first_name', $first_name); }
		if ($last_name && $last_name != 'undefined') { update_user_meta($user_id, 'last_name', $last_name); }
		
		if ($gender && $gender != 'undefined') { update_user_meta($user_id, 'gender', $gender); }
		
		if ($link && $link != 'undefined') { update_user_meta($user_id, 'facebook', $link); }
		
		/* begin display name */
		if (isset($name) && $name != 'undefined' && $name!=0) {
			$display_name = $name;
		} else if ($first_name && $last_name && $first_name != 'undefined' && $last_name != 'undefined' ) {
			$display_name = $first_name . ' ' . $last_name;
		} else if ($email) {
			$display_name = $email;
		} else {
			$display_name = $username;
		}
		
		if ($display_name) {
			if ($linkpro->display_name_exists( $display_name )){
				$display_name = $linkpro->unique_display_name($display_name);
			}
			
			wp_update_user( array('ID' => $user_id, 'display_name' => $display_name ) );
			update_user_meta($user_id, 'display_name', $display_name);
		}
		/* end display name */
		
		do_action('linkpro_after_profile_updated_fb');
		
	}