<?php
	
	define('DVPT', 1);
	define('PROD', 2);
  
	define('DEPL', PROD);
	
  $pm_login = 'Service-PRO';
  $pm_pass  = '9fc57f661d7440238b2131845957a73e';
  
  $product_listing = 50;
  $pricing_per_page = 50;
  $offers_per_page = 50;
	
  if(DEPL == PROD) {
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', 'codeur');
    define('DB_DB'  , 'C48150');
  } else {
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_DB'  , 'C48150');
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