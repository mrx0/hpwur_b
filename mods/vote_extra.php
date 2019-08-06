<?php
// Write to log.
debug_log('vote()');

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

    // User profession update
    my_query(
        "
    UPDATE    users
    SET       prof = '{$data['arg']}'
      WHERE   user_id = {$update['callback_query']['from']['id']}
    "
    );

    if($data['arg'] == '0'){

        // Reset team extra people.
        my_query(
            "
            UPDATE    attendance
            SET       extra_auror = 0,
                      extra_zoolog = 0,
                      extra_prof = 0
              WHERE   raid_id = {$data['id']}
                AND   user_id = {$update['callback_query']['from']['id']}
            "
        );
    } else {
        // Get team.
        $team = 'extra_' . $data['arg'];

        // Increase team extra people.
        my_query(
            "
            UPDATE    attendance
            SET       {$team} = {$team}+1
              WHERE   raid_id = {$data['id']}
                AND   user_id = {$update['callback_query']['from']['id']}
                AND   {$team} < 5
            "
        );
    }

    // Send vote response.
    send_response_vote($update, $data);
} else {
    // Send vote time first.
    //Не пускает, если изначально не выбрали время
    //send_vote_time_first($update);

    my_query(
        "
        INSERT INTO   attendance
        SET           raid_id = {$data['id']},
                      user_id = {$update['callback_query']['from']['id']}
        "
    );

    // Send vote response.
    send_response_vote($update, $data);
}

exit();
