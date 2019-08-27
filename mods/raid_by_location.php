<?php

//raid_by_location.php
//Создаем рейд по локации, сразу выбираем дату рейда

// Write to log.
debug_log('raid_by_location()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get latitude / longitude values from Telegram
if(isset($update['message']['location'])) {
    $lat = $update['message']['location']['latitude'];
    $lon = $update['message']['location']['longitude'];
} else if(isset($update['callback_query'])) {
    $lat = $data['id'];
    $lon = $data['arg'];
} else {
    sendMessage($update['message']['chat']['id'], '<b>' . getTranslation('not_supported') . '</b>');
    exit();
}

// Debug
debug_log('Lat: ' . $lat);
debug_log('Lon: ' . $lon);

// Build address string.
$address = getTranslation('forest');

if(!empty(GOOGLE_API_KEY)){
    $addr = get_address($lat, $lon);

    // Get full address - Street #, ZIP District
    $address = '';
    $address .= (!empty($addr['street']) ? $addr['street'] : '');
    $address .= (!empty($addr['street_number']) ? ' ' . $addr['street_number'] : '');
    $address .= (!empty($addr) ? ', ' : '');
    //$address .= (!empty($addr['postal_code']) ? $addr['postal_code'] . ' ' : '');
    $address .= (!empty($addr['district']) ? $addr['district'] : '');
}

// Temporary place_name
$place_name = '#' . $update['message']['chat']['id'];
$place_letter = substr($place_name, 0, 1);

$rs = my_query(
    "
    INSERT INTO places
    SET         lat = '{$lat}',
                lon = '{$lon}',
		        address = '{$db->real_escape_string($address)}',
                place_name = '{$db->real_escape_string($place_name)}'
    "
);

// Get last insert id from db.
$place_id = my_insert_id();

// Write to log.
debug_log('Place ID: ' . $place_id);
debug_log('Place Name: ' . $place_name);




// Check access - user must be admin for raid_level X
//$admin_access = bot_access_check($update, BOT_ADMINS, true);
//if ($admin_access) {
    // Get the keys.
    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter, true);
//    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter, true);
//} else {
    // Get the keys.
    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter);
//    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter);
//}

//$place_first_letter = 0;

//$keys = raid_edit_opportunity_keys($place_id, $place_first_letter);
//Выбираем дату
$keys = raid_edit_days_keys($place_id, $place_first_letter);

// No keys found.
if (!$keys) {
    // Create the keys.
    $keys = [
        [
            [
                'text'          => getTranslation('abort'),
                'callback_data' => '0:exit:0'
            ]
        ]
    ];
} else {
    // Add navigation keys.
    $nav_keys = [];
    //$nav_keys[] = universal_inner_key($nav_keys, $back_id, $back_action, $back_arg, getTranslation('back'));
    $nav_keys[] = universal_inner_key($nav_keys, $place_id, 'exit', '2', getTranslation('abort'));
    $nav_keys = inline_key_array($nav_keys, 2);
    // Merge keys.
    $keys = array_merge($keys, $nav_keys);
}




// Create the keys.
//$keys = [
//    [
//        [
//            'text'          => getTranslation('next'),
//            'callback_data' => $place_letter . ':edit_raidlevel:' . $place_id
//        ]
//    ],
//    [
//        [
//            'text'          => getTranslation('abort'),
//            'callback_data' => $place_id . ':exit:2'
//        ]
//    ]
//];

// Answer location message.
if(isset($update['message']['location'])) {
    // Build message.
    $msg = getTranslation('create_raid') . ':'.CR. '<i>' . $address . '</i>'.CR2.getTranslation('select_date');

    // Send message.
    send_message($update['message']['chat']['id'], $msg, $keys, ['reply_markup' => ['selective' => true, 'one_time_keyboard' => true]]);

// Answer forwarded location message from geo_create.
} else if(isset($update['callback_query'])) {
    // Build callback message string.
    $callback_response = getTranslation('here_we_go');

    // Answer callback.
    answerCallbackQuery($update['callback_query']['id'], $callback_response);

    // Edit the message.
    edit_message($update, getTranslation('select_date'), $keys);

    // Debug
//    debug_log('$update: ');
//    debug_log($update);
}

// Exit.
exit();
