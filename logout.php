<?php
    session_start();

    function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli, "utf8");

    $_SESSION['accountName']= '';
    $_SESSION['account'] = $catch['account']= '';
    $_SESSION['email'] = $catch['email']= '';
    $_SESSION['password'] = $catch['password']= '';

    header("Refresh:0;url=./login.php");
?>