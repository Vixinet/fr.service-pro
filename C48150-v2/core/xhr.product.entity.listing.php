<?php

	include_once('core.start.php');
	
	$stmt = $sql->stmt_init();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$linePerPage = $product_listing;
	$start = ($page-1) * $linePerPage;
	
	if ($stmt = $sql->prepare("SELECT count(sku) FROM product_entity")) {
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($count);
		$stmt->fetch();
		$totalPages = ceil($count / $linePerPage);
		$xmlOutPages = $xmlOutDoc->createElement('pages');
		for($i = 1; $i <= $totalPages; $i++) {
			$xmlOutPage = $xmlOutDoc->createElement('page', $i);
			if($i == $page) {
				$xmlOutPage->setAttribute('active', 1);
			}
			$xmlOutPages->appendChild($xmlOutPage);
		}
		$xmlOutTransform->appendChild($xmlOutPages);
		$stmt->close();
	} else {
		$errors[] = array('STMT_ERROR', 'xhr.product.entity count Statment fail');
	}
	
	if ($stmt = $sql->prepare("SELECT p.sku, p.stock, p.price_base, price_buy,
																		(select count(*) from pm_offer o where o.sku=p.sku) as totalOfferPM
															 FROM product_entity p
															 ORDER BY lower(p.sku)
															 LIMIT $start, $linePerPage")) {
		
		$stmt->execute();
		$stmt->bind_result($sku, $stock, $price_base, $price_buy, $pm_offers);
		
		while ($stmt->fetch()) {
			$xmlOutProduct = $xmlOutDoc->createElement('product');
			$xmlOutProduct->appendChild($xmlOutDoc->createElement('sku', $sku));
			$xmlOutProduct->appendChild($xmlOutDoc->createElement('stock', $stock));
			$xmlOutProduct->appendChild($xmlOutDoc->createElement('price_base', number_format($price_base,2)));
			$xmlOutProduct->appendChild($xmlOutDoc->createElement('price_buy', number_format($price_buy,2)));
			$xmlOutProduct->appendChild($xmlOutDoc->createElement('pm_offers', $pm_offers));
      $xmlOutTransform->appendChild($xmlOutProduct);
    }
		
		$stmt->close();
	} else {
		$errors[] = array('STMT_ERROR', 'xhr.product.entity.content listing Statment fail');
	}
	
	$xslFile = 'content.product.entity.listing.xsl';
	
	include_once('core.end.php');
	
?>