<?php
function get_mysql(){
    return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
}
function patient_name($patientId,$name){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");

    $sql = "UPDATE `patient` SET `patientName`='$name' WHERE `patientId`='".$patientId."';";
    $result = $mysqli->query($sql);
}
function patient_tel($patientId,$tel){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");

    $sql = "UPDATE `patient` SET `patientTel`='$tel' WHERE `patientId`='".$patientId."';";
    $result = $mysqli->query($sql);
}


if ($_POST["changeName"] != null){
    patient_name($_POST["patientId"],$_POST["changeName"]);
}
if ($_POST["changeTel"] != null){
    patient_tel($_POST["patientId"],$_POST["changeTel"]);
}


header("Refresh:0;url=./patient.php");

?>





