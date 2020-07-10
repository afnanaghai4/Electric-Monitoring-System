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
  <?php include('../includes/navbar.php');

  function get_edit_room($room_id)
  {
    global $connection;
    $query = "select * from rooms where room_id={$room_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
  }
  function get_sensor_name_appliance($room_id)
  {
    global $connection;
    $query = " select sensor_name, sensor_id from rooms join rooms_sensor using (room_id) join sensors using (sensor_id) where room_id={$room_id} order by sensor_name limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
  }

  function get_sensor_name_motion($room_id)
  {
    global $connection;
    $query = " select sensor_name, sensor_id from rooms join rooms_sensor using (room_id) join sensors using (sensor_id) where room_id={$room_id} order by sensor_name desc limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row;
  }

  if (debug($GLOBALS['debug'])) {
    echo print_r($_POST);
  }

  if (isset($_POST['room_id'])) {
    if (isset($_POST['saveChanges'])) {
      update_sensors($_POST['motion_sensor_id'], $_POST['motion_sensor_name']);
      update_sensors($_POST['appliance_sensor_id'], $_POST['appliance_sensor_name']);
      update_room_name($_POST['room_id'], mysqli_real_escape_string($connection,$_POST["room_name"]));
    }
    $result = get_edit_room($_POST['room_id']);
    $row2 = mysqli_fetch_assoc($result);
    $app_sensor = get_sensor_name_appliance($_POST['room_id'])['sensor_name'];
    $motion_sensor = get_sensor_name_motion($_POST['room_id'])['sensor_name'];
    ?>
    <p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Add Room</p>
    <form action="edit.php" method="POST">
      <div class="pl-6 pr-6 pb-6">
        <div class="bg-white shadow py-4 rounded-lg">
          <div class="mb-4 px-4">
            <label class="block text-gray-700 text-lg font-bold mb-2" for="name">
              Edit Room Name
            </label>
            <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="room_name" type="text" placeholder="Room Name" value="<?php echo $row2['room_name'] ?>" />
          </div>
          <div class="mb-4 px-4">
            <label class="block text-gray-700 text-lg font-bold mb-2" for="appliance">
              Edit Motion Sensor Name
            </label>
            <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="motion_sensor_name" type="text" placeholder="Motion Sensor Name" value="<?php echo $motion_sensor ?>" />
          </div>
          <div class="mb-4 px-4">
            <label class="block text-gray-700 text-lg font-bold mb-2" for="appliance">
              Edit Appliance Sensor Name
            </label>
            <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="appliance_sensor_name" type="text" placeholder="Appliance Sensor Name" value="<?php echo $app_sensor ?>" />
            <input type="hidden" name="room_id" value="<?php echo $_POST['room_id'] ?>" />
            <input type="hidden" name="motion_sensor_id" value="<?php echo get_sensor_name_motion($_POST['room_id'])['sensor_id']; ?>" />
            <input type="hidden" name="appliance_sensor_id" value="<?php echo get_sensor_name_appliance($_POST['room_id'])['sensor_id']; ?>" />
          </div>
          <div class="mt-4 flex justify-center">
            <input type='submit' name='saveChanges' value='Save Changes' class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded" />
          </div>
        </div>
      </div>
    </form>
    </div>


    <div id="myModal" class="hidden fixed z-10 left-0 top-0 w-full h-full overflow-auto" style="background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */">

      <!-- Modal content -->
      <div class="rounded-lg px-5 py-4" style="background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  width: 80%; /* Could be more or less, depending on screen size */">


        <p class="text-center pb-2">Are you sure you want to delete this room?</p>
        <div class="flex justify-center w-full">
          <form action="delete.php" class="m-0" method="POST">
            <input type='submit' name='yes' value='Yes' class="mr-4 bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-4 rounded" />
            <input type="hidden" name="room_id" value='<?php echo $_POST['room_id'] ?>' />
            <input type="hidden" name="motion_sensor_id" value="<?php echo get_sensor_name_motion($_POST['room_id'])['sensor_id'] ?>" />
            <input type="hidden" name="appliance_sensor_id" value="<?php echo get_sensor_name_appliance($_POST['room_id'])['sensor_id'] ?>" />
          </form>
          <button class="close bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">No</button>
        </div>
      </div>

    </div>
    <div class="w-full text-center px-6">
      <button id="myBtn" name='Delete room' class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-4 rounded"> Delete Room </button>
    </div>
  <?php } ?>
</body>
<script>
  // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.getElementById("myBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks on the button, open the modal
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>

</html>

<?php mysqli_close($connection) ?>