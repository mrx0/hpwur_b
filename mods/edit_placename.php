<?php

//edit_placename.php
//!!! Доделать! скорее всего нужно вести в бд лог того, на что ждем ответ....

// Write to log.
debug_log('edit_placename()');

// For debug.
debug_log($update);
debug_log($data);


//Raid ID
$id = $data['id'];

//готовим данные
$msg = "Введите новое название: ";

// Init empty keys array.
$keys = [];

//$content = [
//    'chat_id' => $callback_chat_id,
    // зашиваем id сообщения которое надо отредактировать
//    'text' => "Ваш новый текст для сообщения - "
//        . $data['callback_query']['message']['message_id'],
    // автоматически подставляем для ответа
//    'reply_markup' => json_encode(['force_reply' => true], true),
//];
// отправляем сообщение
//send_message($update['callback_query']['message']['chat']['id'], $msg, $keys, ['reply_markup' => ['force_reply' => true, 'selective' => true]]);
send_message($update['callback_query']['message']['chat']['id'], $msg, $keys, ['reply_markup' => ['force_reply' => true]]);




////Дата + Время
//$starttime = $data['arg'];
////День недели
//$week_day = getTranslation('weekday_'.date("w", strtotime($starttime)));
////Дата когда будет рейд
//$date = date('d.m', strtotime($starttime)).' '.$week_day;
////Время когда будет рейд
//$time = date('H:i', strtotime($starttime));
//
//
//debug_log($starttime);
//
//// Get the keys.
//$keys = raid_edit_opportunity_keys($place_id, $place_first_letter, $starttime, true);
//
//// No keys found.
//if (!$keys) {
//    // Create the keys.
//    $keys = [
//        [
//            [
//                'text'          => getTranslation('abort'),
//                'callback_data' => '0:exit:0'
//            ]
//        ]
//    ];
//} else {
//    // Back key id, action and arg
//    $back_id = $place_id;
//    $back_action = 'edit_starttime';
//    $back_arg = date('Y-m-d', strtotime($starttime)) ;
//
//    // Add navigation keys.
//    $nav_keys = [];
//
//    $nav_keys[] = universal_inner_key($nav_keys, $back_id, $back_action, $back_arg, getTranslation('back'));
//    $nav_keys = inline_key_array($nav_keys, 2);
//    // Merge keys.
//    $keys = array_merge($keys, $nav_keys);
//}
//
//// Write to log.
//debug_log($keys);
//
////
//$callback_response = getTranslation('raid_saved');
//
//// Answer callback.
//answerCallbackQuery($update['callback_query']['id'], $callback_response);
//
//// Edit the message.
//edit_message($update, getTranslation('selected_date').SP.$date.CR.getTranslation('selected_time').SP.$time.CR2.getTranslation('select_raid_reason'), $keys);

// Exit.
exit();
