<form method="post" action="">


<h3><i class="linkpro-icon-facebook"></i><?php _e('Facebook Integration','linkpro'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="facebook_connect"><?php _e('Allow Facebook Social Connect','linkpro'); ?></label></th>
		<td>
			<select name="facebook_connect" id="facebook_connect" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', linkpro_get_option('facebook_connect')); ?>><?php _e('Yes','linkpro'); ?></option>
				<option value="0" <?php selected('0', linkpro_get_option('facebook_connect')); ?>><?php _e('No','linkpro'); ?></option>
			</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="facebook_app_id"><?php _e('Facebook App ID','linkpro'); ?></label></th>
		<td>
			<input type="text" name="facebook_app_id" id="facebook_app_id" value="<?php echo linkpro_get_option('facebook_app_id'); ?>" class="regular-text" />
			<span class="description"><?php _e('Open <a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a> create a new app and edit its settings to make it work on your domain. In App Settings, please paste the App ID or API Key into this field.','linkpro'); ?></span>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="facebook_app_secret"><?php _e('Facebook Secret Key','linkpro'); ?></label></th>
		<td>
			<input type="text" name="facebook_app_secret" id="facebook_app_secret" value="<?php echo linkpro_get_option('facebook_app_secret'); ?>" class="regular-text" />
			<span class="description"><?php _e('Open <a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a> create a new app and edit its settings to make it work on your domain. In App Settings, please paste the App Secret Key into this field.','linkpro'); ?></span>
		</td>
	</tr>
	
	
</table>


<!--Globla hook for adding extra setting fields   Added by Rahul-->
<?php do_action("linkpro_add_setting_fields");?>
<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','linkpro'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','linkpro'); ?>"  />
</p>

</form>
