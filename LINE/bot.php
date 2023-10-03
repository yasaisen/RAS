<?php

date_default_timezone_set("Asia/Taipei");

require_once('LINEBotTiny.php');

$channelAccessToken = '@@@';
$channelSecret = '@@@';
if (file_exists(__DIR__ . '/config.ini')) {
    $config = parse_ini_file("config.ini", true);
    if ($config['Channel']['Token'] == null || $config['Channel']['Secret'] == null) {
        error_log("config.ini 配置檔未設定完全！", 0);
    } else {
        $channelAccessToken = $config['Channel']['Token'];
        $channelSecret = $config['Channel']['Secret'];
    }
} else {
    $configFile = fopen("config.ini", "w") or die("Unable to open file!");
    $configFileContent = '; Copyright 2020 GoneTone
;
; Line Bot

[Channel]
Token = ""
Secret = ""
';
    fwrite($configFile, $configFileContent);
    fclose($configFile);
    error_log("config.ini 配置檔建立成功，請編輯檔案填入資料！", 0);
}

$message = null;
$event = null;
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
function lineBroadcast($text){
    $channelToken = 'hpy0wam4zT9/HIBQmhhKw4sfA83+UAKoJaJ/zWil7tbdllvzI73Bnl2WhDDme2S6aS8dCJ/bu6auBGp6tvczWljv3oQtkiAko9ClJ1/SEqcP21IbFhcyoYUSLNJLsemcomp1Rb3mh/lYmivnb+Ne/wdB04t89/1O/w1cDnyilFU=';
    $headers = [
        'Authorization: Bearer ' . $channelToken,
        'Content-Type: application/json; charset=utf-8',
    ];
    $post = [
        'to' => 'U8dd7b8983b9f5edc3f0a88be3ec4c0ce',//'U8fb34ed477bfbc93bf28e7eef500426f',
        'messages' => [
            [
                'type' => 'text',
                'text' => $text,
            ],
        ],
    ];
    $url = 'https://api.line.me/v2/bot/message/push';//broadcast//push//message
    $post = json_encode($post);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $options = [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_POSTFIELDS => $post,
    ];
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
}


function to_string($get){
    return array(
        explode("-", $get)[0].'年'.explode("-", $get)[1].'月'.explode("-", $get)[2].'日',
        explode("-", $get)[3].'00到'.explode("-", $get)[4].'00'
    );
}
function cancelbotton_Fcancelreserve_0($get, $yoyaku, $event){
    return array(
        "type" => "button",
        "action" => array(
            'type' => 'postback',
            'label' => to_string($yoyaku[$get])[0].to_string($yoyaku[$get])[1],
            'data' => 'cancelreserve_0@'.$yoyaku[$get].'@'.$event['timestamp'],
            'displayText' => '我要取消'.to_string($yoyaku[$get])[0].to_string($yoyaku[$get])[1].'的預約'
        )
    );
}
function checkjibunnoyoyaku($get, $yoyaku){
    return array(
        "type" => "text",
        'text' => to_string($yoyaku[$get])[0].to_string($yoyaku[$get])[1],
        "wrap" => true
    );
}
function newliner(){
    return array(
        'type' => 'text',
        'text' => ' '
    );
}
function amorpm2str($amorpm){
    if ($amorpm == 'am'){
        return '上午';
    }else if($amorpm == 'pm'){
        return '下午';
    }
}
function pTimegetter($event, $amorpm){
    $get_userId = userId2patientId($event['source']['userId']);
    $get_date = $event['postback']['params']['date'];
    $yoyakujyotai = check_can_yoyako_or_not($get_date, $get_userId);

    $mysqli = get_mysql();
    $sql = "SELECT * FROM `pTime`;";
    $result = $mysqli->query($sql);
    $sendbackarray = array();
    array_push($sendbackarray, array('type' => 'text','text' => amorpm2str($amorpm)));
    while($catch = $result->fetch_assoc()){
        if ($catch['amorpm'] == $amorpm){
            $temp = yoyakushitai_Fconfirmreserve_0($event, explode(":", $catch['pTimeStart'])[0], explode(":", $catch['pTimeEnd'])[0], $yoyakujyotai[((int)$catch['pTimeId']-1)]);
            array_push($sendbackarray, $temp);
        }
    }
    return $sendbackarray;
}
function yoyakushitai_Fconfirmreserve_0($event, $pTimeStart, $pTimeEnd, $yoyakujyotai){
    if ($yoyakujyotai == 'available'){
        return array(
            "type" => "button",
            "action" => array(
                'type' => 'postback',
                'label' => $pTimeStart.'00到'.$pTimeEnd.'00：'.yoyakujyotai2str($yoyakujyotai),
                'data' => 'confirmreserve_1@'.$event["postback"]['params']['date'].'-'.$pTimeStart.'-'.$pTimeEnd.'@'.$event['timestamp'].'@'.explode("@", $event["postback"]['data'])[2],
                'displayText' => '我要預約'.to_string($event["postback"]["params"]['date'])[0].$pTimeStart.'00到'.$pTimeEnd.'00的時段'
            )
        );
    }else{
        return array(
            "type" => "button",
            "action" => array(
                'type' => 'postback',
                'label' => $pTimeStart.'00到'.$pTimeEnd.'00：'.yoyakujyotai2str($yoyakujyotai),
                'data' => 'pass'
            ),
            "style" => "secondary",
            "color" => "#FFFFFF"
        );
    }
}

