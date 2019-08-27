<?php

//vote_dd.php

// Write to log.
debug_log('vote_dd()');

// For debug.
//debug_log($update);
//debug_log($data);

// Check if the user has voted for this raid before.
$rs = my_query(
    "
    SELECT    user_id
    FROM      attendance
      WHERE   raid_id = {$data['id']}
        AND   user_id = {$update['callback_query']['from']['id']}
    "
);

// Get the answer.
$answer = $rs->fetch_assoc();

// Write to log.
debug_log($answer);

// User has voted before.
if (!empty($answer)) {

    // Set dd option for user
    my_query(
        "
        UPDATE    attendance
        SET       dd = {$data['arg']}
          WHERE   raid_id = {$data['id']}
            AND   user_id = {$update['callback_query']['from']['id']}
        "
    );

    // Send vote response.
    send_response_vote($update, $data);
} else {
    // Send vote time first.

    //send_vote_time_first($update);//<-- Вот это не пускает, если изначально не выбрали время

    //Добавляемся в бд
    my_query(
        "
        INSERT INTO   attendance
        SET           raid_id = {$data['id']},
                      dd = {$data['arg']}
                      user_id = {$update['callback_query']['from']['id']}
        "
    );

    // Send vote response.
    send_response_vote($update, $data);
}

exit();
