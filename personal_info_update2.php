<?php
    session_start();

    function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli, "utf8");

    $accountName = $_SESSION['accountName'];
    $new_account = $_SESSION['new_account'];
    $new_password = $_SESSION['new_password'];
    $new_phone = $_SESSION['new_phone'];

    $sql = "update account set account='$new_account', password='$new_password', phone='$new_phone' where accountName = '$accountName' ";
    // echo $sql;
    $result = $mysqli->query($sql);

    $_SESSION['account']= $_SESSION['new_account'];
    $_SESSION['password']= $_SESSION['new_password'];
    $_SESSION['phone']= $_SESSION['new_phone'];

    header("Refresh:0;url=./personal_info.php");
?>