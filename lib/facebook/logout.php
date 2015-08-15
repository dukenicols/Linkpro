<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
	require 'facebook.php';
	$facebook = new Facebook(array(
		'appId'  => '1479248155693493',
		'secret' => '64ed76aff811c4413e4c5aaf03b1ab78'
	));

	setcookie('fbs_'.$facebook->getAppId(),'', time()-100, '/', 'mostazasocial.com');
	$facebook->destroySession();
	//header('Location: index.php');
?>
