<?php

require_once 'includes/authenticate.php';
require_once 'includes/library/HTMLPurifier.auto.php';

if(!isLoggedIn()){

$csrf = new csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
$_SESSION['anerror'] = "";

$lang = array('wrongcredentials' => 'Το email ή ο κωδικός σας είναι λάθος!',
              'wrongformat'=>'Κάτι δεν πήγε καλά, δοκιμάστε σε λίγο!',
              'wrongpassmatch'=>'Οι κωδικοί δεν ταιριάζουν, προσπαθήστε ξανά!',
              'smallpass'=>'Ο κωδικός σας θα πρέπει να είναι μεγαλύτερος από 5 ψηφία!',
              'strongerpass'=>'Ο κωδικός σας θα πρέπει να περιέχει γράμμα και αριθμό!',
              'registered'=>"Δεν μπορείτε να εγγραφείτε με αυτά τα στοιχεία, προσπαθήστε ξανά!",
              'emailsend'=>'Ένα email έχει σταλεί για να ενεργοποιήσετε τον λογαρισμό σας!',
              'manytimesfailed'=>'Έχετε υπερβεί το όριο αποτυχημένων προσπαθειών για είσοδο! Δοκιμάστε ξανά σε μία ώρα από τώρα. Ωστόσο μπορείτε να επαναφέρετε τον κωδικό σας!','cantsendemail'=>'Η εγγραφή απέτυχε, δεν μπορέσαμε να στείλουμε email. Προσπαθήστε ξανά!');

$form_names = $csrf->form_names(array('linfemail','linfpassword','linfsubmit','regfemail','regfname','regfsurname','regfpassword1','regfpassword2','regfregister'),false,"loginform");

if(isset($_POST[$form_names['loginform']['linfemail']],
         $_POST[$form_names['loginform']['linfpassword']],
         $_POST[$form_names['loginform']['linfsubmit']])){

 if($csrf->check_valid('post')){

  $ip = getUserIp();  
  $email = filterxss($_POST[$form_names['loginform']['linfemail']]);
  $password = filterxss($_POST[$form_names['loginform']['linfpassword']]);
  $attemptstable = false;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false){

      $stmt = selectFromWhere($db, "*", "users", "email", $email);
      $stored = $stmt->fetch();
      $hhhh = selectFromWhere($db, "attempts,created", "login_attempts", "email", $email);
      $attemptsnumber = $hhhh->fetch();
      //if it's been one hour
      if($attemptsnumber){
        $attemptstable = true;
        $hourone = strtotime($attemptsnumber['created']);
        if(time()-$hourone > 60*60){
          deleteFromWhere($db,'login_attempts','email',$email);
          $attemptsnumber['attempts'] = 0;
          $attemptstable = false;
        }
      }

      if(!$stored){$_SESSION['anerror'] = $lang['wrongcredentials'];
      }elseif($attemptsnumber['attempts'] > 4){$_SESSION['anerror'] = $lang['manytimesfailed'];
      }else{

        if(password_verify($password,$stored['pwd'])){

          $_SESSION['user_key'] = $stored['user_key'];
          $_SESSION['email'] = $stored['email'];
          $_SESSION['clientip'] = getUserIp();
          userInfos($db,$_SESSION['user_key']);
          clearSessionVars();
          $loginCookie = new cookieworker();
          $loginCookie->rememberLogin();

        if($attemptstable){deleteFromWhere($db,'login_attempts','email',$stored['email']);}
         if($stored['verify']==1){ $_SESSION['verify'] = 1;header('Location: home.php');exit;
         }else{ $_SESSION['verify'] = 0; header('Location: restricted-home.php');exit; }
        }else{
          //wrong password
          $attemptsCounter = 0;
          if(isset($attemptsnumber)){$attemptsCounter = $attemptsnumber['attempts'];}

          if($attemptsCounter == 0){
            $attemptsCounter+=1;
            $thecols = "(email,attempts,ipaddress)";
            $parndata = array(':one'=>$email,':two'=>$attemptsCounter,':three'=>$ip);
            $binds = "(:one,:two,:three)";
            insertInto($db,'login_attempts',$thecols,$binds,$parndata);
            $_SESSION['anerror'] = $lang['wrongcredentials'];
          }elseif($attemptsCounter > 0){
            $attemptsCounter+=1;
            $theCols = 'attempts=:one,ipaddress=:two';
            $bindss = array(':one'=>$attemptsCounter,':two'=>$ip,':mail'=>$email);
            $wheres = 'email=:mail';
            updateDb($db,'login_attempts',$theCols,$wheres,$bindss);
            $_SESSION['anerror'] = $lang['wrongcredentials'];
          }
        }
      }
    }else{ $_SESSION['anerror'] = $lang['wrongformat']; }
 }else{ $_SESSION['anerror'] = $lang['wrongformat']; }

$form_names = $csrf->form_names(array('linfemail', 'linfpassword','linfsubmit','regfemail','regfname','regfsurname','regfpassword1','regfpassword2','regfregister'), true,"loginform");
}

