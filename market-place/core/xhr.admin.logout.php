<?php
	include_once('core.start.php');
	
	$_SESSION = array();
	session_destroy();
	
	$redirect = 'index.html';

	include_once('core.end.php');
?>