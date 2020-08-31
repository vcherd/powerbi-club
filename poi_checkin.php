<?php
require_once 'config/config.php';

$checkinfile = fopen(FILE_CHECK_IN_FULLPATH, "a+") or die("Unable to open file!");
$txt = date('d-m-Y h:i:s A') . "|" . $_POST["userID"] . "|" . $_POST["userLoc"] . "\n";
fwrite($checkinfile, $txt);
fclose($checkinfile);

echo "Check-in Success!!";
?>
<script>
function countdown() {
    var i = document.getElementById('counter');
    i.innerHTML = parseInt(i.innerHTML)-1;

    if (parseInt(i.innerHTML)<=0) {
        window.close();
    }
}

setInterval(function(){ countdown(); },300);
</script>
