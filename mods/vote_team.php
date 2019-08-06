<?php
// Write to log.
debug_log('vote_team()');

// For debug.
//debug_log($update);
//debug_log($data);

// Update team in users table.
my_query(
    "
    UPDATE    users
    SET    prof = CASE
             WHEN prof = 'auror' THEN 'zoolog'
             WHEN prof = 'zoolog' THEN 'prof'
             ELSE 'auror'
           END
      WHERE   user_id = {$update['callback_query']['from']['id']}
    "
);

// Send vote response.
send_response_vote($update, $data);

exit();
