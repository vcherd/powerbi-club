<?php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'Rby2d2EQ+lCsIXNHUPVcA8SrY1M6ZSBp3D51L50l32LNC4cuR98xnDhr7x0LQcjiALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrUAvLIjTzpfS7u1i8wa6T0QKsSMF2yKXBPlKPJIOHaacQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
//$idPush = 'U508e825223e51da193359f03da202555';

// Get POST body content
//$content = file_get_contents('php://input');
echo "<HTML><BODY>" . $_GET['ec'] . "</BODY></HTML>";

$userfile = "vendor/userlist.txt";
$promotionfile = "vendor/promotion.txt";
/*
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
*/
/*
foreach(file($userfile) as $userrec) {
	$userdb = strtok($userrec,"|");
	$idPush = strtok("|");
	
	foreach(file($promotionfile) as $promo) {
		$userdb_promo = strtok($promo,"|");
		$promo_code = strtok("|");
		$promo_detail = strtok("|");
		
		if ($userdb == $userdb_promo) {
			$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($promo_detail);
			$response = $bot->pushMessage($idPush, $textMessageBuilder);
		}
	}
}

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
*/