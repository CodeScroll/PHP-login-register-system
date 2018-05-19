<?php

require_once 'includes/library/HTMLPurifier.auto.php';
require_once 'includes/authenticate.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/autoload.php';

if(isset($_SESSION['passreset'])){

$csrf = new csrf();
$token_id = $csrf->get_token_id();
$token_value = $csrf->get_token($token_id);
$form_names = $csrf->form_names(array('resfpassword', 'resfsubmit'),false,"resetform");

if(isset($_POST[$form_names['resetform']['resfpassword']],
         $_POST[$form_names['resetform']['resfsubmit']])){
            
 $password = filterxss($_POST[$form_names['resetform']['resfpassword']]);

    try{
        
     $userKey = $_SESSION['passreset']['user_key'];
     $resetCode = $_SESSION['passreset']['reset_code'];
     $username = $_SESSION['passreset']['user_name']; 
     $useremail = $_SESSION['passreset']['user_email'];
     $verify = $_SESSION['passreset']['verify'];

     $stmtuu = $db->prepare("SELECT COUNT(*) FROM resetpassword WHERE user_key = :ukey AND reset_code = :ucode");
     $stmtuu->bindParam(':ukey',$userKey);
     $stmtuu->bindParam(':ucode',$resetCode);
     $stmtuu->execute();

        if($stmtuu->fetchColumn()!=0){

         $stmt = $db->prepare("UPDATE users SET pwd = :password WHERE user_key = :uukey");
         $stmt->bindValue(':uukey', $userKey,PDO::PARAM_STR);
         $stmt->bindValue(':password', password_hash($password,PASSWORD_DEFAULT));
         $stmt->execute();

         $stmtoo = $db->prepare("DELETE FROM resetpassword WHERE user_key = :ukey AND reset_code = :ucode");
         $stmtoo->bindParam(':ukey', $userKey);
         $stmtoo->bindParam(':ucode', $resetCode);
         $stmtoo->execute();
         session_unset();
         session_destroy();
         header('Location: index.php?passwordchange=changed');
         exit;

        }else{ echo "This link have expired";}

    }catch(PDOException $ex){throw $ex; }catch(Exception $exi){ throw $exi; }
}
}else{ header('Location: index.php');exit; }

if(isset($_SESSION['passreset']['reset'],$_SESSION['passreset']['clientip'])){

    if( $_SESSION['passreset']['clientip'] == getUserIp() ){

?>

<!DOCTYPE html>
<html lang="el">
<head>
<title></title>
<meta charset="UTF-8">
<meta name="description" content="">
<meta name="keywords" content="HTML,CSS,XML,JavaScript">
<meta name="author" content="Iordanis Georgiadis">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

</head>
<body>
<?php
definers(); 
uniquePage();
?>

<form action="" method="POST" >
 <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
 <label for="password">Password</label>
 <input type="password" name="<?= $form_names['resetform']['resfpassword']; ?>" id="password">
 <input type="submit" name="<?= $form_names['resetform']['resfsubmit']; ?>" id="reset" Value="Reset">
</form>

</body>
</html>

<?php
    }
}else{ header('Location: index.php');exit; }