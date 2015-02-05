<?php

	include_once('core.start.php');
	
	$sku = $_POST['sku'];
	
	$stmt = $sql->stmt_init();
	if ($stmt = $sql->prepare("SELECT sku, stock FROM product_entity WHERE sku=?")) {
		$stmt->bind_param("s", $sku);
		$stmt->execute();
		$stmt->bind_result($sku, $stock);
		$stmt->fetch();
		$xmlOutProduct = $xmlOutDoc->createElement('product');
		$xmlOutProduct->appendChild($xmlOutDoc->createElement('sku', $sku));
		$xmlOutProduct->appendChild($xmlOutDoc->createElement('stock', $stock));
		$xmlOutTransform->appendChild($xmlOutProduct);
		$stmt->close();
		
		$stmt = $sql->stmt_init();
		if ($stmt = $sql->prepare("SELECT advertid, label, description, url, pack, date_upd, price, shipping FROM pm_offer WHERE sku=?")) {
			$stmt->bind_param("s", $sku);
			$stmt->execute();
			$stmt->bind_result($advertid, $label, $description, $url, $pack, $date_upd, $price, $shipping);
			$xmlOutOffersPM = $xmlOutDoc->createElement('offers_pm');
			while($stmt->fetch()) {
				$xmlOutOffer = $xmlOutDoc->createElement('offer');
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('advertid', $advertid));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('label', htmlspecialchars($label)));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('description', htmlspecialchars($description)));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('url', $url));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('pack', $pack));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('date_upd', $date_upd));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('price', number_Format($price, 2)));
				$xmlOutOffer->appendChild($xmlOutDoc->createElement('shipping', number_Format($shipping, 2)));
				$xmlOutOffersPM->appendChild($xmlOutOffer);
			}
			$xmlOutTransform->appendChild($xmlOutOffersPM);
			$stmt->close();
		} else {
			$errors[] = array('STMT_ERROR', 'xhr.product.entity.info listing Statment fail 1');
		}
	} else {
		$errors[] = array('STMT_ERROR', 'xhr.product.entity.info info Statment fail 2');
	}
	
	$xslFile = 'content.product.entity.info.xsl';
	
	include_once('core.end.php');
	
?>