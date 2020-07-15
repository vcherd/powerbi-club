<?php 

//echo $_POST["name"];

if ($_POST["message"] == "covid") {
	$cv = curl_init();

	curl_setopt($cv, CURLOPT_URL, "https://covid19.th-stat.com/api/open/today");
	 
	//header (‘Content-type: text/html; charset=utf-8’);

	curl_setopt($cv, CURLOPT_RETURNTRANSFER, 1);

	 $output = curl_exec($cv);
	 
	 $js_array=json_decode($output, true);
	 $data = array(
 'message' => '
	รายงานสถานการณ์โควิท
	ผู้ติดเชื้อ : '.$js_array['Confirmed'].' คน
	เสียชีวิต : '.$js_array['Deaths'].' คน
	หายแล้ว : '.$js_array['Recovered'].' คน
	รักษาตัว : '.$js_array['Hospitalized'].' คน
	เวลาล่าสุด : '.$js_array['UpdateDate'].'' );
}
else $data = array('message' => $_POST["message"]); // enter message here


$notifyURL = "https://notify-api.line.me/api/notify";
$accToken = "99UtKRjmbuxfVSh6bbiUQLtIoonngNvI2ipXhml2rPC";
$headers = array(
 'Content-Type: application/x-www-form-urlencoded',
 'Authorization: Bearer '.$accToken
);

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $notifyURL);
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2); 
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1); 
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec( $ch );
curl_close( $ch );
 
var_dump($result);
$result = json_decode($result,TRUE);