<?php
	
	session_start();
	
  include_once('core.library.php');
  
  date_default_timezone_set("Europe/Zurich");
  
	$sql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DB);  
  
  if(mysqli_connect_error()) {
    die('Erreur de connexion (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  }
  
  // Variable qui contient les erreurs ou infos :
	$status  = null;
	$service = null;
  $errors  = array();
  $info    = array();
	$warns   = array();
	
  // On filtre les params POST et GET
  foreach($_POST as $k => $v)
    $_POST[$k] = $sql -> real_escape_string($v);
    
  foreach($_GET as $k => $v)
    $_GET[$k] = $sql -> real_escape_string($v);
	
	/**
	  * 200 = OK and content returned
	  * 204 = OK and no content to return
	  * 
	  * 400 = Bad Request             -> requte incomplte (paramtres)
	  * 401 = Unauthorized            -> token pas valide
	  * 404 = Ressource non trouvŽe   -> service name fournit mais inexistant
	  * 
	  * 501 = Not Implemented	        -> service pas encore dŽvelopŽ
	  * 503 = Service Unavailable	    -> service dŽvelopŽ mais dŽsactivŽ
	  * 505 = Version not supported   -> version du service pas supportŽe
	  * 
	  * */
	
	// Load des services
	$xmlSrvDoc = new DOMDocument();
	$xmlSrvDoc->load('service.definitions.xml');
	
  $xmlOutDoc = new DOMDocument();
	$xmlOutDoc->preserveWhiteSpace = false;
	$xmlOutDoc->formatOutput = true;
	
  $xmlOutRoot   = $xmlOutDoc->createElement('data');
  $xmlOutHeader = $xmlOutDoc->createElement('header');
	$xmlOutRsp    = $xmlOutDoc->createElement('response');
	
	
	// WORK
	if(isset($_POST['service']) or isset($_GET['service'])) {
		
		$srvParams = isset($_POST['service']) ? $_POST : $_GET;
	
		$service = $srvParams['service'];
		
		// Check if the service is declared
		$xmlSrvServices = $xmlSrvDoc->getElementsByTagName('service');
		foreach($xmlSrvServices as $xmlSrvService) {
			if($service == $xmlSrvService->getElementsByTagName('name')->item(0)->nodeValue) {
				// Check if the params are
				$xmlSrvParams = $xmlSrvService->getElementsByTagName('params')->item(0)->getElementsByTagName('param');
				foreach($xmlSrvParams as $xmlSrvParam) {
					$xmlSrvParamName = $xmlSrvParam->getAttribute('name');
					$xmlSrvParamIsRequired = ($xmlSrvParam->hasAttribute('required') and $xmlSrvParam->getAttribute('required') == 'true');
					
					if(!isset($srvParams[$xmlSrvParamName])) {
						if($xmlSrvParamIsRequired) {
							$status = 401;
							$errors[] = array('PARAM_MISSING', 'Param "'.$xmlSrvParamName.'" is missing');	
							break;
						} else {
							$warns[] = array('PARAM_MISSING', 'Param "'.$xmlSrvParamName.'" is missing');	
						}
					} elseif(empty($srvParams[$xmlSrvParamName])) {
						$warns[] = array('PARAM_EMPTY', 'Param "'.$xmlSrvParamName.'" is empty');
					}
				}
				
				if($status == null) {
					$status = 200;
					
					$tokenId = getTokenId($srvParams['login'], $srvParams['pwd'], SOAP_STS.'?realm='.SOAP_URL);
					$soapOrders = getOrders($tokenId);
					
					$xslOrderFile = 'transform/service.order.'.$service.'.xsl';
					
					if(file_exists($xslOrderFile)) {
						$xslOrderServiceDoc = new DOMDocument();
						$xslOrderServiceDoc->load($xslOrderFile);
						
						$procOrderSercice = new XSLTProcessor();
						$procOrderSercice->importStylesheet($xslOrderServiceDoc);
						
						foreach($srvParams as $k => $v) {
							$procOrderSercice->setParameter('', $k, $v);
						}
						
						$procOrderSercice->setParameter('', 'tokenId', $tokenId);
						
						$xmlOrderDoc = $procOrderSercice->transformToDoc($soapOrders['dom']);
					}
					
					if(file_exists("service.$service.php")) {
						
						$xmlServiceDoc = new DOMDocument();
						
						$serviceSoapAction = $xmlSrvService->getElementsByTagName('soap')->item(0)->getAttribute('action');
						$serviceSoapCall = $xmlSrvService->getElementsByTagName('soap')->item(0);
						
						include_once("service.$service.php");
						
						$xmlOutRsp = $xmlOutDoc->importNode($xmlServiceDoc->documentElement, true);
						
					} else {
						$xmlOutRsp = $xmlOutDoc->importNode($xmlOrderDoc->documentElement, true);
					}
					
				}
				break;
			}
		}
		
		if($status == null) {
			$status = 302;
			$errors[] = array('SERVICE_UNKNOW', 'Service name "'.$srvParams['service'].'" unknow.');
		}
		
	} else {
		$status = 301;
			$errors[] = array('SERVICE_UNDEFINED', 'Service name not defined.'); 
	}
	
	// Header inputs
	$xmlOutInput = $xmlOutDoc->createElement('input');
	$xmlOutInput->appendChild(xmlArrayParamsToNode('post', $_POST));
	$xmlOutInput->appendChild(xmlArrayParamsToNode('get', $_GET));
	
	// Header outpus
	$xmlOutOutput = $xmlOutDoc->createElement('output');
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('errors', $errors));
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('information', $info));
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('warnings', $warns));
	$xmlOutOutput->appendChild(getNodeFromArray('session', $_SESSION));
	
	// Header
	$xmlOutHeader->appendChild($xmlOutDoc->createElement('status', $status));
	$xmlOutHeader->appendChild($xmlOutDoc->createElement('service', $service));
	$xmlOutHeader->appendChild($xmlOutInput);
	$xmlOutHeader->appendChild($xmlOutOutput);
	
	// Root
	$xmlOutDoc->appendChild($xmlOutRoot);
	$xmlOutRoot->appendChild($xmlOutHeader);
	$xmlOutRoot->appendChild($xmlOutRsp);
	
	header('Content-type: text/xml');
	
	if(isset($srvParams['ommit_header']) and $srvParams['ommit_header']=='true') {
		$data = $xmlOutDoc->createElement('data');
		$data->appendChild($xmlOutRsp);
		echo $data->ownerDocument->saveXML($data);
	} else {
		echo $xmlOutDoc->saveXML();	
	}
?>