<?php 
require_once 'login.php';
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
include 'menu.php';

	if(isset($_SESSION['anerror'])){
	 if($_SESSION['anerror'] != "" ){  
	  $theError = $_SESSION['anerror'];
	  echo $theError;
	 }
	}
?>

</body>
</html>