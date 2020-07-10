<?php

include_once('../includes/connection.php');
include_once('../functions/queries.php');


//
// A very simple PHP example that sends a HTTP POST to a remote site
//

$switch = $_POST['switch'];
if ($switch == 'On') {
  $url = 'http://device.murtazahanif.now.sh/api/device?flag=on';
} else {
  $url = 'http://device.murtazahanif.now.sh/api/device?flag=off';
}


//$url = 'http://pokeapi.co/api/v2/pokemon/ditto/';
$ch = curl_init();
 
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS,"flag=on");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//$verbose = fopen('D:\\wamp64\\www\\project\\log.txt', 'w+');
//curl_setopt($ch, CURLOPT_STDERR, $verbose);
 
// In real life you should use something like:
// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$result = JSON_DECODE($server_output);
curl_close ($ch);


//print_r( $result)
echo $result->state;
echo 'status';
echo $result->status;
// Further processing ...
 
 





 if ( $result->status ==  'ok')
{
  $time = getTime();
$id = $_POST['id'];
$switch = $_POST['switch'];
if ($switch == 'On') {
  toggleButton($id, 'on');
  add_into_activity_turnOn($id, $time, 'online');
  
} else {
  toggleButton($id, 'off');
  update_electricity($id);
  update_autoOffTime($id, false);
  add_into_activity_turnOff($id, $time, 'offline');
}
}
?>  