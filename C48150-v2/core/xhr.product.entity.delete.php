<?php

	include_once('core.start.php');
	
	$sku = trim($_POST['sku']);
	
	$sql->query("DELETE FROM product_entity WHERE sku='$sku'");
	
	include_once('xhr.product.entity.php');
	
	include_once('core.end.php');
	
?>