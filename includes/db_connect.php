<?php

require_once 'globalvars.php';

try{

 $db = new PDO("mysql:host=".$GLOBALS['dbhost'].";dbname=".$GLOBALS['dbname'],$GLOBALS['dbuser'],$GLOBALS['dbpassword']);
 $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $db->exec('set names utf8');

}catch(Exception $e){ throw $e;	}catch(PDOException $ew){ throw $ew; }