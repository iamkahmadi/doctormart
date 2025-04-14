<?php 

session_start();

require_once 'connect.php';

if(!$_SESSION['userId']) {
	header('location:./login.php');	
} 



?>