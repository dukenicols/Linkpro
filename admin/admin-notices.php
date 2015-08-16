<?php

function linkpro_admin_notice($message, $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	echo "<p><strong>$message</strong></p></div>";
}

function linkpro_admin_notices()
{
	if (current_user_can('manage_options') && get_option('linkpro_trial') == 1) {
		linkpro_admin_notice( sprintf(__('You are using a trial version of linkpro plugin. If you have purchased the plugin, please enter your purchase code to enable the full version. You can enter your <a href="%s">purchase code here</a>.','linkpro'), admin_url() . 'admin.php?page=linkpro&tab=licensing'), true);
	}
}

add_action('admin_notices', 'linkpro_admin_notices');