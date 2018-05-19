<?php

require_once 'includes/init.php';
require_once 'includes/db_connect.php';
require_once 'includes/autoload.php';
require_once 'includes/functions.php';
require_once 'includes/library/HTMLPurifier.auto.php';

if( isset($_GET['id']) && isset($_GET['code']) ){
  
  if(isset($_COOKIE[$GLOBALS['cookie_user']])){

    $token = $_COOKIE[$GLOBALS['cookie_user']];
    $removeCookie = new cookieworker();
    $removeCookie->deleteCookie($token);
  }

  session_unset();
  session_destroy();

  $key = filterxss($_GET['id']);
  $code = filterxss($_GET['code']);
  $verify = false;
    
 try{

  $stmt = $db->prepare("SELECT COUNT(*) FROM verifying WHERE user_key = :key AND user_code = :code");
  $stmt->bindParam(':key',$key);
  $stmt->bindParam(':code',$code);
  $stmt->execute(); 

  if( $stmt->fetchColumn()!=0 ){
   
   $db->beginTransaction();

   $ffff = $db->prepare("UPDATE users SET verify=1 WHERE user_key = :key");
   $ffff->bindParam(':key',$key);
   $ffff->execute();
   $verify = true;

    if($verify){
     $bbb = $db->prepare("DELETE FROM verifying WHERE user_key = :key AND user_code = :code");
     $bbb->bindParam(':key',$key);
     $bbb->bindParam(':code',$code);
     if($bbb->execute()){ $db->commit();header('Location: index.php?verify=success');exit; }
    }

  }else{ echo"this link is invalid"; }
 }catch(PDOException $eh){ $db->rollBack();throw $eh; }catch(Exception $eg){ $db->rollBack();throw $eg; }
}else{ header('Location: index.php');exit; }