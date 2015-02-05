<?php
	include_once('core.start.php');
	
	// $xslFile = 'content.product.entity.xsl';
	$stmt = $sql->stmt_init();
	$stmt = $sql->prepare("select 
		(select count(advertid) from pm_offer) as offers,
		(select count(advertid) from pm_offer where date_check is not null) as done,
		(select count(advertid) from pm_offer where date_check is null) as todo,
		(select count(advertid) from pm_offer where display = 'N/A') as na,
		(select count(advertid) from pm_offer where display = '$pm_login') as owned,
		(select count(advertid) from pm_offer where display is not null and display != '$pm_login' and display != 'N/A') as toprice");
	$stmt->execute();
	$stmt->bind_result($offers, $done, $todo, $na, $owned, $toprice);
	$stmt->fetch();
	$stmt->close();
	
	$eta = $todo*2.0;
	
	$xmlProgress = $xmlOutDoc->createElement('progress');
	$xmlOutTransform->appendChild($xmlProgress);
	$xmlProgress->appendChild($xmlOutDoc->createElement('offers', $offers));
	$xmlProgress->appendChild($xmlOutDoc->createElement('offers_p', 100));
	$xmlProgress->appendChild($xmlOutDoc->createElement('done', $done));
	$xmlProgress->appendChild($xmlOutDoc->createElement('done_p', number_format(100*$done/$offers, 2)));
	$xmlProgress->appendChild($xmlOutDoc->createElement('todo', $todo));
	$xmlProgress->appendChild($xmlOutDoc->createElement('todo_p', number_format(100*$todo/$offers, 2)));
	$xmlProgress->appendChild($xmlOutDoc->createElement('todo_eta_h', floor($eta/3600)));
	$xmlProgress->appendChild($xmlOutDoc->createElement('todo_eta_m', floor($eta/60)%60));
	$xmlProgress->appendChild($xmlOutDoc->createElement('todo_eta_s', $eta%60));
	$xmlProgress->appendChild($xmlOutDoc->createElement('na', $na));
	$xmlProgress->appendChild($xmlOutDoc->createElement('na_p', number_format(100*$na/$done, 2)));
	$xmlProgress->appendChild($xmlOutDoc->createElement('owned', $owned));
	$xmlProgress->appendChild($xmlOutDoc->createElement('owned_p', number_format(100*$owned/$done, 2)));
	$xmlProgress->appendChild($xmlOutDoc->createElement('toprice', $toprice));
	$xmlProgress->appendChild($xmlOutDoc->createElement('toprice_p', number_format(100*$toprice/$done, 2)));
	
	
	$competitorsColors = array('#556627','#8FCF3C','#FFF168','#FFEFB6','#5F8CA3','#A2B5BF','#5C0515','#DB0B32','#C44C51','#FFB6B8');
	
	$xmlCompetitors = $xmlOutDoc->createElement('competitors');
	$xmlOutTransform->appendChild($xmlCompetitors);
	
	$stmt = $sql->stmt_init();
	$stmt = $sql->prepare("select display, count(*) as t from pm_offer where display is not null and display != '$pm_login' and display != 'N/A' group by display order by t desc limit 0,10");
	$stmt->execute();
	$stmt->bind_result($concurrent, $total);
	$other = $toprice;
	$i = 0;
	while($stmt->fetch()) {
		$xmlCompetitor = $xmlOutDoc->createElement('competitor');
		$xmlCompetitors->appendChild($xmlCompetitor);
		$xmlCompetitor->appendChild($xmlOutDoc->createElement('name', $concurrent));
		$xmlCompetitor->appendChild($xmlOutDoc->createElement('color', $competitorsColors[$i]));
		$xmlCompetitor->appendChild($xmlOutDoc->createElement('offers', $total));
		$xmlCompetitor->appendChild($xmlOutDoc->createElement('offers_p', number_format(100*$total/$toprice, 2)));
		$i++;
		$other = $other-$total;
	}
	$stmt->close();
	
	$xmlCompetitor = $xmlOutDoc->createElement('competitor');
	$xmlCompetitors->appendChild($xmlCompetitor);
	$xmlCompetitor->appendChild($xmlOutDoc->createElement('name', 'Autre'));
	$xmlCompetitor->appendChild($xmlOutDoc->createElement('color', '#EEEEEE'));
	$xmlCompetitor->appendChild($xmlOutDoc->createElement('offers', $other));
	$xmlCompetitor->appendChild($xmlOutDoc->createElement('offers_p', number_format(100*$other/$toprice, 2)));
	
	$xslFile = 'content.pricing.watch.xsl';
	
	include_once('core.end.php');
?>