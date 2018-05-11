<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           ?><?php
	define('ROOTDIR',realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ob_start();
	if(ini_get('session.auto_start')!=1)
	{
		session_start();
	}
	header("Content-Type: text/html; charset=utf-8");
	include_once(ROOTDIR.'config.inc.php');
	include_once($config->libDir.'Base.php');
	include_once($config->libDir.'Route.php');
	$route = new Route();
	$route->run();
?>
