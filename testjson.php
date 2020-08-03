<?php
$url="https://covid19.th-stat.com/api/open/today";
$contents = file_get_contents($url); 
$contents = utf8_encode($contents); 
$results = json_decode($contents); 

print_r($results);
/*
foreach ($results as $key => $value) { 
    echo "<h2>$key</h2>";
    foreach ($value as $k => $v) { 
        echo "$k | $v <br />"; 
    }
}
*/