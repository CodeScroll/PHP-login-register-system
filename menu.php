<?php
if(!defined("MenuSctipt")){ header('Location: index.php'); }
?>



<nav class="navbar navbar-default navbar-fixed-top">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Project name</a>
  </div>
  <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav" id='menu-leftul'>
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li role="separator" class="divider"></li>
          <li class="dropdown-header">Nav header</li>
          <li><a href="#">Separated link</a></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li>
    </ul>

<?php if(!isLoggedIn($db)): ?>
<<<<<<< HEAD

<ul class="nav navbar-nav navbar-right" id='menu-rightul'>
  <li><a href="#" id="loginregister-form">
    <span id='loginregister-form-tag'>Είσοδος</span>
    <span id='loginregister-form-arrow' class="glyphicon glyphicon-triangle-bottom"></span></a></li>

<li>
<div id='loginregister-section' class="divcentermenu">
 <h5 class="loginregister-googlefbh5">Σύνδεση με</h5>
 <button class="loginBtn loginBtn--google" id="googlesignin" data-onsuccess="onSignIn">
 Google</button>
<?php 

  require_once 'fbapi/vendor/autoload.php'; 
  $fb = new Facebook\Facebook([
    'app_id' => '1826699470960105', // Replace {app-id} with your app id
    'app_secret' => '15e0f7b4b0cb2998f21fd074097769fd',
    'default_graph_version' => 'v2.2',
    ]);

  $helper = $fb->getRedirectLoginHelper();
  $permissions = ['email']; // Optional permissions
  $loginUrl = $helper->getLoginUrl('https://localhost/LoginRegisterSystem2018/forms/fb-callback.php', $permissions);
  echo '<button onclick="goingWithFb('."'".htmlspecialchars($loginUrl)."'".')" id="fbLoginBtnn" class="loginBtn loginBtn--facebook">Facebook</button>';

 ?>
 <h5 class="loginregister-googlefbh5">ή</h5>
  <div class="tabbable-line">
    <ul class="nav nav-tabs" id='menu-loginregister-tab'>
      <li class="active" id="logintab">
        <a href="#tab_default_1" data-toggle="tab">Είσοδος</a>
      </li>
      <li id="registertab">
        <a href="#tab_default_2" data-toggle="tab">Εγγραφή</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_default_1">
        <form id="login-form" action="" method="post" role="form" style="display: block;">
         <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" >
         <div class="form-group">
          <span class="input input--isao">
            <input class="input__field input__field--isao" type="text" id="lgusername" name="<?= $form_names['loginform']['linfemail'];?>" tabindex="1" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="lgusername" data-content="Email">
              <span class="input__label-content input__label-content--isao">Email</span>
            </label>
          </span>
         </div>
         <div class="form-group">
          <span class="input input--isao">
            <input class="input__field input__field--isao" type="password" id="lgpassword" name="<?= $form_names['loginform']['linfpassword'];?>" tabindex="2" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="lgpassword" data-content="Κωδικός">
              <span class="input__label-content input__label-content--isao">Κωδικός</span>
            </label>
          </span>
         </div>         
         <div class="form-group">
          <input type="submit" name="<?= $form_names['loginform']['linfsubmit']; ?>" id="lglogin-submit" tabindex="3" class="form-control btn btn-login" value="Είσοδος" >
         </div>
         <div class="form-group">
          <div class="text-center">
           <a href="reset-password.php" tabindex="4" class="forgot-password">Ξέχασα τον κωδικό μου </a>
          </div>
         </div>
        </form>
      </div>
      <div class="tab-pane" id="tab_default_2">
        <form id="register-form" action="" method="post" role="form">
          <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>">
          <div class="form-group">
           <span class="input input--isao">
            <input class="input__field input__field--isao" type="text" id="reusername" name="<?= $form_names['loginform']['regfname'];?>" tabindex="5" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="reusername" data-content="Όνομα">
              <span class="input__label-content input__label-content--isao">Όνομα</span>
            </label>
           </span>             
          </div>
          <div class="form-group">
           <span class="input input--isao">
            <input class="input__field input__field--isao" type="text" id="reusersurname" name="<?= $form_names['loginform']['regfsurname'];?>" tabindex="6" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="reusersurname" data-content="Επώνυμο">
              <span class="input__label-content input__label-content--isao">Επώνυμο</span>
            </label>
           </span>             
          </div> 
          <div class="form-group">
           <span class="input input--isao">
            <input class="input__field input__field--isao" type="text" id="reemail" name="<?= $form_names['loginform']['regfemail'];?>" tabindex="7" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="reemail" data-content="Email">
              <span class="input__label-content input__label-content--isao">Email</span>
            </label>
           </span>             
          </div>
          <div class="form-group">
           <span class="input input--isao">
            <input class="input__field input__field--isao" type="password" id="repassword" name="<?= $form_names['loginform']['regfpassword1'];?>" tabindex="8" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="repassword" data-content="Κωδικός">
              <span class="input__label-content input__label-content--isao">Κωδικός</span>
            </label>
           </span>             
          </div>
          <div class="form-group">
           <span class="input input--isao">
            <input class="input__field input__field--isao" type="password" id="confirm-password" name="<?= $form_names['loginform']['regfpassword2'];?>" tabindex="9" autocomplete="off" value="" required />
            <label class="input__label input__label--isao" for="confirm-password" data-content="Επιβεβαίωση κωδικού">
              <span class="input__label-content input__label-content--isao">Επιβεβαίωση κωδικού</span>
            </label>
           </span>             
          </div>
          <div class="form-group">
           <input type="submit" name="<?= $form_names['loginform']['regfregister']; ?>" id="register-submit" tabindex="10" class="form-control btn btn-register" value="Εγγραφή">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</li>
