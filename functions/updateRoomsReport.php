<?php

// include_once('/home/automar7/public_html/ems/includes/connection.php');
// include_once('/home/automar7/public_html/ems/functions/queries.php');
include_once('../includes/connection.php');
include_once('queries.php');


function insert_into_rooms_report($house_id, $room_id, $total_consumption, $wastage, $saved)
{
  global $connection;
  $time = getTime();
  $query = "delete from rooms_report where room_id = {$room_id}";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  $query = "insert into rooms_report (house_id, room_id, total_consumption, electricity_wasted, saved, report_time) values ({$house_id}, {$room_id}, ROUND({$total_consumption}, 3), ROUND({$wastage},3), ROUND({$saved},3), '{$time}')";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
}

function RoomReport($room_id, $house_id)
{
  global $connection;
  $query = "select appliance_id, room_id from rooms join appliances using (room_id) where room_id={$room_id}";
  $result = mysqli_query($connection, $query);
  confirm_query($query);
  $current_consumption = 0;
  $wastage = 0;
  $saved = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $query1 = "select * from electricity where appliance_id={$row['appliance_id']}";
    $result1 = mysqli_query($connection, $query1);
    confirm_query($result1);
    $row1 = mysqli_fetch_assoc($result1);
    $current_consumption += $row1['current_consumption'];
    $wastage += $row1['wastage'];
    $saved += $row1['saved'];
  }
  insert_into_rooms_report($house_id, $room_id, ($current_consumption + $wastage), $wastage, $saved);
}
