<?php

require "vendor/autoload.php";
$access_token = 'IMvRmZtD1nQRW2USHNt//gp3gFBzk5EzRacgthNSv4vCspPSKw0glEq9+URuU0m4ALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrX1Tk5K3FfXSpz41eY4lefXGNSBiITS2i0rlspqVZ0zRgdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
$idPush = 'U508e825223e51da193359f03da202555';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($idPush, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

echo "Hello LINE BOT3";
