<?php
    function get_mysql(){
        $mysqli = new mysqli('127.0.0.1:3306','root','','verysad');
        mysqli_set_charset($mysqli, "utf8");
        return $mysqli;
    }

    $reserveId = $_POST["reserveId"];
    
    $mysqli = get_mysql();
    
    $sql = "UPDATE `reserve` SET `state`='unlook' WHERE `reserveId`=".$reserveId.";";
    $result = $mysqli->query($sql);
    
    header("Refresh:0;url=./medical_control.php");
?>