<?php

	// Header inputs
  if(DEPL == DVPT) {
    $xmlOutInput = $xmlOutDoc->createElement('input');
    $xmlOutInput->appendChild(xmlArrayParamsToNode('post', $_POST));
    $xmlOutInput->appendChild(xmlArrayParamsToNode('get', $_GET));
    $xmlOutHeader->appendChild($xmlOutInput);
	}
  
	// Header outpus
  $xmlOutOutput = $xmlOutDoc->createElement('output');
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('errors', $errors));
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('information', $info));
	$xmlOutOutput->appendChild(xmlArrayMessageToNode('warnings', $warns));
	$xmlOutOutput->appendChild(getNodeFromArray('session', $_SESSION));
	$xmlOutHeader->appendChild($xmlOutOutput);
  
	// Header
	if(isset($status))   $xmlOutHeader->appendChild($xmlOutDoc->createElement('status', $status));
	if(isset($service))  $xmlOutHeader->appendChild($xmlOutDoc->createElement('service', $service));
  if(isset($redirect)) $xmlOutHeader->appendChild($xmlOutDoc->createElement('redirect', $redirect));
  
  // Root
  $xmlOutDoc->appendChild($xmlOutRoot);
  $xmlOutRoot->appendChild($xmlOutHeader);
  $xmlOutRoot->appendChild($xmlOutTransform);
  
  // PrŽparation de la rŽponse
  if(isset($xslFile) and file_exists('transform/' . $xslFile)) {
    $xslOutDoc = new DOMDocument('1.0', 'iso-8859-1');
    $xslOutDoc->load('transform/'.$xslFile);
    $xslOutProc = new XSLTProcessor();
    $xslOutProc->importStylesheet($xslOutDoc);
    $xslOutTrsRsp = $xslOutProc->transformToDoc($xmlOutDoc);
    $xmlOutRsp = $xmlOutDoc->importNode($xslOutTrsRsp->documentElement, true);
  }
  
  $xmlOutRoot->appendChild($xmlOutRsp);
  
	header('Content-type: text/xml');
	echo $xmlOutDoc->saveXML();
  
?>