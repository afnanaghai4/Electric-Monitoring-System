<?php 
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

if (isset($_POST['submit'])) {
  //form was submitted
  $_SESSION['email'] = false;
  header('Location: login.php');
}
?>