function get_mysql(){
    $mysqli = new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
    mysqli_set_charset($mysqli, "utf8");
    return $mysqli;
}
function userId2patientId($get_userId){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `patient` WHERE `LINE_userId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    return $catch['patientId'];
}
function pTimeId2timestr($get){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM ptime WHERE pTimeId = '".$get."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    $temp = explode(":", $catch['pTimeStart'])[0]."-".explode(":", $catch['pTimeEnd'])[0];
    return $temp;
}
function timestr2pTimeId($get_start, $get_end){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM ptime WHERE pTimeStart = '".$get_start.":00:00' AND pTimeEnd = '".$get_end.":00:00';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    return $catch['pTimeId'];
}
function get_yoyakued_data_from_mysql($event){
    $get_userId = userId2patientId($event['source']['userId']);
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `reserve` WHERE `state` = 'true' AND `patientId` = '".$get_userId."' ORDER BY `reserve`.`date`, `reserve`.`pTimeId`  ASC;";
    $result = $mysqli->query($sql);
    $sendbackarray = array();
    $temp = "";
    while ($catch = $result->fetch_assoc()){
        $temp = $catch['date']."-".pTimeId2timestr($catch['pTimeId']);
        array_push($sendbackarray, $temp);
    }
    return $sendbackarray;
}
function i_want_to_cancel($event){
    $get_yoyaku = get_yoyakued_data_from_mysql($event);

    $sendbackarray = array();
    for ($i=0; $i<count($get_yoyaku); $i++){
        array_push($sendbackarray, cancelbotton_Fcancelreserve_0($i, $get_yoyaku, $event));
    }
    return $sendbackarray;
}
function i_want_to_check($event){
    $get_yoyaku = get_yoyakued_data_from_mysql($event);

    $sendbackarray = array();
    for ($i=0; $i<count($get_yoyaku); $i++){
        array_push($sendbackarray, checkjibunnoyoyaku($i, $get_yoyaku), newliner());
    }
    return $sendbackarray;
}
function imayoyaku_to_mysql($event){
    $get_date = explode("-", explode("@", $event["postback"]['data'])[1]);
    $mysqli = get_mysql();
    // $sql = "SELECT * FROM reserve;";
    // $result = $mysqli->query($sql);
    // $temp = 0;
    // while ($catch = $result->fetch_assoc()){
    //     $temp = (int)$catch['reserveId'];
    // }
    // $reserveId = strval($temp+1);
    $patientId = userId2patientId($event['source']['userId']);
    $date = $get_date[0]."-".$get_date[1]."-".$get_date[2];
    $pTimeId = timestr2pTimeId($get_date[3], $get_date[4]);
    $state = "true";

    $sql = "INSERT INTO `reserve` (`reserveId`, `patientId`, `date`, `pTimeId`, `state`)
    VALUES (NULL, '".$patientId."', '".$date."', '".$pTimeId."', '".$state."');";
    $result = $mysqli->query($sql);
}
function imaupdate_to_mysql($event){
    $get_date = explode("-", explode("@", $event["postback"]['data'])[1]);
    $mysqli = get_mysql();

    $patientId = userId2patientId($event['source']['userId']);
    $date = $get_date[0]."-".$get_date[1]."-".$get_date[2];
    $pTimeId = timestr2pTimeId($get_date[3], $get_date[4]);

    $sql = "UPDATE `reserve` SET `state`='false' 
    WHERE `patientId`='".$patientId."' AND `date`='".$date."' AND `pTimeId`='".$pTimeId."';";
    $result = $mysqli->query($sql);
}
function rep2leave($event){
    $mysqli = get_mysql();

    $get_leaveBusTripId = explode("@", $event["postback"]['data'])[1];
    $reserveId = explode("@", $event["postback"]['data'])[2];
    $patientId = userId2patientId($event['source']['userId']);

    $sql = "UPDATE `canceledrecorder` SET `getornot`='true' WHERE 
    `leaveBusTripId`='".$get_leaveBusTripId."' AND 
    `reserveId`='".$reserveId."' AND 
    `patientId`='".$patientId."';";
    $mysqli->query($sql);
    
    $sql = "SELECT `leaveBusTripId`, 
        SUM(CASE WHEN `getornot`='true' THEN 1 ELSE 0 END) AS TR, 
        SUM(CASE WHEN `getornot`='false' THEN 1 ELSE 0 END)AS FA
        FROM `canceledrecorder` WHERE 
        `leaveBusTripId`='".$get_leaveBusTripId."' GROUP BY `leaveBusTripId`;";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    $sql = "UPDATE `leavebustrip` SET `get_counts`='".$catch['TR']."' WHERE `leaveBusTripId`='".$get_leaveBusTripId."';";
    $result = $mysqli->query($sql);
}
function writer_misblocker($event, $kokonotype){
    $get_userId = $event['source']['userId'];
    $get_date = explode("@", $event["postback"]['data']);
    $today = date('Y-m-d H:i:s');
    $mysqli = get_mysql();
    if ($kokonotype == 'confirmreserve_-1'){
        $sql = "INSERT INTO `misblocker` (`userId`, `date`, `type`, `step_1`, `step_2`, `step_3`, `result`) 
        VALUES ('".$get_userId."', '".$today."', 'confirm', '".$event['timestamp']."', 'NULL', 'NULL', 'NULL');";
        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'confirmreserve_0'){
        $sql = "UPDATE `misblocker` SET `step_2`='".$event['timestamp']."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'confirmreserve_1'){
        $sql = "UPDATE `misblocker` SET `step_3`='".$event['timestamp']."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'confirmreserve_2'){
        $sql = "SELECT * FROM misblocker WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

        $catch = $result->fetch_assoc();
        if ($catch['result'] != 'cancel'){
            $sql = "UPDATE `misblocker` SET `result`='success' WHERE `userId`='".$get_userId."' AND 
            `step_1`='".$get_date[4]."' AND 
            `step_2`='".$get_date[3]."' AND 
            `step_3`='".$get_date[2]."';";
            $result = $mysqli->query($sql);

        }
    }else if ($kokonotype == 'confirmcancel'){
        $sql = "UPDATE `misblocker` SET `result`='cancel' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";
        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'cancelreserve_-1'){
        $sql = "INSERT INTO `misblocker` (`userId`, `date`, `type`, `step_1`, `step_2`, `step_3`, `result`) 
        VALUES ('".$get_userId."', '".$today."', 'cancel', '".$event['timestamp']."', 'NULL', 'NULL', 'NULL');";
        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'cancelreserve_0'){
        $sql = "UPDATE `misblocker` SET `step_2`='".$event['timestamp']."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'cancelreserve_1'){
        $sql = "SELECT * FROM misblocker WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);

        $catch = $result->fetch_assoc();
        if ($catch['result'] != 'cancel'){
            $sql = "UPDATE `misblocker` SET `result`='success' WHERE `userId`='".$get_userId."' AND 
            `step_1`='".$get_date[3]."' AND 
            `step_2`='".$get_date[2]."' AND 
            `step_3`='NULL';";
            $result = $mysqli->query($sql);

        }
    }else if ($kokonotype == 'cancelcancel'){
        $sql = "UPDATE `misblocker` SET `result`='cancel' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."' AND 
        `step_3`='NULL';";
        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profileconfig_-1'){
        $sql = "INSERT INTO `misblocker` (`userId`, `date`, `type`, `step_1`, `step_2`, `step_3`, `result`) 
        VALUES ('".$get_userId."', '".$today."', 'config', '".$event['timestamp']."', 'NULL', 'NULL', 'NULL');";
        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profile_phone_0'){
        $sql = "UPDATE `misblocker` SET `type`='config_phone', `step_2`='".$event['timestamp']."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profile_name_0'){
        $sql = "UPDATE `misblocker` SET `type`='config_name', `step_2`='".$event['timestamp']."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profile_phone_1'){
        $sql = "UPDATE `misblocker` SET `step_3`='".$get_date[2]."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profile_name_1'){
        $sql = "UPDATE `misblocker` SET `step_3`='".$get_date[2]."' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);

    }else if ($kokonotype == 'profile_phone_2'){
        $sql = "SELECT * FROM misblocker WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

        $catch = $result->fetch_assoc();
        if ($catch['result'] != 'cancel'){
            $sql = "UPDATE `misblocker` SET `result`='success' WHERE `userId`='".$get_userId."' AND 
            `step_1`='".$get_date[4]."' AND 
            `step_2`='".$get_date[3]."' AND 
            `step_3`='".$get_date[2]."';";
            $result = $mysqli->query($sql);

        }
    }else if ($kokonotype == 'profile_name_2'){
        $sql = "SELECT * FROM misblocker WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);

        $catch = $result->fetch_assoc();
        if ($catch['result'] != 'cancel'){
            $sql = "UPDATE `misblocker` SET `result`='success' WHERE `userId`='".$get_userId."' AND 
            `step_1`='".$get_date[4]."' AND 
            `step_2`='".$get_date[3]."' AND 
            `step_3`='".$get_date[2]."';";
            $result = $mysqli->query($sql);

        }
    }else if ($kokonotype == 'configcancel'){
        $sql = "UPDATE `misblocker` SET `result`='cancel' WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";
        $result = $mysqli->query($sql);

    }
}
function checker_misblocker($event){
    $get_userId = $event['source']['userId'];
    $get_date = explode("@", $event["postback"]['data']);
    $kokonotype = explode("@", $event["postback"]['data'])[0];
    $mysqli = get_mysql();
    if ($kokonotype == 'confirmreserve_0'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_2'] == 'NULL');

    }else if ($kokonotype == 'confirmreserve_1'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_3'] == 'NULL');

    }else if ($kokonotype == 'confirmreserve_2'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }else if ($kokonotype == 'confirmcancel'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }else if ($kokonotype == 'cancelreserve_0'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_2'] == 'NULL');

    }else if ($kokonotype == 'cancelreserve_1'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_3'] == 'NULL');

    }else if ($kokonotype == 'cancelcancel'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[3]."' AND 
        `step_2`='".$get_date[2]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }else if ($kokonotype == 'profile_phone_0'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_2'] == 'NULL');

    }else if ($kokonotype == 'profile_name_0'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_2'] == 'NULL');

    }else if ($kokonotype == 'profile_phone_1'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_3'] == 'NULL');

    }else if ($kokonotype == 'profile_name_1'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='NULL';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['step_3'] == 'NULL');

    }else if ($kokonotype == 'profile_phone_2'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }else if ($kokonotype == 'profile_name_2'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }else if ($kokonotype == 'configcancel'){
        $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
        `step_1`='".$get_date[4]."' AND 
        `step_2`='".$get_date[3]."' AND 
        `step_3`='".$get_date[2]."';";

        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return !($catch['result'] == 'NULL');

    }
}
function yoyakujyotai2str($get){
    if ($get == 'available') {
        return '尚可預約';
    }else if($get == 'full'){
        return '已額滿';
    }else if($get == 'cant'){
        return '未開放';
    }else if($get == 'booked'){
        return '您已預約';
    }else if($get == 'sick' || $get == 'personal'){
        return '治療師請假';
    }else if($get == 'business'){
        return '治療師公出';
    }
}
function check_can_yoyako_or_not($get_date, $get_userId){
    $yoyakujyotai = array(
        'cant',
        'cant',
        'cant',
        'cant',
        'cant',
        'cant',
        'cant'
    );
    $todayis = date("w",strtotime($get_date));
    $totoday = (strtotime($get_date) - strtotime(date("Y-m-d")))/86400;

    if ($totoday <= 30 && $totoday >= 1){
        for ($i=0; $i<7; $i++){
            $yoyakujyotai[$i] = 'available';
        }
    }


    $mysqli = get_mysql();

    $sql = "SELECT
            SUM(CASE WHEN `pTimeId`=1 THEN 1 ELSE 0 END) AS '1', 
            SUM(CASE WHEN `pTimeId`=2 THEN 1 ELSE 0 END) AS '2', 
            SUM(CASE WHEN `pTimeId`=3 THEN 1 ELSE 0 END) AS '3', 
            SUM(CASE WHEN `pTimeId`=4 THEN 1 ELSE 0 END) AS '4', 
            SUM(CASE WHEN `pTimeId`=5 THEN 1 ELSE 0 END) AS '5', 
            SUM(CASE WHEN `pTimeId`=6 THEN 1 ELSE 0 END) AS '6',
            SUM(CASE WHEN `pTimeId`=7 THEN 1 ELSE 0 END) AS '7'
            FROM `reserve` 
            WHERE `state` = 'true' AND `date` = '".$get_date."'
            GROUP BY `date`;";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        for ($i=1; $i<=7; $i++){
            if ($catch[$i] >= 3){
                $yoyakujyotai[$i-1] = 'full';
            }
        }
    }
    $sql = "SELECT * FROM `reserve` WHERE `reserve`.`state` = 'true' AND `reserve`.`patientId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($get_date == $catch['date']){
            for ($i=1; $i<=7; $i++){
                if ($catch['pTimeId'] == strval($i)){
                    $yoyakujyotai[$i-1] = 'booked';
                }
            }
        }
    }

    $sql = "SELECT * FROM `leavebustrip` WHERE ('".$get_date."' between `dateStart` and `dateEnd`);";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($catch['dateStart'] == $catch['dateEnd']){
            if ($get_date == $catch['dateStart']){
                for ($i=(int)$catch['pTimeStart']-1; $i<(int)$catch['pTimeEnd']; $i++){
                    $yoyakujyotai[$i] = $catch['type'];
                }
            }
        }else{
            if ($get_date == $catch['dateStart']){
                for ($i=(int)$catch['pTimeStart']-1; $i<7; $i++){
                    $yoyakujyotai[$i] = $catch['type'];
                }
            }else if($get_date == $catch['dateEnd']){
                for ($i=0; $i<(int)$catch['pTimeEnd']; $i++){
                    $yoyakujyotai[$i] = $catch['type'];
                }
            }else{
                for ($i=0; $i<7; $i++){
                    $yoyakujyotai[$i] = $catch['type'];
                }
            }
        }
    }

    $sql = "SELECT * FROM `pos2reserve`";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($todayis == $catch['weekNum']){
            if ($catch['amorpm'] == 'am' && $catch['pos2reserve'] == "false"){
                $yoyakujyotai[0] = 'cant';
                $yoyakujyotai[1] = 'cant';
                $yoyakujyotai[2] = 'cant';
                $yoyakujyotai[3] = 'cant';
            }else if ($catch['amorpm'] == 'pm' && $catch['pos2reserve'] == "false"){
                $yoyakujyotai[4] = 'cant';
                $yoyakujyotai[5] = 'cant';
                $yoyakujyotai[6] = 'cant';
            }
        }
    }
    if ($todayis == 6 || $todayis == 7){
        for ($i=0; $i<7; $i++){
            $yoyakujyotai[$i] = 'cant';
        }
    }
    return $yoyakujyotai;
}
function initializer($event){
    yoyakulatepasser($event);
    // yoyakulookpasser($event);
}
function yoyakulatepasser($event){
    $get_userId = userId2patientId($event['source']['userId']);
    $mysqli = get_mysql();
    $sql = "SELECT `reserveId`, `date` FROM `reserve` WHERE `state` = 'true' AND `patientId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($catch['date'] < date("Y-m-d")){
            $sql = "UPDATE `reserve` SET `state`='passed' WHERE `reserveId`=".$catch['reserveId'].";";
            $result_ = $mysqli->query($sql);
        }
    }
}
function yoyakulookpasser($event){
    $get_userId = userId2patientId($event['source']['userId']);
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `confirm` LEFT OUTER JOIN `reserve` ON `confirm`.`reserveId`=`reserve`.`reserveId`
    WHERE `state` = 'true' AND `patientId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        $sql = "UPDATE `reserve` SET `state`='looked' WHERE `reserveId`=".$catch['reserveId'].";";
        $result_ = $mysqli->query($sql);
    }
}
function check_num_of_yoyaku($event){
    $get_userId = userId2patientId($event['source']['userId']);
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `reserve` WHERE `reserve`.`state` = 'true' AND `reserve`.`patientId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $tamp = 0;
    while ($catch = $result->fetch_assoc()){
        $tamp = $tamp + 1;
    }
    if($tamp < 6){
        return false;
    }else{
        return true;
    }
}
function creat_new_acc($event){
    $mysqli = get_mysql();
    
    // $sql = "SELECT * FROM patient;";
    // $result = $mysqli->query($sql);
    // $temp = 0;
    // while ($catch = $result->fetch_assoc()){
    //     $temp = (int)$catch['patientId'];
    // }

    // $patientId = strval($temp+1);
    $patientName = 'NULL';
    $patientTel = 'NULL';
    $LINE_userId = $event['source']['userId'];
    $state = 'first@start_profile_config_0';
    $registration = 'false';

    $sql = "INSERT INTO `patient` (`patientId`, `patientName`, `patientTel`, `LINE_userId`, `state`, `registration`)
    VALUES (NULL, '".$patientName."', '".$patientTel."', '".$LINE_userId."', '".$state."', '".$registration."');";
    $result = $mysqli->query($sql);
}
function get_old_timestamp($event, $nameorphone){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `misblocker` WHERE `userId`='".$get_userId."' AND 
    `type`='config_".$nameorphone."' AND 
    `step_3`='NULL' AND 
    `result`='NULL';";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($catch['date'] >= date("Y-m-d H:i:s",strtotime('-1hour'))){
            if ((int)($catch['step_1']) + 100000 >= (int)($event["timestamp"])){
                return $catch['step_2'].'@'.$catch['step_1'];
            }
        }
    }
    return 'NULL';
}
function update_profile($get_type, $get_text, $get_userId){
    $mysqli = get_mysql();
    $sql = "UPDATE `patient` SET '".$get_type."'='".$get_text."', `state`='false' WHERE `LINE_userId`='".$get_userId."';";
    $result = $mysqli->query($sql);
}
function gottext($event, $client){
    $get_userId = $event['source']['userId'];
    $old_timestamp = '';
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `patient` WHERE `LINE_userId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    if (explode("@", $catch['state'])[1] == 'profile_name_0' && (strlen($event['message']['text']) > 5 || strlen($event['message']['text']) <= 0)){
        setstateback2normal($event);
        sentmessage($event, $client, "未符合輸入格式，請重新操作。");
    }else if(explode("@", $catch['state'])[1] == 'profile_phone_0' && (strlen($event['message']['text']) != 10 || !ctype_digit($event['message']['text']))){
        setstateback2normal($event);
        sentmessage($event, $client, "未符合輸入格式，請重新操作。");
    }else{
        if ($catch['state'] == 'first@start_profile_config_0'){
            if (strlen($event['message']['text']) > 30 || strlen($event['message']['text']) <= 0){
                $ask_text = "未符合輸入格式，請重新操作。";
                $postback_text = 'start_profile_config_0@NULL@';
                sendbuttonflex($event, $client, $ask_text, $postback_text);
            }else{
                $sql = "UPDATE `patient` SET `patientName`='".$event['message']['text']."', `state`='first@start_profile_config_0' WHERE `LINE_userId`='".$get_userId."';";
                $result = $mysqli->query($sql);
                postconfirm($event, $client, "profile_name_0", $event['message']['text'], true, 'name', $event["timestamp"], $old_timestamp);
            }
        }else if ($catch['state'] == 'first@start_profile_config_1'){
            if (strlen($event['message']['text']) != 10 || !ctype_digit($event['message']['text'])){
                $ask_text = "未符合輸入格式，請重新操作。";
                $postback_text = 'start_profile_config_1@NULL@';
                sendbuttonflex($event, $client, $ask_text, $postback_text);
            }else{
                $sql = "UPDATE `patient` SET `patientTel`='".$event['message']['text']."', `state`='first@start_profile_config_1' WHERE `LINE_userId`='".$get_userId."';";
                $result = $mysqli->query($sql);
                postconfirm($event, $client, "profile_phone_0", $event['message']['text'], true, 'phone', $event["timestamp"], $old_timestamp);
            }
    
        }else if ($catch['state'] != 'false'){
            if ((int)(explode("@", $catch['state'])[0]) + 100000 >= (int)($event["timestamp"])){
                if (explode("@", $catch['state'])[1] == 'profile_name_0'){
                    $old_timestamp = get_old_timestamp($event, 'name');
                }else if (explode("@", $catch['state'])[1] == 'profile_phone_0'){
                    $old_timestamp = get_old_timestamp($event, 'phone');
                }
    
                postconfirm($event, $client, explode("@", $catch['state'])[1], $event['message']['text'], false, '', $event["timestamp"], $old_timestamp);
    
            }else{
                setstateback2normal($event);
                sentmessage($event, $client, "輸入限制時間已超時，請重新操作。");
            }
        }
    }
}
function setstateback2normal($event){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "SELECT `registration` FROM `patient` WHERE `LINE_userId`='".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ($catch['registration'] == 'true'){
        $sql = "UPDATE `patient` SET `state`='false' WHERE `LINE_userId`='".$get_userId."';";
        $result = $mysqli->query($sql);
    }
}
function nextstep($event, $get_postback_tag, $registration){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "UPDATE `patient` SET `state`='".$get_postback_tag."', `registration`='".$registration."' WHERE `LINE_userId`='".$get_userId."';";
    $result = $mysqli->query($sql);
}
function registrationornot($event){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "SELECT `registration` FROM `patient` WHERE `LINE_userId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ($catch['registration'] == 'true'){
        return false;
    }else{
        return true;
    }
}
function from_sql_get($event, $nameorphone){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `patient` WHERE `LINE_userId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ($nameorphone == 'name'){
        return $catch['patientName'];
    }else if ($nameorphone == 'phone'){
        return $catch['patientTel'];
    }
}
function namaechecker($event, $get_text){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "SELECT `state` FROM `patient` WHERE `LINE_userId` = '".$get_userId."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ((int)(explode("@", $catch['state'])[0]) + 75000 >= (int)($event["timestamp"])){
        if ($get_text == '我要預約'){
            return true;
        }else if ($get_text == '我要取消'){
            return true;
        }else if ($get_text == '查看我的預約'){
            return true;
        }else if ($get_text == '編輯我的個人資料'){
            return true;
        }
    }else{
        setstateback2normal($event);
        return false;
    }
}
function open_access_2_config($event){
    $get_userId = $event['source']['userId'];
    $mysqli = get_mysql();
    $sql = "UPDATE `patient` SET `state`='".$event['timestamp']."@".explode("@", $event["postback"]['data'])[0]."' WHERE `LINE_userId`='".$get_userId."';";
    $result = $mysqli->query($sql);
}
function profile_type_2_str($get_text){
    if ($get_text == 'profile_name_0' || $get_text == 'profile_name_1'){
        return '姓名';
    }else if ($get_text == 'profile_phone_0' || $get_text == 'profile_phone_1'){
        return '連絡電話';
    }
}
function profile_type_2_sql($get_text){
    if ($get_text == 'profile_name_0' || $get_text == 'profile_name_1'){
        return 'patientName';
    }else if ($get_text == 'profile_phone_0' || $get_text == 'profile_phone_1'){
        return 'patientTel';
    }
}
function postconfirm($event, $client, $get_type, $get_text, $get_ima, $nameorphone, $get_timestamp, $old_timestamp){
    if ($get_ima){
        $confirm_label = '確定設定';
        $cancel_label = '重新設定';
        if ($nameorphone == 'name'){
            $ask_text = '確定要將您的'.profile_type_2_str($get_type).'設定為：「'.from_sql_get($event, $nameorphone).'」嗎';
            $confirm_postback_data = 'start_profile_config_1@'.$get_text.'@'.$get_timestamp.'@'.$old_timestamp;
            $cancel_postback_data = 'start_profile_config_0@NULL@'.$get_timestamp.'@'.$old_timestamp;
        }else if ($nameorphone == 'phone'){
            $ask_text = '確定要將您的'.profile_type_2_str($get_type).'設定為：「'.from_sql_get($event, $nameorphone).'」嗎';
            $confirm_postback_data = 'start_profile_config_2@'.$get_text.'@'.$get_timestamp.'@'.$old_timestamp;
            $cancel_postback_data = 'start_profile_config_1@NULL@'.$get_timestamp.'@'.$old_timestamp;
        }
    }else{
        $ask_text = '確定要將您的'.profile_type_2_str($get_type).'修改為：「'.$get_text.'」嗎';
        $confirm_postback_data = explode("_", $get_type)[0].'_'.explode("_", $get_type)[1].'_2@'.$get_text.'@'.$get_timestamp.'@'.$old_timestamp;
        $cancel_postback_data = 'configcancel@NULL@'.$get_timestamp.'@'.$old_timestamp;
        $confirm_label = '確定修改';
        $cancel_label = '放棄修改';
    }
    $event['postback']['data'] = explode("_", $get_type)[0].'_'.explode("_", $get_type)[1].'_1@NULL@'.$event['timestamp'].'@'.$old_timestamp;

    if(checker_misblocker($event) && !$get_ima){
        sentmessage($event, $client, "已操作，請重新操作。");
    }else{
        
        sentConfirm($event, $client, $ask_text, $confirm_postback_data, $cancel_postback_data, $confirm_label, $cancel_label);

        writer_misblocker($event, explode("_", $get_type)[0].'_'.explode("_", $get_type)[1].'_1');
    }
}
function timestampchecker($event){
    $temp = !((int)(explode("@", $event["postback"]['data'])[2]) + 20000 >= (int)($event["timestamp"]));
    return $temp;
}
function sentmessage($event, $client, $say_text){
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $say_text
            )
        )
    ));
}
function sentConfirm($event, $client, $ask_text, $confirm_postback_data, $cancel_postback_data, $confirm_label, $cancel_label){
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        "messages" => [
            array(
                "type" => "template",
                "altText" => $ask_text,
                "template" => array(
                    "type" => "confirm",
                    "text" => $ask_text,
                    "actions" => [
                        array(
                            'type' => 'postback',
                            'label' => $confirm_label,
                            'data' => $confirm_postback_data,
                            'displayText' => $confirm_label
                        ),
                        array(
                            'type' => 'postback',
                            'label' => $cancel_label,
                            'data' => $cancel_postback_data,
                            'displayText' => $cancel_label
                        )
                    ]
                )
            )
        ]
    ));
}
function sendbodyaruflex($event, $client, $ask_text, $content_array, $size){
    $contentsArray = array(
        "type" => "bubble",
        // "hero" => array(
        //     "type" => "image",
        //     "url" => "https://api.reh.tw/images/gonetone/logos/icons/icon-256x256.png",
        //     "aspectRatio" => "16:9",
        //     "size" => "full",
        //     "aspectMode" => "cover"
        // ),
        "header" => array(
            "type" => "box",
            "layout" => "vertical",
            "contents" => array(
                array(
                    "type" => "text",
                    "text" => $ask_text,
                )
            ),
            "background" => array(
                "type" => "linearGradient",
                "angle" => "90deg",
                "startColor" => "#FAFAFA",
                "endColor" => "#FAFAFA"
            ),
            "borderColor" => "#F2F2F2"
        ),
        "body" => array(
            "type" => "box",
            "layout" => "vertical",
            "contents" => $content_array
        ),
        "size" => $size
    );
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'flex',
                'altText' => $ask_text,
                'contents' => $contentsArray
            )
        )
    ));
}
function formatfitter($get_kotoba){
    return array(
        'type' => 'text',
        'text' => $get_kotoba,
        "wrap" => true
    );
}
function arraypusher($get_kotoba_array){
    $sendbackarray = array();
    for ($i=0; $i<count($get_kotoba_array); $i++){
        array_push($sendbackarray, formatfitter($get_kotoba_array[$i]));
    }
    return $sendbackarray;
}
function sendheaderdageflex($event, $client, $ask_text, $content_array){
    $contentsArray = array(
        "type" => "bubble",
        // "hero" => array(
        //     "type" => "image",
        //     "url" => "https://api.reh.tw/images/gonetone/logos/icons/icon-256x256.png",
        //     "aspectRatio" => "16:9",
        //     "size" => "full",
        //     "aspectMode" => "cover"
        // ),
        "header" => array(
            "type" => "box",
            "layout" => "vertical",
            "contents" => arraypusher($content_array)
        ),
        "size" => "kilo"
    );
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'flex',
                'altText' => $ask_text,
                'contents' => $contentsArray
            )
        )
    ));
}
function sendbuttonflex($event, $client, $ask_text, $postback_text){
    $contentsArray = array(
        "type" => "bubble",
        "header" => array(
            "type" => "box",
            "layout" => "vertical",
            "contents" => array(
                array(
                    'type' => 'text',
                    'text' => $ask_text,
                    "wrap" => true
                ),
                array(
                    "type" => "button",
                    "action" => array(
                        'type' => 'postback',
                        'label' => '重新輸入',
                        'data' => $postback_text,
                        'displayText' => '重新輸入'
                    )
                )
            )
        ),
        "size" => "kilo"
    );
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'flex', //訊息類型 (flex)
                'altText' => $ask_text, //替代文字
                'contents' => $contentsArray //Flex Message 內容
            )
        )
    ));
}
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message': //訊息觸發
            $message = $event['message'];
            switch ($message['type']) {
                case 'text': //訊息為文字
                    if (strtolower($message['text']) == "我要預約") {
                        if (namaechecker($event, "我要預約")){
                            sentmessage($event, $client, "個人資料尚未填寫完成，現在不可使用關鍵字，請重新輸入。");

                        }else if (registrationornot($event)){
                            sentmessage($event, $client, "請先完成註冊。");

                        }else if (check_num_of_yoyaku($event)){
                            sentmessage($event, $client, "最多僅可預約六次。");

                        }else{
                            initializer($event);
                            $client->replyMessage(array(
                                'replyToken' => $event['replyToken'],
                                'messages' => array(
                                    array(
                                        'type' => 'template', //訊息類型 (模板)
                                        'altText' => '請選擇預約的日期', //替代文字
                                        'template' => array(
                                            'type' => 'buttons', //類型 (按鈕)
                                            // 'thumbnailImageUrl' => 'https://api.reh.tw/line/bot/example/assets/images/example.jpg', //圖片網址 <不一定需要>
                                            //'title' => '挑一天ㄅ', //標題 <不一定需要>
                                            'text' => '請選擇預約日期', //文字
                                            'actions' => array(
                                                array(
                                                    "type" => "datetimepicker",
                                                    "data" => "confirmreserve_0@NULL@".$event['timestamp'], // will be included in postback action
                                                    "label" => "選擇預約日期",
                                                    "mode" => "date", // date | time | datetime
                                                    // "initial": (string)date('Y-m-d'), // 2017-06-18 | 00:00 | 2017-06-18T00:00
                                                    // "max": (string)date('Y-m-d'), //date('Y').'-'.((int)date('m')+1).'-'.date('d'), // 2017-06-18 | 00:00 | 2017-06-18T00:00
                                                    // "min": (string)date('Y-m-d') // 2017-06-18 | 00:00 | 2017-06-18T00:00 date('Y-m-d')
                                                )
                                            )
                                        )
                                    )
                                )
                            ));
                            writer_misblocker($event, 'confirmreserve_-1');
                        }
                    }else if (strtolower($message['text']) == "我要取消") {
                        if (registrationornot($event)){
                            sentmessage($event, $client, "請先完成註冊。");

                        }else if (namaechecker($event, "我要取消")){
                            sentmessage($event, $client, "個人資料尚未填寫完成，現在不可使用關鍵字，請重新輸入。");

                        }else{
                            initializer($event);
                            $ask_text = '請選擇欲取消的時段';
                            $content_array = i_want_to_cancel($event);
                            $size = "mega";
                            
                            sendbodyaruflex($event, $client, $ask_text, $content_array, $size);
                            
                            writer_misblocker($event, 'cancelreserve_-1');
                        }
                    }else if (strtolower($message['text']) == "查看我的預約") {
                        if (registrationornot($event)){
                            sentmessage($event, $client, "請先完成註冊。");

                        }else if (namaechecker($event, "查看我的預約")){
                            sentmessage($event, $client, "個人資料尚未填寫完成，現在不可使用關鍵字，請重新輸入。");

                        }else{
                            initializer($event);
                            $ask_text = '您的預約如下';
                            $content_array = i_want_to_check($event);
                            $size = "mega";
                            
                            sendbodyaruflex($event, $client, $ask_text, $content_array, $size);

                        }
                    // }else if (strtolower($message['text']) == "編輯我的個人資料") {
                    //     if (registrationornot($event)){
                    //         sentmessage($event, $client, "請先完成註冊。");

                    //     }else if (namaechecker($event, "編輯我的個人資料")){
                    //         sentmessage($event, $client, "個人資料尚未填寫完成，現在不可使用關鍵字，請重新輸入。");

                    //     }else{
                    //         initializer($event);
                    //         $ask_text = '請選擇欲修改的部分';
                    //         $content_array = array(
                    //             array(
                    //                 "type" => "button",
                    //                 "action" => array(
                    //                     'type' => 'postback',
                    //                     'label' => '修改姓名',
                    //                     'data' => 'profile_name_0@NULL@'.$event['timestamp'],
                    //                     'displayText' => '修改我的姓名'
                    //                 )
                    //             ),
                    //             array(
                    //                 "type" => "button",
                    //                 "action" => array(
                    //                     'type' => 'postback',
                    //                     'label' => '修改聯絡電話',
                    //                     'data' => 'profile_phone_0@NULL@'.$event['timestamp'],
                    //                     'displayText' => '修改我的聯絡電話'
                    //                 )
                    //             )
                    //         );
                    //         $size = "kilo";
                            
                    //         sendbodyaruflex($event, $client, $ask_text, $content_array, $size);

                    //         writer_misblocker($event, 'profileconfig_-1');
                    //     }
                    }else if (strtolower($message['text']) == "測試新註冊") {
                        $contentsArray = array(
                            "type" => "bubble",
                            // "hero" => array(
                            //     "type" => "image",
                            //     "url" => "https://api.reh.tw/images/gonetone/logos/icons/icon-256x256.png",
                            //     "aspectRatio" => "16:9",
                            //     "size" => "full",
                            //     "aspectMode" => "cover"
                            // ),
                            "header" => array(
                                "type" => "box",
                                "layout" => "vertical",
                                "contents" => array(
                                    array(
                                        'type' => 'text',
                                        'text' => '您好，歡迎使用預約診系統。',
                                        "wrap" => true
                                    ),
                                    newliner(),
                                    array(
                                        'type' => 'text',
                                        'text' => '請先依以下指導完成姓名及連絡電話的註冊，註冊完成後即可開始預約。',
                                        "wrap" => true
                                    ),
                                    newliner(),
                                    array(
                                        'type' => 'text',
                                        'text' => '謝謝您。',
                                        "wrap" => true
                                    ),
                                    array(
                                        "type" => "button",
                                        "action" => array(
                                            'type' => 'postback',
                                            'label' => '開始註冊',
                                            'data' => 'start_profile_config_0@NULL',
                                            'displayText' => '開始註冊'
                                        )
                                    )
                                )
                            ),
                            "size" => "kilo"
                        );
                        $client->replyMessage(array(
                            'replyToken' => $event['replyToken'],
                            'messages' => array(
                                array(
                                    'type' => 'flex', //訊息類型 (flex)
                                    'altText' => '歡迎使用預約診系統', //替代文字
                                    'contents' => $contentsArray //Flex Message 內容
                                )
                            )
                        ));
                        creat_new_acc($event);
                    }else{
                        gottext($event, $client);
                    }
                    // if (strtolower($message['text']) == "欸") {
                    //     $client->replyMessage(array(
                    //         'replyToken' => $event['replyToken'],
                    //         'messages' => array(
                    //             array(
                    //                 'type' => 'text',
                    //                 'text' => get_yoyakued_data_from_mysql($event)
                    //             )
                    //         )
                    //     ));
                    // }
                    break;
                default:
                    //error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        case 'postback': //postback 觸發
            if (explode("@", $event["postback"]['data'])[0] == 'confirmreserve_0'){
                if(timestampchecker($event)) {
                    sentmessage($event, $client, "操作逾時，請重新操作。");
                }else if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    $contentsArray = array(
                        "type" => "bubble",
                        // "hero" => array(
                        //     "type" => "image",
                        //     "url" => "https://api.reh.tw/images/gonetone/logos/icons/icon-256x256.png",
                        //     "aspectRatio" => "16:9",
                        //     "size" => "full",
                        //     "aspectMode" => "cover"
                        // ),
                        "header" => array(
                            "type" => "box",
                            "layout" => "vertical",
                            "contents" => array(
                                array(
                                    "type" => "text",
                                    "text" => '請選擇'.to_string($event["postback"]["params"]['date'])[0].'的預約時段',
                                    "wrap" => true
                                )
                            ),
                            "background" => array(
                                "type" => "linearGradient",
                                "angle" => "90deg",
                                "startColor" => "#FAFAFA",
                                "endColor" => "#FAFAFA"
                            ),
                            "borderColor" => "#F2F2F2"
                        ),
                        "body" => array(
                            "type" => "box",
                            "layout" => "vertical",
                            "contents" => pTimegetter($event, 'am')
                        ),
                        "footer" => array(
                            "type" => "box",
                            "layout" => "vertical",
                            "contents" => pTimegetter($event, 'pm')
                        ),
                        "size" => "kilo"
                    );
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'flex', //訊息類型 (flex)
                                'altText' => '請選擇'.to_string($event["postback"]["params"]['date'])[0].'的預約時段', //替代文字
                                'contents' => $contentsArray //Flex Message 內容
                            )
                        )
                    ));
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'confirmreserve_1') {
                if(timestampchecker($event)) {
                    sentmessage($event, $client, "操作逾時，請重新操作。");
                }else if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    $ask_text = '確定要預約'.to_string(explode("@", $event["postback"]['data'])[1])[0].to_string(explode("@", $event["postback"]['data'])[1])[1].'的時段嗎';
                    $confirm_postback_data = 'confirmreserve_2@'.explode("@", $event["postback"]['data'])[1].'@'.$event['timestamp'].'@'.explode("@", $event["postback"]['data'])[2].'@'.explode("@", $event["postback"]['data'])[3];
                    $cancel_postback_data = 'confirmcancel@'.explode("@", $event["postback"]['data'])[1].'@'.$event['timestamp'].'@'.explode("@", $event["postback"]['data'])[2].'@'.explode("@", $event["postback"]['data'])[3];
                    $confirm_label = '是的';
                    $cancel_label = '取消';
                    
                    sentConfirm($event, $client, $ask_text, $confirm_postback_data, $cancel_postback_data, $confirm_label, $cancel_label);
                    
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'confirmreserve_2') {
                if(timestampchecker($event)) {
                    sentmessage($event, $client, "操作逾時，請重新操作。");
                }else if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    imayoyaku_to_mysql($event);
                    $ask_text = '預約成功';
                    $content_array = array(
                        '已完成預約，詳細資料如下：',
                        ' ',
                        '日期：'.to_string(explode("@", $event["postback"]['data'])[1])[0],
                        '時間：'.to_string(explode("@", $event["postback"]['data'])[1])[1],
                        ' ',
                        '謝謝您。'
                    );

                    sendheaderdageflex($event, $client, $ask_text, $content_array);

                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'cancelreserve_0') {
                if(timestampchecker($event)) {
                    sentmessage($event, $client, "操作逾時，請重新操作。");
                }else if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    $ask_text = "確定要取消".to_string(explode("@", $event["postback"]['data'])[1])[0].to_string(explode("@", $event["postback"]['data'])[1])[1]."嗎";
                    $confirm_postback_data = 'cancelreserve_1@'.explode("@", $event["postback"]['data'])[1].'@'.$event['timestamp'].'@'.explode("@", $event["postback"]['data'])[2];
                    $cancel_postback_data = 'cancelcancel@'.explode("@", $event["postback"]['data'])[1].'@'.$event['timestamp'].'@'.explode("@", $event["postback"]['data'])[2];
                    $confirm_label = '確定取消';
                    $cancel_label = '放棄取消';
                    
                    sentConfirm($event, $client, $ask_text, $confirm_postback_data, $cancel_postback_data, $confirm_label, $cancel_label);

                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'cancelreserve_1') {
                if(timestampchecker($event)) {
                    sentmessage($event, $client, "操作逾時，請重新操作。");
                }else if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    imaupdate_to_mysql($event);
                    $ask_text = '取消成功';
                    $content_array = array(
                        '已取消'.to_string(explode("@", $event["postback"]['data'])[1])[0].to_string(explode("@", $event["postback"]['data'])[1])[1]."的預約。"
                    );

                    sendheaderdageflex($event, $client, $ask_text, $content_array);
                    
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'confirmcancel' || explode("@", $event["postback"]['data'])[0] == 'cancelcancel' || explode("@", $event["postback"]['data'])[0] == 'configcancel') {
                if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{

                    sentmessage($event, $client, "已取消動作，請重新操作。");

                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'profile_name_0') {
                if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    open_access_2_config($event);
                    $ask_text = '請輸入您的姓名';
                    $content_array = array(
                        '請輸入您的姓名，並按下發送鍵。範例如下：',
                        ' ',
                        '王小明',
                        ' ',
                        '謝謝您。'
                    );

                    sendheaderdageflex($event, $client, $ask_text, $content_array);
            
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'profile_phone_0') {
                if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    open_access_2_config($event);
                    $ask_text = '請輸入您的連絡電話';
                    $content_array = array(
                        '請輸入您的連絡電話，並按下發送鍵。範例如下：',
                        ' ',
                        '0912345678',
                        ' ',
                        '謝謝您。'
                    );

                    sendheaderdageflex($event, $client, $ask_text, $content_array);
                
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'profile_name_2' || explode("@", $event["postback"]['data'])[0] == 'profile_phone_2') {
                if(checker_misblocker($event)){
                    sentmessage($event, $client, "已操作，請重新操作。");
                }else{
                    $ask_text = '修改成功';
                    $content_array = array(
                        '已將您的'.profile_type_2_str(explode("@", $event["postback"]['data'])[0]).'修改為：「'.explode("@", $event["postback"]['data'])[1].'」。',
                        ' ',
                        '謝謝您。'
                    );

                    update_profile(profile_type_2_sql(explode("@", $event["postback"]['data'])[0]), explode("@", $event["postback"]['data'])[1], $event['source']['userId']);

                    sendheaderdageflex($event, $client, $ask_text, $content_array);

                    setstateback2normal($event);
                    writer_misblocker($event, explode("@", $event["postback"]['data'])[0]);
                }
            }
            if(explode("@", $event["postback"]['data'])[0] == 'start_profile_config_0') {
                $ask_text = '請輸入您的姓名';
                $content_array = array(
                    '首先，先請輸入您的姓名，並按下發送鍵。範例如下：',
                    ' ',
                    '王小明',
                    ' ',
                    '謝謝您。'
                );

                sendheaderdageflex($event, $client, $ask_text, $content_array);
                        
            }
            if(explode("@", $event["postback"]['data'])[0] == 'start_profile_config_1') {
                nextstep($event, 'first@start_profile_config_1', 'false');
                $ask_text = '請輸入您的連絡電話';
                $content_array = array(
                    '已將您的姓名設定為：「'.from_sql_get($event, 'name').'」。',
                    ' ',
                    '最後，請輸入您的連絡電話，並按下發送鍵。範例如下：',
                    ' ',
                    '0912345678',
                    ' ',
                    '謝謝您。'
                );

                sendheaderdageflex($event, $client, $ask_text, $content_array);
                        
            }
            if(explode("@", $event["postback"]['data'])[0] == 'start_profile_config_2') {
                nextstep($event, 'false', 'true');
                $ask_text = '請輸入您的連絡電話';
                $content_array = array(
                    '註冊完成',
                    ' ',
                    '您的連絡電話為：「'.from_sql_get($event, 'phone').'」。',
                    '您的姓名為：「'.from_sql_get($event, 'name').'」。',
                    ' ',
                    '預約功能已開放。',
                    ' ',
                    '謝謝您。'
                );

                sendheaderdageflex($event, $client, $ask_text, $content_array);
                        
            }
            if(explode("@", $event["postback"]['data'])[0] == 'get_leave') {
                rep2leave($event);
                $ask_text = '造成不便非常抱歉';
                $content_array = array(
                    '謝謝您的回覆。',
                    '造成不便非常抱歉。'
                );

                sendheaderdageflex($event, $client, $ask_text, $content_array);
                        
            }
            break;
        case 'follow': //加為好友觸發
            $contentsArray = array(
                "type" => "bubble",
                // "hero" => array(
                //     "type" => "image",
                //     "url" => "https://api.reh.tw/images/gonetone/logos/icons/icon-256x256.png",
                //     "aspectRatio" => "16:9",
                //     "size" => "full",
                //     "aspectMode" => "cover"
                // ),
                "header" => array(
                    "type" => "box",
                    "layout" => "vertical",
                    "contents" => array(
                        array(
                            'type' => 'text',
                            'text' => '您好，歡迎使用預約診系統。',
                            "wrap" => true
                        ),
                        newliner(),
                        array(
                            'type' => 'text',
                            'text' => '請先依以下指導完成姓名及連絡電話的註冊，註冊完成後即可開始預約。',
                            "wrap" => true
                        ),
                        newliner(),
                        array(
                            'type' => 'text',
                            'text' => '謝謝您。',
                            "wrap" => true
                        ),
                        array(
                            "type" => "button",
                            "action" => array(
                                'type' => 'postback',
                                'label' => '開始註冊',
                                'data' => 'start_profile_config_0@NULL',
                                'displayText' => '開始註冊'
                            )
                        )
                    )
                ),
                "size" => "kilo"
            );
            $client->replyMessage(array(
                'replyToken' => $event['replyToken'],
                'messages' => array(
                    array(
                        'type' => 'flex', //訊息類型 (flex)
                        'altText' => '歡迎使預約診系統', //替代文字
                        'contents' => $contentsArray //Flex Message 內容
                    )
                )
            ));
            creat_new_acc($event);
            break;
//         case 'join': //加入群組觸發

//             break;
        default:
            //error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
}
