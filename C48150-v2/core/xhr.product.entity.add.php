<?php

	include_once('core.start.php');
	
	$sku = trim($_POST['sku']);
	
	if(empty($sku)) {
		$errors[] = array('', 'Merci d\'indiquer le nom du produit');
	} else {
		$stmt = $sql->stmt_init();
		if ($stmt = $sql->prepare("SELECT count(sku) FROM product_entity WHERE sku=?")) {
			$stmt->bind_param('s', $sku);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($count);
			$stmt->fetch();
			$stmt->close();
			if($count == 0) {
				$sql->query("INSERT INTO product_entity (sku) VALUES ('$sku')");
				include_once('xhr.product.entity.info.php');
			} else {
				$errors[] = array('ITEM_EXIST', 'Product Sku already exists');
				include_once('xhr.product.entity.php');
			}
			
		} else {
			$errors[] = array('STMT_ERROR', 'xhr.product.entity count Statment fail');
		}
		
		$sql->query("UPDATE product_entity SET stock=$stock WHERE sku='$sku'");
	}
	
	include_once('core.end.php');
	
?>