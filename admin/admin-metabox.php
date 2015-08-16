<?php

add_action( 'post_submitbox_misc_actions', 'linkpro_edit_restrict' );
add_action( 'save_post', 'save_linkpro_edit_restrict' );

function linkpro_edit_restrict() {
    global $post;
	echo '<div class="misc-pub-section misc-pub-section-last misc-pub-linkpro" style="border-top: 1px solid #eee;">';
	wp_nonce_field( plugin_basename(__FILE__), 'linkpro_edit_restrict_nonce' );
	$val = get_post_meta( $post->ID, '_linkpro_edit_restrict', true ) ? get_post_meta( $post->ID, '_linkpro_edit_restrict', true ) : 'none';
	echo '<input type="radio" name="linkpro_edit_restrict" id="linkpro_edit_restrict-none" value="none" '.checked($val,'none',false).' /> <label for="linkpro_edit_restrict-none" class="select-it">'.__('No restriction','linkpro').'</label><br />';
	echo '<input type="radio" name="linkpro_edit_restrict" id="linkpro_edit_restrict-true" value="true" '.checked($val,'true',false).'/> <label for="linkpro_edit_restrict-true" class="select-it">'.__('Restricted to All Members','linkpro').'</label><br />';
	echo '<input type="radio" name="linkpro_edit_restrict" id="linkpro_edit_restrict-verified" value="verified" '.checked($val,'verified',false).'/> <label for="linkpro_edit_restrict-verified" class="select-it">'.__('Restricted to <b>Verified Accounts</b>','linkpro').'</label><br />';
	echo '<input type="radio" name="linkpro_edit_restrict" id="linkpro_edit_restrict-roles" value="roles" '.checked($val,'roles',false).'/> <label for="linkpro_edit_restrict-roles" class="select-it">'.__('Restricted to <b>User Roles</b>','linkpro').'</label>';
	
	?>
	<p class="restrict_roles"><select name="restrict_roles[]" id="restrict_roles[]" multiple="multiple" class="chosen-select" style="width:300px" data-placeholder="<?php _e('Select roles','linkpro'); ?>">
		<?php
		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
			$roles = $wp_roles->get_names();
			foreach($roles as $k=>$v) {
			?>
			<option value="<?php echo $k; ?>" <?php linkpro_is_selected($k, get_post_meta( $post->ID, 'restrict_roles', true) ); ?>><?php echo $v; ?></option>
		<?php } ?>
	</select></p>
	<?php
	
	echo '</div>';
}

function save_linkpro_edit_restrict($post_id) {

    if (!isset($_POST['post_type']) )
        return $post_id;

    if ( !wp_verify_nonce( $_POST['linkpro_edit_restrict_nonce'], plugin_basename(__FILE__) ) )
        return $post_id;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

    if ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    
    if (!isset($_POST['linkpro_edit_restrict']))
        return $post_id;
    else {
        $mydata = $_POST['linkpro_edit_restrict'];
        update_post_meta( $post_id, '_linkpro_edit_restrict', $_POST['linkpro_edit_restrict'] );
		
		update_post_meta( $post_id, 'restrict_roles', '');
		
		if (isset($_POST['restrict_roles']) && !empty($_POST['restrict_roles']) && $_POST['linkpro_edit_restrict'] == 'roles'){
			update_post_meta( $post_id, 'restrict_roles', $_POST['restrict_roles'] );
		}
		
    }

}