<?php

  session_start();
  
  include_once('core.library.php');
  
  // date_default_timezone_set("Europe/Zurich");
  
  $sql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DB);  
  
  if(mysqli_connect_error()) {
    die('Erreur de connexion (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  }
  
  // Variable qui contient les erreurs ou infos :
	$errors  = array();
  $info    = array();
	$warns   = array();
  
  // On filtre les params POST et GET
  foreach($_POST as $k => $v)
    $_POST[$k] = $sql -> real_escape_string($v);
    
  foreach($_GET as $k => $v)
    $_GET[$k] = $sql -> real_escape_string($v);
  
  
  // Document xml de sortie
  $xmlOutDoc = new DOMDocument('1.0', 'iso-8859-1');
	$xmlOutDoc->preserveWhiteSpace = false;
	$xmlOutDoc->formatOutput = true;
	
  $xmlOutRoot      = $xmlOutDoc->createElement('data');
  $xmlOutHeader    = $xmlOutDoc->createElement('header');
	$xmlOutRsp       = $xmlOutDoc->createElement('response');
  $xmlOutTransform = $xmlOutDoc->createElement('transform');
?>