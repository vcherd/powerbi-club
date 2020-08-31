<?php
require_once './config/config.php';

$checkinfile = fopen(FILE_CHECK_IN_FULLPATH, "a+") or die("Unable to open file!");
$txt = $_POST["userID"] . "\t" . $_POST["userLoc"];
fwrite($checkinfile, $txt);
fclose($checkinfile);

echo $_POST["userLoc"] . "<BR>" . $_POST["userID"];
?>