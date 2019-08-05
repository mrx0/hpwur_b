<?php
// Write to log.
debug_log('edit_raidlevel()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get place data via ID in arg
$place_id = $data['arg'];
$place = get_place($place_id);

// Back key id, action and arg
$back_id = 0;
$back_action = 'raid_by_gym';
$back_arg = $data['id'];
$place_first_letter = $back_arg;

// Active raid?
$duplicate_id = active_raid_duplication_check($place_id);

if ($duplicate_id > 0) {
    $keys = [];
    $raid_id = $duplicate_id;
    $raid = get_raid($raid_id);
    $msg = EMOJI_WARN . SP . getTranslation('raid_already_exists') . SP . EMOJI_WARN . CR . show_raid_poll_small($raid);

    // Check if the raid was already shared.
    $rs_share = my_query(
        "   
        SELECT  COUNT(*) AS raid_count
        FROM    cleanup
        WHERE   raid_id = '{$raid_id}'
        "
    );

    $shared = $rs_share->fetch_assoc();

    // Add keys for sharing the raid.
    if($shared['raid_count'] == 0) {
        $user_id = $update['callback_query']['from']['id'];
        $keys = share_raid_keys($raid_id, $user_id);

        // Exit key
        $empty_exit_key = [];
        $key_exit = universal_key($empty_exit_key, '0', 'exit', '0', getTranslation('abort'));
        $keys = array_merge($keys, $key_exit);
    }

    // Answer callback.
    answerCallbackQuery($update['callback_query']['id'], getTranslation('raid_already_exists'));

    // Edit the message.
    edit_message($update, $msg, $keys);

    // Exit.
    exit();
}

// Check access - user must be admin for raid_level X
$admin_access = bot_access_check($update, BOT_ADMINS, true);

if ($admin_access) {
    // Get the keys.
    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter, true);
    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter, true);
} else {
    // Get the keys.
    //$keys = raid_edit_raidlevel_keys($place_id, $place_first_letter);
    $keys = raid_edit_opportunity_keys($place_id, $place_first_letter);
}

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

// Build message.
$msg = getTranslation('create_raid') . ': <i>' . $place['address'] . '</i>';

// Build callback message string.
$callback_response = getTranslation('gym_saved');

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
//edit_message($update, $msg . CR . getTranslation('select_raid_level') . ':', $keys);
edit_message($update, $msg . CR . getTranslation('select_raid_reason') . ':', $keys);

// Exit.
exit();

