<?php

require_once 'includes/init.php';
require_once 'includes/autoload.php';

if(isset($_COOKIE[$GLOBALS['cookie_user']])){

 $token = $_COOKIE[$GLOBALS['cookie_user']];
 $delete = new cookieworker();
 $delete->deleteCookie($token);

}

session_unset();
session_destroy();

header('Location: index.php');
exit;