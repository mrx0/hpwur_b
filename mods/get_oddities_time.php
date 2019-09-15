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




// Build message.
$msg = 'lat: ' . $lat. CR . 'lon: ' . $lon;

// Build callback message string.
$callback_response = 'Oddities';

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
edit_message($update, $msg, []);

// Exit.
exit();
