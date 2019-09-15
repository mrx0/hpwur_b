<?php

//get_oddities_time.php
//Запрашиваем и показываем время спавна odditiesс

// Write to log.
debug_log('get_oddities_time()');

// For debug.
//debug_log($update);
//debug_log($data);

//Set latitude / longitude values
$lat = explode('+', $data['arg'])[0];
$lon = explode('+', $data['arg'])[1];

// Debug
debug_log('Lat: ' . $lat);
debug_log('Lon: ' . $lon);


//Запрос солнечного календаря
//Date !!! изменить, чтоб как минимум текущую показывало
//$date = '2019-09-14';
//
//$URL = 'https://api.sunrise-sunset.org/json?';
//$query = $URL.'lat='.$lat.'&lng='.$lon.'&date='.$date;
//
//$ch = curl_init();
//
//curl_setopt($ch, CURLOPT_URL, $query);
//
//curl_setopt($ch, CURLOPT_HEADER, FALSE);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//curl_setopt($ch, CURLOPT_TIMEOUT, 15);
//curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
//
//// Use Proxyserver for curl if configured
//if (CURL_USEPROXY == true) {
//    curl_setopt($curl, CURLOPT_PROXY, CURL_PROXYSERVER);
//}
//
//$str = curl_exec($ch);
////var_dump($str);
//
//curl_close($ch);


//Запрос полнолуний
//https://isitfullmoon.com/api.php?format=json&tz=Europe/Moscow
//TimeZone
$tz = 'Europe/Moscow';
//Format
$format = 'json';

$URL = 'https://isitfullmoon.com/api.php?';
$query = $URL.'tz='.$tz.'&format='.$format;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $query);

curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);

// Use Proxyserver for curl if configured
if (CURL_USEPROXY == true) {
    curl_setopt($curl, CURLOPT_PROXY, CURL_PROXYSERVER);
}

$str = curl_exec($ch);
//var_dump($str);

curl_close($ch);



$msg = $str;




// Build message.
//$msg = 'lat: ' . $lat. CR . 'lon: ' . $lon;

// Build callback message string.
$callback_response = 'Oddities';

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
edit_message($update, $msg, []);

// Exit.
exit();
