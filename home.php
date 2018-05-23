<?php

require_once 'includes/authenticate.php';
require_once 'includes/library/HTMLPurifier.auto.php';
homeAuth();

echo 'home:'.$_SESSION['email'];
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

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<script src = 'js/jquery-3.3.1.min.js'></script>
<script src = 'js/bootstrap.min.js'></script>

<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/home.css">

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

</head>
<body>
<?php
 definers(); 
 uniquePage();
 include 'menu.php';
?>


<script src='js/menu.js'></script>
<script src='js/home.js'></script>
</body>
</html>