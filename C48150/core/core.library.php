<?php

	define('SOAP_STS', 'https://sts.cdiscount.com/users/httpIssue.svc/');
	define('SOAP_URL', 'https://wsvc.cdiscount.com/MarketplaceAPIService.svc');
	define('IS_PRODUCTION', false);
  
  if(IS_PRODUCTION) {
    define('DB_HOST', 'mysql51-101.perso');
    define('DB_USER', 'svproadmin');
    define('DB_PASS', 'codeur71');
    define('DB_DB'  , 'svproadmin');
  } else {
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '39248204');
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
  
	
	function getOrders($tokenId) {
    $now = new DateTime();
    $dateEnd = $now->format('Y-m-d');
    $now->sub(new DateInterval('P2M'));
    $dateBeg = $now->format('Y-m-d');
    
    $call = file_get_contents('soap.getOrders-base.xml');
    $call = str_replace('${#Project#token}', $tokenId, $call);
    $call = str_replace('${#dateBeg}', $dateBeg, $call);
    $call = str_replace('${#dateEnd}', $dateEnd, $call);
    
    $soapOrders = doSoapRequest(SOAP_URL,
                                SOAP_URL.'?wsdl',
                                'http://www.cdiscount.com/IMarketplaceAPIService/GetOrderList',
                                $call,
                                1.0);
    
    return $soapOrders;
	}
  
	function doSoapRequest($url, $wsdl, $action, $call, $version) {
    
    $soap = array();
    $soap['client']    = new SoapClient($wsdl);
    $soap['dom']       = new DOMDocument();
    $soap['call']      = $call;
		$soap['response']  = $soap['client']->__doRequest($call, $url, $action, $version);
    
		$soap['dom']->loadXML($soap['response']);
    
    return $soap;
	}
	
	function getTokenId($u, $p, $url) {
    
    $file = file_get_contents('token.txt');
    $data = explode(':', $file);
    
    if($data[0] > time()) {
      $token = $data[1];
    } else {
      $opts = array(
        'http' => array(
          'method'=>"GET",
          'header'=>"Authorization: Basic ".base64_encode("$u:$p")
        )
      );
      $fluxTokenId = file_get_contents($url, false, stream_context_create($opts));
      $xmlTempDoc = new DOMDocument();
      $xmlTempDoc->loadXML($fluxTokenId);
      $token = $xmlTempDoc->getElementsByTagName('string')->item(0)->nodeValue;
      $time = time() + (47 * 3600);
      file_put_contents('token.txt', $time.':'.$token);
    }
    
    return $token;
	}
?>