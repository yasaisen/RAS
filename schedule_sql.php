<?php
    function get_mysql(){
        return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
    }
    function update_sukejuru_from_sql($get_weeNum, $get_amorpm, $get_stu){
        $mysqli = get_mysql();
        if ($get_stu == 'true'){
            $sql = "UPDATE `pos2reserve` SET `pos2reserve`='false' WHERE `weekNum` = ".$get_weeNum." AND `amorpm` = '".$get_amorpm."';";
        }else if($get_stu == 'false'){
            $sql = "UPDATE `pos2reserve` SET `pos2reserve`='true'  WHERE `weekNum` = ".$get_weeNum." AND `amorpm` = '".$get_amorpm."';";
        }
        $result = $mysqli->query($sql);
    }
    $get_weeNum = explode('@', $_POST["update_sukejuru_from_sql"])[0];
    $get_amorpm = explode('@', $_POST["update_sukejuru_from_sql"])[1];
    $get_stu = explode('@', $_POST["update_sukejuru_from_sql"])[2];
    update_sukejuru_from_sql($get_weeNum, $get_amorpm, $get_stu);

    // echo $get_weeNum.$get_amorpm.$get_stu;

    header("Refresh:0;url=./schedule.php");