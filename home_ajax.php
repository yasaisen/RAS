<?php
session_start();
date_default_timezone_set("Asia/Taipei");
function get_mysql(){
    $mysqli = new mysqli('localhost', 'root', '', 'verysad');
    mysqli_set_charset($mysqli, "utf8");
    return $mysqli;
}
function pTimeId2str($get_pTimeId){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `pTime` WHERE `pTimeId` = ".$get_pTimeId.";";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    $pTimeStart=explode(":", $catch['pTimeStart'])[0].':00';
    $pTimeEnd=explode(":", $catch['pTimeEnd'])[0].':00';
    return $pTimeStart.'~'.$pTimeEnd;
}
function weeNum2date($get_date, $get_weeNum){
    $nearestMon = date("Y-m-d", strtotime('-'.strval(((int)date("w",strtotime($get_date)))-1).'day',strtotime($get_date)));

    return date("Y-m-d",strtotime('+'.strval((int)$get_weeNum-1).'day',strtotime($nearestMon)));
}
function todayornot($get_date){
    if (strval($get_date) == strval(date('Y-m-d'))){
        return '<b>';
    }else{
        return'';
    }
}
function top_row($get_date){
    return 
    '<div class="row" style="padding:0;">
        <div class="col"style="padding:0;border: black solid ;border-width:1px;"></div>
        <div class="col"style="padding:0;border: black solid ;border-width:1px;">'.todayornot(weeNum2date($get_date, '1')).'星期一('.weeNum2date($get_date, '1').')</b></div>
        <div class="col"style="padding:0;border: black solid ;border-width:1px;">'.todayornot(weeNum2date($get_date, '2')).'星期二('.weeNum2date($get_date, '2').')</b></div>
        <div class="col"style="padding:0;border: black solid ;border-width:1px;">'.todayornot(weeNum2date($get_date, '3')).'星期三('.weeNum2date($get_date, '3').')</b></div>
        <div class="col"style="padding:0;border: black solid ;border-width:1px;">'.todayornot(weeNum2date($get_date, '4')).'星期四('.weeNum2date($get_date, '4').')</b></div>
        <div class="col"style="padding:0;border: black solid ;border-width:1px;">'.todayornot(weeNum2date($get_date, '5')).'星期五('.weeNum2date($get_date, '5').')</b></div>
    </div>';
}
function sukejuru_row($get_date, $get_pTimeId){
    return 
    '<div class="row">
        <div class="col"style="font-size:18px;border: black solid ;border-width:1px;height:97px;text-align:center;line-height:97px;font-family:"Times New Roman",\'標楷體\';"> 
            '.pTimeId2str($get_pTimeId).'
        </div>
        <div class="col"style="border: black solid ;border-width:1px;"> 
            '.sukejurustr($get_date, 1, $get_pTimeId).'
        </div>
        <div class="col"style="border: black solid ;border-width:1px;"> 
            '.sukejurustr($get_date, 2, $get_pTimeId).'
        </div>
        <div class="col"style="border: black solid ;border-width:1px;"> 
            '.sukejurustr($get_date, 3, $get_pTimeId).'
        </div>
        <div class="col"style="border: black solid ;border-width:1px;">  
            '.sukejurustr($get_date, 4, $get_pTimeId).'
        </div>
        <div class="col"style="border: black solid ;border-width:1px;">
            '.sukejurustr($get_date, 5, $get_pTimeId).'
        </div>
    </div>';
}
function sukejurustr($get_date, $get_weeNum, $get_pTimeId){
    $getarray = get_namaearray_from_sql($get_date, $get_weeNum, $get_pTimeId);
    $sendbackstr = '';
    for ($i=0; $i<count($getarray); $i++){
        if($getarray[$i] == ''){
            '<div></div>';
        }else if($getarray[$i] == 'locked'){
            $sendbackstr = '<div style="font-size:20px; background-color:rgb(114, 151, 173);;color:white;">未開放預約</div>';

        }else if($getarray[$i] == 'personal'){
            $sendbackstr = '<div style="font-size:20px; background-color:rgb(114, 151, 173);;color:white;">事   假</div>';

        }else if($getarray[$i] == 'sick'){
            $sendbackstr = '<div style="font-size:20px; background-color:rgb(114, 151, 173);;color:white;">病   假</div>';

        }else if($getarray[$i] == 'business'){
            $sendbackstr = '<div style="font-size:20px; background-color:rgb(114, 151, 173);;color:white;">公   出</div>';

        }else{
            $sendbackstr = $sendbackstr.namaearray2sukejurustr($getarray[$i]);

        }
    }
    return $sendbackstr;
}
function namaearray2sukejurustr($get_id){
    $mysqli = get_mysql();
    $sql = "SELECT `patientName`, `reserve`.`state` AS TEMP FROM `reserve`, `patient` WHERE 
        `reserve`.`patientId`=`patient`.`patientId` AND 
        `reserve`.`reserveId`=".$get_id.";";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    $type = $catch['TEMP'];
    if ($type=='unlook' || $type=='passed'){
        return '<div style="font-size:20px;color:red;">'.$catch['patientName'].'</div>';
    }else if($type=='true'){
        return '<div style="font-size:20px">'.$catch['patientName'].'</div>';
    }else if($type="looked"){
        return '<div style="font-size:20px;color:green;">'.$catch['patientName'].'</div>';
    }
}
function get_namaearray_from_sql($get_date, $get_weeNum, $get_pTimeId){

    $weeNumnodate = weeNum2date($get_date, $get_weeNum);

    $mysqli = get_mysql();
    $sql = "SELECT (CASE WHEN `pTimeId`=".$get_pTimeId." THEN `reserveId` END) AS TEMP
    FROM `reserve`, `patient`
    WHERE (
        `reserve`.`state` = 'true' OR 
        `reserve`.`state` = 'unlook' OR 
        `reserve`.`state` = 'looked' OR 
        `reserve`.`state` = 'passed'
        ) AND
        `date` = '".weeNum2date($get_date, $get_weeNum)."' AND
        `reserve`.`patientId` = `patient`.`patientId`
    GROUP BY `date`, `reserveId`;";
    
    $result = $mysqli->query($sql);
    $sendbackarray = array();
    while ($catch = $result->fetch_assoc()){
        array_push($sendbackarray, $catch['TEMP']);
    }


    $sql = "SELECT `pos2reserve`.`pos2reserve` AS TEMP FROM `pos2reserve`, `pTime` WHERE 
        `pTime`.`amorpm`=`pos2reserve`.`amorpm` AND
        `weekNum`='".$get_weeNum."' AND
        `pTimeId`=".$get_pTimeId.";";

    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    if ($catch['TEMP'] == 'false'){
        array_push($sendbackarray, 'locked');
    }


    $sql = "SELECT `type` FROM `leavebustrip` WHERE 
        ('".$weeNumnodate."' between `dateStart` and `dateEnd`) AND
        ('".$get_pTimeId."' between `pTimeStart` and `pTimeEnd`);";

    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    if ($catch['type'] != ''){
        array_push($sendbackarray, $catch['type']);
    }
    
    return $sendbackarray;
}

