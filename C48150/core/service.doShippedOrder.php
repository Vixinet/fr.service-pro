<?php
	
	$debug = false;
	
	if($debug) {
		$xmlServiceDoc = $xmlOrderDoc;
	} else {
		// Change status;
		$soap = doSoapRequest(SOAP_URL,
													SOAP_URL.'?wsdl',
													$serviceSoapAction,
													$xmlOrderDoc->saveXML(),
													1.0);
		
		$xmlServiceDoc = $soap['dom'];
	}
?>