if(isset($_POST[$form_names['loginform']['regfemail']],
         $_POST[$form_names['loginform']['regfpassword1']],
         $_POST[$form_names['loginform']['regfpassword2']],
         $_POST[$form_names['loginform']['regfname']],
         $_POST[$form_names['loginform']['regfsurname']],
         $_POST[$form_names['loginform']['regfregister']])){

 if( $csrf->check_valid('post') ){

  $ip = getUserIp();    
  $register_code = md5(uniqid(rand(), true));
  $email = filterxss($_POST[$form_names['loginform']['regfemail']]);
  $username = filterxss($_POST[$form_names['loginform']['regfname']]);
  $surname = filterxss($_POST[$form_names['loginform']['regfsurname']]);
  $password1 = filterxss($_POST[$form_names['loginform']['regfpassword1']]);
  $password2 = filterxss($_POST[$form_names['loginform']['regfpassword2']]);
  $user_key = hash('sha256',microtime(true).mt_rand().$email);
  $tttt = selectFromWhere($db, "user_key", "users", "user_key", $user_key);
  $storedkey = $tttt->fetchColumn();

  do{

   $user_key = hash('sha256',microtime(true).mt_rand().$email);
   $testkey = selectFromWhere($db,'COUNT(*)','users','user_key',$user_key);
   $testkeyCounter = $testkey->fetchColumn();

  }while($testkeyCounter>0);


if(protectOne($password1) && 
   protectOne($password2) && 
   protectOne($username) && 
   protectOne($surname)){

if($password1 != $password2 ){$_SESSION['anerror'] = $lang['wrongpassmatch'];
}elseif(strlen($password1) < 6){$_SESSION['anerror'] = $lang['smallpass'];
}elseif(passwordRegister($password1)){$_SESSION['anerror'] = $lang['strongerpass'];
}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) === false){

if(validate_email($email)){

  $qwer = selectFromWhere($db,'COUNT(*)','users','email',$email);
  if($stmt = $qwer->fetchColumn()!=0){

   $_SESSION['anerror'] = $lang['registered'];

  }else{

    if(registerEmail($email,$username,$surname,$user_key,$register_code)){
    //$m = new EMailSender;
    //if( true ){
    //if( $m->sendMail($email, $username." ".$surname, $user_key, $register_code) ){

     $db->beginTransaction();

    try{

     $stmtreg = $db->prepare("INSERT INTO users(user_key, username, surname, email, pwd) VALUES(:key, :username, :surname, :email, :pwd)");
     $stmtreg->bindParam(':key',$user_key);
     $stmtreg->bindParam(':username',$username);
     $stmtreg->bindParam(':surname',$surname);
     $stmtreg->bindParam(':email',$email);
     $stmtreg->bindValue(':pwd',password_hash($password1,PASSWORD_DEFAULT));
     $stmtreg->execute();

     $stmtregs= $db->prepare("INSERT INTO verifying(user_key, user_code) VALUES(:key, :code)");
     $stmtregs->bindParam(':key',$user_key);
     $stmtregs->bindParam(':code',$register_code);
     $stmtregs->execute();
     $_SESSION['anerror'] = $lang['emailsend'];

     $db->commit();
     //register success
     if($stmtreg->rowCount()){

      $_SESSION['user_key'] = $user_key;
      $_SESSION['clientip'] = $ip;
      $_SESSION['verify'] = 0;
      $_SESSION['email'] = $email;
      $registerCookie = new cookieworker();
      $registerCookie->rememberLogin();
      header('Location: restricted-home.php');
      exit;
     }

     }catch(Exception $exx){ $db->rollBack();throw $exx;
     }catch(PDOException $exx){ $db->rollBack();throw $exx;}
    }else{ $_SESSION['anerror'] = $lang['cantsendemail']; }//send email
  }//else
}else{ $_SESSION['anerror'] = $lang['wrongformat']; }
}else{ $_SESSION['anerror'] = $lang['wrongformat']; }
}else{ $_SESSION['anerror'] = $lang['wrongformat']; }
}else{ $_SESSION['anerror'] = $lang['wrongformat']; }

$form_names = $csrf->form_names(array('linfemail', 'linfpassword','linfsubmit','regfemail','regfname','regfsurname','regfpassword1','regfpassword2','regfregister'), true,"loginform");
}

}else{ loginRegisterAuth(); }
?>