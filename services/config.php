<?php 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // throw exceptions
$db = mysqli_connect('localhost','root','','forex_app');
if($db->connect_errno > 0){
	die('Unable to connect database ['. $db->connect_error. ']');
}

$select_query = "SELECT * FROM user" ;
$row = mysqli_fetch_assoc(mysqli_query($db,$select_query));


session_start();
$_SESSION["id"] = $row["id"];
$_SESSION["firstname"] = $row["firstname"];
$_SESSION["lastname"] = $row["lastname"];
$_SESSION["password"] = $row["password"];


?>