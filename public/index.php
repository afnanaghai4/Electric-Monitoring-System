<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
//Check if signed in
if (isLoggedIn()) {
  header("Location: home.php");
} else {
  if(isset($_SESSION['loggedin']))
  $_SESSION['loggedin'] = false;
}
?>

<html>

<?php
include_once('../includes/header.php'); ?>

<body>


  <div class="bg-cover bg-fixed bg-no-repeat bg-center flex" style="background-image: url(../images/loginbg.jpg)">
    <div class="w-screen h-screen flex items-center justify-center p-10">
      <div class="max-w-lg flex-1">
        <div class="flex justify-center mb-4">
          <a href="signup.php" class="bg-white text-center hover:bg-blue-600 hover:text-white text-blue-500 font-bold py-3 w-full rounded-full focus:outline-none focus:shadow-outline">SIGN IN</a>
        </div>
        <div class="flex justify-center ">
          <a class="text-base text-yellow-400 font-bold hover:underline hover:text-yellow-200" href="login.php">
            Already have an account? Log in
          </a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>