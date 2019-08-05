<?php
// Write to log.
debug_log('START()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get gym by name.
// Trim away everything before "/start "
$searchterm = $update['message']['text'];
$searchterm = substr($searchterm, 7);

// Get the keys by gym name search.
if(!empty($searchterm)) {
    $keys = raid_get_gyms_list_keys($searchterm);
} 

// Get the keys if nothing was returned. 
if(!$keys) {
    $keys = raid_edit_gyms_first_letter_keys();
}

// No keys found.
if (!$keys) {
    // Create the keys.
    $keys = [
        [
            [
                'text'          => getTranslation('not_supported'),
                'callback_data' => '0:exit:0'
            ]
        ]
    ];
}

//этим тестировал эмоджи
//.''.EMOJI_AUROR.''.''.EMOJI_MAGOZOOLOGIST.''.''.EMOJI_PROFESSOR.''.''.EMOJI_FORT.''.''.EMOJI_PAPERCLIP.''.''.EMOJI_FLASH.''.''.EMOJI_SKULL.''.EMOJI_BEER.''.EMOJI_MAGOZOOLOGIST2.''.EMOJI_FUCK.''.EMOJI_EYE

// Set message.
//$msg = '<b>' . getTranslation('select_gym_first_letter') . '</b>' . (RAID_VIA_LOCATION == true ? (CR2 . CR .  getTranslation('send_location')) : '');
$msg = '<b>'.getTranslation('send_location').''.EMOJI_PAPERCLIP.''.getTranslation('send_location2').'</b>';

// Send message.
send_message($update['message']['chat']['id'], $msg, $keys, ['reply_markup' => ['selective' => true, 'one_time_keyboard' => true]]);

?>
