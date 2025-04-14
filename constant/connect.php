<?php
// DB credentials.
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "NOWDEMY_pharmacy";
//$store_url = "http://localhost/phpinventory/";
// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if ($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // Ojmp2tYgDfl
  // echo "Successfully connected";
}
