<?php
// Write to log.
debug_log('exit()');

// For debug.
//debug_log($update);
//debug_log($data);

// Set empty keys.
$keys = [];

// Build message string.
$msg = ($data['arg'] == 1) ? (getTranslation('done') . '!') : (getTranslation('action_aborted'));

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $msg);

// Edit the message.
edit_message($update, $msg, $keys);

// Set place_user_id tag.
$place_user_id = '#' . $update['callback_query']['from']['id'];

// Get place.
$place = get_place($data['id']);

// Delete place from database.
if($place['place_name'] == $place_user_id && $place['show_place'] == 0 && $data['arg'] == 2) {
    delete_place($data['id']);
}

// Exit.
exit();
