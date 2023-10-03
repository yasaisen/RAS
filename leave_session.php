<?php
    date_default_timezone_set("Asia/Taipei");
    session_start();
    function get_mysql(){
        $mysqli = new mysqli('localhost', 'root', '', 'verysad');
        mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }
    function new2sql($new_reasontype, $new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason){
        $mysqli = get_mysql();
        $sql = "INSERT INTO `leavebustrip` (`leaveBusTripId`, `dateStart`, `dateEnd`, `pTimeStart`, `pTimeEnd`, `type`, `reason`, `all_counts`, `get_counts`, `updateId`) 
                VALUES (NULL, '".$new_start_date."', '".$new_end_date."', '".$new_start_list."', '".$new_end_list."', '".$new_reasontype."', '".$reason."', 0, 0, '0');";
        $result = $mysqli->query($sql);

        $sql = "SELECT `leaveBusTripId` FROM leavebustrip WHERE 
        `dateStart`='".$new_start_date."' AND
        `dateEnd`='".$new_end_date."' AND
        `pTimeStart`='".$new_start_list."' AND
        `pTimeEnd`='".$new_end_list."' AND
        `reason`='".$reason."';";
        // echo $sql.'<br><br>';
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();

        $get_id = $catch['leaveBusTripId'];

        canceledrecorder_updater($new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason, $get_id, '0', '0');

    }
    function canceledrecorder_updater($new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason, $get_id, $updateId, $all_count){
        $mysqli = get_mysql();
        $sql = "SELECT * FROM `reserve`, `patient` WHERE 
        `reserve`.`patientId`=`patient`.`patientId` AND 
        (`date` between '".$new_start_date."' and '".$new_end_date."') AND 
        `reserve`.`state`='true';";
        // echo $sql.'<br><br>';

        $result = $mysqli->query($sql);
        $str_temp = '';
        $cou_temp = 0;
        // echo '<br>=======================<br>';
        while ($catch = $result->fetch_assoc()){
            // echo $new_start_list.'@'.$catch['pTimeId'].'@'.$new_end_list.'@'.$catch['pTimeId'].'<br>';

            $get_date = $catch['date'];
            $get_pTimeId = $catch['pTimeId'];
            $get_LINE_userId = $catch['LINE_userId'];
            $get_reserveId = $catch['reserveId'];
            $get_patientId = $catch['patientId'];
            
            if ($catch['date'] == $new_start_date && $catch['date'] == $new_end_date){
                if (((int)$new_start_list <= (int)$catch['pTimeId']) && ((int)$new_end_list >= (int)$catch['pTimeId'])){
                    $cou_temp = $cou_temp+1;
                    boom($get_date, $get_pTimeId, $reason, $get_LINE_userId, $get_reserveId, $get_id, $updateId, $get_patientId);
                }
            }else if($catch['date'] == $new_start_date && $catch['date'] != $new_end_date){
                if ((int)$new_start_list <= (int)$catch['pTimeId']){
                    $cou_temp = $cou_temp+1;
                    boom($get_date, $get_pTimeId, $reason, $get_LINE_userId, $get_reserveId, $get_id, $updateId, $get_patientId);
                }
            }else if($catch['date'] != $new_start_date && $catch['date'] == $new_end_date){
                if ((int)$new_end_list >= (int)$catch['pTimeId']){
                    $cou_temp = $cou_temp+1;
                    boom($get_date, $get_pTimeId, $reason, $get_LINE_userId, $get_reserveId, $get_id, $updateId, $get_patientId);
                }
            }
        }

        $all_counts = strval((int)$all_count + $cou_temp);

        $sql = "UPDATE `leavebustrip` SET `all_counts`='".$all_counts."' WHERE `leaveBusTripId`='".$get_id."';";
        // echo $sql;
        $result = $mysqli->query($sql);
    }
    function boom($get_date, $get_pTimeId, $reason, $get_LINE_userId, $get_reserveId, $get_id, $updateId, $get_patientId){
        $mysqli = get_mysql();
        if ($reason == ''){
            $str_temp = '您在'.to_string($get_date, $get_pTimeId).'的預約，因故取消。';
        }else{
            $str_temp = '您在'.to_string($get_date, $get_pTimeId).'的預約，物理治療師因'.$reason.'而先將您的預約取消。';
        }
        lineBroadcast($str_temp, $get_LINE_userId, $get_id, $get_reserveId);

        $sql = "UPDATE `reserve` SET `state`='canceled' WHERE `reserveId`='".$get_reserveId."';";
        $mysqli->query($sql);

        $sql = "INSERT INTO `canceledrecorder` (`leaveBusTripId`, `updateId`, `patientId`, `reserveId`, `getornot`) 
        VALUES ('".$get_id."', '".$updateId."', '".$get_patientId."', '".$get_reserveId."', 'false');";
        $mysqli->query($sql);
    }
    function update2sql($new_reasontype, $new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason, $get_id){
        $mysqli = get_mysql();
        
        $sql = "SELECT `updateId` FROM `leavebustrip` WHERE `leaveBusTripId`='".$get_id."';";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        $updateId = strval((int)$catch['updateId']+1);

        $sql = "UPDATE `leavebustrip` SET 
            `dateStart`='".$new_start_date."', 
            `dateEnd`='".$new_end_date."', 
            `pTimeStart`='".$new_start_list."', 
            `pTimeEnd`='".$new_end_list."', 
            `type`='".$new_reasontype."', 
            `reason`='".$reason."', 
            `updateId`='".$updateId."'
            WHERE `leaveBusTripId`='".$get_id."';";
        $result = $mysqli->query($sql);

        $sql = "SELECT `all_counts` FROM `leavebustrip` WHERE `leaveBusTripId`='".$get_id."';";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        $all_count = $catch['all_counts'];

        canceledrecorder_updater($new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason, $get_id, $updateId, $all_count);
    }
    function delfromsql($delete){
        $mysqli = get_mysql();
        $sql = "DELETE FROM `leavebustrip` WHERE `leavebustrip`.`leaveBusTripId` = ".$delete.";";
        $result = $mysqli->query($sql);
    }
    function lineBroadcast($text, $get_user_id, $leave_id, $re_id){//U8fb34ed477bfbc93bf28e7eef500426f
        $channelToken = 'p1e9wbo/z9vYUoMWfnLBvewNbzub2duAdG5E6ncIsN8MIeDar3U7Mve1YbkbrtCapYP0eGKfLFW3c18N39Ufe9G4Mv6PtqwbbMIqU0GJVOvgEflRe3zzm4mb+8tRb8QCLCzGykSYx2bPuhEI0Z4nmgdB04t89/1O/w1cDnyilFU=';
        $headers = [
            'Authorization: Bearer ' . $channelToken,
            'Content-Type: application/json; charset=utf-8',
        ];
        $contentsArray = array(
            "type" => "bubble",
            "header" => array(
                "type" => "box",
                "layout" => "vertical",
                "contents" => array(
                    array(
                        'type' => 'text',
                        'text' => '很抱歉',
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => ' ',
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => $text,
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => ' ',
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => '造成不便非常抱歉。',
                        "wrap" => true
                    ),
                    array(
                        "type" => "button",
                        "action" => array(
                            'type' => 'postback',
                            'label' => '我知道了',
                            'data' => 'get_leave@'.$leave_id.'@'.$re_id,
                            'displayText' => '我知道了'
                        )
                    )
                )
            ),
            "size" => "kilo"
        );
        $post = [
            'to' => $get_user_id,
            "messages" => array(
                array(
                    'type' => 'flex',
                    'altText' => '您的預約已被取消，造成不便非常抱歉。',
                    'contents' => $contentsArray
                )
            )
        ];
        $url = 'https://api.line.me/v2/bot/message/push';
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
    function id2str($get_time){
        $mysqli = get_mysql();
        $sql = "SELECT * FROM `ptime` WHERE `ptimeId`=".$get_time.";";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        return explode(":", $catch['pTimeStart'])[0].'00到'.explode(":", $catch['pTimeEnd'])[0].'00';
    }
    function to_string($get_date, $get_time){
        return explode("-", $get_date)[0].'年'.explode("-", $get_date)[1].'月'.explode("-", $get_date)[2].'日'.id2str($get_time);
    }

    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date'] = $_POST['end_date'];
    $_SESSION['reasontype'] = $_POST['reasontype'];

    $new_start_date = $_POST['new_start_date'];
    $new_start_list = $_POST['new_start_list'];
    $new_end_date = $_POST['new_end_date'];
    $new_end_list = $_POST['new_end_list'];
    $reason = $_POST['reason'];
    $new_reasontype = $_POST['new_reasontype'];

    $from_update = $_POST['from_update'];
    $delete = $_POST['delete'];



    if ($_POST['from_new'] == 'true'){
        if ($new_start_date == ''){
            echo '<script> alert("請選擇正確的開始日期");</script>';
    
        }else if ((strtotime($new_start_date) - strtotime(date("Y-m-d")))/86400 < 0){
            echo '<script> alert("請輸入未來日期");</script>';
    
        }else if ($new_start_list == ''){
            echo '<script> alert("請選擇正確的開始時段");</script>';
            
        }else if ($new_end_date == ''){
            echo '<script> alert("請選擇正確的結束日期");</script>';
            
        }else if ((strtotime($new_end_date) - strtotime(date("Y-m-d")))/86400 < 0){
            echo '<script> alert("請輸入未來日期");</script>';
    
        }else if ($new_end_list == ''){
            echo '<script> alert("請選擇正確的結束時段");</script>';
            
        }else if($from_update != ''){
            update2sql($new_reasontype, $new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason, $from_update);
        }else{
            new2sql($new_reasontype, $new_start_date, $new_start_list, $new_end_date, $new_end_list, $reason);
        }
    }
    if ($delete != ''){
        delfromsql($delete);
    }

    header("Refresh:0;url=./leave.php");
?>