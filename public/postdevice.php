<?php 
$url = 'http://device.murtazahanif.now.sh/api/device';
$data = array('flag' => 'off');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { /* Handle error */ }

var_dump($result);

// $url = 'http://device.murtazahanif.now.sh/api/device';
// // $data = array('flag' => 'off');

// //The data you want to send via POST
// $fields = [
//     'flag'      => 'off'
// ];

// //url-ify the data for the POST
// $fields_string = http_build_query($fields);

// //open connection
// $ch = curl_init();

// //set the url, number of POST vars, POST data
// curl_setopt($ch,CURLOPT_URL, $url);
// curl_setopt($ch,CURLOPT_POST, true);
// curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// //So that curl_exec returns the contents of the cURL; rather than echoing it
// curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
// curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

// //execute post
// $result = curl_exec($ch);
// var_dump($result);
// ?>