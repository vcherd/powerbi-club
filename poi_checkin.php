<?php
require_once 'config/config.php';

$checkinfile = fopen(FILE_CHECK_IN_FULLPATH, "a+") or die("Unable to open file!");
$txt = date('d-m-Y h:i:s A') . "|" . $_POST["userID"] . "|" . $_POST["userLoc"] . "\n";
fwrite($checkinfile, $txt);
fclose($checkinfile);

echo "Check-in Success!!";
?>
<script> window.setTimeout("window.close()", 1000); </script>
