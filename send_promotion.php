<?php
require "vendor/autoload.php";
$access_token = '3ALKAbKFoGuJyJnoDdn0HeyfbxLFtEXBKiC0lFeoNl/XbL4WhoCZzefp2n7UDuXaCWfErIDro07BnZNggJmXJChXTIlMPo8LRJ+n1LEgbRUaKehDkiCr5p5CakHrPX+gauOGX/R5bB2e5yi7xjnHDAdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';



	//send message
	$idPush = 'U508e825223e51da193359f03da202555';
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
	$response = $bot->pushMessage($idPush, $textMessageBuilder);

	echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
