<?php
	include_once('core.start.php');
	
	// RŽcup des infos de Princeminster
	$nexttoken = '';
	
	do {
		// 
		$call = "https://ws.priceminister.com/stock_ws?action=export&login=$pm_login&pwd=$pm_pass&version=2012-10-23&scope=PRICING$nexttoken";
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
			$price      = $xpathService->query("x:price/x:amount", $product)->item(0)->nodeValue;
			$shipping   = $xpathService->query("x:shippingcost/x:amount", $product)->item(0)->nodeValue;
			
			$req = $sql->query("SELECT display
														FROM pm_offer
													 WHERE advertid=$advertid
														 AND date_check is not null
														 AND date_apply is null
														 AND display != 'N/A' 
														 AND display != '$pm_login'");
			
			if($req->num_rows == 1) {
				if($display == $pm_login) {
					// nothing
				} elseif($display == 'N/A') {
					// nothing
				} else {
					$res = $req->fetch_assoc();
					$display = $res['display'];
					
					$competitors = $xpathService->query("x:productsummary/x:pricing/x:adverts/x:newadverts/x:advert[x:seller/x:login=\"$display\"]", $product);
					
					if($competitors->length > 1) {
						$cheaper = null;
						foreach($competitors as $c) { 
							if($cheaper == null) {
								$cheaper = $c;
							} else {
								$v1 = $xpathService->query("x:price/x:amount", $c)->item(0)->nodeValue+$xpathService->query("x:shippingcost/x:amount", $c)->item(0)->nodeValue;
								$v2 = $xpathService->query("x:price/x:amount", $cheaper)->item(0)->nodeValue+$xpathService->query("x:shippingcost/x:amount", $cheaper)->item(0)->nodeValue;
								if($v1 < $v2) {
									$cheaper = $c;
								}
							}
						}
					}
					
					if($competitors->length != 0) {
						$competitor = $competitors->item(0);
						$c_price    = $xpathService->query("x:price/x:amount", $competitor)->item(0)->nodeValue;
						$c_shipping = $xpathService->query("x:shippingcost/x:amount", $competitor)->item(0)->nodeValue;
						$c_rating   = $xpathService->query("x:seller/x:rating", $competitor)->item(0)->nodeValue;
						
						$stmt = $sql->stmt_init();
						$stmt = $sql->prepare("UPDATE pm_offer SET display_price=?, display_shipping=?, display_rating=?, price=?, shipping=?, date_pricing=now() WHERE advertid=?");
						$stmt->bind_param("sssddi", $c_price, $c_shipping, $c_rating, $price, $shipping, $advertid);
						$stmt->execute();
						$stmt->close();
					}
				}
			}
		}
	} while(strlen($nexttoken) > 0);
	
	$info[] = array('PRICING', 'Prix des concurents r&#233;cup&#233;r&#233;s');
	
	include_once('xhr.pricing.change.php');
	
	include_once('core.end.php');
?>