</ul>
=======
  
  <button id ="signin" data-onsuccess="onSignIn">
  Google</button>
  <?php 
  
    require_once 'fbapi/vendor/autoload.php'; 

    $fb = new Facebook\Facebook([
      'app_id' => '1826699470960105', // Replace {app-id} with your app id
      'app_secret' => '15e0f7b4b0cb2998f21fd074097769fd',
      'default_graph_version' => 'v2.2',
      ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://localhost/LoginRegisterSystem2018/forms/fb-callback.php', $permissions);

    //echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
    echo '<button onclick="goingWithFb('."'".htmlspecialchars($loginUrl)."'".')" id="fbLoginBtnn">Facebook</button>';

   ?>

  <form action="" method="post" role="form">
   <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" >
    <input type="text" name="<?= $form_names['loginform']['linfemail']; ?>" tabindex="1" placeholder="Email" autocomplete="off" value="" required>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
>>>>>>> 005e548a66e8d00d499ddac825956b2d5cabd365
   <script src="https://apis.google.com/js/api:client.js"></script>
   <script>

  var googleUser = {};
  var startApp = function(){
    gapi.load('auth2', function(){
      auth2 = gapi.auth2.init({
        client_id: '543465498689-7qve5u1dvm1hdlpvlu4s5mmkkeeg1uj6.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
      });
      attachSignin(document.getElementById('googlesignin'));
    });
  };

  function attachSignin(element){
    auth2.attachClickHandler(element,{},
        function(googleUser){
          document.getElementById('googlesignin').innerText = googleUser.getBasicProfile().getName();
            onSignIn(googleUser);
        }, 
        function(error) {
          alert(JSON.stringify(error, undefined, 2));
        });
  }

  function onSignIn(googleUser){
  
   document.getElementById("googlesignin").style.fontSize = "12px";
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
        goToMenu('restricted-home');
      }else if(data == 'google_register_ok'){
        alert('Η εγγραφή σας πραγματοποιήθηκε. Ελέγξτε το email σας!');
        goToMenu('restricted-home');
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

  <ul class="nav navbar-nav navbar-right" id='rightul'>
    <li class="dropdown">
     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
      <?php echo $_SESSION['email']; ?>
      <span class="caret"></span>
    </a>
     <ul class="dropdown-menu" id='logged-submenu'>
      <li><a href='account.php' id='logged-submenu-settings'>Ρυθμίσεις λογαριασμού</a></li>
      <li>
       <a href='logout.php' id='logged-submenu-logout'>
        <span class="glyphicon glyphicon-off"></span>ΑΠΟΣΥΝΔΕΣΗ</a>
      </li>
     </ul>
    </li>
  </ul>
 </div><!--/.nav-collapse -->
</nav>

<?php echo $_SESSION['email']; ?>

<?php  
 endif;  
?>
