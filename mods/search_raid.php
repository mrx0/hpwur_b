<?php

//search_raid.php
//Ищем открытый рейд по локации в указанном радиусе

// Write to log.
debug_log('search_raid()');

// For debug.
//debug_log($update);
//debug_log($data);

//Если возвращаемся сюда, то place_id не будет пустым
//$place_id = 0;
//
//if (isset($data['id'])) {
    $place_id = $data['id'];
//}

if ($place_id != NULL){
    debug_log('place_id: ' . $place_id);

    $place = get_place($place_id);
//    debug_log('place:');
//    debug_log($place);

    $place_name = $place['place_name'];
    $lat = $place['lat'];
    $lon = $place['lon'];
    $address = $place['address'];

}else {
    //Set latitude / longitude values
    //$id = $data['arg'];
    $lat = explode('+', $data['arg'])[0];
    $lon = explode('+', $data['arg'])[1];

    // Debug
    debug_log('Lat: ' . $lat);
    debug_log('Lon: ' . $lon);
    debug_log('place_id: ' . $place_id);

    // Build address string.
    $address = getTranslation('forest');

    /*$lat и $lng - координаты точки, $radius - радиус в метрах (0,1988 переводит в радианы) –*/
    $radius = 4000;

    $rs = my_query(
        "
        SELECT id, ( 6371 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians({$lon}) ) + sin( radians({$lat}) ) * sin( radians( lat ) ) ) )
        AS distance 
        FROM places
        ORDER BY distance
        HAVING distance < {$radius} 
        /*LIMIT 0 , 20;*/
        "
        );
    $res = $rs->fetch_assoc();

    debug_log($res);
}

//    if (!empty(GOOGLE_API_KEY)) {
//        $addr = get_address($lat, $lon);
//
//        // Get full address - Street #, ZIP District
//        $address = '';
//        $address .= (!empty($addr['street']) ? $addr['street'] : '');
//        $address .= (!empty($addr['street_number']) ? ' ' . $addr['street_number'] : '');
//        $address .= (!empty($addr) ? ', ' : '');
//        //$address .= (!empty($addr['postal_code']) ? $addr['postal_code'] . ' ' : '');
//        $address .= (!empty($addr['district']) ? $addr['district'] : '');
//    }
//
//    // Temporary place_name
//    $place_name = '#' . $update['callback_query']['message']['chat']['id'];
//
//    $rs = my_query(
//        "
//    INSERT INTO places
//    SET         lat = '{$lat}',
//                lon = '{$lon}',
//		        address = '{$db->real_escape_string($address)}',
//                place_name = '{$db->real_escape_string($place_name)}'
//    "
//    );
//
//    // Get last insert id from db.
//    $place_id = my_insert_id();
//}
//
////$place_letter = substr($place_name, 0, 1);
//
//// Write to log.
//debug_log('Place ID: ' . $place_id);
//debug_log('Place Name: ' . $place_name);
//
//
//// Check access - user must be admin for raid_level X
////$admin_access = bot_access_check($update, BOT_ADMINS, true);
////if ($admin_access) {
//    // Get the keys.
//    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter, true);
////    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter, true);
////} else {
//    // Get the keys.
//    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter);
////    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter);
////}
//
////$place_first_letter = 0;
//
////$keys = raid_edit_opportunity_keys($place_id, $place_first_letter);
////Выбираем дату
//$keys = raid_edit_days_keys($place_id);
//
//// No keys found.
//if (!$keys) {
//    // Create the keys.
//    $keys = [
//        [
//            [
//                'text' => getTranslation('abort'),
//                'callback_data' => '0:exit:0'
//            ]
//        ]
//    ];
//} else {
//    // Back key id, action and arg
//    $back_id = $lat;
//    $back_action = 'choose_action';
//    $back_arg = $lon;
//    // Add navigation keys.
//    $nav_keys = [];
//    //$nav_keys[] = universal_inner_key($nav_keys, $back_id, $back_action, $back_arg, getTranslation('back'));
//    $nav_keys[] = universal_inner_key($nav_keys, $place_id, 'exit', '2', getTranslation('abort'));
//    $nav_keys = inline_key_array($nav_keys, 2);
//    // Merge keys.
//    $keys = array_merge($keys, $nav_keys);
//}
//
//// Write to log.
//debug_log($keys);
//
//// Build message.
//$msg = getTranslation('create_raid') . ':' . CR . '<i>' . $address . '</i>' . CR2 . getTranslation('select_date');
//
//// Build callback message string.
//$callback_response = getTranslation('create_raid');
//
//// Answer callback.
//answerCallbackQuery($update['callback_query']['id'], $callback_response);
//
//// Edit the message.
//edit_message($update, $msg, $keys);

// Exit.
exit();
