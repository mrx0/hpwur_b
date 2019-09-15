<?php

//edit_starttime.php

// Write to log.
debug_log('edit_starttime()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get the argument.
//Start Date
$date_arg = $data['arg'];
//debug_log('$date_arg: '.$date_arg);
//День недели
$week_day = getTranslation('weekday_'.date("w", strtotime($date_arg)));
//Дата когда будет рейд
$date = date('d.m', strtotime($date_arg)).' '.$week_day;

// Check for options.
//if (strpos($arg, ',') !== false){
//    $args = explode(',', $arg);
//    $pokemon_id = $args[0];
//    $arg = $args[1];
//    debug_log('More options got requested for raid duration!');
//    debug_log('Received Pokemon ID and argument: ' . $pokemon_id . ', ' . $arg);
//} else {
//    $pokemon_id = $arg;
//}

// Set the id.
$id = $data['id'];
$place_id = explode(',', $data['id'])[0];

//debug_log($data['id']);
//debug_log(explode(',', $data['id'])[0]);

// Get level of pokemon
//$raid_level = '0';
//$raid_level = get_raid_level($pokemon_id);
//debug_log('Pokemon raid level: ' . $raid_level);

// Pokemon in level X?
//if($raid_level == 'X') {
//    // Init empty keys array.
//    $keys = [];
//
//    // Current month
//    $current_month = date('Y-m', strtotime('now'));
//    //$current_month_name = date('F', strtotime('now'));
//    $current_month_name = getTranslation('month_' . substr($current_month, -2));
//    $year_of_current_month_name = substr($current_month, 0, 4);
//
//    // Next month
//    $next_month = date('Y-m', strtotime('first day of +1 months'));
//    //$next_month_name = date('F', strtotime('first day of +1 months'));
//    $next_month_name = getTranslation('month_' . substr($next_month, -2));
//    $year_of_next_month_name = substr($next_month, 0, 4);
//
//    // Buttons for current and next month
//    $keys[] = array(
//        //'text'          => $current_month_name . ' (' . $current_month . ')',
//        'text'          => $current_month_name . ' ' . $year_of_current_month_name,
//        'callback_data' => $id . ':edit_date:' . $pokemon_id . ',' . $current_month
//    );
//
//    $keys[] = array(
//        //'text'          => $next_month_name . ' (' . $next_month . ')',
//        'text'          => $next_month_name . ' ' . $year_of_next_month_name,
//        'callback_data' => $id . ':edit_date:' . $pokemon_id . ',' . $next_month
//    );
//    // Get the inline key array.
//    $keys = inline_key_array($keys, 2);

// Pokemon not in level X?
//} else if (true || $arg == "minutes" || $arg == "clocktime") {
//    if ($arg != "minutes" && $arg != "clocktime") {
//        // Get default raid duration style from config
//        if (RAID_DURATION_CLOCK_STYLE == true) {
//            $arg = "clocktime";
//        } else {
//            $arg = "minutes";
//        }
//    }

    // Init empty keys array.
    $keys = [];

    // Timezone - maybe there's a more elegant solution as date_default_timezone_set?!
    $tz = TIMEZONE;

    // Now
    $now = time();

    //debug_log('$now: '.date('Y-m-d', $now));
    //!!!Выводим ближайшие полчаса/час
    //$rounded_now = round($now / (30 * 60)) * (30 * 60);

//    if ($arg == "minutes") {
//        // Set switch view.
//        $switch_text = getTranslation('raid_starts_when_clocktime_view');
//        $switch_view = "clocktime";
//        $key_count = 4;
//
//        for ($i = 1; $i <= RAID_DURATION * 24; $i = $i + 30) {
//            $now_plus_i = $rounded_now + $i*60;
//            // Create the keys.
//            $keys[] = array(
//                // Just show the time, no text - not everyone has a phone or tablet with a large screen...
//                'text'          => floor($i / 60) . ':' . str_pad($i % 60, 2, '0', STR_PAD_LEFT),
//                'callback_data' => $id . ':edit_time:' . $pokemon_id . ',' . unix2tz($now_plus_i,$tz,"H-i")
//            );
//        }
        // Set switch view.
//        $switch_text = getTranslation('raid_starts_when_clocktime_view');
//        $switch_view = "clocktime";
//        $key_count = 4;
//
//        for ($i = 5; $i <= 60; $i = $i+5) {
//            $now_plus_i = $i*60;
//            // Create the keys.
//            $keys[] = array(
//                // Just show the time, no text - not everyone has a phone or tablet with a large screen...
//                'text'          => floor($i / 60) . ':' . str_pad($i % 60, 2, '0', STR_PAD_LEFT),
//                'callback_data' => $id . ':edit_time:' . $pokemon_id . ',' . unix2tz($now_plus_i,$tz,"H-i")
//            );
//        }
//    } else {
        // Set switch view.
        //$switch_text = getTranslation('raid_starts_when_minutes_view');
        //$switch_view = "minutes";
        // Small screen fix
        $key_count = 4;

//        for ($i = 0; $i <= RAID_DURATION * 12; $i = $i + 30) {
//	        $now_plus_i = $rounded_now + $i*60;
//            // Create the keys.
//            $keys[] = array(
//	            // Just show the time, no text - not everyone has a phone or tablet with a large screen...
//	            'text'	        => unix2tz($now_plus_i, $tz,"H:i"),
//                'callback_data' => $id . ':edit_time:' . $pokemon_id . ',' . unix2tz($now_plus_i,$tz,"H-i")
//            );
//        }

        //С какого часа предлагаем выбор времени начала
        $first_hour = 0;

        //Если дата совпадает с сегодняшней
        if ($date_arg === date('Y-m-d', $now)) {
            //Даём выбор от текущего часа и до полуночи
//            debug_log('$date_arg = $now');
//            debug_log($date_arg === date('Y-m-d', $now));
//            debug_log(date('H', $now));

            //Текущий час
            $first_hour = date('H', $now) + 1;

            //Костыль: час не может быть больше 23х
            if ($first_hour > 23){
                $first_hour = 23;
            }

        }

        for ($i = $first_hour; $i <= 23; $i++) {
            //$now_plus_i = $rounded_now + $i*60;
            // Create the keys.
            //Переход на сохранение
//            $keys[] = array(
//                // Just show the time, no text - not everyone has a phone or tablet with a large screen...
//                'text' => $i . ':00',
//                'callback_data' => $id . ':raid_save:' . $date_arg . ' ' . dateTransformation($i) . '.00.00'
//            );
            //Переход на выбор типа (башня, глаза...)
            $keys[] = array(
                // Just show the time, no text - not everyone has a phone or tablet with a large screen...
                'text' => $i . ':00',
                'callback_data' => $id . ':edit_actions:' . $date_arg . ' ' . dateTransformation($i) . '.00.00'
            );
        }


//    }

    // Get the inline key array.
    $keys = inline_key_array($keys, $key_count);

//    // Raid already running
//    $keys[] = array(
//        'text'	        => getTranslation('is_raid_active'),
//        'callback_data' => $id . ':edit_time:' . $pokemon_id . ',' . unix2tz($now,$tz,"H-i").",more,0"
//    );
//
//    // Switch view: clocktime / minutes until start
//    $keys[] = array(
//        'text'	        => $switch_text,
//        'callback_data' => $id . ':edit_starttime:' . $pokemon_id . ',' . $switch_view
//    );


//} else {
//    // Edit opportunity.
//    $keys = raid_edit_raidlevel_keys($id);
//}

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
    // Back key id, action and arg
    $back_id = $place_id;
    $back_action = 'raid_by_location';
    //$back_arg = get_raid_level($pokemon_id);
    $back_arg = 0;

    // Add navigation keys.
    $nav_keys = [];

    // Raid already running
//    $nav_keys[] = array(
//        'text'	        => getTranslation('is_raid_active'),
//        'callback_data' => $id . ':edit_time:' . $pokemon_id . ',' . unix2tz($now,$tz,"H-i").",more,0"
//    );

    // Switch view: clocktime / minutes until start
//    $nav_keys[] = array(
//        'text'	        => $switch_text,
//        'callback_data' => $id . ':edit_starttime:' . $pokemon_id . ',' . $switch_view
//    );

    $nav_keys[] = universal_inner_key($nav_keys, $back_id, $back_action, $back_arg, getTranslation('back'));
    $nav_keys[] = universal_inner_key($nav_keys, $place_id, 'exit', '2', getTranslation('abort'));
    $nav_keys = inline_key_array($nav_keys, 2);

    // Merge keys.
    $keys = array_merge($keys, $nav_keys);
}

// Build callback message string.
//if ($data['arg'] != "minutes" && $data['arg'] != "clocktime") {
//    //$callback_response = getTranslation('pokemon_saved') . get_local_pokemon_name($data['arg']);
//    $callback_response = getTranslation('raid_saved');
//} else {
//    $callback_response = getTranslation('raid_starts_when_view_changed');
//}

// Write to log.
debug_log($keys);

//
$callback_response = getTranslation('raid_saved');

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
//if ($arg == "minutes") {
//    edit_message($update, getTranslation('raid_starts_when_minutes'), $keys);
//} else {
//    edit_message($update, getTranslation('raid_starts_when'), $keys);
//}

//
edit_message($update, getTranslation('selected_date').' '.$date.CR2.getTranslation('raid_starts_when'), $keys);

// Exit.
exit();
