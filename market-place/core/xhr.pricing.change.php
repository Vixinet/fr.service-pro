<?php
	include_once('core.start.php');
	
	$req = $sql->query('SELECT count(*) as t from pm_offer where date_pricing is not null and sku is not null and date_apply is null');
	$res = $req->fetch_assoc();
	$xmlOutTransform->appendChild($xmlOutDoc->createElement('pricing_pm_total', $res['t']));
	
	$stmt = $sql->stmt_init();
	if ($stmt = $sql->prepare("SELECT o.advertid, o.sku, o.pack, o.price, o.shipping,
																		o.display, o.display_price, o.display_shipping, o.display_rating,
																		o.date_check, o.date_pricing, o.url,
																		p.price_base
														FROM pm_offer o
														LEFT JOIN product_entity p ON  p.sku = o.sku
														WHERE o.date_pricing is not null
														AND o.date_apply is null
														AND o.sku is not null
														ORDER BY abs((o.price+o.shipping)-(o.display_price+o.display_shipping)), o.sku  LIMIT 0,$pricing_per_page")) {
		$stmt->execute();
		$stmt->bind_result($advertid, $sku,  $pack, $price, $shipping, $display, $display_price, $display_shipping, $display_rating, $date_check, $date_pricing, $url, $price_base);
		$xmlOutPricingPM = $xmlOutDoc->createElement('pricing_pm');
		while($stmt->fetch()) {
			
			$total = number_Format($price+$shipping, 2);
			$display_total = number_Format($display_price+$display_shipping, 2);
			$rating = number_Format($display_rating, 2);
			
			$delta_rp = 0;
			$delta_r = 0;
			
			switch($rating) {
				case 5 :
					$delta_r = 0.01;
					break;
				case 4.9 :
					$delta_rp = 0.05;
					break;
				case 4.8 :
					$delta_r = 0.01;
					break;
			}
			
			$price_total = $display_total-$delta_r;
			$price_total -= $price_total*$delta_rp;
			
			$pricing_price = $price_total - $display_shipping; 
			$pricing_shipping = $display_shipping;
			
			if($pricing_shipping == 0) {
				$pricing_price-=0.10;
				$pricing_shipping+=0.10;
			}
			
			if($pricing_price < 1.5) {
				$d = 1.5-$pricing_price;
				$pricing_shipping -= $d;
				$pricing_price += $d;
			}
			
			if($pricing_shipping == 0 and $pricing_price == 1.5) {
				$pricing_shipping = 0.01;	
			}
			
			$xmlOutPricing = $xmlOutDoc->createElement('pricing');
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('advertid', $advertid));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('url', $url));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('sku', $sku));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('pack', $pack));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('price', number_Format($price, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('price_base', number_Format($price_base, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('shipping', number_Format($shipping, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('total', $total));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('display', $display));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('display_price', number_Format($display_price, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('display_shipping', number_Format($display_shipping, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('display_total', $display_total));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('display_rating', $rating));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('date_check', $date_check));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('date_pricing', $date_pricing));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('pricing_price', number_Format($pricing_price, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('pricing_shipping', number_Format($pricing_shipping, 2)));
			$xmlOutPricing->appendChild($xmlOutDoc->createElement('pricing_total', number_Format($pricing_price+$pricing_shipping, 2)));
			
			$xmlOutPricingPM->appendChild($xmlOutPricing);
		}
		$xmlOutTransform->appendChild($xmlOutPricingPM);
		$stmt->close();
	} else {
		$errors[] = array('STMT_ERROR', 'Statment fail 1');
	}
	
	$xslFile = 'content.pricing.change.xsl';
	
	include_once('core.end.php');
?>