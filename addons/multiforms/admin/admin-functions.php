<?php

	/* Create a multi form / seperate fields */
	add_action('wp_ajax_nopriv_linkpro_mu_create', 'linkpro_mu_create');
	add_action('wp_ajax_linkpro_mu_create', 'linkpro_mu_create');
	function linkpro_mu_create(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		if (!isset($_POST['name']) || !isset($_POST['linkpro_mu_fields'])) die();
		
		global $linkpro;
		$output = '';
		
		$name = $_POST['name'];
		$fields = $_POST['linkpro_mu_fields'];
		
		$multi_forms= linkpro_mu_get_option('multi_forms');
		$multi_forms[$name] = $fields;
		linkpro_mu_set_option('multi_forms',$multi_forms);
		
		$output['result'] = sprintf(__('Done. You can use this seperate registration form by adding this to your register shortcode: <code>type=%1$s</code> Example: <strong>[linkpro template=register type=%1$s]</strong>','linkpro'), $name);
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}

	/* Get register fields as checkboxes */
	add_action('wp_ajax_nopriv_linkpro_mu_getfields', 'linkpro_mu_getfields');
	add_action('wp_ajax_linkpro_mu_getfields', 'linkpro_mu_getfields');
	function linkpro_mu_getfields(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro;
		$output = '';
		
		$res = '';
		$res .= '<p>'.__('Now check all fields that you want to make available for this registration form. (Choose only these that apply)','linkpro').'</p>';
		$res .= '<form action="" method="post" class="linkpro_mu_form">';
		foreach( linkpro_fields_group_by_template( 'register', 'default') as $key => $array ) {
			if ( $linkpro->field_label($key) || isset($array['heading']) && $array['heading'] != ''){
			$res .= '<p><label class="linkpro-checkbox">
					<input type="checkbox" value="'.$key.'" name="linkpro_mu_fields[]" />&nbsp;&nbsp;';
			if ( $linkpro->field_label($key) ) {
			$res .= $linkpro->field_label($key);
			} elseif ($array['heading'] != '') {
			$res .= '<strong>'.$array['heading'].'</strong>';
			}
			$res .= '</label></p>';
			}
		}
		$res .= '</form>';
		
		$output['res'] = $res;
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
