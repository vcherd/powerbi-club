<?php


//echo phpinfo();


$cv = curl_init();

curl_setopt($cv, CURLOPT_URL, "https://sdr-nodejs.herokuapp.com/api/bcpoilprice");
 
//header (‘Content-type: text/html; charset=utf-8’);

curl_setopt($cv, CURLOPT_RETURNTRANSFER, 1);

 $output = curl_exec($cv);
 
 $js_array=json_decode($output, true);
 //echo $js_array['postDate'];
 //echo $output . "<br>";
 //echo print_r($js_array);

 $notifyURL = "https://notify-api.line.me/api/notify";
$accToken = "99UtKRjmbuxfVSh6bbiUQLtIoonngNvI2ipXhml2rPC";
$headers = array(
 'Content-Type: application/x-www-form-urlencoded',
 'Authorization: Bearer '.$accToken
);

$idx = 0;
 
$message = '
แจ้งปรับราคาน้ำมัน
วันที่ประกาศ : '.$js_array[$idx]['postDate'].'
วันที่มีผล : '.$js_array[$idx]['effectiveDate'].' 
ชนิดน้ำมัน | '.$js_array[$idx]['unit'].' | ส่วนต่าง
';

//echo print_r($js_array[$idx]);
echo print_r($js_array[$idx]['oilproce'][0]['id']);

/*
for ($i=0; $i<sizeof($js_array[$idx]); $i++) {
	$message = $message . $js_array[$idx]['oilprice'][$i]['id'] . " | " . $js_array[$idx]['oilprice'][$i]['updatedPrice'] . " | " . $js_array[$idx]['oilprice'][$i]['variance'] . "\n";
}

$data = array('message' => $message);


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
*/