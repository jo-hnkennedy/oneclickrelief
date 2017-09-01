<?php

$db = "oneclick";
$db_user = "oneclickadmin";
$db_pass = "harveycanfuckoff1$";

$c = new mysqli("oneclick.crbvit8ifwfb.us-east-1.rds.amazonaws.com:3306 ", $db_user, $db_pass, $db);

if ($c->connect_error) {
	die ("Conn error: " . $c->connect_error);
}


$ip_addr = $_SERVER['REMOTE_ADDR'];
$quantity = $_GET['quantity'];
$asin = $_GET['asin'];

$sql = $c->prepare("INSERT INTO events (ip_address, quantity, asin) VALUES(?, ?, ?);");
$sql->bind_param("sis", $ip_addr, $quantity, $asin);
$sql->execute();

?>
