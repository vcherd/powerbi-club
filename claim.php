<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Rby2d2EQ+lCsIXNHUPVcA8SrY1M6ZSBp3D51L50l32LNC4cuR98xnDhr7x0LQcjiALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrUAvLIjTzpfS7u1i8wa6T0QKsSMF2yKXBPlKPJIOHaacQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
//$idPush = 'U508e825223e51da193359f03da202555';

// Get POST body content
//$content = file_get_contents('php://input');
//echo "<HTML><BODY>" . $_GET['ec'] . "</BODY></HTML>";
$eCoupon = $_GET['ec'];


$userfile = "vendor/userlist.txt";
$promotionfile = "vendor/promotion.txt";


echo "<HTML><BODY>" . $eCoupon; //

foreach(file($promotionfile) as $promo) {
	$userdb_promo = strtok($promo,"|");
	$promo_code = strtok("|");
	$promo_detail = strtok("|");
	
	echo $userdb_promo . "<br>"; //
	
	if ($eCoupon == $promo_code) {
		echo "Found"; //
		
		// get user id
		foreach(file($userfile) as $userrec) {
			$userdb = strtok($userrec,"|");
			if ($userdb == $userdb_promo) $idPush = strtok("|");
		}
		echo $idPush;
		/*
		//reply to user
		
		
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("Claimed successfully");
		$response = $bot->pushMessage($idPush, $textMessageBuilder);
*/
	}
}
echo "</BODY></HTML>";
//echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
