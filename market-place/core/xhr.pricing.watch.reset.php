<?php
	include_once('core.start.php');
	$sql->query("update pm_offer set display=null, display_price=null, display_shipping=null, display_rating=null, date_upd=null, date_check=null, date_pricing=null, date_apply=null where advertid>0");
	$info[] = array('WATCH', 'Mise &#224; jour. Veuillez reactualiser et attendre que le bot se relance (environ 5 min)'.$sql->error);
	include_once('core.end.php');
?>