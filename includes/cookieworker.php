<?php

class cookieworker{

private $host = "", $user = "", $password = "", $dbname = "", $db;
private $cookie = '';
private $expiry;
private $cookiePath = '/';
private $domain = '';
private $secure = null;
private $httponly = true;
private $uemail = 'email';
private $ukey = 'user_key';
private $theKey;
    
public function __construct(){

    require_once 'db_connect.php';

    try{

      $this->dbname = $GLOBALS['dbname'];
      $this->host = $GLOBALS['dbhost'];
      $this->user = $GLOBALS['dbuser'];
      $this->password = $GLOBALS['dbpassword'];

      $this->db = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->user,$this->password);
      $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    }catch(PDOException $e){

        die('Database connection error');
    }
    $this->cookie = $GLOBALS['cookie_user'];
    $this->expiry = time()+86400;
}    

private function getUserIp(){
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

private function generateToken(){

return bin2hex(openssl_random_pseudo_bytes(32));
}

private function storeToken($token){

    $clientIp = $this->getUserIp();
    
    if( isset($token) && isset($clientIp)){
    
        try{

         $stmt = $this->db->prepare("INSERT INTO autologin(user_key, token, ip)VALUES(:key, :token, :ip)");
         $stmt->bindParam(':key',$_SESSION[$this->ukey]);
         $stmt->bindParam(':token',$token);
         $stmt->bindParam(':ip',$clientIp);
         $stmt->execute();

        }catch(PDOException $e){
            throw $e;
        }catch(Exception $eq){
            throw $eq;
        }
    }
}

private function setCookie($token){

setcookie($this->cookie,$token,$this->expiry,$this->cookiePath,$this->domain,$this->secure,$this->httponly);

}

public function rememberLogin(){
  $this->deleteOldStore();
  $token = $this->generateToken();
  $this->storeToken($token);
  $this->setCookie($token);
}

private function deleteOldStore(){
  
  try{

   $www = $this->db->prepare("DELETE FROM autologin WHERE user_key = :kkk");
   $www->bindParam(':kkk',$_SESSION['user_key']);
   $www->execute();

  }catch(Exception $r){ return false; }catch(PDOException $t){ return false; }
}

private function checkCookieToken($token,$ip){

 $alfa = $this->expireCookie($token,getUserIp());
 if(!$alfa){ return false; }
 $www = new DateTime();
 $rrr = $www->format('Y-m-d H:i:s');
 $ts1 = strtotime($alfa);
 $ts2 = strtotime($rrr);
 $seconds_diff = $ts2 - $ts1;

 if($seconds_diff > 86400){

  $ffg = $this->db->prepare("DELETE FROM autologin WHERE token = :token AND ip = :ip");
  $ffg->bindParam(':token',$token);
  $ffg->bindParam(':ip',$ip);
  $ffg->execute();
 }

 try{

  $stmt = $this->db->prepare("SELECT COUNT(*) FROM autologin WHERE token = :token AND ip = :ip");
  $stmt->bindParam(':token',$token);
  $stmt->bindParam(':ip',$ip);
  $stmt->execute();

  if($stmt->fetchColumn()>0){ return true; }else{ return false; }

 }catch(Exception $r){
    throw $r;
 }catch(PDOException $t){
    throw $t;
 }
}

private function expireCookie($token,$clientIp){

  try{

   $see = $this->db->prepare("SELECT created FROM autologin WHERE token = :token AND ip = :ip");
   $see->bindParam(':token',$token);
   $see->bindParam(':ip',$clientIp);
   $see->execute(); 
   $res = $see->fetchColumn();
   return $res;

  }catch(Exception $r){ throw $r; }catch(PDOException $t){ throw $t; }
}

public function userAuthentication($token){
    
    $clientIp = $this->getUserIp();

    if( isset($token,$clientIp) ){
        return $this->checkCookieToken($token,$clientIp);
    }else{ return false; }
}

public function getInfos($token){
    
  try{

    $stmt = $this->db->prepare("SELECT user_key FROM autologin WHERE token = :token");
    $stmt->bindParam(':token',$token);
    $stmt->execute();

    if( $theKey = $stmt->fetchColumn() ){

      $vvv = $this->db->prepare("SELECT verify,email,user_key FROM users WHERE user_key = :uukey");
      $vvv->bindParam(':uukey',$theKey);
      $vvv->execute();
      if( $res = $vvv->fetch() ){ return $res; }else{ return false; }

    }else{ return false; }
  }catch(Exception $r){ throw $r; }catch(PDOException $t){ throw $t; } 
}

private function garbage(){

   $stmt= $this->db->prepare("DELETE FROM autologin WHERE created < (NOW() - INTERVAL 1 DAY)");
   $stmt->execute();    
}

public function deleteCookie($token){
    
  try{

    $stmt = $this->db->prepare("DELETE FROM autologin WHERE token = :token");
    $stmt->bindParam(':token',$token);
    $stmt->execute();  
    setcookie($this->cookie, '', time() - 60*60*24,'');
    setcookie($this->cookie, '', time() - 60*60*24,'/');
    unset($_COOKIE[$this->cookie]);

  }catch(Exception $r){ throw $r; }catch(PDOException $t){ throw $t; }
}

}