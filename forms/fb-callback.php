<?php

require_once '../fbapi/vendor/autoload.php'; 
require_once '../includes/authenticate.php';

$fb = new Facebook\Facebook([
 'app_id' => '1826699470960105',
 'app_secret' => '15e0f7b4b0cb2998f21fd074097769fd',
 'default_graph_version' => 'v2.2',
]);

$helper = $fb->getRedirectLoginHelper();

try{
 
	$accessToken = $helper->getAccessToken();

	 if($accessToken !== null){

	  $oResponse = $fb->get('/me?fields=id,name,email', $accessToken);
    $resdata = $oResponse->getGraphUser();

    $thename = $resdata->getField('name');
    $theemail = $resdata->getField('email');
    $theid = $resdata->getField('id');

    try{

    $db->beginTransaction();

    $one = $db->prepare("SELECT * FROM facebook_users WHERE fbid = :alfa AND fbemail = :bita");
    $one->bindParam(':alfa',$theid);
    $one->bindParam(':bita',$theemail);
    $one->execute();

    if($res = $one->fetch()){

    $ukey = $res['user_key'];

    $oneone = selectFromWhere($db,'*','users','user_key',$ukey);
    $logged = $oneone->fetch();

    if(!$logged){ fb_login_problem(); }

    $_SESSION['user_key'] = $logged['user_key'];
    $_SESSION['email'] = $logged['email'];
    $_SESSION['clientip'] = getUserIp();
    userInfos($db,$_SESSION['user_key']);
    clearSessionVars();
    $loginCookie = new cookieworker();
    $loginCookie->rememberLogin();
    $db->commit();
    if($logged['verify']==1){

      $_SESSION['verify'] = 1;
      fb_home_ok();

    }else{ 

      $_SESSION['verify'] = 0;
      fb_reshome_ok();
    }

    }else{

      $tempname = explode(' ', $thename);
      $firstname = $tempname[0];
      $lastname = $tempname[1];
      $user_key = hash('sha256',microtime(true).mt_rand().$theemail);
      $genpass = bin2hex(openssl_random_pseudo_bytes(4));
      $register_code = md5(uniqid(rand(), true));

    do{

     $user_key = hash('sha256',microtime(true).mt_rand().$theemail);
     $testkey = selectFromWhere($db,'COUNT(*)','users','user_key',$user_key);
     $testkeyCounter = $testkey->fetchColumn();

     $testkeytwo = selectFromWhere($db,'COUNT(*)','admin','user_key',$user_key);
     $testkeyCountertwo = $testkeytwo->fetchColumn();

    }while($testkeyCounter>0 || $testkeyCountertwo>0);

      $emailCheckq = $db->prepare("SELECT * FROM users WHERE email = :ttemail");
      $emailCheckq->bindParam(':ttemail',$theemail);
      $emailCheckq->execute();
      $emailCheck = $emailCheckq->fetch();
     
     if(!$emailCheck){

      $stmtreg = $db->prepare("INSERT INTO users(user_key, username, surname, email, pwd,verify) VALUES(:key, :username, :surname, :email, :pwd, :ver)");
      $stmtreg->bindParam(':key',$user_key);
      $stmtreg->bindParam(':username',$firstname);
      $stmtreg->bindParam(':surname',$lastname);
      $stmtreg->bindParam(':email',$theemail);
      $stmtreg->bindValue(':pwd',password_hash($genpass,PASSWORD_DEFAULT));
      $stmtreg->bindValue(':ver',1);
      $stmtreg->execute();

      $two = $db->prepare("INSERT INTO facebook_users(fbid,fbname,fbemail,user_key) VALUES(:thename,:themail,:thesub,:user_key)");
      $two->bindParam(':thename',$theid);
      $two->bindParam(':themail',$thename);
      $two->bindParam(':thesub',$theemail);
      $two->bindParam(':user_key',$user_key);
      $two->execute();

      //fbRegisterEmail();
      
      $_SESSION['user_key'] = $user_key;
      $_SESSION['clientip'] = getUserIp();
      $_SESSION['verify'] = 1;
      $_SESSION['email'] = $theemail;
      userInfos($db,$_SESSION['user_key']);
      clearSessionVars();
      $registerCookie = new cookieworker();
      $registerCookie->rememberLogin();
      $db->commit();
      fb_register_ok(); 
     }else{ $db->commit();fb_registered(); }
    }

    }catch(Exception $rr){
      $db->rollBack();
      throw $rr;
    }catch(PDOException $rr){
      $db->rollBack();
      throw $rr;
    }
	 }

}catch(Facebook\Exceptions\FacebookResponseException $e){
  throw $e;
}catch(Facebook\Exceptions\FacebookSDKException $e){
  throw $e;
}

if(!isset($accessToken)){
  if ($helper->getError()){
    bad_request();
  }else{ bad_request(); }
  exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId('120604775447049');
$tokenMetadata->validateExpiration();

if(!$accessToken->isLongLived()){
  try{
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  }catch (Facebook\Exceptions\FacebookSDKException $e) {
    throw $e;
  }
}

function fb_login_problem(){
 redirectJsTopic('../error.php?fbloginfail=problem');
}

function fb_register_ok(){
 redirectJsTopic('../home.php');
}
function fb_home_ok(){
  redirectJsTopic('../home.php');
}

function fb_reshome_ok(){
  redirectJsTopic('../home.php');
}

function fb_registered(){
  redirectJsTopic('../index.php');
}

function bad_request(){
  redirectJsTopic('../error.php?fbloginfail=badrequest');
}


function redirectJsTopic($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location='.$url.';</script‌​>');
    }else{
      header('Location: ' . $url);
      die();
    }
}