<?php
    function get_mysql(){
        $mysqli = new mysqli('127.0.0.1:3306','root','','verysad');
        mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }

    $confirmId = $_POST['confirmId'];
    $reserveId = $_POST['reserveId'];

    $select1 = $_POST['select1'];
    $select2 = $_POST['select2'];
    $select3 = $_POST['select3'];
    $select4 = $_POST['select4'];

    $mysqli = get_mysql();

    $sql = "UPDATE `confirm` SET 
        `disCodeId_1`=".$select1.",
        `disCodeId_2`=".$select2.",
        `disCodeId_3`=".$select3.",
        `outDeptsId`=".$select4." WHERE 
        `confirmId`=".$confirmId.";";
    // echo $sql;
    $result = $mysqli->query($sql);

    $sql = "UPDATE `reserve` SET `state`='looked' WHERE `reserveId`='".$reserveId."';";
    $result = $mysqli->query($sql);




    header("Refresh:0;url=./medical_control.php");
?>