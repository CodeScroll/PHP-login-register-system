<?php

if(isset($_GET["goto"])){

$theget = str_replace('"',"",$_GET["goto"]);
$urls = array("error","home","index","reset-password","restricted-home");

	if (in_array($theget, $urls)){

	 $goto = str_replace('"', '', $_GET["goto"]);
	 header("Location: $goto.php");
	 exit;

	}else{ header("Location: logout.php");exit; }
}