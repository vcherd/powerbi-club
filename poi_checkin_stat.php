<?php
require_once 'config/config.php';

$myfile = fopen(FILE_CHECK_IN_FULLPATH, "r") or die("Unable to open file!");
echo str_replace("\n","<BR>",fread($myfile,filesize(FILE_CHECK_IN_FULLPATH)));
fclose($myfile);
?>