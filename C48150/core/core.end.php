<?php
  
  if(isset($redirect)) {
    $xmlOutRoot->appendChild($xmlOutDoc->createElement('redirect', $redirect));
  }
  
  $nodeSession = $xmlOutDoc->createElement('session');
  $nodeErrors = $xmlOutDoc->createElement('errors');
  $nodeInfos = $xmlOutDoc->createElement('infos');
  $nodePOST = $xmlOutDoc->createElement('post');
  $nodeGET = $xmlOutDoc->createElement('get');
  
  
  foreach($errors as $k => $v) $nodeErrors->appendChild($xmlOutDoc->createElement('error', $v));
  foreach($infos  as $k => $v) $nodeInfos->appendChild($xmlOutDoc->createElement('info', $v));
  foreach($_POST  as $k => $v) $nodePOST->appendChild($xmlOutDoc->createElement($k, $v));
  foreach($_GET  as $k => $v) $nodeGET->appendChild($xmlOutDoc->createElement($k, $v));
  
  $xmlOutRoot->appendChild(getNodeFromArray('session', $_SESSION));
  $xmlOutRoot->appendChild($nodeErrors);
  $xmlOutRoot->appendChild($nodeInfos);
  $xmlOutRoot->appendChild($nodePOST);
  $xmlOutRoot->appendChild($nodeGET);
  
  if(file_exists('transform/'.$xslFile)) {
    $xslFile = 'transform/'.$xslFile;
  } else {
    $xslFile = 'transform/page.nothing.xsl';
  }
  
  $xslDoc = new DOMDocument();
  $xslDoc->load($xslFile);
  $proc = new XSLTProcessor();
  $proc->importStylesheet($xslDoc);
  
  echo $proc->transformToXML($xmlOutDoc);
?>