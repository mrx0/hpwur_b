<?php

//oddities_time.php
//Запрашиваем и показываем время спавна odditiesс

// Write to log.
debug_log('oddities_time()');

// For debug.
//debug_log($update);
//debug_log($data);

//Set latitude / longitude values
$lat = explode('+', $data['arg'])[0];
$lon = explode('+', $data['arg'])[1];

// Debug
debug_log('Lat: ' . $lat);
debug_log('Lon: ' . $lon);
//TimeZone + Sunrise/Sunset
//$tz = 'Europe/Moscow';
//$tz = '+03:00';
$time_zone = curl_get_timezone($lat, $lon, date('Y-m-d', time()));
//debug_log('time_zone:');
//debug_log($time_zone);

if (is_array($time_zone)) {

    //Если дата по локации отличается от текущей с телефона, переделываем запрос к данным
    if (date('Y-m-d', time()) != date("Y-m-d", strtotime($time_zone['time']))){
        //Date
        $date_now = date("Y-m-d", strtotime($time_zone['time']));
        //Следующий день для рассвета и заката
        $date_next = date('Y-m-d', strtotime($date_now.'+1 days'));
    //А если соответствует...
    }else{
        //Date
        $date_now = date('Y-m-d', time());
        //Следующий день для рассвета и заката
        $date_next = date('Y-m-d', strtotime($date_now.'+1 days'));
    }

    //TimeZone + Sunrise/Sunset + etc
    $time_zone = curl_get_timezone($lat, $lon, $date_next);
    debug_log('time_zone:');
    debug_log($time_zone);
    //Timezone
    $tz = $time_zone['timezoneId'];
    //Time
    $time_now = date('H:i', strtotime($time_zone['time']));

    //
    $day_now = date('d', strtotime($time_zone['time']));
    $month_now = getTranslation('month_'.date('m', strtotime($time_zone['time'])));
    $year_now = date('Y', strtotime($time_zone['time']));
    // Build message string.
    $msg = $day_now.' '.$month_now.' '.$year_now.SP.$time_now.CR;

    //Рассвет/закат по факту в эту дату
    $sunrise_time = date("H:i", strtotime($time_zone['sunrise']));
    $sunset_time = date("H:i", strtotime($time_zone['sunset']));


    //Рассвет/закат начало/конец в игре
    $sunrise_time_start_ingame = date("H:i", strtotime($time_zone['sunrise'].'-1 hours'));
    $sunrise_time_end_ingame = date("H:i", strtotime($time_zone['sunrise'].'+1 hours'));

    $sunset_time_start_ingame = date("H:i", strtotime($time_zone['sunset'].'-1 hours'));
    $sunset_time_end_ingame = date("H:i", strtotime($time_zone['sunset'].'+1 hours'));

    //То же самое, но завтра
//    if (isset($time_zone['dates'])) {
//        debug_log('dates:');
//        debug_log($time_zone['dates']);
        $sunrise_time_next = date("H:i", strtotime($time_zone['dates'][0]['sunrise']));
        $sunset_time_next = date("H:i", strtotime($time_zone['dates'][0]['sunset']));

        $sunrise_time_next_start_ingame = date("H:i", strtotime($time_zone['dates'][0]['sunrise'].'-1 hours'));
        $sunrise_time_next_end_ingame = date("H:i", strtotime($time_zone['dates'][0]['sunrise'].'+1 hours'));

        $sunset_time_next_start_ingame = date("H:i", strtotime($time_zone['dates'][0]['sunset'].'-1 hours'));
        $sunset_time_next_end_ingame = date("H:i", strtotime($time_zone['dates'][0]['sunset'].'+1 hours'));
//    }


    //Шапка сообщения
    $msg .= getTranslation('sunrise') . ': ' . $sunrise_time.' / '.getTranslation('sunset') . ': ' . $sunset_time.CR;
    $msg .= '<i>'.getTranslation('tomorrow').'</i>'.CR;
    $msg .= getTranslation('sunrise') . ': ' . $sunrise_time_next.' / '.getTranslation('sunset') . ': ' . $sunset_time_next.CR;


    //Doxy
    //на рассвете
    //Если сегодня рассвет в игре уже прошёл
    if ($time_now > $sunrise_time_end_ingame){
        //Указываем завтрашний рассвет
        $doxy_time = getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame;
    //Если сегодня рассвет в игре еще не наступил
    }elseif ($time_now < $sunrise_time_start_ingame){
        //Указываем сегодняшний рассвет
        $doxy_time = getTranslation('today').SP.$sunrise_time_start_ingame.' - '.$sunrise_time_end_ingame;
    //Если сейчас рассвет в игре
    }else{
        //Указываем сегодня до конца рассвета в игре
        $doxy_time = getTranslation('now').SP.getTranslation('to').SP.$sunrise_time_end_ingame;
    }

    //Serpent + Leprechaun
    //на рассвете и на закате
    //Если сегодня закат в игре уже прошёл
    if ($time_now > $sunset_time_end_ingame){
        //Указываем всё на завтра
        $serpent_time = getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame.', '.$sunset_time_next_start_ingame.' - '.$sunset_time_next_end_ingame;
        $leprechaun_time = getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame.', '.$sunset_time_next_start_ingame.' - '.$sunset_time_next_end_ingame;
    //Если сейчас закат в игре
    }elseif (($time_now >= $sunset_time_start_ingame) && ($time_now <= $sunset_time_end_ingame)){
        //Указываем сегодня до конца заката в игре
        $serpent_time = getTranslation('now').SP.getTranslation('to').SP.$sunset_time_end_ingame;
        $leprechaun_time = getTranslation('now').SP.getTranslation('to').SP.$sunset_time_end_ingame;
        //и завтрашний рассвет
        $serpent_time .= '.'.SP.getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame;
        $leprechaun_time .= '.'.SP.getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame;
    //Если сегодня закат в игре еще не наступил
    }else{
        //Если сегодня рассвет в игре уже прошёл
        if ($time_now > $sunrise_time_end_ingame){
            //Указываем сегодняшний закат
            $serpent_time = getTranslation('today').SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
            $leprechaun_time = getTranslation('today').SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
            //и завтрашний рассвет
            $serpent_time .= '.'.SP.getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame;
            $leprechaun_time .= '.'.SP.getTranslation('tomorrow').SP.$sunrise_time_next_start_ingame.' - '.$sunrise_time_next_end_ingame;
        //Если сегодня рассвет в игре еще не наступил
        }elseif ($time_now < $sunrise_time_start_ingame){
            //Указываем сегодняшний рассвет
            $serpent_time = getTranslation('today').SP.$sunrise_time_start_ingame.' - '.$sunrise_time_end_ingame;
            $leprechaun_time = getTranslation('today').SP.$sunrise_time_start_ingame.' - '.$sunrise_time_end_ingame;
            //и сегодняшний закат
            $serpent_time .= ','.SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
            $leprechaun_time .= ','.SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
        //Если сейчас рассвет в игре
        }else{
            //Указываем сегодня до конца рассвета в игре
            $serpent_time = getTranslation('now').SP.getTranslation('to').SP.$sunrise_time_end_ingame;
            $leprechaun_time = getTranslation('now').SP.getTranslation('to').SP.$sunrise_time_end_ingame;
            //и сегодняшний закат
            $serpent_time .= ','.SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
            $leprechaun_time .= ','.SP.$sunset_time_start_ingame.' - '.$sunset_time_end_ingame;
        }
    }

    //Vampire + Pixie
    //от конца текущего заката до начала следующего рассвета (ночь)
    //Если сегодня ночь в игре еще не наступила
    if ($time_now < $sunset_time_end_ingame){
        //Указываем сегодня от конца заката до начала рассвета завтра в игре
        $vampire_time = getTranslation('today').SP.getTranslation('from').SP.$sunset_time_end_ingame.SP.getTranslation('to').SP.$sunrise_time_next_start_ingame;
        $pixie_time = getTranslation('today').SP.getTranslation('from').SP.$sunset_time_end_ingame.SP.getTranslation('to').SP.$sunrise_time_next_start_ingame;
    //Если сейчас ночь в игре (до полуночи)
    }elseif ($time_now >= $sunset_time_end_ingame){
        //Указываем сегодня и до начала следующего рассвета в игре
        $vampire_time = getTranslation('now').SP.getTranslation('to').SP.$sunrise_time_next_start_ingame;
        $pixie_time = getTranslation('now').SP.getTranslation('to').SP.$sunrise_time_next_start_ingame;
    //Если сейчас ночь в игре (после полуночи)
    }elseif($time_now <= $sunrise_time_start_ingame) {
        //Указываем сегодня до начала рассвета в игре
        $vampire_time = getTranslation('now') . SP . getTranslation('to') . SP . $sunrise_time_start_ingame;
        $pixie_time = getTranslation('now') . SP . getTranslation('to') . SP . $sunrise_time_start_ingame;
        //и сегодня от конца заката до начала рассвета завтра в игре
        $vampire_time = ',' . SP . $sunset_time_end_ingame . SP . getTranslation('to') . SP . $sunrise_time_next_start_ingame;
        $pixie_time = ',' . SP . $sunset_time_end_ingame . SP . getTranslation('to') . SP . $sunrise_time_next_start_ingame;
    }

    //!!! доделать! хз когда
    //Dragon
    //Сегодня от расвета до заката
    $dragon_time = $sunrise_time_end_ingame.' - '.$sunset_time_start_ingame;



    //Запрос полнолуний
    $moon_date_time = curl_get_fullmoon($tz);
//    debug_log('moon_date_time:');
//    debug_log($moon_date_time);


    // Полнолуние + Оборотни
    if (is_array($moon_date_time)){

        //!!! добавить условие если например предыдущий / сейчас / завтра и так далее + время начала (ночь закат и тд)
        //Полнолуние по факту
        $moon_date_prev = date('Y-m-d', $moon_date_time['isitfullmoon']['prev']);
        $moon_date_next = date('Y-m-d', $moon_date_time['isitfullmoon']['next']);

        $moon_time_day = date('d', strtotime($moon_date_next));
        $moon_time_month = getTranslation('month_'.date('m', strtotime($moon_date_next)));
        $moon_time_year = date('Y', strtotime($moon_date_next));

        //Полнолуние начало/конец в игре
        $moon_date_next_start_ingame = date('Y-m-d', strtotime($moon_date_next.'-2 days'));
        $moon_time_next_start_ingame_day = date('d', strtotime($moon_date_next_start_ingame));
        $moon_time_next_start_ingame_month = getTranslation('month_'.date('m', strtotime($moon_date_next_start_ingame)));


        $moon_date_next_end_ingame = date('Y-m-d', strtotime($moon_date_next.'+2 days'));
        $moon_time_next_end_ingame_day = date('d', strtotime($moon_date_next_end_ingame));
        $moon_time_next_end_ingame_month = getTranslation('month_'.date('m',strtotime($moon_date_next_end_ingame)));

        //За два дня до полнолуния и два дня после
        $werewolf_time = $moon_time_next_start_ingame_day.' '.$moon_time_next_start_ingame_month.' - '.$moon_time_next_end_ingame_day.' '.$moon_time_next_end_ingame_month;



        $msg .= getTranslation('fullmoon') . ': '. $moon_time_day .' '. $moon_time_month .' '.  $moon_time_year .CR2;
        $msg .= '<b>Werewolf:</b> <i>'.$werewolf_time.'</i>'.CR;

    }else{
        $msg .= getTranslation('no_werewolf_data').CR2;
    }

    // Остальные oddities
//    if (is_array($sun_time)) {
        $msg .= '<b>Doxy:</b> <i>'.$doxy_time.'</i>'.CR;
        $msg .= '<b>Serpent:</b> <i>'.$serpent_time.'</i>'.CR;
        $msg .= '<b>Vampire:</b> <i>'.$vampire_time.'</i>'.CR;
        $msg .= '<b>Pixie:</b> <i>'.$pixie_time.'</i>'.CR;
        $msg .= '<b>Leprechaun:</b> <i>'.$leprechaun_time.'</i>'.CR;
        $msg .= '<b>Erkling:</b> <i>'.getTranslation('anytime').'</i>'.CR;
        $msg .= '<b>Centaur:</b> <i>'.getTranslation('anytime').'</i>'.CR;
        $msg .= '<b>Dragon:</b> нет точных данных'.CR;
//    }else{
//        $msg .= getTranslation('no_other_oddities_data').CR;
//    }

    $msg .= CR.'<i>'.getTranslation('geo_time').'</i>';

}else{
    $msg .= getTranslation('no_data').CR;
}

// Build callback message string.
$callback_response = 'Oddities';

// Answer callback.
answerCallbackQuery($update['callback_query']['id'], $callback_response);

// Edit the message.
edit_message($update, $msg, []);

// Exit.
exit();
