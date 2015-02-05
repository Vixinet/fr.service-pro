<?php

	include_once('core.start.php');
	
	$stmt = $sql->stmt_init();
	$stmt = $sql->prepare("SELECT sku FROM product_entity ORDER BY lower(sku)");
	$stmt->execute();
	$stmt->bind_result($sku);
	$xmlOutProducts = $xmlOutDoc->createElement('products');
	$xmlOutTransform->appendChild($xmlOutProducts);
	while ($stmt->fetch()) {
		$xmlOutProducts->appendChild($xmlOutDoc->createElement('product', $sku));
	}
	$stmt->close();
	
	$stmt = $sql->stmt_init();
	if ($stmt = $sql->prepare("SELECT advertid, sku, label, description, url, pack, date_upd, price, shipping
														FROM pm_offer WHERE sku is null or pack is null LIMIT 0,$offers_per_page")) {
		$stmt->execute();
		$stmt->bind_result($advertid, $sku, $label, $description, $url, $pack, $date_upd, $price, $shipping);
		$xmlOutOffersPM = $xmlOutDoc->createElement('offers_pm');
		while($stmt->fetch()) {
			$xmlOutOffer = $xmlOutDoc->createElement('offer');
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('advertid', $advertid));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('label', htmlspecialchars($label)));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('description', htmlspecialchars($description)));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('pack', $pack));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('sku', $sku));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('url', $url));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('date_upd', $date_upd));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('price', number_Format($price, 2)));
			$xmlOutOffer->appendChild($xmlOutDoc->createElement('shipping', number_Format($shipping, 2)));
			$xmlOutOffersPM->appendChild($xmlOutOffer);
		}
		$xmlOutTransform->appendChild($xmlOutOffersPM);
		$stmt->close();
	} else {
		$errors[] = array('STMT_ERROR', 'xhr.product.entity.info listing Statment fail');
	}
	
	$req = $sql->query("select count(*) as t from pm_offer where sku is null or pack is null");
	$res = $req->fetch_assoc();
	$xmlOutTransform->appendChild($xmlOutDoc->createElement('total',$res['t']));
	
	$xslFile = 'content.offers.manage.xsl';
	
	include_once('core.end.php');
	
?>