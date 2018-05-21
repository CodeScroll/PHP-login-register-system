<?php

require_once '../includes/authenticate.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/library/HTMLPurifier.auto.php';

if(isset( $_REQUEST['token'] )){

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$response = file_get_contents("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$_REQUEST['token'], false, stream_context_create($arrContextOptions));
//$response = file_get_contents ('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $_REQUEST['token'] );    

    $response = json_decode ( $response,true );

    if(empty($response)){
      echo 'bad_request';
      exit;
    }

    if (strpos($response['aud'],'apps.googleusercontent.com')==false){
      echo 'bad_request';
      exit;
    }

    if($response['iss'] !== 'accounts.google.com' && $response['iss'] !== 'https://accounts.google.com'){
      echo 'bad_request';
      exit;
    }

    try{
    
     $db->beginTransaction();

      $sub = $response['sub'];
      $gname = $response['name'];
      $gemail = $response['email'];

      $one = $db->prepare("SELECT * FROM google_users WHERE sub = :alfa AND google_email = :bita");
      $one->bindParam(':alfa',$sub);
      $one->bindParam(':bita',$gemail);
      $one->execute();

      if($res = $one->fetch()){

        $ukey = $res['user_key'];

        $oneone = selectFromWhere($db,'*','users','user_key',$ukey);
        $logged = $oneone->fetch();

        if(!$logged){
          echo 'google_login_problem';
          exit;
        }

        $_SESSION['user_key'] = $logged['user_key'];
        $_SESSION['email'] = $logged['email'];
        $_SESSION['clientip'] = getUserIp();
        userInfos($db,$_SESSION['user_key']);
        clearSessionVars();
        $loginCookie = new cookieworker();
        $loginCookie->rememberLogin();

        if($logged['verify']==1){

          $_SESSION['verify'] = 1;
          echo 'google_home_ok';
          exit;

        }else{ 

          $_SESSION['verify'] = 0;
          echo 'google_reshome_ok';
          exit; 
        }

      }else{

        $tempname = explode(' ', $gname);
        $firstname = $tempname[0];
        $lastname = $tempname[1];
        $user_key = hash('sha256',microtime(true).mt_rand().$gemail);
        $genpass = bin2hex(openssl_random_pseudo_bytes(4));
        $register_code = md5(uniqid(rand(), true));
        
        do{

         $user_key = hash('sha256',microtime(true).mt_rand().$gemail);
         $testkey = selectFromWhere($db,'COUNT(*)','users','user_key',$user_key);
         $testkeyCounter = $testkey->fetchColumn();

         $testkeytwo = selectFromWhere($db,'COUNT(*)','admin','user_key',$user_key);
         $testkeyCountertwo = $testkeytwo->fetchColumn();

        }while($testkeyCounter>0 || $testkeyCountertwo>0);

          $emailCheckq = $db->prepare("SELECT * FROM users WHERE email = :ttemail");
          $emailCheckq->bindParam(':ttemail',$gemail);
          $emailCheckq->execute();
          $emailCheck = $emailCheckq->fetch();

         if(!$emailCheck){

          $stmtreg = $db->prepare("INSERT INTO users(user_key, username, surname, email, pwd,verify) VALUES(:key, :username, :surname, :email, :pwd,:ver)");
          $stmtreg->bindParam(':key',$user_key);
          $stmtreg->bindParam(':username',$firstname);
          $stmtreg->bindParam(':surname',$lastname);
          $stmtreg->bindParam(':email',$gemail);
          $stmtreg->bindValue(':pwd',password_hash($genpass,PASSWORD_DEFAULT));
          $stmtreg->bindValue(':ver',1);
          $stmtreg->execute();

          $two = $db->prepare("INSERT INTO google_users(google_name,google_email,sub,user_key) VALUES(:thename,:themail,:thesub,:user_key)");
          $two->bindParam(':thename',$gname);
          $two->bindParam(':themail',$gemail);
          $two->bindParam(':thesub',$sub);
          $two->bindParam(':user_key',$user_key);
          $two->execute();

          //googleRegisterEmail();

          $_SESSION['user_key'] = $user_key;
          $_SESSION['clientip'] = getUserIp();
          $_SESSION['verify'] = 1;
          $_SESSION['email'] = $gemail;
          userInfos($db,$_SESSION['user_key']);
          clearSessionVars();
          $registerCookie = new cookieworker();
          $registerCookie->rememberLogin();
          echo 'google_register_ok';

         }else{ echo 'google_registered'; }
      }

     $db->commit();
    }catch(Exception $rr){
     $db->rollBack();
     echo 'google_login_problem';
     throw $rr;
    }catch(PDOException $rr){
     $db->rollBack();
     echo 'google_login_problem';
     throw $rr;
    }

}else{ echo 'failed'; }

?>