<?php

require_once 'includes/authenticate.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/autoload.php';
require_once 'includes/library/HTMLPurifier.auto.php';
    
if( isset($_GET['key'],$_GET['reset_code'])){

    $key = filterxss($_GET['key']);
    $code = filterxss($_GET['reset_code']);
    $verify = false;
    
    try{

     $stmtw = $db->prepare("SELECT * FROM resetpassword WHERE user_key = :key AND reset_code = :code");
     $stmtw->bindParam(':key',$key);
     $stmtw->bindParam(':code',$code);
     $stmtw->execute();
     $result = $stmtw->fetch();
    
        if($result){

            $_SESSION['passreset']['user_email'] = $result['email'];
            $_SESSION['passreset']['user_name'] = $result['name'];
            $_SESSION['passreset']['user_key'] = $result['user_key'];
            $_SESSION['passreset']['reset_code'] = $result['reset_code'];
            $_SESSION['passreset']['verify'] = ( $result['verify']==1 ? 1 : 0);
            $_SESSION['passreset']['reset'] = 1;
            $_SESSION['passreset']['clientip'] = getUserIp();
            header('Location: reset-password-submit.php');
            exit;
        
        }else{ echo"this link has expired";}

    }catch(PDOException $ewe){ throw $ewe; }catch(Exception $e){ throw $e; }        

}else{ header('Location: index.php');exit; }
