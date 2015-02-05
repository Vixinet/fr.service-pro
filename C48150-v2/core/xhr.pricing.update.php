<?php
	include_once('core.start.php');
	
	$out = array();
	foreach($_POST as $k => $v) {
		$preg = preg_match("/field_(.*)_(.*)/", $k, $m);
		$out[$m[1]][$m[2]] = $v;
	}
	
	$stream = '';
	
	foreach($out as $k => $v) {
		$price        = $v['p'];
		$shipping     = $v['s'];
		$checked      = $v['c'];
		$coeff        = 0.8;
		$shipping_n   = $shipping;
		$shipping_s   = ($price > 40) ? $shipping : ($shipping_n * 1.2) + 2.9;
		$shipping_r   = ($shipping_s * 1.2) + 2.9;
		$shipping_n_f = $shipping_n * $coeff;
		$shipping_s_f = $shipping_s * $coeff;
		$shipping_r_f = $shipping_r * $coeff;
		
		$sql->query("UPDATE pm_offer SET date_apply=now() WHERE advertid=$k");
		
		if($checked == 'true') {
			$stream .= "<item>
										<attributes>
											<advert>
												<attribute>
													<key>aid</key>
													<value>$k</value>
												</attribute>
												<attribute>
													<key>sellingPrice</key>
													<value>$price</value>
												</attribute>
											</advert>
											<shipping>
												<configuration>
													<zone>
														<name>FRANCE</name>
														<type>
															<name>NORMAL</name>
															<authorization>1</authorization>
															<leader_price>$shipping_n</leader_price>
															<follower_price>".number_format(round($shipping_n_f,1),1)."</follower_price>
														</type>
														<type>
															<name>SUIVI</name>
															<authorization>1</authorization>
															<leader_price>".number_format(round($shipping_s,1),1)."</leader_price>
															<follower_price>".number_format(round($shipping_s_f,1),1)."</follower_price>
														</type>
														<type>
															<name>RECOMMANDE</name>
															<authorization>1</authorization>
															<leader_price>".number_format(round($shipping_r,1),1)."</leader_price>
															<follower_price>".number_format(round($shipping_r_f,1),1)."</follower_price>
														</type>
													</zone>
												</configuration>
											</shipping>
										</attributes>
									</item>";
		}
	}
	
	$filename = 'pm_work/pricing-'.date('Ymd-His').'.xml';
	$fp = fopen($filename, 'w');
	fwrite($fp, "<?xml version='1.0' encoding='ISO-8859-1'?><items>$stream</items>");
	fclose($fp);
	
	$xmlResponseDoc = new DOMDocument('1.0', 'iso-8859-1');
	$xmlResponseDoc->loadXML(PM_send_file($filename));
	$xpathService = new DOMXpath($xmlResponseDoc);
	$xpathService->registerNamespace('x',$xmlResponseDoc->lookupNamespaceUri($xmlResponseDoc->namespaceURI));
	
	$importid = $xpathService->query("/x:importresult/x:response/x:importid")->item(0)->nodeValue;
	$status = $xpathService->query("/x:importresult/x:response/x:status")->item(0)->nodeValue;
	
	$info[] = array('PRICEMINISTER', "Importation : ID=$importid - STATUS:$status");
	
	function PM_send_file($file) {
		$url  = "https://ws.priceminister.com/stock_ws?action=genericimportfile&login=$pm_login&pwd=$pm_pass&version=2011-11-29";
		$post = array('file' => '@'.($file));
		$ch   = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($ch);
		curl_close($ch);
	
		return $response;
	}
	
	include('xhr.pricing.change.php');
	
	include_once('core.end.php');
?>