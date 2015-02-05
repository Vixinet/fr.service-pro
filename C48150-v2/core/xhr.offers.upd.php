<?php

	include_once('core.start.php');
	
	$out = array();
	foreach($_POST as $k => $v) {
		$preg = preg_match("/field_(.*)_(.*)/", $k, $m);
		$out[$m[1]][$m[2]] = $v;
	}
	
	$i=0;
	foreach($out as $k => $v) {
		$sku = $v['sku'];
		$pack = intval($v['pack']);
		
		if(!empty($sku) and $pack > 0) {
			// $info[] = array('DEBUG', "UPDATE pm_offer SET sku='$sku', pack=$pack WHERE advertid=$k");
			$sql->query("UPDATE pm_offer SET sku='$sku', pack=$pack WHERE advertid=$k");
			$i++;
		}
	}
	
	$info[] = array('OFFRES_MAJ', "$i offres ont &#233;t&#233; mise &#224; jour");
	
	include('xhr.offers.manage.php');
	
	include_once('core.end.php');
	
?>