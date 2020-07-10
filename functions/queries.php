<?php 

function debug($debugging) {
    if ($debugging)
        return true;
    return false;
}
function isLoggedIn() {
    if(isset($_SESSION['email'])) {
        $id = get_house_id($_SESSION['email']);
        if($id) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}
function getTime() {
    date_default_timezone_set("Asia/Karachi");   
    $time =  date('Y-m-d') ." ". date("H:i:s");
    return $time;
}


function setUnits($dataInkwH)
{
$unit = $_SESSION['unit'];
  if ($unit == 'kW/H') {
    
  } else if ($unit == 'W/H') {
    $dataInkwH = $dataInkwH * 1000;
  } else if ($unit == 'kW/s') {
    $dataInkwH = $dataInkwH *3600;
  } else if ($unit == 'W/s') {
    $dataInkwH = $dataInkwH * 1000 * 3600;
  } else if ($unit == 'kW/m') {
    $dataInkwH = $dataInkwH * 60;
  } else {
    //$unit = 'W/m' 
    $dataInkwH = $dataInkwH * 1000 * 60;
  }
  return $dataInkwH;
}

function toggleButton($appliance_id, $toggle) {
    global $connection;
    $query = "select flag from rooms join appliances using (room_id) where appliance_id={$appliance_id} order by flag desc limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    if($row['flag'] == 1) {
        $url = 'http://device.murtazahanif.now.sh/api/device?flag=' . $toggle;
        //$url = 'http://pokeapi.co/api/v2/pokemon/ditto/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,"flag=on");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $server_output = curl_exec($ch);
        $result = JSON_DECODE($server_output);
        curl_close($ch);
        echo 'status';
        echo $result->status;
        if ($result->status ==  'ok') {
            $query = "update appliances set is_on='{$toggle}' where appliance_id={$appliance_id}";
            $result = mysqli_query($connection, $query);
            confirm_query($result);
        }
    } else {
        $query = "update appliances set is_on='{$toggle}' where appliance_id={$appliance_id}";
        $result = mysqli_query($connection, $query);
        confirm_query($result);
    }
    
    
    //when switch is turned on
    if($toggle == 'on') {
        $query = "select auto_off_time, rating from appliances where appliance_id={$appliance_id}";
        $result = mysqli_query($connection, $query);
        confirm_query($result);
        $row = mysqli_fetch_assoc($result);
        if($row['auto_off_time']!=null) {
            $current_time = strtotime(getTime());
            $auto_off_time = strtotime($row['auto_off_time']);
            $diff = abs($auto_off_time - $current_time);
            $minutes_passed = ($diff)/(60*60);
            $query3 = "select * from electricity where appliance_id={$appliance_id}";
            $result3 = mysqli_query($connection, $query3);
            confirm_query($result3);
            $row3 = mysqli_fetch_assoc($result3);
            $saved = $row3['saved'];
            $saved/=1000;
            $saved += $row['rating'] * ($minutes_passed);
            $getTime = getTime();
          $query2 = "update electricity set saved={$saved} where appliance_id = {$appliance_id}, , update_time='{$getTime}'";
          $result2 = mysqli_query($connection, $query2);
          confirm_query($result2);
        }
    }
    
}

function add_into_activity_turnOn($appliance_id, $turnOnTime, $current_state) {
    global $connection;
    $query = "select device_id from activity where appliance_id = {$appliance_id} limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $device = $row['device_id'];
      }
    $query = "insert into activity (appliance_id, device_id, turnOn_time, current_state) values ({$appliance_id}, {$device}, '{$turnOnTime}', '{$current_state}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function add_into_activity_turnOff($appliance_id, $turnOff, $current_state) {
    global $connection;
    $query = "select device_id from activity where appliance_id = {$appliance_id} limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $device = $row['device_id'];
      }
    $query = "insert into activity (appliance_id, device_id, turnOff_time, current_state) values ({$appliance_id}, {$device}, '{$turnOff}', '{$current_state}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function confirm_query($result_set) {
    if(!$result_set) {
        die("Database query failed");
    }
}

