<?php
session_start();
header('Refresh: 2; url=found_fake.php');
include_once('../includes/connection.php');
include_once('../functions/queries.php');
$room_id = $_SESSION['room_id'];
$house_id = $_SESSION['house_id'];

//DATA FROM addroom.php
if (isset($_POST["submit"])) {
  //form was submitted
  $room =  mysqli_real_escape_string($connection,$_POST["room_name"]);
  $appliances = $_POST["appliances"];
  insert_into_rooms($room_id, $house_id, $room, $appliances, 4);
  global $connection;
    $query2 = "select flag from rooms where room_id={$room_id}";
    $result2 = mysqli_query($connection, $query2);
    confirm_query($result2);
    $row = mysqli_fetch_assoc($result2);
    $_SESSION['flag'] = $row['flag'];
    if($row['flag'] == '1') { ?>
      <script>
      location.replace("found.php");
    </script> <?php
    } else { ?>
  <script>
      location.replace("found_fake.php");
    </script> <?php
    }
    
    $room_id++;
  $_SESSION['room_id'] = $room_id;
} else {
  $room_name = "";
  $appliances = "";
}


?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body>
    <div class="bg-white w-screen h-screen flex items-center justify-center">
        <div class="max-w-lg flex-1 text-center">
            <p class="text-3xl text-center">Searching for sensors</p>
            <img src="../images/spinner.gif" class="h-24 w-24 mx-auto" alt="loading">
        </div>
    </div>
</body>
</html>

<?php mysqli_close($connection) ?>