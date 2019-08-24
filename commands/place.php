<?php
// Write to log.
debug_log('PLACE()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get place name.
$place_name = trim(substr($update['message']['text'], 6));

// Write to log.
debug_log('Setting place name to ' . $place_name);
//debug_log('message_chat_type ' . $update['message']['chat']['type']);

// Private chat type.
if ($update['message']['chat']['type'] == 'private') {

    try {
     
         // Update place name in raid table.
        $query = '
            UPDATE places
            SET place_name = :place_name, show_gym = 1
            WHERE
                place_name = :place_id
            ORDER BY
                id DESC
            LIMIT 1
        ';
        $statement = $dbh->prepare( $query );
        $statement->bindValue(':place_name', $place_name, PDO::PARAM_STR);
        //$statement->bindValue(':place_id', '#'.$update['message']['from']['id'], PDO::PARAM_INT);
        $statement->bindValue(':place_id', '#'.$update['message']['from']['id'], PDO::PARAM_STR);
        $statement->execute();
    }
    catch (PDOException $exception) {

        error_log($exception->getMessage());
        $dbh = null;
        exit;
    }

    // Send the message.
    sendMessage($update['message']['chat']['id'], getTranslation('place_name_updated'));
}
?>
