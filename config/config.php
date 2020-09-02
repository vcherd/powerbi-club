<?php
require_once './config/config_value.php';
require_once './config/config_function.php';

//calculate hash
$verifydata = hash(HASH_ALGORITHM, $_GET["userID"] . SALT);

if ($_GET["sID"] != $verifydata) die ("Error");

?>