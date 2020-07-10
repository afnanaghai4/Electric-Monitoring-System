<?php include_once('../includes/connection.php');


  global $connection;
 
  $query = "select auto_off_time from appliances";
  $result = mysqli_query($connection, $query);
  
  if(!$result) {
    die('asdas');
  }
  $row = mysqli_fetch_assoc($result);
  if($row['auto_off_time']==null) {
    echo "hello";
  };
 ?>