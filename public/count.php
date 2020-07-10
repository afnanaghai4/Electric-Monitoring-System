<?php

// include_once('/home/automar7/public_html/ems/includes/connection.php');
include_once('../includes/connection.php');
include_once('../functions/queries.php');
$time = getTime();
//update consumption values
$count = $_POST['count'];
$room_id = $_POST['room_id'];
update_room_count($count, $room_id);

?>