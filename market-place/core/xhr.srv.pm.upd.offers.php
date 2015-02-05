<?php
	include_once('core.start.php');
	
	// RŽcup des infos de Princeminster
	$offersUpdated = 0;
	$offersAdded = 0;
	$nexttoken = '';
	
	// $sql->query('UPDATE pm_offer SET date_upd=null');
	
	do {
		$call = "https://ws.priceminister.com/stock_ws?action=export&login=$pm_login&pwd=$pm_pass&version=2012-10-23$nexttoken";
		// echo $call;
		$nexttoken = '';	
		$response = file_get_contents($call);
		
		// Traite produit
		$xmlServiceDoc = new DOMDocument('1.0', 'iso-8859-1');
		$xmlServiceDoc->loadXML($response);
		$xpathService = new DOMXpath($xmlServiceDoc);
		$xpathService->registerNamespace('x', $xmlServiceDoc->lookupNamespaceUri($xmlServiceDoc->namespaceURI));
		
		$token = $xpathService->query("/x:inventoryresult/x:response/x:nexttoken");
		if (!is_null($token) and !empty($token->item(0)->nodeValue)) {
			$nexttoken = '&nexttoken='. $token->item(0)->nodeValue;
		}
		
		$products = $xpathService->query("/x:inventoryresult/x:response/x:advertlist/x:advert");
		
		foreach ($products as $product) {
			$advertid   = $xpathService->query("x:advertid", $product)->item(0)->nodeValue;
			$label      = $xpathService->query("x:productsummary/x:headline", $product)->item(0)->nodeValue;
			$descript   = $xpathService->query("x:comment", $product)->item(0)->nodeValue;
			$urlproduct = $xpathService->query('x:productsummary/x:url', $product)->item(0)->nodeValue;
			$price      = $xpathService->query("x:price/x:amount", $product)->item(0)->nodeValue;
			$shipping   = $xpathService->query("x:shippingcost/x:amount", $product)->item(0)->nodeValue;
			
			$stmt = $sql->stmt_init();
			$stmt->prepare("SELECT count(*) FROM pm_offer  WHERE advertid=?");
			$stmt->bind_param("i", $advertid);
			$stmt->execute();
			$stmt->bind_result($total);
			$stmt->fetch();
			$stmt->close();
			
			if($total == 1) {
				$stmt = $sql->stmt_init();
				$stmt = $sql->prepare("UPDATE pm_offer SET description=?, label=?, url=?, price=?, shipping=?, date_upd=now() WHERE advertid=?");
				$stmt->bind_param("sssddi", $descript, $label, $urlproduct, $price, $shipping, $advertid);
				$stmt->execute();
				$stmt->close();
				$offersUpdated++;
			} else {
				$stmt = $sql->stmt_init();
				$stmt = $sql->prepare("INSERT INTO pm_offer (advertid, description, label, url, price, shipping, date_upd) VALUES (?,?,?,?,?,?,now()) ");
				$stmt->bind_param("isssdd", $advertid, $descript, $label, $urlproduct, $price, $shipping);
				$stmt->execute();
				$stmt->close();
				$offersAdded++;
			}
		}
	} while(strlen($nexttoken) > 0);
	
	/*
	$req = $sql->query('SELECT count(advertid) as t FROM pm_offer WHERE date_upd is null');
	$res = $req->fetch_assoc();
	$offersDeleted = $res['t'];
	*/
	// $sql->query('DELETE FROM pm_offer WHERE date_upd is null');

	$info[] = array('PRODUCT_MANAGEMENT', "$offersAdded ajout&#233;es");
	$info[] = array('PRODUCT_MANAGEMENT', "$offersUpdated mises &#224; jour");
	// $info[] = array('PRODUCT_MANAGEMENT', "$offersDeleted deleted");
	
	include_once('core.end.php');
?>