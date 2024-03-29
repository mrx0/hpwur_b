<?php
// Write to log.
debug_log('DELETE()');

// For debug.
//debug_log($update);
//debug_log($data);

// Check access.
bot_access_check($update, BOT_ACCESS);

// Get timezone.
$tz = TIMEZONE;

// Count results.
$count = 0;

// Init text and keys.
$text = '';
$keys = [];

try {

    $query = '
        SELECT
            raids.*, places.lat ,
            places.lon ,
            places.address ,
            places.place_name ,
            places.ex_gym ,
            users. NAME ,
            UNIX_TIMESTAMP(start_time) AS ts_start ,
            UNIX_TIMESTAMP(end_time) AS ts_end ,
            UNIX_TIMESTAMP(NOW()) AS ts_now ,
            UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(NOW()) AS t_left
        FROM
            raids
        LEFT JOIN places ON raids.place_id = places.id
        LEFT JOIN users ON raids.user_id = users.user_id
        WHERE
            raids.end_time > NOW()
        AND raids.timezone = :timezone
        ORDER BY
            raids.end_time ASC
        LIMIT 20
    ';
    $statement = $dbh->prepare( $query );
    $statement->bindValue(':timezone', $tz, PDO::PARAM_STR);
    $statement->execute();
    while ($row = $statement->fetch()) {
    
        // Set text and keys.
        $text .= $row['place_name'] . CR;
        $raid_day = unix2tz($row['ts_start'], $row['timezone'], 'Y-m-d');
        $today = unix2tz($row['ts_now'], $row['timezone'], 'Y-m-d');
        $text .= get_local_pokemon_name($row['pokemon']) . SP . '—' . SP . (($raid_day == $today) ? '' : ($raid_day . ', ')) . unix2tz($row['ts_start'], $row['timezone']) . SP . getTranslation('to') . SP . unix2tz($row['ts_end'], $row['timezone']) . CR . CR;
        $keys[] = array(
            'text'          => $row['place_name'],
            'callback_data' => $row['id'] . ':raids_delete:0'
        );

        // Counter++
        $count = $count + 1;
    }
}
catch (PDOException $exception) {

    error_log($exception->getMessage());
    $dbh = null;
    exit;
}

// Set message.
if($count == 0) {
    $msg = '<b>' . getTranslation('no_active_raids_found') . '</b>';
} else {
    // Get the inline key array.
    $keys = inline_key_array($keys, 1);

    // Add exit key.
    $keys[] = [
        [
            'text'          => getTranslation('abort'),
            'callback_data' => '0:exit:0'
        ]
    ];

    // Build message.
    $msg = '<b>' . getTranslation('list_all_active_raids') . ':</b>' . CR;
    $msg .= $text;
    $msg .= '<b>' . getTranslation('select_gym_name') . '</b>' . CR;
}

// Build callback message string.
$callback_response = 'OK';

// Send message.
send_message($update['message']['chat']['id'], $msg, $keys, ['reply_markup' => ['selective' => true, 'one_time_keyboard' => true]]);
?>
