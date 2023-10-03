<?php 
    session_start(); 
    $_SESSION['account'];
    if($_SESSION['account'] == null)
    {
        echo "<script>alert('請先登入'); location.href = './login.php';</script>";
    }
    function insertpTimeId(){
        $mysqli = get_mysql();
        $sql = "SELECT * FROM pTime";
        $result = $mysqli->query($sql);
        while ($catch = $result->fetch_assoc()){
            $pTimeStart=explode(":", $catch['pTimeStart'])[0].':00';
            $pTimeEnd=explode(":", $catch['pTimeEnd'])[0].':00';
            echo '<option value="'.$catch['pTimeId'].'">'.$pTimeStart.'~'.$pTimeEnd.'</option>';
        }
    }
?>
<!doctype html>
<?php
    function get_mysql(){
        return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
    }
    function check_sukejuru_from_sql($get_weeNum, $get_amorpm){
        $mysqli = get_mysql();
        $sql = "SELECT * FROM `pos2reserve` WHERE `weekNum` = ".$get_weeNum." AND `amorpm` = '".$get_amorpm."';";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();
        if ($catch['pos2reserve'] == 'true'){
            echo '<td><center><button data-bs-toggle="modal" data-bs-target="#exampleModal_'.$get_weeNum.$get_amorpm.'" style="background-color: green;color:white;width:100px;height:40px;margin: 10px; border-color: rgb(0, 225, 0); border: 2px solid; border-radius: 8px;" class="name" >開放預約</center></button>';
            mess_box($get_weeNum, $get_amorpm, $catch['pos2reserve']);
        }else if ($catch['pos2reserve'] == 'false'){
            echo '<td><center><button data-bs-toggle="modal" data-bs-target="#exampleModal_'.$get_weeNum.$get_amorpm.'" style="background-color: red;color:white;width:100px;height:40px;margin: 10px; border-color: rgb(186, 225, 250); border: 2px solid; border-radius: 8px;" class="name">不開放預約</center></button>';
            mess_box($get_weeNum, $get_amorpm, $catch['pos2reserve']);
        }
    }
    // function update_sukejuru_from_sql($get_weeNum, $get_amorpm, $get_stu){
    //     if ($get_stu == 'true'){
    //         $mysqli = get_mysql();
    //         $sql = "UPDATE `pos2reserve` SET `pos2reserve`='true' WHERE `weekNum` = ".$get_weeNum." AND `amorpm` = '".$get_amorpm."';";
    //         $result = $mysqli->query($sql);
    //     }else if($get_stu == 'false'){
    //         $mysqli = get_mysql();
    //         $sql = "UPDATE `pos2reserve` SET `pos2reserve`='true'  WHERE `weekNum` = ".$get_weeNum." AND `amorpm` = '".$get_amorpm."';";
    //         $result = $mysqli->query($sql);
    //     }
    // }
    function weeNum2str($get_weeNum){
        if ($get_weeNum == '1'){
            return "一";
        }else if ($get_weeNum == '2'){
            return "二";
        }else if ($get_weeNum == '3'){
            return "三";
        }else if ($get_weeNum == '4'){
            return "四";
        }else if ($get_weeNum == '5'){
            return "五";
        }
    }
    function amorpm2str($get_amorpm){
        if ($get_amorpm == 'am'){
            return "08:00~12:00";
        }else if ($get_amorpm == 'pm'){
            return "14:00~17:00";
        }
    }
    function stu2str($get_stu){
        if ($get_stu == 'true'){
            return "不開放預約";
        }else if ($get_stu == 'false'){
            return "開放預約";
        }
    }
    function mess_box($get_weeNum, $get_amorpm, $get_stu){
        echo '<div class="modal fade" id="exampleModal_'.$get_weeNum.$get_amorpm.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <br><center><b><i><font size="5">確定變更</font></b></i></center></br>
                            <br><center>確定將<b>星期'.weeNum2str($get_weeNum).amorpm2str($get_amorpm).'</b>變更為<font color="red">'.stu2str($get_stu).'?</font><center></br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="./schedule_sql.php">
                            <input name="update_sukejuru_from_sql" type="hidden" value="'.$get_weeNum.'@'.$get_amorpm.'@'.$get_stu.'" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary"  data-bs-dismiss="modal">確定</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="./style.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>復健治療預約系統</title>
        <link rel="shortcut icon" href="./image/csh_icon.ico" type="image/x-icon">
    </head>
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
                <!-- 選單&標題 -->
                <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(186, 225, 250); padding-bottom: 0px;padding-top: 0px; --bs-navbar-color:black; --bs-navbar-hover-color: rgb(114, 151, 173);">
                    <div class="container-fluid" style=" padding-left: 7%; font-family:'Times New Roman','標楷體'; font-size:20px; ">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="./home.php"><b>預約記錄表</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./medical_control.php"><b>出席確認</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./history_record.php"><b>看診紀錄</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./attendance_record.php"><b>病人出席紀錄</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./leave.php"><b>請假</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./schedule.php"><b>編輯治療時段</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./item_dept.php"><b>編輯代碼</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./patient.php"><b>編輯病人資料</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./personal_info.php"><b>個人資料</b></a>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#staticBackdrop" 
                                        style="color:black; border:0px; background-color: rgb(186, 225, 250); font-weight:bold; text-decoration:underline;">
                                        手動新增預約
                                    </button>  
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="title">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <!-- 功能標題 -->
                                <b style="font-family:'Times New Roman','標楷體';">編輯治療時段</b>
                            </div> 
                            <div class="col" style="margin-right: 50px;">
                                <script>
                                    const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
                                    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
                                </script>
                                <div class="dropdown-center" style="float:right; --bs-btn-bg:red;">
                                    <a class="btn btn-secondary dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" 
                                        style="--bs-btn-bg:rgb(114, 151, 173);--bs-btn-active-bg:rgb(114, 151, 173);">
                                        <?php session_start(); echo $_SESSION['accountName'];?> 治療師
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
                                        <li><a class="dropdown-item" style="--bs-dropdown-link-active-bg:rgb(114, 151, 173);">登出</a></li>
                                        <script>
                                            var button = document.querySelector('.dropdown-item');
                                            function popup(e) 
                                            {
                                                if (confirm('確定要登出嗎') == true) {
                                                    window.location.href="logout.php" 
                                                } else {
                                                    window.location.href="schedule.php" 
                                                }
                                            };
                                            button.addEventListener('click', popup);
                                        </script>
                                    </ul>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <br>
                <!-- 班表表格 -->
                <div>
                    <div class="container" >
                        <div class="row align-items-center" style="font-family:'Times New Roman','標楷體';font-size:20px;">
                            <table class="record_table">
                                <tr style="border-width: 2px; border-style:solid ; width: 80px; height: 90px; border-color:black;">
                                    <th><center></center></th>
                                    <th><center>星期一</center></th>
                                    <th><center>星期二</center></th>
                                    <th><center>星期三</center></th>
                                    <th><center>星期四</center></th>
                                    <th><center>星期五</center></th>
                                </tr>
                                <tr style="border-width: 2px; border-style:solid ; width: 80px; height: 90px; border-color:black;">
                                    <?php
                                        echo '<td><center>'.amorpm2str('am').'</td>';
                                        check_sukejuru_from_sql('1', 'am');
                                        check_sukejuru_from_sql('2', 'am');
                                        check_sukejuru_from_sql('3', 'am');
                                        check_sukejuru_from_sql('4', 'am');
                                        check_sukejuru_from_sql('5', 'am');
                                    ?>
                                </tr>
                                <tr style="border-width: 2px; border-style:solid ; width: 80px; height: 90px; border-color:black;">
                                    <?php
                                        echo '<td><center>'.amorpm2str('pm').'</td>';
                                        check_sukejuru_from_sql('1', 'pm');
                                        check_sukejuru_from_sql('2', 'pm');
                                        check_sukejuru_from_sql('3', 'pm');
                                        check_sukejuru_from_sql('4', 'pm');
                                        check_sukejuru_from_sql('5', 'pm');
                                    ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
        <!--footer-->
        <footer class="footer" style="color:white; font-size:15px; font-family:Times New Roman; text-align:center;">
            Copyright © 2022 CSMU-MI Inc. <br> All rights reserved.Web Design by CSMU-MI
        </footer>
        <script src="js/bootstrap.bundle.min.js"></script>
    </body>
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:'Times New Roman','標楷體';">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"style="text-align:center;">新增預約資料</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./schedule_insert_patient.php" method="post">
                        <div class="container-fluid">                                            
                            <td>
                                病患姓名
                                <td class="col" style="justify-content:center;">
                                    <input type="text" name='insertName' style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                </td>                                                 
                            </td>
                            <td><br>
                                聯絡電話
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <input type="text" name='insertPhone' maxlength="10"
                                        style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;"
                                        onkeyup="value=value.replace(/[^0-9]/g,'')" onpaste="value=value.replace(/[^0-9]/g,'')" oncontextmenu = "value=value.replace(/[^0-9]/g,'')">
                                </td>                                                    
                            </td>
                            <br>
                            <br>
                            <td>
                                <br>
                                預約日期
                                <td class="col" style="justify-content:center;">
                                    <input type="date" name='insertDate' style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                </td>
                                預約時段
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <select name='insertTime' style="width:120px">
                                        <option value="">選擇時間</option>
                                        <?php insertpTimeId(); ?> 
                                    </select>
                                </td>
                            </td>
                            <br>
                            <br>
                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-family:'Times New Roman','標楷體';">
                                <div class="col">
                                    <td nowrap="nowrap" class="col medical_record_search_font" >
                                    <input type="checkbox" name='firstlook'>
                                    第一次預約
                                    </td>
                                </div>
                            </div>
                            <br>
                            <input name="from_update" type="hidden" value="" />
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="check(insertName.value); check(insertPhone.value);">存檔</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</html>