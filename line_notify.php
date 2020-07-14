<?php
//define('LINE_API',”https://notify-api.line.me/api/notify");

require "vendor/autoload.php";
$access_token = 'Rby2d2EQ+lCsIXNHUPVcA8SrY1M6ZSBp3D51L50l32LNC4cuR98xnDhr7x0LQcjiALq2X4CsHufXuE+jvHiVb+s+DPZaSR/HlkUnW+sJrrUAvLIjTzpfS7u1i8wa6T0QKsSMF2yKXBPlKPJIOHaacQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '55ccde8729536a6df0e0dfca954ef261';
//$idPush = 'U508e825223e51da193359f03da202555';

$userfile = "vendor/userlist.txt";
$promotionfile = "vendor/promotion.txt";

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
/*
 
$token = "HHAwXfTK32lHD8V620krk2aFixxUksd0NPNCIFu2mn0" //ใส่Token ที่copy เอาไว้
$str = “Hello”; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
 
$res = notify_message($str,$token);
print_r($res);
function notify_message($message,$token){
 $queryData = array(‘message’ => $message);
 $queryData = http_build_query($queryData,’’,’&’);
 $headerOptions = array( 
         ‘http’=>array(
            ‘method’=>’POST’,
            ‘header’=> “Content-Type: application/x-www-form-urlencoded\r\n”
                      .”Authorization: Bearer “.$token.”\r\n”
                      .”Content-Length: “.strlen($queryData).”\r\n”,
            ‘content’ => $queryData
         ),
 );
 $context = stream_context_create($headerOptions);
 $result = file_get_contents(LINE_API,FALSE,$context);
 $res = json_decode($result);
 return $res;
}
*/
//echo "<HTML><BODY>555</BODY></HTML>";