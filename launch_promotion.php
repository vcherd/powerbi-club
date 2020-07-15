<?php
require "vendor/autoload.php";
$access_token = 'Rby2d2EQ+lCsIXNHUPVcA8SrY1M6ZSBp3D51L50l32LNC4cuR98xnDhr7x0LQcjiALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrUAvLIjTzpfS7u1i8wa6T0QKsSMF2yKXBPlKPJIOHaacQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
//$idPush = 'U508e825223e51da193359f03da202555';

$userfile = "db/userlist.txt";
$promotionfile = "db/promotion.txt";

foreach(file($userfile) as $userrec) {
	$userdb = strtok($userrec,"|");
	$idPush = strtok("|");
	
	foreach(file($promotionfile) as $promo) {
		$userdb_promo = strtok($promo,"|");
		$promo_code = strtok("|");
		$promo_detail = strtok("|");
		
		$promo_detail = $promo_detail . "\nClick here to claim: https://stark-mountain-69352.herokuapp.com/claim.php?ec=" . $promo_code;
		
		if ($userdb == $userdb_promo) {
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($promo_detail);
			$response = $bot->pushMessage($idPush, $textMessageBuilder);
		}
	}
}

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
