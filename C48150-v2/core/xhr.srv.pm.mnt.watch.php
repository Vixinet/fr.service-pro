<?php
	include_once('core.start.php');
	
	do {
		$req = $sql->query("select advertid, url from pm_offer where date_check is null limit 0, 5");
		while ($res = $req->fetch_assoc()) {
			crawl($res['advertid'], 'http://www.priceminister.com'.$res['url']);
		}
		
		$req = $sql->query("select count(advertid) as t from pm_offer where date_check is null");
		$res = $req->fetch_assoc();
		if($res['t'] == 0) {
			echo "** Nothing to do, sleep 5 minutes **\n\n";
			sleep(300);
		}
	} while(true);
	
	function crawl($id, $url) {
		
		$response = get($url);
		
		switch($response[1]) {
			case 301 :
				if(!empty($response[2])) {
					crawl($id, $response[2]);
				} elseif(preg_match('#<a href="(.*)">here</a>#', $response[0], $m) == 1) {
					crawl($id, $m[1]);
				} else {
					print_r($response);
					echo "id:$id\nurl:$url\n\n";
					echo ("> ERROR : Error 301 - preg match fail !\n");
				}
			break;
			case 302 :
				if(!empty($response[2])) {
					crawl($id, $response[2]);
				} else {
					print_r($response);
					echo "id:$id\nurl:$url\n\n";
					echo ("> ERROR : Error 302 - preg match fail !\n");
				}
			break;
			case 200 :
				if(strpos($response[0], '<title>PriceMinister - Erreur 403</title>')) {
					die("$id: Arrrg Error 403\n");
				} elseif(strlen($response[0]) > 0) {
					$m = null;
					if(preg_match('#<p class="seller" title="(.*) - Note#', $response[0], $m) == 1) {
						$display = $m[1];
					} elseif(preg_match('#Annonce de#', $response[0], $m) == 1) {
						$display = $pm_login;
					} else {
						$display = 'N/A';
					}
					save($id, $display);
				} else {
					die("> ERROR : Error 200 - Content empty !\nid:$id\n> URL : $url\n".$response[0]."\n\n");
				}
			break;
			default :
				print_r($response);
				echo ("> ERROR : Error ??? - Error not catched !\nid:$id\n");
			break;
		}
	}
	
	
	function save($id, $display) {
		global $sql;
		$sql->query("UPDATE pm_offer SET display='$display', date_check=now() where advertid=$id");
		echo "$id: $display (".time().")\n";
	}
	
	function get($url) {
		sleep(1.5);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$output = curl_exec($ch);
		
		list($headers, $response) = explode("\r\n\r\n", $output, 2);
		
		$location = '';
		$headers = explode("\n", $headers);
		foreach($headers as $header) {
				if (stripos($header, 'Location:') !== false) {
					$location = 'http://www.priceminister.com'.substr($header, 10);
				}
		}
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		return array($response, $http_status, $location);
	}
?>