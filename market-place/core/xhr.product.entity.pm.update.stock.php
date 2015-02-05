<?php
	include_once('core.start.php');
	// xhr.product.entity.pm.update.stock.php
	$sku = $_POST['sku'];
	
	$req = $sql->query("SELECT stock FROM product_entity WHERE sku='$sku'");
	$res = $req->fetch_assoc();
	
	$stock = $res['stock'];
	
	$stream = '';
	
	$req = $sql->query("SELECT advertid FROM pm_offer WHERE sku='$sku'");
	while($res = $req->fetch_assoc()) {
		$advertid = $res['advertid'];
	
		$stream .= "<item>
									<attributes>
										<advert>
											<attribute>
												<key>aid</key>
												<value>$advertid</value>
											</attribute>
											<attribute>
												<key>qty</key>
												<value>$stock</value>
											</attribute>
										</advert>
									</attributes>
								</item>";
	}
	$filename = 'pm_work/stock-'.date('Ymd-His').'.xml';
	$content = "<?xml version='1.0' encoding='ISO-8859-1'?><items>$stream</items>";
	$fp = fopen($filename, 'w');
	fwrite($fp, $content);
	fclose($fp);
	chmod($filename, 0777);
	$file = PM_send_file($filename);
	$xmlResponseDoc = new DOMDocument('1.0', 'iso-8859-1');
	$xmlResponseDoc->loadXML($file);
	$xpathService = new DOMXpath($xmlResponseDoc);
	$xpathService->registerNamespace('x',$xmlResponseDoc->lookupNamespaceUri($xmlResponseDoc->namespaceURI));
	
	$importid = $xpathService->query("/x:importresult/x:response/x:importid")->item(0)->nodeValue;
	$status = $xpathService->query("/x:importresult/x:response/x:status")->item(0)->nodeValue;
	
	$info[] = array('PRICEMINISTER', "Importation de stock : ID=$importid - STATUS:$status");
	
	function PM_send_file($file) {
		global $pm_login, $pm_pass;
		$url  = "https://ws.priceminister.com/stock_ws?action=genericimportfile&login=$pm_login&pwd=$pm_pass&version=2011-11-29";
		$post = array('file' => '@'.($file));
		$ch   = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($ch);
		echo 'r:'.$response;
		echo 'u:'.$url;
		curl_close($ch);
	
		return $response;
	}
	
	include_once('core.end.php');
?>