<?php
    function get_mysql(){
        $mysqli = new mysqli('localhost', 'root', '', 'verysad');
        mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }
    function yoyaku($patientName, $patientTel, $date, $pTimeId){
        $mysqli = get_mysql();
        $sql = "SELECT `patientId` FROM `patient` WHERE `patientName` = '".$patientName."' AND `patientTel` = '".$patientTel."'; ";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        $patientId = $catch['patientId'];
    
        $sql = "INSERT INTO `reserve` (`reserveId`, `patientId`, `date`, `pTimeId`, `state`) 
            VALUES (NULL, '".$patientId."', '".$date."', '".$pTimeId."', 'true');";
        $result = $mysqli->query($sql);
    }
    $firstlook = $_POST['firstlook'] == 'on';

    $patientName = $_POST['insertName'];
    $patientTel = $_POST['insertPhone'];
    $date = $_POST['insertDate'];
    $pTimeId = $_POST['insertTime'];

    if ($firstlook){
        $mysqli = get_mysql();
        $sql = "INSERT INTO `patient` (`patientId`, `patientName`, `patientTel`, `LINE_userId`, `state`, `registration`) 
            VALUES (NULL, '".$patientName."', '".$patientTel."', NULL, 'false', 'false');";
        $result = $mysqli->query($sql);
    }
    yoyaku($patientName, $patientTel, $date, $pTimeId);

    header("Refresh:0;url=./medical_control.php");
?>