<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
/////////////////////////
$body=file_get_contents("https://ewelink.icemelt72.now.sh/api/test");
 
 
$x = json_decode($body,true);
 
// Implicitly cast the body to a string and echo it

$len = count($x["devicelist"]);

 ///////////////////////////////
$time = getTime();
if ($_GET) {
  $room_id = $_GET['room_id'];
  $random = $_SESSION['random_ids'];
} else {
  $random = $_SESSION['random_ids'];
  $room_id = $_SESSION['room_id'];
  $room_id--;
}


if (!isLoggedIn()) header('Location: login.php');

//DATA FROM addMotionSensor.php and addAppliance.php
if (debug($GLOBALS['debug'])) {
  echo print_r($_POST) . '<br />';
  echo print_r($_SESSION) . '<br />';
}
if (isset($_POST['submit'])) {
  if ($_POST['page'] == 'motion_page') {
    //set sensor
    $motion_sensor_name = $_POST['motion_sensor_name'];
    if ("" == trim($_POST['motion_sensor_name'])) {
      $motion_sensor_name = 'Motion Sensor';
    }
    //update sensore name
    update_sensors($_POST['id'], $motion_sensor_name);
  } else {
    $time = getTime();
    for ($i = 0; $i < $_POST['count']; $i++) {
      insert_into_appliances($room_id, ucfirst($_POST['appliance_name' . $i]), $_POST['rating' . $i], 'on', $time);
      insert_into_activity(get_appliance_id(), $_POST['id'], $time, 'online');
      insert_into_electricity(get_appliance_id(), 0, 0, 0, 0);
    }
    //set sensor
    $appliance_sensor_name = $_POST['appliance_sensor_name'];
    //update sensore name
    if ("" == trim($_POST['appliance_sensor_name'])) {
      $appliance_sensor_name = 'Appliance Sensor';
    }
    update_sensors($_POST['id'], $appliance_sensor_name);
  }
}
$isSensor = isSensorInRoom($room_id);
?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <div class="bg-gray-300">
    <p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Available Sensors</p>
    <div class="pl-6 pr-6 pb-6">
      <div class="bg-white shadow pt-4 rounded-lg">
        <?php $sensor = 'Appliance Sensor'; $status ="";
        $link = "";
        // foreach ($random as $element) {
        //   if ($sensor == 'Appliance Sensor') {
        //     $sensor = 'Motion Sensor';
        //     $link = 'addMotionSensor.php';
        //   } else {
        //     $sensor = 'Appliance Sensor';
        //     $link = 'addApplianceSensor.php';
        //   }

            
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
              <?php if ($isSensor == 'false') {
                  if ($sensor == 'Motion Sensor') { $status="Add Motion Sensor";?>
                  <input type='submit' name="submit" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" value='Add Sensor' />
                <?php } else { $status="Now add Appliance Sensor";?>
                  <input type='submit' name="submit" disabled class="bg-green-200 text-white font-medium py-2 px-4 rounded cursor-not-allowed" value='Add Sensor' />
                <?php }
                  } else if ($isSensor == 'true') {
                    if ($sensor == 'Motion Sensor') { ?>
                  <input type='submit' name="submit" disabled class="bg-green-200 text-white font-medium py-2 px-4 rounded cursor-not-allowed" value='Add Sensor' />
                <?php } else { $status="Now add Appliance Sensor"; ?>
                  <input type='submit' name="submit" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" value='Add Sensor' />
                <?php }
                  } else { $status="All Sensors have been added!";
                    ?>
                <input type='submit' name="submit" disabled class="bg-green-200 text-white font-medium py-2 px-4 rounded cursor-not-allowed" value='Add Sensor' />
              <?php
                } ?>
            </form>
          </div>

        <?php
        }
        ?>
        <p class="text-center pb-2 font-bold text-lg"><?php echo $status ?></p>
      </div>
    </div>
    <a href="home.php">
      <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
    </a>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>