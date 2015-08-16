<?php

class linkpro_api {
	
	function __construct() {

		$this->temp_id = null;

		$this->upload_dir = wp_upload_dir();
		
		$this->upload_base_dir = $this->upload_dir['basedir'];
		if ( strstr( $this->upload_base_dir, 'wp-content/uploads/sites' ) ) { 
			$this->upload_base_dir = $this->str_before( $this->upload_base_dir, '/wp-content/uploads/sites' );
			$this->upload_base_dir = $this->upload_base_dir . '/wp-content/uploads/linkpro/';
		} else {
			$this->upload_base_dir = $this->upload_base_dir . '/linkpro/';
		}
		
		$this->upload_base_url = $this->upload_dir['baseurl'];
		if ( strstr( $this->upload_base_url, 'wp-content/uploads/sites' ) ) { 
			$this->upload_base_url = $this->str_before( $this->upload_base_url, '/wp-content/uploads/sites' );
			$this->upload_base_url = $this->upload_base_url . '/wp-content/uploads/linkpro/';
		} else {
			$this->upload_base_url = $this->upload_base_url . '/linkpro/';
		}
		
		$this->upload_path_wp = trailingslashit($this->upload_dir['path']);
		$this->upload_path = $this->upload_dir['basedir'] . '/linkpro/';
		$this->badges_url = linkpro_url . 'img/badges/';
		$this->img_url = linkpro_url . 'img/'; 
		
		$this->fields = get_option('linkpro_fields');
		$this->groups = get_option('linkpro_fields_groups');
		$this->get_cached_results = (array) get_option('linkpro_cached_results');
		
		//if(!get_transient('linkpro_no_update'))
		
		//add_action('init', array(&$this, 'quick_actions'), 9);
		
		//add_action('init', array(&$this, 'load_twitter'), 9);
		
	//	add_action('init', array(&$this, 'twitter_authorize'), 10);
		
		//add_action('init', array(&$this, 'load_google'), 11);
		
	//	add_action('init', array(&$this, 'google_authorize'), 12);
		
		//add_action('init',  array(&$this, 'trial_version'), 9);

  //      add_action('init', array(&$this, 'linkedin_authorize'));

		//add_action('init', array(&$this, 'instagram_authorize'));
		
		//add_action('init',  array(&$this, 'process_email_approve'), 9);
		
	//	add_action('init',  array(&$this, 'process_verification_invites'), 9);
		
		//add_action('wp',  array(&$this, 'update_online_users'), 9);
		
		/* Export settings */
		//add_action('template_redirect', array(&$this, 'admin_redirect_download_files') );
		//add_filter('init', array(&$this,'add_query_var_vars') );
		
		//delete_option('get_twitter_auth_url');
		
	}

	/******************************************
	Create uploads dir if does not exist
	******************************************/
	function do_uploads_dir($user_id=0) {
	
		if (!file_exists( $this->upload_base_dir . '.htaccess') ) {

$data = <<<EOF
<Files ~ "\.txt$">
Order allow,deny
Deny from all
</Files>
EOF;

			file_put_contents( $this->upload_base_dir . '.htaccess' , $data);
		}
	
		if (!file_exists( $this->upload_base_dir )) {
			@mkdir( $this->upload_base_dir, 0777, true);
		}
		
		if ($user_id > 0) { // upload dir for a user
			if (!file_exists( $this->upload_base_dir . $user_id . '/' )) {
				@mkdir( $this->upload_base_dir . $user_id . '/', 0777, true);
			}
		}
	}


function get_uploads_dir($user_id=0){
		if ($user_id > 0) {
			return $this->upload_base_dir . $user_id . '/';
		}
		return $this->upload_base_dir;
	}

/******************************************
	Return the uploads URL
	******************************************/
	function get_uploads_url($user_id=0){
		if ($user_id > 0) {
			return $this->upload_base_url . $user_id . '/';
		}
		return $this->upload_base_url;
	}

	/******************************************
	Unique display names
	******************************************/
	function display_name_exists($display_name) {
		$users = get_users(array(
			'meta_key'     => 'display_name',
			'meta_value'   => $display_name,
			'meta_compare' => '='
		));
		if ( isset($users[0]->ID) && ( $users[0]->ID == get_current_user_id()) ) {
			return false;
		} elseif ( isset($users[0]->ID) && current_user_can('manage_options') ) {
			return false;
		} elseif ( isset($users[0]->ID) ) {
			return true;
		}
		return false;
	}	

	/******************************************
	Make display_name unique
	******************************************/
	function unique_display_name($display_name){
		$r = str_shuffle("0123456789");
		$r1 = (int) $r[0];
		$r2 = (int) $r[1];
		$display_name = $display_name . $r1 . $r2;
		return $display_name;
	}

} //linkpro_api





$linkpro = new linkpro_api();