<?php
session_start();
$dbhost="localhost";	
$dbuser='ADMIN';			
$dbpass='IamBCP#2020';			
$dbname='CheckInDB';			

$conn=mysql_connect($dbhost,$dbuser,$dbpass) or die ("Can not connect Host Server");
mysql_select_db($dbname) or die ("Can not connect DB Server");
mysql_query( "SET NAMES UTF8", $conn );

//session_start();
?>