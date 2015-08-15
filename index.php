<?php
/*
Plugin Name: LinkPro
Plugin URI: http://dodable.com/linkpro
Description: Permite a tus usuarios de wordpress vincular su cuenta de facebook, enviar notificaciones de nuevos posts.
Version: 0.1
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
require_once linkpro_path . "functions/shortcode-main.php";
require_once linkpro_path . "functions/facebook.php";



?>