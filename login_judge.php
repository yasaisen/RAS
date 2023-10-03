<?php
    session_start();

    function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli, "utf8");

    $account = $_POST['account'];
    $password = $_POST['password'];

    if( $account != '' ) {
        $sql = "select * from account where account = '$account' ";
    }else{
        echo '<script>alert("登入失敗，請輸入帳號") ; window.location="login.php"</script>';
    }
    // echo $sql;
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if($catch['account']==$_POST['account'] && $catch['password']==$_POST['password']){
            $_SESSION['accountName']=$catch['accountName'];
            $_SESSION['account']=$catch['account'];
            $_SESSION['password']=$catch['password'];
            $_SESSION['phone']=$catch['phone'];
            // echo $_SESSION['accountName'];
            // echo 'login success';
            echo '<script>window.location="home.php"</script>';
        }else if($catch['account']==$_POST['account'] && $catch['password']!=$_POST['password']){
            // echo 'login NOT success';
            echo '<script>alert("登入失敗，密碼錯誤") ; window.location="login.php"</script>';
        }
    }
?>