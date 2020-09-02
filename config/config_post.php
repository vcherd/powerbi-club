<?php
require_once './config/config_value.php';
require_once './config/config_function.php';

//calculate hash
$verifydata = hash(HASH_ALGORITHM, $_POST["userID"] . SALT);

if ($_POST["sID"] != $verifydata) die ("Error");
?>