function insert_into_account($first_name, $last_name, $email, $pass) {
    global $connection;
    $query = "insert into accounts (first_name, last_name, email, pass) values ('{$first_name}','{$last_name}','{$email}','{$pass}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insert_into_houses($account_id, $house_name, $owner_name, $rooms) {
    global $connection;
    $query = "insert into houses (account_id, house_name, owner_name, rooms) values ({$account_id}, '{$house_name}','{$owner_name}',{$rooms})";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insert_into_settings($account_id) {
    global $connection;
    $query = "insert into settings (house_id, unit) values ({$account_id}, 'kW/H')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insert_into_rooms($room_id, $house_id, $room_name, $appliances, $count) {
    global $connection;
    $query2 = "select flag from houses join rooms using (house_id) where flag=1";
    $result2 = mysqli_query($connection, $query2);
    confirm_query($result2);
  
    if(mysqli_num_rows($result2)<=0) {
        $query = "insert into rooms (room_id, house_id, room_name, appliances, count, flag) values ({$room_id}, {$house_id}, '{$room_name}',{$appliances},{$count}, 1)";
    } else {
        $query = "insert into rooms (room_id, house_id, room_name, appliances, count, flag) values ({$room_id}, {$house_id}, '{$room_name}',{$appliances},{$count}, 0)";
    }
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insert_into_rooms_sensor($room_id, $sensor_id) {
  global $connection;
  $query = "insert into rooms_sensor (room_id, sensor_id) values ({$room_id}, {$sensor_id})";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
}

function insert_into_appliances($room_id, $appliance_name, $rating,$is_on, $add_time) {
    global $connection;
    $query = "insert into appliances (room_id, appliance_name, rating, is_on, add_time) values ({$room_id}, '{$appliance_name}', {$rating}, '{$is_on}', '{$add_time}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
  }

function insert_into_activity($appliance_id, $device_id, $turnOnTime, $current_state) {
    global $connection;
    $query = "insert into activity (appliance_id, device_id, turnOn_time, current_state) values ({$appliance_id}, {$device_id}, '{$turnOnTime}', '{$current_state}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function insert_into_electricity($appliance_id, $current_consumption, $previous_consumption, $wastage, $temp) {
    global $connection;
    $getTime = getTime();
    $query = "insert into electricity (appliance_id, current_consumption, previous_consumption, wastage, temp, saved, update_time) values ({$appliance_id}, {$current_consumption}, {$previous_consumption}, {$wastage}, {$temp}, 0, '{$getTime}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function update_electricity($appliance_id) {
    global $connection;
    $result = get_electricity_detials($appliance_id);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    $query = "update  electricity set temp={$row['current_consumption']}, temp_waste={$row['temp_waste']} where appliance_id = {$appliance_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function update_autoOffTime($appliance_id, $boolean) {
    global $connection;
    if($boolean) {
        $auto_off_time = getTime();
        $query = "update appliances set auto_off_time = '{$auto_off_time}' where appliance_id = {$appliance_id}";
    } else {
        $query = "update appliances set auto_off_time = null where appliance_id = {$appliance_id}";
    }
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);
  }

function update_sensors($sensor_id, $sensor_name) {
  global $connection;
  $query = "update sensors set sensor_name='{$sensor_name}' where sensor_id={$sensor_id}";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
}

function update_room_count($count, $room_id) {
    global $connection;
    if($count<1) {
        $time=getTime();
        $query = "update rooms set count={$count} , motion_time='{$time}' where room_id={$room_id}";
    } else {    
        $query = "update rooms set count={$count} where room_id={$room_id}";
    }
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);
}

