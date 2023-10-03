<?php
    session_start();

    function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli, "utf8");

    if( $_POST['new_account'] == ''){
        $_SESSION['new_account'] = $_SESSION['account'];
    }else{
        $_SESSION['new_account'] = $_POST['new_account'];
    }
    if( $_POST['new_password'] == ''){
        $_SESSION['new_password'] = $_SESSION['password'];
    }else{
        $_SESSION['new_password'] = $_POST['new_password'];
    }
    if( $_POST['new_phone'] == ''){
        $_SESSION['new_phone'] = $_SESSION['phone'];
    }else{
        $_SESSION['new_phone'] = $_POST['new_phone'];
    }

    header("Refresh:0;url=./personal_info2.php");
?>