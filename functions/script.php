<?php
session_start();
// include_once('/home/automar7/public_html/ems/includes/connection.php');
// include_once('/home/automar7/public_html/ems/functions/queries.php');
include_once('../includes/connection.php');
include_once('queries.php');

function time_interval($waitTime, $diff)
{
  $str = explode(" ", $waitTime);
  if ($str[1] == "h") {
    $time_passed = ($diff) / (60 * 60);
  } else if ($str[1] == "m") {
    $time_passed = ($diff) / (60);
  } else if ($str[1] == "s") {
    $time_passed = $diff;
  }
  $resultSet['time_passed'] = $time_passed;
  $resultSet['wait_time'] = $str[0];
  return $resultSet;
}

//update consumption values
function updateTime()
{
  global $connection;
  //get appliance details
  $query = "select * from appliances join rooms using (room_id)";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  while ($row = mysqli_fetch_assoc($result)) {
    //Get consumptions
    $query3 = "select * from electricity where appliance_id={$row['appliance_id']}";
    $result3 = mysqli_query($connection, $query3);
    confirm_query($result3);
    while ($row3 = mysqli_fetch_assoc($result3)) {

      $curr_consumption = $row3['current_consumption'];
      $wastage = $row3['wastage'];
      $temp = $row3['temp'];
      $temp_waste = $row3['temp_waste'];
      //if device is on
      if ($row['is_on'] == 'on') {

        $query4 = "select turnOn_time from activity where appliance_id = {$row['appliance_id']} order by activity_id asc";
        $result4 = mysqli_query($connection, $query4);
        confirm_query($result4);
        while ($row4 = mysqli_fetch_assoc($result4)) {
          $turnOnTime = $row4['turnOn_time'];
        }
        if ($row['count'] > 0) {
          //if presence
          $current_time = strtotime(getTime());
          $turnOnTime = strtotime($turnOnTime);
          $diff = abs($turnOnTime - $current_time);
          echo "Appliance_id :" . $row['appliance_id'] . " " . $diff . "<br />";
          $minutes_passed = ($diff) / (60 * 60);
          echo $minutes_passed . "<br />";

          $curr_consumption = $row['rating'] * ($minutes_passed);
          $curr_consumption /= 1000;
          $curr_consumption += $temp;
          $getTime = getTime();
          $query2 = "update electricity set current_consumption=ROUND({$curr_consumption},5) , update_time='{$getTime}' where appliance_id = {$row['appliance_id']}";
          $result2 = mysqli_query($connection, $query2);
          confirm_query($result2);
        } else {
          //if no presence          
          $current_time = strtotime(getTime());
          $motionTime = strtotime($row['motion_time']);
          $diff = abs($motionTime - $current_time);
          $minutes_passed = ($diff) / (60 * 60);
          echo $minutes_passed;
          $getTime = getTime();
          $wastage = $row['rating'] * ($minutes_passed);
          $wastage /= 1000;
          $wastage += $temp_waste;
          $query2 = "update electricity set wastage=ROUND({$wastage}, 5) , update_time='{$getTime}' where appliance_id = {$row['appliance_id']}";
          $result2 = mysqli_query($connection, $query2);
          confirm_query($result2);

          //Time passed until lights closed automatically
          $diff = abs($motionTime - $current_time);
          $waitTime = $_SESSION['wait_time'];
          $time_passed = time_interval($waitTime, $diff)['time_passed'];
          $wait_time = time_interval($waitTime, $diff)['wait_time'];
          echo "<br />" . "diff :" . $diff . " wait time: " . $waitTime . " time passed: " . $time_passed . " wait_time: " . $wait_time . "<br />";

          if ($time_passed >= $wait_time) {
            toggleButton($row['appliance_id'], 'off');
            update_autoOffTime($row['appliance_id'], true);
            add_into_activity_turnOff($row['appliance_id'], getTime(), 'offline');
            update_electricity($row['appliance_id']);
          }
        }
      }
    }
  }
}
updateTime();
