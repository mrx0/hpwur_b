<?php

//desc.php
//Меняет описание рейда

// Write to log.
debug_log('DESCRIPTION()');

// For debug.
debug_log($update);
//debug_log($data);

// Get raid description.
$raid_description = trim(substr($update['message']['text'], 5));

// Write to log.
debug_log('Setting raid description to ' . $raid_description);

// Private chat type.
if ($update['message']['chat']['type'] == 'private') {

    try {

        // Update raid description
        //Костыль! ориентируемся на user_id, а не на raid_id
        $query = '
            UPDATE raids
            SET descr = :raid_description
            WHERE
                user_id = :user_id
            ORDER BY
                id DESC
            LIMIT 1
        ';
        $statement = $dbh->prepare( $query );
        $statement->bindValue(':raid_description', $raid_description, PDO::PARAM_STR);
        $statement->bindValue(':user_id', ''.$update['message']['from']['id'], PDO::PARAM_STR);
        $statement->execute();
    }
    catch (PDOException $exception) {

        error_log($exception->getMessage());
        $dbh = null;
        exit;
    }

    // Send the message.
    sendMessage($update['message']['chat']['id'], getTranslation('raid_description_updated'));

    //!!!
    //20190825 пытался обновить сообщение в чате бота онлайн - пока не получилось, надо видимо смотреть в сторону editMessage
    //вернуться потом и доделать
//    //Get raid
//    $raid = get_raid($update['message']['from']['id']);
//    // Build message string.
//    $msg .= show_raid_poll_small($raid);
}
?>
