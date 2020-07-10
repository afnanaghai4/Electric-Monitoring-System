<?php

// include_once('/home/automar7/public_html/ems/includes/connection.php');
// include_once('/home/automar7/public_html/ems/functions/queries.php');
include_once('../includes/connection.php');
include_once('queries.php');


function insert_into_dailyreport($house_id, $total_consumption, $wastage, $saved)
{
  global $connection;
  $time=getTime();
  $query = "insert into daily_report (house_id, total_consumption, electricity_wasted, saved, report_time) values ({$house_id}, ROUND({$total_consumption},3), ROUND({$wastage}, 3), ROUND({$saved},3), '{$time}')";
  $result = mysqli_query($connection, $query);
  if(!$result) {
    die("asdasd query failed");
}
}

function DailyReport()
{
  global $connection;
  $query = "select electricity.*, house_id from houses join rooms using (house_id) join appliances using (room_id) join electricity using (appliance_id)";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  $current_consumption = 0;
  $wastage = 0;
  $saved = 0;
  while( $row = mysqli_fetch_assoc($result)) {
    $current_consumption += $row['current_consumption'];
    $wastage += $row['wastage'];
    $saved+=$row['saved'];
  }
  $query2 = "select house_id from houses";
  $result2 = mysqli_query($connection, $query2);
  confirm_query($result2);
  while( $row2 = mysqli_fetch_assoc($result2)) {
    insert_into_dailyreport($row2['house_id'], ($current_consumption + $wastage), $wastage, $saved);  
  }
  
}
