<?php
require_once './config/config_value.php';

//calculate hash
$verifydata = hash(HASH_ALGORITHM, $_POST["userID"] . SALT);

if ($_POST["sID"] != $verifydata) die ("Error");
?>