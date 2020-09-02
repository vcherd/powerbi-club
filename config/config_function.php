<?php

function CheckRef($ref_site) {
    //echo "ref = ".$_SERVER['HTTP_REFERER'] . "<BR>ref2=" . $ref_site;

    if(strpos($_SERVER['HTTP_REFERER'],$ref_site) == FALSE )die("Error");
    //header('Location:page1.php')
}
?>