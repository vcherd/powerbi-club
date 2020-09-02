<?php
require_once './config/config.php';

if (($_POST["userID"] == "") || ($_POST["userLoc"] == "")) die ("Internal error, missing value.");

$checkinfile = fopen(FILE_CHECK_IN_FULLPATH, "a+") or die("Unable to open file!");
$txt = date('d-m-Y h:i:s A') . "|" . $_POST["userID"] . "|" . $_POST["userLoc"] . "\n";
fwrite($checkinfile, $txt);
fclose($checkinfile);

echo "Check-in at " . $_POST["userLoc"] . " Success.";

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    $radlat1 = M_PI * $lat1/180;
    $radlat2 = M_PI * $lat2/180;
    $radlon1 = M_PI * $lon1/180;
    $radlon2 = M_PI * $lon2/180;
    $theta = $lon1-$lon2;
    $radtheta = M_PI * $theta/180;
    $dist = sin($radlat1) * sin($radlat2) + cos($radlat1) * cos($radlat2) * cos($radtheta);
    $dist = acos($dist);
    $dist = $dist * 180/M_PI;
    $dist = $dist * 60 * 1.1515;
    if ($unit=="K") { $dist = $dist * 1.609344; }
    if ($unit=="N") { $dist = $dist * 0.8684; }
return $dist;
}
?>
<script> window.setTimeout("window.close()", 1000); </script>