function get_account($email) {
    global $connection;
    $query = "select account_id, concat(first_name,' ',last_name) as owner_name from accounts where email='{$email}'";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_rooms($house_id) {
    global $connection;
    $query = "select * from rooms where house_id={$house_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_count($appliance_id) {
    global $connection;
    $query = "select count from rooms join appliances using (room_id) where appliance_id ={$appliance_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function isRoom($room_id) {
    global $connection;
    $query = "select * from rooms where room_id={$room_id};";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if (mysqli_fetch_assoc($result) > 0) return true; 
    return false;
}

function get_house_id($email) {
    global $connection;
    $query = "Select house_id from houses join accounts using (account_id) where email='{$email}'";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['house_id'];
      }
}

function get_unit($house_id) {
    global $connection;
    $query = "select unit from settings where house_id='{$house_id}'";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['unit'];
}

function get_wait_time($house_id) {
    global $connection;
    $query = "select wait_time from settings where house_id='{$house_id}'";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['wait_time'];
}

function get_update_time($house_id) {
    global $connection;
    $query = "select update_time from rooms join appliances using (room_id) join electricity using (appliance_id) join houses using (house_id) where house_id={$house_id} limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['update_time'];
}

function add_new_sensor($sensor_id, $sensor_name, $sensor_type) {
    global $connection;
    $query = "insert into sensors (sensor_id, sensor_name, type) values ('{$sensor_id}','{$sensor_name}','{$sensor_type}')";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
   
}

function get_sensors($room_id) {
    global $connection;
    $query = "select * from rooms_sensors where room_id='{$room_id}'";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_room_id($house_id) {
    global $connection;
    $query = "SELECT room_id FROM rooms where house_id={$house_id} ORDER BY room_id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['room_id'];
      }
}

function get_appliance_count($room_id, $house_id) {
    global $connection;
    $query = "SELECT appliances FROM rooms where house_id={$house_id} and room_id={$room_id};";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['appliances'];
      }
}

function get_appliances($room_id) {
    global $connection;
    $query = "SELECT appliance_id, appliance_name, is_on FROM appliances where room_id={$room_id};";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_appliance_details($appliance_id) {
    global $connection;
    $query = "SELECT * FROM appliances where appliance_id={$appliance_id};";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_daily_details($house_id) {
    global $connection;
    $query = "select * from daily_report where house_id={$house_id} order by report_time desc limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if(mysqli_num_rows($result) <=0) {
        return 0;
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
}

function get_room_report($room_id) {
    global $connection;
    $query = "select * from rooms_report join rooms using (room_id) where room_id={$room_id} order by report_time desc limit 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if(mysqli_num_rows($result) <=0) {
        return 0;
    } else {
        
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
}

function get_appliance_id() {
    global $connection;
    $query = "SELECT appliance_id FROM appliances ORDER BY appliance_id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['appliance_id'];
      }
}

function get_activity($appliance_id) {
    global $connection;
    $query = "select turnOn_time, turnOff_time, current_state from activity where appliance_id = {$appliance_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_electricity_detials($appliance_id) {
    global $connection;
    $query = "select * from electricity where appliance_id = {$appliance_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function get_all_electricity_appliance($house_id) {
    global $connection;
    $query = "select current_consumption, appliance_id, appliance_name, room_name from rooms join appliances using (room_id) join electricity using (appliance_id) join houses using (house_id) where house_id={$house_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function isSensorInRoom($room_id) {
    global $connection;
    $query = "SELECT * FROM rooms_sensor join sensors using (sensor_id) where room_id={$room_id};";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    if(mysqli_num_rows($result) <=0) {
        return 'false';
    } else if (mysqli_num_rows($result) <=1) {
        return 'true';
    } else {
        return 'null';
    }
}

function delete_room($room_id) {
    global $connection;
    $query = "delete from rooms where room_id = {$room_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    return $result;
}

function update_room_name($room_id, $room_name) {
    global $connection;
    $query = "update rooms set room_name='{$room_name}' where room_id={$room_id}";
    $result = mysqli_query($connection, $query);
    confirm_query($result);
  }