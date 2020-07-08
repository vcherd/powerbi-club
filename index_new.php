<?php

require "vendor/autoload.php";
$access_token = 'IMvRmZtD1nQRW2USHNt//gp3gFBzk5EzRacgthNSv4vCspPSKw0glEq9+URuU0m4ALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrX1Tk5K3FfXSpz41eY4lefXGNSBiITS2i0rlspqVZ0zRgdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
$idPush = 'U0e5b5c3f0cb7345f8234f2d8bf6ce20b';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('ยืนยันการสั่งซื้อน้ำมัน');
$response = $bot->pushMessage($idPush, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

echo "Hello LINE BOT4";
