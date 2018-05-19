<?php
if(!defined("MenuSctipt")){ header('Location: index.php'); }
?>

<?php if(!isLoggedIn($db)): ?>
  
  <button id ="signin" data-onsuccess="onSignIn">
  Google</button>
  <?php 
  
    require_once 'fbapi/vendor/autoload.php'; 

    $fb = new Facebook\Facebook([
      'app_id' => '120604775447049', // Replace {app-id} with your app id
      'app_secret' => 'xxxx',
      'default_graph_version' => 'v2.2',
      ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://localhost/delivery/forms/fb-callback.php', $permissions);

    //echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
    echo '<button onclick="goingWithFb('."'".htmlspecialchars($loginUrl)."'".')" id="fbLoginBtnn">Facebook</button>';

   ?>

  <form action="" method="post" role="form">
   <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" >
    <input type="text" name="<?= $form_names['loginform']['linfemail']; ?>" tabindex="1" class="form-control" placeholder="Email" autocomplete="off" value="" required>
    <input type="password" name="<?= $form_names['loginform']['linfpassword']; ?>" tabindex="2" placeholder="Κωδικός" autocomplete="off" value="" required>
    <input type="submit" name="<?= $form_names['loginform']['linfsubmit']; ?>" tabindex="4" value="Είσοδος" >
    <a href="reset-password.php" tabindex="5">Ξέχασα τον κωδικό μου</a>
  </form>

  <form action="" method="post" role="form">
    <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>">
     <input type="text" name="<?= $form_names['loginform']['regfname']; ?>" tabindex="6" placeholder="Όνομα" value="" required>
     <input type="text" name="<?= $form_names['loginform']['regfsurname']; ?>" tabindex="7" placeholder="Επώνυμο" value="" required>
     <input type="email" name="<?= $form_names['loginform']['regfemail']; ?>" tabindex="8" placeholder="Email" autocomplete="off" value="" required>
     <input type="password" name="<?= $form_names['loginform']['regfpassword1']; ?>" tabindex="9" placeholder="Κωδικός" autocomplete="off" required>
     <input type="password" name="<?= $form_names['loginform']['regfpassword2']; ?>" tabindex="10" placeholder="Επιβεβαίωση κωδικού" autocomplete="off" required>
     <input type="submit" name="<?= $form_names['loginform']['regfregister']; ?>" tabindex="11" value="Εγγραφή">
  </form>

   <script src="https://apis.google.com/js/api:client.js"></script>
   <script>

    var googleUser = {};
    var startApp = function(){
      gapi.load('auth2', function(){
        auth2 = gapi.auth2.init({
          client_id: '508546614538-16291r3u6ufsia15fqms2u4835o19nrt.apps.googleusercontent.com',
          cookiepolicy: 'single_host_origin',
        });
        attachSignin(document.getElementById('signin'));
      });
    };

    function attachSignin(element){
      auth2.attachClickHandler(element,{},
          function(googleUser){
            document.getElementById('signin').innerText = googleUser.getBasicProfile().getName();
              //onSignIn(googleUser);
          }, 
          function(error) {
            alert(JSON.stringify(error, undefined, 2));
          });
    }

    function onSignIn(googleUser){
    
     document.getElementById("signin").style.fontSize = "12px";
     var id_token = googleUser.getAuthResponse().id_token;

      $.ajax({
       type: 'POST',
       url: 'forms/googlelogin.php',
       data:{'token':id_token },
       success: function (data){console.log(data);

        if(data == 'bad_request'){

          alert('Υπήρξε τεχνικό πρόβλημα. Δοκιμάστε σε λίγο!');

        }else if(data == 'google_login_problem'){
          alert('Υπήρξε τεχνικό πρόβλημα. Δοκιμάστε σε λίγο!');
        }else if(data == 'google_home_ok'){
          goToMenu('home');
        }else if(data == 'google_reshome_ok'){
          goToMenu('restrictedhome');
        }else if(data == 'google_register_ok'){
          alert('Η εγγραφή σας πραγματοποιήθηκε. Ελέγξτε το email σας!');
          goToMenu('restrictedhome');
        }else if(data == 'googleonly_register_ok'){
          goToMenu('restrictedhome');
        }else if(data == 'google_registered'){
      
          alert('Έχετε εγγραφεί με το συγκεκριμένο email! Δεν μπορεί να πραγματοποιηθεί εγγραφή!');
        }
       },
       error: function(textStatus, errorThrown){
        console.log(textStatus, errorThrown);
       }
      });
    }
    
    function goToMenu(dest){
     window.location.href='redirect.php?goto='+dest;
    }

    startApp();

    function goingWithFb(thelink){
      window.location.href=thelink;
    }
    </script>

 <?php 
  else:
 ?>

<?php echo $_SESSION['email']; ?>
<a href='logout.php'>ΑΠΟΣΥΝΔΕΣΗ</a>


<?php  
 endif;  
?>
