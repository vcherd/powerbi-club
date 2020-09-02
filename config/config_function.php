<?php

function CheckRef($ref_site) {
    if($_SERVER['HTTP_REFERER'] != $ref_site) die("Error");
    //header('Location:page1.php')
}
?>