<!-- <?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

/////////////////////////
$body=file_get_contents("https://ewelink.icemelt72.now.sh/api/test");
 
 
$x = json_decode($body,true);
 
// Implicitly cast the body to a string and echo it

$len = count($x["devicelist"]);

 ///////////////////////////////

if($_GET) {
  $room_id = $_GET['room_id'];
} {
  $random = $_SESSION['random_ids'];
  $room_id = $_SESSION['room_id'];
  $room_id--;
}


if (!isLoggedIn()) header('Location: login.php');

//DATA FROM addMotionSensor.php and addAppliance.php
if (isset($_POST['submit'])) {
  if ($_POST['page'] == 'motion_page') {
    //set sensor
    $motion_sensor_name = $_POST['motion_sensor_name'];
    //update sensore name
    update_sensors($_POST['id'], $motion_sensor_name);
  } else {
    for ($i = 0; $i < $_POST['count']; $i++) {
      insert_into_appliances($room_id, $_POST['appliance_name' . $i], $_POST['rating' . $i]);
    }
    //set sensor
    $appliance_sensor_name = $_POST['appliance_sensor_name'];
    //update sensore name
    update_sensors($_POST['id'], $appliance_sensor_name);
  }
}

?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <div class="bg-gray-300">
    <p class="text-center text-3xl pt-4 pb-2 mb-4 bg-gray-200">Available Sensors</p>
    <div class="pl-6 pr-6 pb-6">
      <div class="bg-white shadow pt-4 rounded-lg">
        <?php $sensor = 'Appliance Sensor';
        $link = "";

         
        //foreach ($len as $element)

        for ($i=0;$i<$len;$i++)
         {
          if ($sensor == 'Appliance Sensor') {
            $sensor = 'Motion Sensor';
            $sensorID = $x["devicelist"][$i]["deviceid"];
            $ProductModel = $x["devicelist"][$i]["productModel"];
            $ActualName = $x["devicelist"][$i]["name"];
            $link = 'addMotionSensor.php';
          } else {
            $sensor = 'Appliance Sensor';
            $sensorID = $x["devicelist"][$i]["deviceid"];
            $ProductModel = $x["devicelist"][$i]["productModel"];
            $ActualName = $x["devicelist"][$i]["name"];
            $link = 'addApplianceSensor.php';
          }
          ?>
          <div class="pb-4 px-4 flex flex-wrap">
            <div>
            <p class="text-xl block w-full"><?php echo $sensor . ' - #' . $sensorID; ?></p> 
            <p class="text-xs block w-full"><?php echo $ActualName . ' - #' . $ProductModel; ?></p>
            </div>
            <form class="ml-auto" action='<?php echo $link ?>' method="POST">
              <input type="hidden" name="name" value='<?php echo $sensor ?>' />
              <input type="hidden" name="type" value='<?php echo $sensor ?>' />
              <input type="hidden" name="id" value='<?php echo $i ?>' />
              <input type='submit' name="submit" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" value="Add Sensor" />
            </form>
          </div>

        <?php
        }
        ?>
      </div>
    </div>
    <a href="home.php">
      <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
    </a>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?> -->