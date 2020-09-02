<?php
require_once './config/config_value.php';

//calculate hash
$verifydata = hash(HASH_ALGORITHM, $_GET["userID"] . SALT);

if ($_GET["sID"] != $verifydata) die ("Error");

function CheckRef($ref_site) {
    if($_SERVER['HTTP_REFERER'] != $ref_site) die("Error");
    //header('Location:page1.php')
}
?>