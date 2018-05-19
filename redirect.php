<?php

if(isset($_GET["goto"])){

$theget = str_replace('"',"",$_GET["goto"]);
$urls = array("account","cart","error","home","index","offers","orders-history","orders","store","resetpassword","restrictedhome");

	if (in_array($theget, $urls)){

	 $goto = str_replace('"', '', $_GET["goto"]);
	 header("Location: $goto.php");
	 exit;

	}else{ header("Location: logout.php");exit; }
}