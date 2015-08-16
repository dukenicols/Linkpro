<?php

	/* Admin bar */
	function linkpro_admin_bar(){
		global $linkpro_admin;
	?>
			<div class="linkpro-admin-head">
				<div class="linkpro-admin-left">
					<a href="<?php echo admin_url('admin.php'); ?>?page=linkpro"></a>
					<span class="linkpro-admin-version"><?php echo $linkpro_admin->version; ?></span>
				<!--	<span class="linkpro-admin-span"><?php if (get_option('linkpro_activated')) { _e('Thank you for activating linkpro!','linkpro'); } else { _e('This copy is unlicensed. Please activate your copy.','linkpro'); } ?></span> -->
				</div>

		<!--		<div class="linkpro-admin-right">
					
					<a href="http://codecanyon.net/user/DeluxeThemes#contact" class="button"><?php _e('Email Support','linkpro'); ?></a>
					<a href="http://linkproplugin.com/linkpro/docs/" class="button"><?php _e('User Manual','linkpro'); ?></a>
					<a href=" http://codecanyon.net/item/linkpro-user-profiles-with-social-login/5958681
" class="button"><?php _e('Support linkpro with your 5-star rating','linkpro'); ?></a>
					<a href="http://codecanyon.net/item/linkpro-user-profiles-with-social-login/5958681" class="button button-primary"><?php _e('Download Latest','linkpro'); ?></a>
					<a href="admin.php?page=linkpro&check=update" class="button"><?php _e('Check For Update','linkpro'); ?></a>
				</div> -->
				<div class="clear"></div>
			</div>
	<?php
	}

	/* Get post value */
	function linkpro_admin_post_value($key, $value, $post){
		if (isset($_POST[$key])){
			if ($_POST[$key] == $value)
				echo 'selected="selected"';
		}
	}
	
	/* Get skin list */
	function linkpro_admin_skins(){
		$skins = scandir( linkpro_path . 'skins/' );
		if (file_exists( get_stylesheet_directory() . '/linkpro/skins/' ) ) {
			$custom_skins = scandir( get_stylesheet_directory() . '/linkpro/skins/');
			$arr = array_merge($skins, $custom_skins);
		} else {
			$arr = $skins;
		}
		
		if (class_exists('linkpro_sk_api')) {
			$arr = array_merge($arr, scandir( linkpro_sk_path . '/skins/'));
		}
		
		$arr = array_unique($arr);
		return $arr;
	}

	/* Show builtin mail vars */
	function linkpro_admin_list_builtin_vars($var1=null){
		$res = null;
		
		$array = array(
			'{linkpro_ADMIN_EMAIL}' => __('Displays the admin email that users can contact you at. You can configure it under Mail settings.','linkpro'),
			'{linkpro_BLOGNAME}' => __('Displays blog name','linkpro'),
			'{linkpro_BLOG_URL}' => __('Displays blog URL','linkpro'),
			'{linkpro_BLOG_ADMIN}' => __('Displays blog WP-admin URL','linkpro'),
			'{linkpro_LOGIN_URL}' => __('Displays the linkpro login page','linkpro'),
			'{linkpro_USERNAME}' => __('Displays the Username of user','linkpro'),
			'{linkpro_FIRST_NAME}' => __('Displays the user first name','linkpro'),
			'{linkpro_LAST_NAME}' => __('Displays the user last name','linkpro'),
			'{linkpro_NAME}' => __('Displays the user display name or public name','linkpro'),
			'{linkpro_EMAIL}' => __('Displays the E-mail address of user','linkpro'),
			'{linkpro_PROFILE_LINK}' => __('Displays the User Profile address','linkpro'),
			'{linkpro_PROFILE_FIELDS}' => __('Outputs all profile fields in the e-mail','linkpro'),
			'{linkpro_VALIDATE_URL}' => __('The account validation URL that user receives after signing up (If you enable e-mail validation feature)','linkpro'),
			'{linkpro_PENDING_REQUESTS_URL}' => __('Gives a link to the admin to manage his pending user requests and registrations.','linkpro'),
			'{linkpro_ACCEPT_VERIFY_INVITE}' => __('This is an automatic generated URL that user will click to become verified after an invitation to get verified is sent to him.','linkpro'),
			'{linkpro_custom_field}' => __('Displays value of custom field','linkpro')
		);
		
		if ($var1){
			$array[$var1] = __('Custom Variable 1','linkpro');
		}
		
		foreach($array as $key => $val) {
			$res .= '<br /><code>'.$key.'</code> '. $val;
		}
		
		echo $res;
	}

	/* Count fields */
	function linkpro_admin_count_fields(){
		$array= get_option('linkpro_fields');
		return sprintf(__('%s fields available','linkpro'), count($array));
	}
	
	/* Return field actions */
	function linkpro_admin_field_actions($key=null,$arr=null){
		$output = null;
		$output .= '<div class="upadmin-field-actions">';
	
		
		if ($arr){
		
			if (isset($arr['type'])) {
				if (!isset($arr['help'])) $arr['help'] = '';
				if (!isset($arr['placeholder'])) $arr['placeholder'] = '';
				if (!isset($arr['html'])) $arr['html'] = 0;
				if (!isset($arr['hideable'])) $arr['hideable'] = 0;
				if (!isset($arr['hidden'])) $arr['hidden'] = 0;
				if (!isset($arr['required'])) $arr['required'] = 0;
				if (!isset($arr['locked'])) $arr['locked'] = 0;
				if (!isset($arr['private'])) $arr['private'] = 0;
			}
			
			ksort($arr);
		
			foreach($arr as $k=>$v){
				if ($k && in_array($k, array('html','hideable','hidden','required','locked','private') ) ) {
				if ($v == 0) { $class = 'off'; } else { $class = 'on'; }
				$output .= '<a href="#" class="upadmin-field-action upadmin-field-action-'.$k.' '.$class.'" data-key="'.$key.'" data-role="'.$k.'" data-value="'.$v.'"></a>';
				}
			}
			
		}
		
		$output .= '<a href="#" title="'.__('Edit Field','linkpro').'" class="upadmin-field-action-edit"></a>';
		
		$output .= '<a href="#" title="'.__('Delete Field','linkpro').'" class="upadmin-field-action-remove"></a>';
		
		$output .= '</div>';
		return $output;
	}

	/**
	List all fields
	**/
	function linkpro_admin_list_fields($specific_field=null){
		$output = null;
		$unsorted = get_option('linkpro_fields');
		if ($specific_field){
			$arr = $unsorted[$specific_field];
				if (isset($arr['type']) && $arr['type'] != '') {
					$label = (isset($arr['label'])) ? $arr['label'] : '';
					if (!isset($arr['help'])) $arr['help'] = '';
					if (!isset($arr['placeholder'])) $arr['placeholder'] = '';
					if (!isset($arr['html'])) $arr['html'] = 0;
					if (!isset($arr['hideable'])) $arr['hideable'] = 0;
					if (!isset($arr['hidden'])) $arr['hidden'] = 0;
					if (!isset($arr['required'])) $arr['required'] = 0;
					if (!isset($arr['locked'])) $arr['locked'] = 0;
					if (!isset($arr['private'])) $arr['private'] = 0;
					if (!isset($arr['ajaxcheck'])) $arr['ajaxcheck'] = '';
					if (!isset($arr['woo'])) $arr['woo'] = 0;
					if (linkpro_get_field_icon($specific_field)) { $arr['icon'] = linkpro_get_field_icon($specific_field); } else { $arr['icon'] = ''; }
					if (!isset($arr['button_text']) && isset($arr['type']) && ( $arr['type'] == 'file' || $arr['type'] == 'picture') ) $arr['button_text'] = '';
					if (!isset($arr['list_id']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' ) ) $arr['list_id'] = '';
					if (!isset($arr['list_text']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' ) ) $arr['list_text'] = '';
					if (!isset($arr['security_qa']) && isset($arr['type']) && ( $arr['type'] == 'securityqa')) $arr['security_qa'] = 'Sample Question 1:Answer
Sample Question 2:Answer'; // Security Question new Field
				}
				$output .= "<li class='field woo-".$arr['woo']."' id='upadmin-$specific_field'>$label <span class='ufieldkey'>$specific_field</span>";
				
				if (isset($arr['type']) && in_array($arr['type'], array('select','multiselect','checkbox','checkbox-full','radio','radio-full' , 'security_qa'))){
					if (!isset($arr['options'])) $arr['options'] = '';
				}
	
				$output .= '<div class="upadmin-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
				
				if (is_array($arr)){
				ksort($arr);
				foreach($arr as $opt=>$val){
					if (in_array($opt, array('label','help','placeholder')) ) {
					$output .= linkpro_admin_field_desc($opt) . '<input type="text" name="'.$specific_field.'-'.$opt.'" id="'.$specific_field.'-'.$opt.'" value="'.stripslashes($val).'" />';
					}
					if (in_array($opt, array('options' , 'security_qa')) ) {
					if ($val != '' && is_array($val) ) $val = implode("\n", $val);
					$output .= '<textarea name="'.$specific_field.'-'.$opt.'" id="'.$specific_field.'-'.$opt.'" cols="40" rows="10">'.stripslashes($val).'</textarea>';
					}
				}
				}
				
				$output .= '</div>';
				
				ksort($arr);
				foreach($arr as $opt=>$val){
					if (!in_array($opt, array('label','help','placeholder','options' , 'security_qa')) ) {
					$output .= "<input type='hidden' name='$specific_field-$opt' id='$specific_field-$opt' value='$val' />";
					}
				}
				
				$output .= linkpro_admin_field_actions($specific_field, $arr);
				$output .= "</li>";
		} else {
			foreach($unsorted as $k=>$arr){
				if (is_array($arr)) {
				if (isset($arr['type']) && $arr['type'] != '') {
					$label = (isset($arr['label'])) ? $arr['label'] : '';						
					if (!isset($arr['help'])) $arr['help'] = '';
					if (!isset($arr['placeholder'])) $arr['placeholder'] = '';
					if (!isset($arr['html'])) $arr['html'] = 0;
					if (!isset($arr['hideable'])) $arr['hideable'] = 0;
					if (!isset($arr['hidden'])) $arr['hidden'] = 0;
					if (!isset($arr['required'])) $arr['required'] = 0;
					if (!isset($arr['locked'])) $arr['locked'] = 0;
					if (!isset($arr['private'])) $arr['private'] = 0;
					if (!isset($arr['ajaxcheck'])) $arr['ajaxcheck'] = '';
					if (!isset($arr['woo'])) $arr['woo'] = 0;
					if (linkpro_get_field_icon($k)) { $arr['icon'] = linkpro_get_field_icon($k); } else { $arr['icon'] = ''; }
					if (!isset($arr['button_text']) && isset($arr['type']) && ( $arr['type'] == 'file' || $arr['type'] == 'picture') ) $arr['button_text'] = '';
					if (!isset($arr['list_id']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' ) ) $arr['list_id'] = '';
					if (!isset($arr['list_text']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' )) $arr['list_text'] = '';
					if (!isset($arr['follower_text']) && isset($arr['type']) && ( $arr['type'] == 'followers' )) $arr['follower_text'] = '';
					if (!isset($arr['security_qa']) && isset($arr['type']) && ( $arr['type'] == 'securityqa')) $arr['security_qa'] = 'Sample Question 1:Answer
Sample Question 2:Answer'; // Security Question new Field
				}
				$woo = isset($arr['woo'])?$arr['woo']:'';
				$output .= "<li class='field woo-".$woo."' id='upadmin-$k'>$label <span class='ufieldkey'>$k</span>";
				
				if (isset($arr['type']) && in_array($arr['type'], array('select','multiselect','checkbox','checkbox-full','radio','radio-full'))){
					if (!isset($arr['options'])) $arr['options'] = '';
				}
				
				$output .= '<div class="upadmin-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
				
				if (is_array($arr)){
				ksort($arr);
				foreach($arr as $opt=>$val){
					if (in_array($opt, array('label','help','placeholder','ajaxcheck','icon','button_text','list_id','list_text','follower_text')) ) {
					$output .= linkpro_admin_field_desc($opt) . '<input type="text" name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" value="'.stripslashes($val).'" />';
					}
					if (in_array($opt, array('options' , 'security_qa')) ) {
					if ($val != '' && is_array($val) ) $val = implode("\n", $val);
					$output .= '<textarea name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" cols="40" rows="10">'.stripslashes($val).'</textarea>';
					}
				}
				}
				
				$output .= '</div>';
			
				ksort($arr);
				foreach($arr as $opt=>$val){
if (!in_array($opt, array('label','help','placeholder','options','ajaxcheck','icon','button_text','list_id','list_text','follower_text' , 'security_qa')) ) {
					if (!is_array($val)){
					$output .= "<input type='hidden' name='$k-$opt' id='$k-$opt' value='$val' />";
					} else {
					$output .= "<input type='hidden' name='$k-$opt' id='$k-$opt' value='options' />";
					}
					}
				}
				
				$output .= linkpro_admin_field_actions($k, $arr);
				$output .= "</li>";
				}
			}
		}
		return $output;
	}
	
	/** List all groups **/
	function linkpro_admin_list_groups(){
		global $linkpro;
		$output = null;
		$groups = $linkpro->groups;
		
		unset($groups['register']);
		unset($groups['edit']);
		unset($groups['view']);
		unset($groups['login']);
		unset($groups['social']);

		$array = array(
			'register' => __('Registration Fields','linkpro'),
			'edit' => __('Edit Profile Fields','linkpro'),
			'login' => __('Login Fields','linkpro'),
			'social' => __('Social Fields','linkpro')
		);
		
		if (is_array($groups) ){
			foreach($groups as $k=>$v){
				$add[$k] = strtoupper($k);
			}
			if (isset($add) && is_array($add)){
				$array = array_merge($array, $add );
			}
		}
		
		foreach($array as $template => $name) {
			$output .= '<div class="upadmin-tpl upadmin-tpl-'.$template.'">
				<form action="" method="post" data-role="'.$template.'" data-group="default" data-loading="'.linkpro_url.'admin/images/loading.gif">
				<div class="upadmin-tpl-head max">
					'.$name.'
					<div class="upadmin-icon-abs">';
						if ($template != 'social' && !isset($add[$template]) ) {
						$output .= '<a href="'.linkpro_admin_link($template).'" class="button upadmin-noajax">'.__('View Page','linkpro').'</a>';
						}
						$output .= '<a href="#" class="button resetgroup">'.__('Reset','linkpro').'</a>
						<a href="#" class="button button-primary saveform">'.__('Save','linkpro').'</a>
						<a href="#" class="max"></a>
					</div>
				</div>
				<div class="upadmin-tpl-body max">
					<ul>'.linkpro_admin_list_fields_by_group($template,'default').'</ul>
				</div>
				</form>
			</div>';

 if($template=='register')
                                         {
								
 							
                                                              $output.='<div class="description">';
                                                             $output.=__(' Fields which user will see at time of registration.','linkpro');    
 								 $output.='</div>'; 
                                         }
		 if($template=='edit')
                                         {
								
 							
                                                             $output.='<div class="description">';
                                                             $output.=__('  Fields which user will see at time of edit profile. ','linkpro'); 
                                                              $output.='</div>'; 
                                         }
                  if($template=='login')
                                         {
								
 							      $output.='<div class="description">';
                                                              $output.=__(' Fields which user will see at time of login. ','linkpro'); 
								 $output.='</div>'; 
                                         }

                 if($template=='social')
                                         {
								
 							  $output.='<div class="description">';
                                                           $output.= __(' Social Fields.','linkpro');
                                                             $output.='</div>'; 
                                         }
		}
		return $output;
	}
	
	/** List one group **/
	function linkpro_admin_list_group($role, $group){
		$output = null;
		$array = array(
			'register' => __('Registration Fields','linkpro'),
			'edit' => __('Edit Profile Fields','linkpro'),
			'login' => __('Login Fields','linkpro'),
			'social' => __('Social Fields','linkpro'),
		);
		foreach($array as $template => $name) {
			if ($template == $role) {
			$output .= '<div class="upadmin-tpl">
				<form action="" method="post" data-role="'.$template.'" data-group="default" data-loading="'.linkpro_url.'admin/images/loading.gif">
				<div class="upadmin-tpl-head max">
					'.$name.'
					<div class="upadmin-icon-abs">';
					
					if ($template != 'social') {
					$output .= '<a href="'.linkpro_admin_link($template).'" class="button upadmin-noajax">'.__('View Page','linkpro').'</a>';
					}
					
					$output .= '<a href="#" class="button resetgroup">'.__('Reset','linkpro').'</a>
						<a href="#" class="button button-primary saveform">'.__('Save','linkpro').'</a>
						<a href="#" class="max"></a>
					</div>
				</div>
				<div class="upadmin-tpl-body max">
					<ul>'.linkpro_admin_list_fields_by_group($template, $group).'</ul>
				</div>
				</form>
			</div>';
			}
		}
		return $output;
	}
	
	/* Field description */
	function linkpro_admin_field_desc($opt) {
		switch ($opt){
			case 'label': $text = __('Label','linkpro'); break;
			case 'help': $text = __('Help Text','linkpro'); break;
			case 'placeholder' : $text = __('Placeholder','linkpro'); break;
			case 'ajaxcheck' : $text = __('Ajax Check Callback (advanced)','linkpro'); break;
			case 'heading' : $text = __('Heading Text','linkpro'); break;
			case 'collapsible' : $text = __('Collapsible Section','linkpro'); break;
			case 'collapsed' : $text = __('Collapsed','linkpro'); break;
			case 'follower_text' : $text = __('followers email alert text','linkpro'); break;			
			case 'button_text' : $text = __('Upload Button Text','linkpro'); break;
			case 'list_id' : $text = __('MailChimp List ID','linkpro'); break;
			case 'list_text' : $text = __('MailChimp Subscribe Text','linkpro'); break;
			case 'icon' : $text = __('Font Icon Code','linkpro'); break;
			case 'security_qa': $text = __('Security Question' , 'linkpro'); break;
		}
		return '<span class="upadmin-field-zone-desc">'.$text.'</span>';
	}
	
	/** List fields based on group **/
	function linkpro_admin_list_fields_by_group($template, $group) {
		$array = get_option('linkpro_fields_groups');
		$output = '<li>'.__('Drag fields / sections into this area','linkpro').'</li>';
		$group = $array[$template][$group];
		if (isset($group) && !empty($group)){
			foreach($group as $k=> $arr){
				if (isset($arr['heading']) && $arr['heading'] != '' || isset($arr['label']) && $arr['label'] != '') {
					if (isset($arr['heading'])) { // seperator
					$output .= '<li class="heading" data-special="'.$k.'"><span>'.$arr['heading'].'</span> <span class="ufieldkey">'.$k.'</span>';
					
					$output .= '<div class="upadmin-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
					
					if (!isset($arr['collapsible'])) $arr['collapsible'] = 0;
					if (!isset($arr['collapsed'])) $arr['collapsed'] = 0;
					
					ksort($arr);
					foreach($arr as $opt=>$val){
						if (in_array($opt, array('heading')) ) {
						$output .= linkpro_admin_field_desc($opt) . '<input type="text" name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" value="'.stripslashes($val).'" />';
						}
						if (in_array($opt, array('collapsible','collapsed')) ) {
						$output .= linkpro_admin_field_desc($opt);
						$output .= "<select name='$k-$opt' id='$k-$opt'>
										<option value='1' ".selected(1, $val, 0).">".__('Yes','linkpro')."</option>
										<option value='0' ".selected(0, $val, 0).">".__('No','linkpro')."</option>
									</select>";
						}
					}
					$output .= '</div>';
					
					ksort($arr);
					foreach($arr as $opt=>$val){
						if (!in_array($opt, array('heading','collapsible','collapsed') ) ) {
						$output .= "<input type='hidden' name='$k-$opt' id='$k-$opt' value='$val' />";
						}
					}
					
					$output .= linkpro_admin_field_actions($k, $arr);
					$output .= '</li>';
					} else {
					
					if (!isset($arr['help'])) $arr['help'] = '';
					if (!isset($arr['placeholder'])) $arr['placeholder'] = '';
					if (!isset($arr['html'])) $arr['html'] = 0;
					if (!isset($arr['hideable'])) $arr['hideable'] = 0;
					if (!isset($arr['hidden'])) $arr['hidden'] = 0;
					if (!isset($arr['required'])) $arr['required'] = 0;
					if (!isset($arr['locked'])) $arr['locked'] = 0;
					if (!isset($arr['private'])) $arr['private'] = 0;
					if (!isset($arr['ajaxcheck'])) $arr['ajaxcheck'] = '';
					if (!isset($arr['woo'])) $arr['woo'] = 0;
					if (linkpro_get_field_icon($k)) { $arr['icon'] = linkpro_get_field_icon($k); } else { $arr['icon'] = ''; }
					if (!isset($arr['button_text']) && isset($arr['type']) && ( $arr['type'] == 'file' || $arr['type'] == 'picture') ) $arr['button_text'] = '';
					if (!isset($arr['list_id']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' )) $arr['list_id'] = '';
					if (!isset($arr['list_text']) && isset($arr['type']) && ( $arr['type'] == 'mailchimp' )) $arr['list_text'] = '';
					if (!isset($arr['follower_text']) && isset($arr['type']) && ( $arr['type'] == 'followers' )) $arr['follower_text'] = '';
					if (!isset($arr['security_qa']) && isset($arr['type']) && ( $arr['type'] == 'securityqa')) $arr['security_qa'] = 'Sample Question 1:Answer
Sample Question 2:Answer'; // Security Question new Field
					$output .= '<li class="field woo-'.$arr['woo'].'">'.$arr['label'] . '<span class="ufieldkey">'.$k.'</span>';
					
					$output .= '<div class="upadmin-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
					
					if (isset($arr['type']) && in_array($arr['type'], array('select','multiselect','checkbox','checkbox-full','radio','radio-full' , 'security_qa'))){
						if (!isset($arr['options'])) $arr['options'] = '';
					}
					
					if (is_array($arr)){
					ksort($arr);
					foreach($arr as $opt=>$val){
						if (in_array($opt, array('label','help','placeholder','ajaxcheck','icon','button_text','list_id','list_text','follower_text')) ) {
						$output .= linkpro_admin_field_desc($opt) . '<input type="text" name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" value="'.stripslashes($val).'" />';
						}
						if (in_array($opt, array('options' , 'security_qa'))){
						if ($val != '' && is_array($val) ) $val = implode("\n", $val);
						$output .= '<textarea name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" cols="40" rows="10">'.stripslashes($val).'</textarea>';
						}
					}
					}
					
					$output .= '</div>';
					
					ksort($arr);
					foreach($arr as $opt=>$val){
						if (!in_array($opt, array('label','help','placeholder','options','ajaxcheck','icon','button_text','list_id','list_text','follower_text' , 'security_qa' )) ) {
						if (!is_array($val)){
						$output .= "<input type='hidden' name='$k-$opt' id='$k-$opt' value='$val' />";
						} else {
						$output .= "<input type='hidden' name='$k-$opt' id='$k-$opt' value='options' />";
						}
						}
					}
					
					$output .= linkpro_admin_field_actions($k, $arr);
					
					$output .= '</li>';
					
					}
				}
			}
		}
		return $output;
	}
	
	/* new section empty */
	function linkpro_admin_new_section(){
		$output = null;
		$output .= '<li class="heading" data-special="newsection"><span>'.__('Add Seperator / Section','linkpro').'</span>';
		
		$k = 'newsection';
		$arr['heading'] = __('My Custom Heading','linkpro');
		$arr['collapsible'] = 0;
		$arr['collapsed'] = 0;
		
			$output .= '<div class="upadmin-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
			
			ksort($arr);
			foreach($arr as $opt=>$val){
				if (in_array($opt, array('heading')) ) {
				$output .= linkpro_admin_field_desc($opt) . '<input type="text" name="'.$k.'-'.$opt.'" id="'.$k.'-'.$opt.'" value="'.stripslashes($val).'" />';
				}
				if (in_array($opt, array('collapsible','collapsed')) ) {
				$output .= linkpro_admin_field_desc($opt);
				$output .= "<select name='$k-$opt' id='$k-$opt'>
								<option value='1' ".selected(1, $val, 0).">".__('Yes','linkpro')."</option>
								<option value='0' ".selected(0, $val, 0).">".__('No','linkpro')."</option>
							</select>";
				}
			}
			$output .= '</div>';
			
		$output .= linkpro_admin_field_actions();
		$output .= '</li>';
		return $output;
	}
	
	/** Get link of specific page */
	function linkpro_admin_link($template){
		$pages = get_option('linkpro_pages');
		if ($template=='view') $template = 'profile';
		if (isset($pages[$template])){
			return get_page_link( $pages[$template] );
		}
	}
	
	/* Check page exists */
	function linkpro_admin_page_exists($template) {
		$pages = get_option('linkpro_pages');
		if ($template=='view') $template = 'profile';
		if (isset($pages[$template]))
			$page_id = $pages[$template];
			if(isset($page_id)){
				$page_data = get_page($page_id);
			}
			if(isset($page_data) && $page_data->post_status == 'publish'){
				return true;
			}
		return false;
	}
	
	/* Broken page notification */
	function linkpro_admin_broken_page() {
		return '<div class="upadmin-broken">'.__('Broken page. Please rebuild plugin pages.','linkpro').'</div>';
	}
	
	/** Display field types **/
	function linkpro_admin_field_types(){
		$array = array(
			'text' => __('Text Input','linkpro'),
			'picture' => __('Photo Upload','linkpro'),
			'file' => __('File Upload','linkpro'),
			'textarea' => __('Textarea','linkpro'),
			'select' => __('Select Dropdown','linkpro'),
			'multiselect' => __('Multiselect Box','linkpro'),
			'checkbox' => __('Checkbox (floating)','linkpro'),
			'checkbox-full' => __('Checkbox (full width)','linkpro'),
			'radio' => __('Radio Choice (floating)','linkpro'),
			'radio-full' => __('Radio Choice (full width)','linkpro'),
			'datepicker' => __('Date Picker','linkpro'),
			'mailchimp' => __('MailChimp Newsletter Subscription','linkpro'),
			'password' => __('Password Field','linkpro'),
			'passwordstrength' => __('Password Strength Meter','linkpro'),
			'securityqa'	=> __('Security Question Answer' , 'linkpro') // Security Question new Filled
		);
		foreach($array as $k=>$v){
			echo '<option value="'.$k.'">'.$v.'</option>';
		}
	}
	
	/**Sync usermeta **/
	function linkpro_admin_usermeta(){
		$array = get_user_meta( get_current_user_id() );
		echo '<option value="">&mdash; Choose an existing usermeta &mdash;</option>';
		ksort($array);
		foreach($array as $k=>$v){
			if (!strstr($k, 'hide_')){
			echo '<option value="'.$k.'">'.$k.'</option>';
			}
		}
	}
	
	/**
	Resort fields
	**/
	add_action('wp_ajax_nopriv_linkpro_field_sort', 'linkpro_field_sort');
	add_action('wp_ajax_linkpro_field_sort', 'linkpro_field_sort');
	function linkpro_field_sort(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
		$output='';	
		
		$order = explode(',', $_POST['order']);
		foreach($order as $item) {
			$item = str_replace('upadmin-','',$item);
			$clean[$item] = $item;
		}
		
		$unsorted = get_option('linkpro_fields');
		$sorted = array_merge(array_flip( $clean ), $unsorted);
		update_option('linkpro_fields', $sorted);
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Create a new field in backend
	**/
	add_action('wp_ajax_nopriv_linkpro_create_field', 'linkpro_create_field');
	add_action('wp_ajax_linkpro_create_field', 'linkpro_create_field');
	function linkpro_create_field(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		$newfield= array();
		$upadmin_n_sync = isset($_POST['upadmin_n_sync'])?$_POST['upadmin_n_sync']:'';
		$upadmin_n_key = isset($_POST['upadmin_n_key'])?$_POST['upadmin_n_key']:'';
		if ( empty($_POST['upadmin_n_title']) ){
			$output['error']['upadmin_n_title'] = __('Each field must have a title.','linkpro');
		} elseif ( empty($_POST['upadmin_n_key']) && empty($_POST['upadmin_n_sync']) ){
			$output['error']['upadmin_n_key'] = __('Please enter a unique key or choose an existing usermeta.','linkpro');
		} else {
			
			if (isset($upadmin_n_sync) && !empty($upadmin_n_sync) ){
				$key=$upadmin_n_sync;
			}else{
				$key=$upadmin_n_key;
			}
		
			// check that field key is unique
			$fields = get_option('linkpro_fields');
			if (isset($fields[$upadmin_n_sync]) && !empty($upadmin_n_sync) ){
				$output['error']['upadmin_n_sync'] = __('This existing usermeta already exists in your fields list below.','linkpro');
			} elseif (isset($fields[$upadmin_n_key]) && !empty($upadmin_n_key) ){
				$output['error']['upadmin_n_key'] = __('This unique key already exists in your fields list below.','linkpro');
				
			} else {
			
			// create the field
			
			$newfield[$key] = array(
				'_builtin' => 0,
				'type' => $_POST['upadmin_n_type']
			);
			
			if (isset($_POST['upadmin_n_title']) && !empty($_POST['upadmin_n_title'])) {
				$newfield[$key]['label'] = $_POST['upadmin_n_title'];
			}
			
			if (isset($_POST['upadmin_n_help']) && !empty($_POST['upadmin_n_help'])) {
				$newfield[$key]['help'] = $_POST['upadmin_n_help'];
			}
			
			if (isset($_POST['upadmin_n_ph']) && !empty($_POST['upadmin_n_ph'])) {
				$newfield[$key]['placeholder'] = $_POST['upadmin_n_ph'];
			}
			
			if (isset($_POST['upadmin_n_filetypes']) && !empty($_POST['upadmin_n_filetypes'])){
				$newfield[$key]['allowed_extensions'] = str_replace(' ','', $_POST['upadmin_n_filetypes']);
			} elseif ($_POST['upadmin_n_type'] == 'file'){
				$newfield[$key]['allowed_extensions'] = 'zip,pdf,txt';
			}
			
			if ( isset($_POST['upadmin_n_choices_builtin']) && !empty($_POST['upadmin_n_choices_builtin']) ){
				$newfield[$key]['options'] = linkpro_filter_to_array( $upadmin_n_choices_builtin );
			} elseif ( isset($_POST['upadmin_n_choices']) && !empty($_POST['upadmin_n_choices']) ){
				$n_choices = preg_split('/[\r\n]+/', $_POST['upadmin_n_choices'], -1, PREG_SPLIT_NO_EMPTY);
				$newfield[$key]['options'] = $n_choices;
			}
			
			/* finished creating new field */
			
			$allfields = $newfield+$fields;
			update_option('linkpro_fields',$allfields);
			
			$output['html'] = linkpro_admin_list_fields($key);
			$output['count'] = linkpro_admin_count_fields();
			
			}
			
		}
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Restore default fields in backend
	**/
	add_action('wp_ajax_nopriv_linkpro_restore_builtin_fields', 'linkpro_restore_builtin_fields');
	add_action('wp_ajax_linkpro_restore_builtin_fields', 'linkpro_restore_builtin_fields');
	function linkpro_restore_builtin_fields(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
		
		$_builtin = get_option('linkpro_fields_builtin');
		update_option('linkpro_fields',$_builtin);
		delete_option('linkpro_pre_icons_setup');
		delete_option('linkpro_update_1036');     // Added by Yogesh to solve profile background custom field issue
		$output['html'] = linkpro_admin_list_fields();
		$output['count'] = linkpro_admin_count_fields();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Restore default groups in backend
	**/
	add_action('wp_ajax_nopriv_linkpro_restore_builtin_groups', 'linkpro_restore_builtin_groups');
	add_action('wp_ajax_linkpro_restore_builtin_groups', 'linkpro_restore_builtin_groups');
	function linkpro_restore_builtin_groups(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
		
		$_builtin = get_option('linkpro_fields_groups_default');
		update_option('linkpro_fields_groups',$_builtin);
		
		$output['html'] = linkpro_admin_list_groups();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Restore default group in backend
	**/
	add_action('wp_ajax_nopriv_linkpro_reset_group', 'linkpro_reset_group');
	add_action('wp_ajax_linkpro_reset_group', 'linkpro_reset_group');
	function linkpro_reset_group(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
		
		$_builtin_group = get_option('linkpro_fields_groups_default_'.$_POST['role']);
		$all = get_option('linkpro_fields_groups');
		$all[$_POST['role']]['default'] = $_builtin_group;
		update_option('linkpro_fields_groups',$all);
		
		$output['html'] = linkpro_admin_list_group($_POST['role'], 'default');
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/* Get choices of field */
	function linkpro_admin_field_choices($key){
		$fields = get_option('linkpro_fields');
		return $fields[$key]['options'];
	}
	
	/**
	Save/update field groups
	**/
	add_action('wp_ajax_nopriv_linkpro_save_group', 'linkpro_save_group');
	add_action('wp_ajax_linkpro_save_group', 'linkpro_save_group');
	function linkpro_save_group(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro;
		$fields = $linkpro->fields;
		$output = '';

		// Save field group
		$groups = get_option('linkpro_fields_groups');
		$groups[$_POST['role']][$_POST['group']] = '';
		foreach($_POST as $k => $v){
			$encoding=mb_detect_encoding($v,'auto');
			if($encoding!='ASCII' && $encoding!='UTF-8')
			{
				$v=mb_convert_encoding($v,'UTF-8','auto');
			}
			$v = stripslashes($v);
			if ($k != 'role' && $k != 'group' && $k != 'action'){
				$key = explode('-',$k,2);
				if ($key[1] != 'options' && $key[1] != 'icon'){
					$groups[$_POST['role']][$_POST['group']][$key[0]][$key[1]] = $v;
				} elseif ($key[1] == 'options') {
					$groups[$_POST['role']][$_POST['group']][$key[0]][$key[1]] = preg_split('/[\r\n]+/', $v, -1, PREG_SPLIT_NO_EMPTY);
				} elseif ($key[1] == 'icon') {
					$fields[$key[0]]['icon'] = $v;
				}
			}
		}
		
		//Save view group
		unset($groups['view']);
		$groups['view'] = $groups['edit'];
		
		update_option('linkpro_fields_groups', $groups);
		update_option('linkpro_fields',$fields);
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	User signup deny
	**/
	add_action('wp_ajax_nopriv_linkpro_admin_user_deny', 'linkpro_admin_user_deny');
	add_action('wp_ajax_linkpro_admin_user_deny', 'linkpro_admin_user_deny');
	function linkpro_admin_user_deny(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->delete_user($user_id);
		$output['count'] = $linkpro_admin->get_pending_verify_requests_count_only();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	User signup approve
	**/
	add_action('wp_ajax_nopriv_linkpro_admin_user_approve', 'linkpro_admin_user_approve');
	add_action('wp_ajax_linkpro_admin_user_approve', 'linkpro_admin_user_approve');
	function linkpro_admin_user_approve(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->activate($user_id);
		$output['count'] = $linkpro_admin->get_pending_verify_requests_count_only();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Verify a user instantly
	**/
	add_action('wp_ajax_nopriv_linkpro_verify_user', 'linkpro_verify_user');
	add_action('wp_ajax_linkpro_verify_user', 'linkpro_verify_user');
	function linkpro_verify_user(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->verify($user_id);
		
		$arr = get_option('linkpro_verify_requests');
		if (isset($arr) && is_array($arr)){
			$arr = array_diff($arr, array( $user_id ));
			update_option('linkpro_verify_requests', $arr);
		}
		
		$output['count'] = $linkpro_admin->get_pending_verify_requests_count_only();
		
		$output['admin_tpl'] = '<a href="#" class="button button-primary upadmin-unverify-u" data-user="'.$user_id.'">'.linkpro_get_badge('verified').'</a>';
		if ($linkpro->get_verified_status($user_id) == 0){
			$output['admin_tpl'] .= '<a href="#" class="button upadmin-invite-u" data-user="'.$user_id.'">'.__('Verified Invite','linkpro').'</a>';
		}
		
		do_action('linkpro_after_account_verified');
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Unverify a user instantly
	**/
	add_action('wp_ajax_nopriv_linkpro_unverify_user', 'linkpro_unverify_user');
	add_action('wp_ajax_linkpro_unverify_user', 'linkpro_unverify_user');
	function linkpro_unverify_user(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->unverify($user_id);
		
		$output['count'] = $linkpro_admin->get_pending_verify_requests_count_only();
		
		$output['admin_tpl'] = '<a href="#" class="button upadmin-verify-u" data-user="'.$user_id.'">'.linkpro_get_badge('unverified').'</a>';
		if ($linkpro->get_verified_status($user_id) == 0){
			$output['admin_tpl'] .= '<a href="#" class="button upadmin-invite-u" data-user="'.$user_id.'">'.__('Verified Invite','linkpro').'</a>';
		}
		
		do_action('linkpro_after_account_unverified');
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	 Block an user instantly
	 **/
	add_action('wp_ajax_nopriv_linkpro_block_account', 'linkpro_block_account');
	add_action('wp_ajax_linkpro_block_account', 'linkpro_block_account');
	function linkpro_block_account(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->block_account($user_id);
		$output['admin_tpl'] = '<a href="#" class="button upadmin-unblock-u" data-user="'.$user_id.'">'.linkpro_get_badge('blocked').'</a><span class="button" data-user="'.$user_id.'">'.__('Account Blocked','linkpro').'</span>';
		do_action('linkpro_after_account_blocked');
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	 Unblock an user instantly
	 **/
	add_action('wp_ajax_nopriv_linkpro_unblock_account', 'linkpro_unblock_account');
	add_action('wp_ajax_linkpro_unblock_account', 'linkpro_unblock_account');
	function linkpro_unblock_account(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->unblock_account($user_id);
	
	
		$output['admin_tpl'] = '<a href="#" class="button upadmin-block-u" data-user="'.$user_id.'">'.linkpro_get_badge('unblocked').'</a>';
		do_action('linkpro_after_account_unblocked');
	
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	/**
	Send a verification invitation
	**/
	add_action('wp_ajax_nopriv_linkpro_verify_invite', 'linkpro_verify_invite');
	add_action('wp_ajax_linkpro_verify_invite', 'linkpro_verify_invite');
	function linkpro_verify_invite(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro, $linkpro_admin;
		$output = '';
		$user_id = $_POST['user_id'];
		$linkpro->new_invitation_verify($user_id);

		$output['admin_tpl'] = '<a href="#" class="button upadmin-verify-u" data-user="'.$user_id.'">'.linkpro_get_badge('unverified').'</a>';
		if ($linkpro->get_verified_status($user_id) == 0){
			$output['admin_tpl'] .= '&nbsp;&nbsp;' . __('Invitation sent!','linkpro');
		}
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Delete a default field
	**/
	add_action('wp_ajax_nopriv_linkpro_delete_field', 'linkpro_delete_field');
	add_action('wp_ajax_linkpro_delete_field', 'linkpro_delete_field');
	function linkpro_delete_field(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro;
		$field = $_POST['field'];
		$output = '';
		
		$fields = $linkpro->fields;
		unset($fields[$field]);
		update_option('linkpro_fields', $fields);

		$output['count'] = linkpro_admin_count_fields();
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}
	
	/**
	Update a default field
	**/
	add_action('wp_ajax_nopriv_linkpro_update_field', 'linkpro_update_field');
	add_action('wp_ajax_linkpro_update_field', 'linkpro_update_field');
	function linkpro_update_field(){
		if (!current_user_can('manage_options'))
			die(); // admin priv
			
		global $linkpro;
		$output = '';
		$field = $_POST['field'];
		unset($_POST['action']);
		unset($_POST['field']);
		
		$linkpro->update_field($field, $_POST);
		
		$output=json_encode($output);
		if(is_array($output)){ print_r($output); }else{ echo $output; } die;
	}

