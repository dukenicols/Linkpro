<?php

class linkpro_mu_admin {

	var $options;

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'linkpro';
		$this->subslug = 'linkpro-multi';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( linkpro_mu_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('linkpro_admin_menu_hook', array(&$this, 'add_menu'), 9);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		
	}
	
	function admin_init() {
	
		$this->tabs = array(
			'settings' => __('Setup Multiple Forms','linkpro'),
		);
		$this->default_tab = 'settings';
		
		$this->options = get_option('linkpro_mu');
		if (!get_option('linkpro_mu')) {
			update_option('linkpro_mu', linkpro_mu_default_options() );
		}
		
	}
	
	function get_pending_verify_requests_count(){
		$pending = get_option('linkpro_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			return '<span class="upadmin-bubble-new">'.count($pending).'</span>';
		}
	}
	
	function admin_head(){

	}

	function add_styles(){
		wp_register_script( 'linkpro_mu_admin', linkpro_mu_url.'admin/scripts/admin.js' );
		wp_enqueue_script( 'linkpro_mu_admin' );
	}
	
	function add_menu() {
		add_submenu_page( 'linkpro', __('Multiple Registration Forms','linkpro'), __('Multiple Registration Forms','linkpro'), 'manage_options', 'linkpro-multi', array(&$this, 'admin_page') );
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
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->subslug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			}
			include_once linkpro_mu_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	function save() {
		
		/* other post fields */
		foreach($_POST as $key => $value) {
			if ($key != 'submit') {
				if (!is_array($_POST[$key])) {
					$this->options[$key] = esc_attr($_POST[$key]);
				} else {
					$this->options[$key] = $_POST[$key];
				}
			}
		}
		
		update_option('linkpro_mu', $this->options);
		echo '<div class="updated"><p><strong>'.__('Settings saved.','linkpro').'</strong></p></div>';
	}

	function reset() {
		update_option('linkpro_mu', linkpro_mu_default_options() );
		$this->options = array_merge( $this->options, linkpro_mu_default_options() );
		echo '<div class="updated"><p><strong>'.__('Settings are reset to default.','linkpro').'</strong></p></div>';
	}
	
	function rebuild_pages() {
		linkpro_mu_setup($rebuild=1);
		echo '<div class="updated"><p><strong>'.__('Your plugin pages have been rebuilt successfully.','linkpro').'</strong></p></div>';
	}

	function admin_page() {

		if (isset($_POST['submit'])) {
			$this->save();
		}

		if (isset($_POST['reset-options'])) {
			$this->reset();
		}
		
		if (isset($_POST['rebuild-pages'])) {
			$this->rebuild_pages();
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

$linkpro_mu_admin = new linkpro_mu_admin();