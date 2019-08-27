<?php

//vote_level.php

// Write to log.
debug_log('vote_level()');

// For debug.
//debug_log($update);
//debug_log($data);

// Get action.
$action = $data['arg'];

// Standart level.
if ($action == '0') {
    // Standart users level.
    my_query(
        "
        UPDATE    users
        SET       level = 8
          WHERE   user_id = {$update['callback_query']['from']['id']}
        "
    );
}

// Up-vote.
if ($action == 'up') {
    // Increase users level.

//    my_query(
//        "
//        UPDATE    users
//        SET       level = IF(level = 0, 30, level+1)
//          WHERE   user_id = {$update['callback_query']['from']['id']}
//            AND   level < 40
//        "
//    );

    my_query(
        "
        UPDATE    users
        SET       level = IF(level = 0, 8, level+1)
          WHERE   user_id = {$update['callback_query']['from']['id']}
            AND   level < 15
        "
    );
}

// Down-vote.
if ($action == 'down') {
    // Decrease users level.
    my_query(
        "
        UPDATE    users
        SET       level = level-1
          WHERE   user_id = {$update['callback_query']['from']['id']}
            AND   level > 0
        "
    );
}

// Send vote response.
send_response_vote($update, $data);

exit();
