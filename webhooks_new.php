<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

//$access_token = '3ALKAbKFoGuJyJnoDdn0HeyfbxLFtEXBKiC0lFeoNl/XbL4WhoCZzefp2n7UDuXaCWfErIDro07BnZNggJmXJChXTIlMPo8LRJ+n1LEgbRUaKehDkiCr5p5CakHrPX+gauOGX/R5bB2e5yi7xjnHDAdB04t89/1O/w1cDnyilFU=';
$access_token = 'Rby2d2EQ+lCsIXNHUPVcA8SrY1M6ZSBp3D51L50l32LNC4cuR98xnDhr7x0LQcjiALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrUAvLIjTzpfS7u1i8wa6T0QKsSMF2yKXBPlKPJIOHaacQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

$txt_file = "vendor/test.txt";
if (!file_exists($txt_file)) {
	$text = "not found";
}
else {
	$text = "found";
	//$array = file($txt_file);
	//$text = $text . $array[0];
}

// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			/*if (str_contains($event['message']['text'], 'register')) {
				//$text = $event['source']['userId'];
				//$text = 'Hello Pimchanok';
				file_put_contents($txt_file, $event['source']['userId'] . "\n", FILE_APPEND);
				
					
			}
			else {
				$text = "hi there"
			}
			*/
			if (strpos($event['message']['text'], 'register') !== false) {  //register
				//$strtok = explode (" ",$event['message']['text'])
				//if !empty($strtok[1]) {
				//	file_put_contents($txt_file, $strtok[1] . "|" . $event['source']['userId'] . "\n", FILE_APPEND);
					$text = "registered";
				//}
				//else {
				//	$text = "missing argument";
				//}
			}	
			elseif (strpos($event['message']['text'], 'query') !== false) { // query
				$array = file($txt_file);
				$text = join("",$array);
			}
			else {
				$text = $event['message']['text'];
			}
			
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
echo "OK";
