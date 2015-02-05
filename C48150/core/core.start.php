<?php

  session_start();
  
  include_once('core.library.php');
  
  date_default_timezone_set("Europe/Zurich");
  
  /*
   CREATE TABLE IF NOT EXISTS `user_entity` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(45) NULL,
    `password` VARCHAR(45) NULL,
    `email` VARCHAR(45) NULL,
    PRIMARY KEY (`id`)
   ) ENGINE = InnoDB;
   
   INSERT INTO `svproadmin`.`user_entity` (`username`, `password`, `email`) VALUES ('Sylvain', '00chtiopadre@71', 'sylvain@svpro.fr');
  */
  
  $sql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DB);  
  
  if(mysqli_connect_error()) {
    die('Erreur de connexion (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  }
  
  // Variable qui contient les erreurs ou infos :
  $errors = array();
  $infos = array();
  
  // On filtre les params POST et GET
  foreach($_POST as $k => $v)
    $_POST[$k] = $sql -> real_escape_string($v);
    
  foreach($_GET as $k => $v)
    $_GET[$k] = $sql -> real_escape_string($v);
  
  
  // Document xml de sortie
  $xmlOutDoc = new DOMDocument();
  $xmlOutRoot = $xmlOutDoc->createElement('data');
  $xmlOutDoc->appendChild($xmlOutRoot);
  
  $xslFile = 'page.nothing.xsl';
  
?>