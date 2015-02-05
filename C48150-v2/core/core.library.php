<?php

	define('CDISCOUNT_SOAP_STS', 'https://sts.cdiscount.com/users/httpIssue.svc/');
	define('CDISCOUNT_SOAP_URL', 'https://wsvc.cdiscount.com/MarketplaceAPIService.svc');
	
	define('DVPT', 1);
	define('PROD', 2);
  
	define('DEPL', DVPT);
	
  $pm_login = 'yooshop';
  $pm_pass  = '725cc6fe8b3441efa45de9a9115c775b';
  
  $product_listing = 50;
  $pricing_per_page = 50;
  $offers_per_page = 50;
	
  if(DEPL == PROD) {
    define('DB_HOST', 'mysql51-101.perso');
    define('DB_USER', 'svproadmin');
    define('DB_PASS', 'codeur71');
    define('DB_DB'  , 'svproadmin');
  } else {
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '39248204');
    define('DB_DB'  , 'C48150e2e2');
  }
  
  function getNodeFromArray($key, $array) {
    
    global $xmlOutDoc;
    
    if(is_array($array)) {
      $node = $xmlOutDoc->createElement($key);
      foreach($array as $k => $v) {
        $node->appendChild(getNodeFromArray($k, $v));
      }
      return $node;
    } else {
      return $xmlOutDoc->createElement($key, $array);
    }
  }
  
	function xmlArrayParamsToNode($nodeName, $array) {
		
		global $xmlOutDoc;
		
		$xmlNode = $xmlOutDoc->createElement($nodeName);
		
		foreach($array as $k => $v) {
			$item = $xmlOutDoc->createElement('param', $v);
			$item->setAttribute('name', $k);
			$xmlNode->appendChild($item);
		}
		
		return $xmlNode;
	}
  
	function xmlArrayMessageToNode($nodeName, $array) {
		
		global $xmlOutDoc;
		
		$xmlNode = $xmlOutDoc->createElement($nodeName);
		
		foreach($array as $v) {
      $item = $xmlOutDoc->createElement('message', $v[1]);
			$item->setAttribute('code', $v[0]);
			$xmlNode->appendChild($item);
		}
		
		return $xmlNode;
	}
  
?>