# PHP-login-register-system
A full feature php session-cookie login/register system.

Some "RULES"
 
 - The core is in "includes" folder
 
 - instead of "session_start()" use "init.php"
 
 - The first call of every page should be "authenticate.php" for authenticate the user
 
 -The most important functions for user authentication are
 
   - loginRegisterAuth(), every page that wants the  status of user, "disconnected"
   
   - resHomeAuth(), every page that wants the  status of user, "connected" and unverified
   
   - homeAuth(), every page that wants the  status of user, "connected" and verified
   
   -isLoggedIn(), if the user is "connected" or "disconnected"
   
To run the project, you must set the email functionality of your server. 
If you want to run it locally without email  you must comment the line 153 at "login.php" and uncomment the 154.

Stay here, the project grows up day by day :)
