<?php

	/* get a global option */
	function linkpro_mu_get_option( $option ) {
		$linkpro_default_options = linkpro_mu_default_options();
		$settings = get_option('linkpro_mu');
		switch($option){
		
			default:
				if (isset($settings[$option])){
					return $settings[$option];
				} else {
					return $linkpro_default_options[$option];
				}
				break;
	
		}
	}
	
	/* set a global option */
	function linkpro_mu_set_option($option, $newvalue){
		$settings = get_option('linkpro_mu');
		$settings[$option] = $newvalue;
		update_option('linkpro_mu', $settings);
	}
	
	/* default options */
	function linkpro_mu_default_options(){
		$array = array();
		$array['multi_forms'] = '';
		$array['multi_forms_default'] = '';
		return apply_filters('linkpro_mu_default_options_array', $array);
	}