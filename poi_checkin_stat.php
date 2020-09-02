<?php
require_once 'config/config.php';

//$myfile = fopen(FILE_CHECK_IN_FULLPATH, "r") or die("Unable to open file!");
//echo str_replace("\n","<BR>",fread($myfile,filesize(FILE_CHECK_IN_FULLPATH)));
//echo "userid = " . $_GET["userID"];
$found = false;

if ($file = fopen(FILE_CHECK_IN_FULLPATH, "r")) {
    while(!feof($file)) {
        $line = fgets($file);
        $checkindatetime = strtok($line,"|");
        $uid_fromfile = strtok("|");
        $loc = strtok("|");
        
        if ($uid_fromfile == $_GET["userID"]) {
            echo $checkindatetime . " at " . $loc . "<BR>";
            $found = true;
        }
    }
    fclose($file);
}
else die("Unable to open file!");

fclose($myfile);

if ($found == false) echo ("No check-in data.");
?>