<?php
/*
Plugin Name: LinkPro
Plugin URI: http://dodable.com/linkpro
Description: Permite a tus usuarios de wordpress vincular su cuenta de facebook, enviar notificaciones de nuevos posts.
Version: 1.0
Author: Nicolas Duke
Author URI: http://dodable.com
License: MIT
*/


define('linkpro_url', plugin_dir_url(__FILE__ ));
define('linkpro_path', plugin_dir_path(__FILE__ ));

	/* init */



function linkpro_init() {
		
		if(!isset($_SESSION))
		{
			session_start();
		}
		
		global $linkpro;
		
		$linkpro->do_uploads_dir();
		
		//TODO add multi languages support
		//load_plugin_textdomain('linkpro', false, dirname(plugin_basename(__FILE__)) . '/languages');
		

		
if(!class_exists('FacebookSession')):

		require_once linkpro_path . 'lib/facebook/facebook.php';

endif;
		
}

add_action('init', 'linkpro_init');

/* functions */
require_once linkpro_path . "functions/api.php";
require_once linkpro_path . "functions/defaults.php";
require_once linkpro_path . "functions/user-functions.php";
require_once linkpro_path . "functions/shortcode-functions.php";
require_once linkpro_path . "functions/shortcode-main.php";
require_once linkpro_path . "functions/facebook.php";

/* administration */
	if (is_admin()){
		foreach (glob(linkpro_path . 'admin/*.php') as $filename) { include $filename; }
	}

/* load addons */
	require_once linkpro_path . 'addons/multiforms/index.php';




?>