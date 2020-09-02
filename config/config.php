<?php
require_once './config/config_value.php';

//calculate hash
$verifydata = hash(HASH_ALGORITHM, $_GET["userID"] . SALT);

echo $_GET["userID"] . "<BR>". $verifydata;
?>