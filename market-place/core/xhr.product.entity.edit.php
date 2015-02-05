<?php

	include_once('core.start.php');
	
	$sku = $_POST['sku'];
	$sku_ref = $_POST['sku_ref'];
	$price_base = ($_POST['price_base']);
	$price_buy = ($_POST['price_buy']);
	$stock = intval($_POST['stock']);
	
	if($stock === '' or $price_base==='' or $price_buy ==='' or $sku=='') {
		$errors[] = array('', 'one or more Fields empty');
	} else {
		$info[] = array('SUCCESS', 'Product updated');
		$sql->query("UPDATE product_entity SET 	stock=$stock,
																						price_base=$price_base,
																						price_buy=$price_buy,
																						sku='$sku'
																		 WHERE sku='$sku_ref'");
	}
	
	if($price_buy > $price_base) {
		$warns[] = array($sku, 'Le prix de base est inf&#233;rieur au prix d\'achat.');
	}
	
	include_once('xhr.product.entity.php');
	
	include_once('core.end.php');
	
?>