<?php

	/* Convert all args to data */
	function linkpro_args_to_data( $args ) {
		$res = null;
		foreach($args as $k => $v) {
			if ( $v == '' || empty($v) ) {
				$res .= " data-$k='0'";
			} else {
				$v = htmlentities($v, ENT_QUOTES, "UTF-8");
				$res .= " data-$k='$v'";
			}
		}
		echo $res;
	}

function linkpro_redirect($url)
{
$string = '<script type="text/javascript">';
$string .= 'window.location = "' . $url . '"';
$string .= '</script>';

echo $string;
}

function linkpro_get_url_parameter( $param ) {
	if ( isset($_GET[$param]) ) {
			return (int) $_GET[$param];
	} 
	return false;
}