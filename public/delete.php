<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
include_once('../functions/updateDailyReport.php');

if (!isLoggedIn())
  header('Location: login.php')

  ?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-200">
  <?php include('../includes/navbar.php'); ?>
  <?php
  if (debug($GLOBALS['debug'])) {
    echo print_r($_POST);
  }

  if (isset($_POST['room_id'])) { 
    delete_room($_POST['room_id']);
    array_push($_SESSION['random_ids'], $_POST['motion_sensor_id']);
    array_push($_SESSION['random_ids'], $_POST['appliance_sensor_id']);
  } ?>

  <div class=" w-full text-center">
    <p class="text-3xl">
      Room deleted successfully
    </p>
  </div>
  <a href="home.php">
          <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
        </a>
  <?php
  ?>
</body>

</html>

<?php mysqli_close($connection) ?>