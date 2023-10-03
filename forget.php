<?php
function get_mysql(){
    return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
}
function make(){
    action;
}
function login_work(){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "SELECT * FROM account inner join patient on reserve.patientId=patient.patientId where reserve.state = 'true' and reserve.date>= CURDATE() order by reserve.date asc";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        $result1 = $catch['accont'];
        $result2 = $catch['password'];
        echo "<div style=' padding: 0px; '>$record1 $record2 預約看診<br> </div>";
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="./style.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>復健治療預約系統</title>
        <link rel="shortcut icon" href="./image/csh_icon.ico" type="image/x-icon">
    </head>
    <script>                                
        let btn=document.querySelector("#show");
        let infoModal=document.querySelector("#infoModal");
        btn.addEventListener("click", function(){
        infoModal.showModal();
        })
        var toalarm = false;
        var ch;
        var stralarm = new Array("<",">",".","!","-","$","@","%","^","&","*","(",")","=","~","`","\\","/","+","|","[","]","{","}","\"","\'",":",";","?"); //列出所有被禁止的方法字元
        function check(str){
            for (var i=0;i<stralarm.length;i++){ //依序載入使用者輸入的每個字元
                for (var j=0;j<str.length;j++){
                    ch=str.substr(j,1);
                    if (ch==stralarm[i]){
                    toalarm = true; //設置此變數為true
                    }
                }
            }
            if (toalarm){
            alert("包含特殊字元,請修正!");
            }
        }
    </script>
    <body style="background-color:rgb(255, 251, 245);">
        <div class="wrapper">
            <div class="content">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
                <!--header-->
                <header class="header">
                    <div class="container">
                        <div class="row">
                            <img src="./image/csmu.png"  class="title_picture" style="width:30%;">
                        </div>
                    </div>
                </header>
                <br>
                <!-- 忘記密碼 -->
                <script type="text/javascript">
                    function refresh_code()
                    { 
                        document.getElementById("imgcode").src="captcha_create.php"; 
                    } 
                </script>
                <div style="background-color: white; border-style:dashed; border-color:rgb(114, 151, 173); padding:5px; border-radius:20px; 
                    width: 35%; text-align: center; margin: 5% auto;">
                    <div style="font-family:'Times New Roman','標楷體';">
                        <div>
                            <form id="login_judge" name="login_judge" action="./captcha_judge.php" method="POST">
                                <p style="font-size:30px"><b>忘記密碼</b><hr style="border: 2px solid black;"/></p>
                                <p>電話：
                                    <input type="text" placeholder="請輸入電話號碼" name="phone" style="margin-top: 10px;"/>
                                </p>
                                <p>驗證：                                    
                                    <input type="text" placeholder="請輸入驗證碼" name="checkword" maxlength="3" style=""/>
                                </p>
                                <p><img id="imgcode" src="captcha_create.php" onclick="refresh_code()">
                                    點擊圖片更換(區分大小寫)
                                </p>
                                <div> 
                                    <center><button class="attendance_record_page_btn" type="submit"><font style="vertical-align: inherit;" onclick="check(account.value); check(checkword.value);">送出</font></button><center>
                                </div>
                            </form>
                            <center>
                                <button class="attendance_record_page_btn" type="submit" style="margin-top:10px">
                                    <font style="vertical-align: inherit;" onclick="location.href='./login.php'">返回</font>
                                </button>
                            <center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--footer-->
        <footer class="footer" style="color:white; font-size:15px; font-family:Times New Roman; text-align:center;">
            Copyright © 2022 CSMU-MI Inc. <br> All rights reserved.Web Design by CSMU-MI
        </footer>
        <script src="js/bootstrap.bundle.min.js"></script>  
    </body>
</html>