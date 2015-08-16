<?php

class linkpro_admin {

	var $options;

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'linkpro';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( linkpro_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('admin_menu', array(&$this, 'add_menu'), 9);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		
	}

	/* Create export download link */
	function create_export_download_link($echo = false, $setting='linkpro_export_options'){
		$site_url = get_bloginfo('url');
		$args = array(
			$setting => 'safe_download',
			'nonce' => wp_create_nonce($setting)
		);
		$export_url = add_query_arg($args, esc_url($site_url));
		if ($echo === true)
			echo '<a href="'.$export_url.'" class="button" target="_blank">'.__('Download Export','linkpro').'</a>';
		elseif ($echo == 'url')
			return $export_url;
		return '<a href="'.$export_url.'" class="button" target="_blank">'.__('Download Export','linkpro').'</a>';
	}
	
	function admin_init() {
		
		$this->tabs = array(
			'settings' => __('Settings','linkpro'),
		);
		$this->default_tab = 'settings';
		
		$this->options = get_option('linkpro');
		if (!get_option('linkpro')) {
			update_option('linkpro', linkpro_default_options() );
		}
		
	}
	
	function get_pending_verify_requests_count(){
		$count = 0;
		
		// verification status
		$pending = get_option('linkpro_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			$count = count($pending);
		}
		
		// waiting email approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		// waiting admin approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		if ($count > 0){
			return '<span class="upadmin-bubble-new">'.$count.'</span>';
		}
	}
	
	function get_pending_verify_requests_count_only(){
		$count = 0;
		
		// verification status
		$pending = get_option('linkpro_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			$count = count($pending);
		}
		
		// waiting email approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		// waiting admin approve
		$users = get_users(array(
			'meta_key'     => '_account_status',
			'meta_value'   => 'pending_admin',
			'meta_compare' => '=',
		));
		if (isset($users)) {
			$count += count($users);
		}
		
		if ($count > 0){
			return $count;
		}
	}
	
	function delete_pending_request($user_id){
		$arr = get_option('linkpro_verify_requests');
		if (isset($arr) && is_array($arr)){
			$arr = array_diff($arr, array( $user_id ));
			update_option('linkpro_verify_requests', $arr);
		}
	}
	
	function admin_head(){
		$screen = get_current_screen();
		$slug = $this->slug;
		$icon = linkpro_url . "admin/images/$slug-32.png";
		echo '<style type="text/css">';
			if (in_array( $screen->id, array( $slug ) ) || strstr($screen->id, $slug) ) {
				print "#icon-$slug {background: url('{$icon}') no-repeat left;}";
			}
		echo '</style>';
		if(is_rtl()){
			?>
				<script type="text/javascript">
					jQuery(function(){
						jQuery('select').attr('class' , jQuery('select').attr('class')+'chosen-rtl');
						jQuery('.chosen-container-single').attr('class' , 'chosen-container chosen-container-single chosen-rtl');
					});
				</script>
			<?php
		}
	}

	function add_styles(){
	
		wp_register_style('linkpro_admin', linkpro_url.'admin/css/admin.css');
		wp_enqueue_style('linkpro_admin');
		
		if ( linkpro_get_option('rtl') ) {
			$css = 'css/linkpro.min.css';
		} else {
			$css = 'css/linkpro-rtl.min.css';
		}
		wp_register_style('linkpro_admin_fa', linkpro_url . $css);
		wp_enqueue_style('linkpro_admin_fa');
		
		wp_register_style('linkpro_chosen', linkpro_url . 'skins/default/style.css');
		wp_enqueue_style('linkpro_chosen');
		
		wp_register_script('linkpro_chosen', linkpro_url . 'admin/scripts/admin-chosen.js');
		wp_enqueue_script('linkpro_chosen');
		
		wp_register_script( 'linkpro_admin', linkpro_url.'admin/scripts/admin.js', array( 
			'jquery',
			'jquery-ui-core',
			'jquery-ui-draggable',
			'jquery-ui-droppable',
			'jquery-ui-sortable'
		) );
		wp_enqueue_script( 'linkpro_admin' );
		
	}
	
	function add_menu() {
	
		$pending_count = $this->get_pending_verify_requests_count_only();
		$pending_title = esc_attr( sprintf(__( '%d new verification requests','linkpro'), $pending_count ) );
		if ($pending_count > 0){
		$menu_label = sprintf( __( 'Link Pro %s','linkpro' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
		} else {
		$menu_label = __('Link Pro','linkpro');
		}
		
		add_menu_page( __('linkpro','linkpro'), $menu_label, 'manage_options', $this->slug, array(&$this, 'admin_page'), linkpro_url .'admin/images/'.$this->slug.'-16.png', '200.150');
		
		do_action('linkpro_admin_menu_hook');
	
	}

	function admin_tabs( $current = null ) {
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = $_GET['tab'];
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->slug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			}
			require_once linkpro_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	function do_action(){
		global $linkpro;
		if ($_GET['linkpro_act'] == 'clear_unused_uploads'){
			$files = glob( $linkpro->upload_base_dir . '*');
			$i = 0;
			foreach($files as $file){
				if(is_file($file)) {
					$i++;
					unlink($file);
				}
			}
			echo '<div class="updated"><p><strong>'.sprintf(__('%s files deleted.','linkpro'), $i).'</strong></p></div>';
		}
		if ($_GET['linkpro_act'] == 'clear_deleted_users') {
			$files = glob( $linkpro->upload_base_dir . '*');
			$i = 0;
			foreach($files as $file){
				if(!is_file($file)) {
					if (!$linkpro->user_exists( basename($file) )) {
						$i++;
						$linkpro->delete_folder($file);
					}
				}
			}
			echo '<div class="updated"><p><strong>'.sprintf(__('%s unused folders deleted.','linkpro'), $i).'</strong></p></div>';
		}
		if ($_GET['linkpro_act'] == 'clear_cache') {
			global $linkpro;
			$linkpro->clear_cache();
			echo '<div class="updated"><p><strong>'.sprintf(__('%s Members Cache Clear .','linkpro'), $i).'</strong></p></div>';
		}
		if ($_GET['linkpro_act'] == 'reset_online_users') {
			delete_transient('linkpro_users_online');
			echo '<div class="updated"><p><strong>'.__('Online users data is reset.','linkpro').'</strong></p></div>';
		}
		if ($_GET['linkpro_act'] == 'clear_activity') {
			delete_option('linkpro_activity');
			echo '<div class="updated"><p><strong>'.__('Activity stream has been reset.','linkpro').'</strong></p></div>';
		}
	}
	
	function save() {
	
		/* restrict tab */
		if (isset($_GET['tab']) && $_GET['tab'] == 'restrict'){
			$this->options['linkpro_restricted_pages'] = '';
		}
		
		/* field roles tab */
		if (isset($_GET['tab']) && $_GET['tab'] == 'fieldroles'){
			$fields = get_option('linkpro_fields');
			foreach($fields as $key => $field){
				$this->options[$key.'_roles'] = '';
			}
		}
		
		/* roles that can view profiles */
		if (isset($_GET['tab']) && $_GET['tab'] == 'settings'){
			$this->options['roles_can_view_profiles'] = '';
		}
		
		/* other post fields */
		
		if(!isset($_POST['allowed_roles']))
		{
			$this->options['allowed_roles']=array();
		}
		if(!isset($_POST['roles_can_view_profiles']))
		{
			$this->options['roles_can_view_profiles']=array();
		}
		foreach($_POST as $key => $value) {
	
			if ($key != 'submit') {
				if (!is_array($_POST[$key])) {
				
					$this->options[$key] = stripslashes( esc_attr($_POST[$key]) );
				} else {
					
				
					$this->options[$key] = $_POST[$key];
					
				}
			}
		}
		
		update_option('linkpro', $this->options);
		
		echo '<div class="updated"><p><strong>'.__('Settings saved.','linkpro').'</strong></p></div>';
	}

	function reset() {
		update_option('linkpro', linkpro_default_options() );
		$this->options = array_merge( $this->options, linkpro_default_options() );
		echo '<div class="updated"><p><strong>'.__('Settings are reset to default.','linkpro').'</strong></p></div>';
	}
		
	
	function reinstall(){
		global $linkpro;
		
		// trash current pages
		$pages = get_option('linkpro_pages') + get_option('linkpro_sc_pages');
		foreach( $pages as $page_id ) {
			wp_delete_post( $page_id, true );
		}
		
		// delete existing pages from settings
		delete_option('linkpro_pages');
		
		// trash linkpro options
		foreach( wp_load_alloptions() as $k => $v) {
			if (strstr($k, 'linkpro')){
				delete_option( $k );
			}
		}
		
		// install default fields again
		linkpro_init_setup();

		// update icons
		if (!get_option('linkpro_pre_icons_setup') ) {
			$linkpro->update_field_icons();
		}
		
		echo '<div class="updated"><p><strong>'.__('linkpro has been reset to factory settings.','linkpro').'</strong></p></div>';
	}
	

	
	function admin_page() {
	
		
		if (isset($_POST['submit'])) {
			$this->save();
		}
		
		if (isset($_POST['reset-options'])) {
			$this->reset();
		}
		
		
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin">
		
			<?php linkpro_admin_bar(); ?>
			
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo $this->slug; ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}

$linkpro_admin = new linkpro_admin();
