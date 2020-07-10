<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'salman33';
$dbname = 'dbproject';
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
  die("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
}
$GLOBALS['debug'] = true;
?>