if ($_POST['get_date'] == 'firstload' || $_POST['get_date'] == '0'){
    $_SESSION['get_date'] = 0;
}else{
    $_SESSION['get_date'] = (int)$_SESSION['get_date'] + (int)$_POST['get_date'];
}

$today = date('Y-m-d');
if ((int)$_SESSION['get_date'] > 0){
    $get_date = date("Y-m-d",strtotime('+'.strval($_SESSION['get_date']).'day',strtotime($today)));
}else if((int)$_SESSION['get_date'] == 0){
    $get_date = date("Y-m-d",strtotime($today));
}else if((int)$_SESSION['get_date'] < 0){
    $get_date = date("Y-m-d",strtotime(strval($_SESSION['get_date']).'day',strtotime($today)));
}

$json = array();   
$json["0"] = array("sukejuru_row" => top_row($get_date));
$json["1"] = array("sukejuru_row" => sukejuru_row($get_date, "1"));   
$json["2"] = array("sukejuru_row" => sukejuru_row($get_date, "2"));   
$json["3"] = array("sukejuru_row" => sukejuru_row($get_date, "3"));   
$json["4"] = array("sukejuru_row" => sukejuru_row($get_date, "4"));   
$json["5"] = array("sukejuru_row" => sukejuru_row($get_date, "5"));   
$json["6"] = array("sukejuru_row" => sukejuru_row($get_date, "6"));   
$json["7"] = array("sukejuru_row" => sukejuru_row($get_date, "7"));   
echo json_encode($json); 


