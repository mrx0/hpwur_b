<?php

//edit_actions.php

// Write to log.
debug_log('edit_actions()');

// For debug.
//debug_log($update);
//debug_log($data);

//Место
$place_id = $data['id'];
//Дата + Время
$starttime = $data['arg'];
//День недели
$week_day = getTranslation('weekday_'.date("w", strtotime($starttime)));
//Дата когда будет рейд
$date = date('d.m', strtotime($starttime)).' '.$week_day;
//Время когда будет рейд
$time = date('H:i', strtotime($starttime));


debug_log($starttime);

// Get the keys.
$keys = raid_edit_opportunity_keys($place_id, $place_first_letter, $starttime, true);

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
    $back_action = 'edit_starttime';
    $back_arg = date('Y-m-d', strtotime($starttime)) ;

    // Add navigation keys.
    $nav_keys = [];

    $nav_keys[] = universal_inner_key($nav_keys, $back_id, $back_action, $back_arg, getTranslation('back'));
    $nav_keys = inline_key_array($nav_keys, 2);
    // Merge keys.
    $keys = array_merge($keys, $nav_keys);
}

// Write to log.
debug_log($keys);

//
$callback_response = getTranslation('raid_saved');

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
edit_message($update, getTranslation('selected_date').SP.$date.CR.getTranslation('selected_time').SP.$time.CR2.getTranslation('select_raid_reason'), $keys);

// Exit.
exit();
