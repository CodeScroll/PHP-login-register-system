<?php

require_once 'init.php';
require_once 'autoload.php';
require_once 'functions.php';
require_once 'db_connect.php';

set_exception_handler('exceptor');

if(isset($_COOKIE[$GLOBALS['cookie_user']])){

$token = $_COOKIE[$GLOBALS['cookie_user']]; 
$check = new cookieworker();

if( $check->userAuthentication($token) ){

	if(!isset($_SESSION['user_key'])){
	 if( $infos = $check->getInfos($token) ){
	  $_SESSION['clientip'] = getUserIp();
	  $_SESSION['user_key'] = $infos['user_key'];
	  $_SESSION['verify'] = $infos['verify'];
	  $_SESSION['email'] = $infos['email'];
	  userInfos($db,$_SESSION['user_key']);
	 }
	}

}else{
 
 $check->deleteCookie($token);
 session_unset();
 session_destroy();
 sec_session_start();
}

}