<?php
    function get_mysql(){
        $mysqli = new mysqli('127.0.0.1:3306','root','','verysad');
        mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }

    $patientId = $_POST['patientId'];
    $reserveId = $_POST['reserveId'];
    $accountId = $_POST['accountId'];

    $select1 = $_POST['select1'];
    $select2 = $_POST['select2'];
    $select3 = $_POST['select3'];
    $select4 = $_POST['select4'];

    $mysqli = get_mysql();

    $sql = "INSERT INTO `confirm` (`confirmId`, `patientId`, `reserveId`, `disCodeId_1`, `disCodeId_2`, `disCodeId_3`, `outDeptsId`, `accountId`) 
    VALUES (NULL, '".$patientId."', '".$reserveId."', '".$select1."','".$select2."','".$select3."','".$select4."', '".$accountId."');";
    $result = $mysqli->query($sql);

    $sql = "UPDATE `reserve` SET `state`='looked' WHERE `reserveId`='".$reserveId."';";
    $result = $mysqli->query($sql);

    header("Refresh:0;url=./medical_control.php");
?>