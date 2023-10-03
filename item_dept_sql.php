<?php
function get_mysql(){
    return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
}
function insert_item($get){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "INSERT INTO `dispositioncode` (`disCodeName`) VALUES ('".$get."');";
    $result = $mysqli->query($sql);
}
function del_item($get){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "DELETE FROM `dispositioncode` WHERE disCodeId=".$get.";";
    $result = $mysqli->query($sql);
}
function insert_dept($get){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "INSERT INTO `outpatientdepts` (`outDeptsName`) VALUES ('".$get."');";
    $result = $mysqli->query($sql);
}
function del_dept($get){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "DELETE FROM `outpatientdepts` WHERE outDeptsId=".$get.";";
    $result = $mysqli->query($sql);
}

if ($_POST["medical_item_name"] != null){
    insert_item($_POST["medical_item_name"]);
}
else if ($_POST["item_delete"] != null){
    del_item($_POST["item_delete"]);
}
if ($_POST["medical_dept_name"] != null){
    insert_dept($_POST["medical_dept_name"]);
}
else if ($_POST["dept_delete"] != null){
    del_dept($_POST["dept_delete"]);
}

header("Refresh:0;url=./item_dept.php");

?>





