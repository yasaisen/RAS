<?php 
session_start();
date_default_timezone_set("Asia/Taipei");
$_SESSION['account'];
if($_SESSION['account'] == null)
{
    echo "<script>alert('請先登入'); location.href = './login.php';</script>";
}
function get_mysql(){
    $mysqli = new mysqli('localhost', 'root', '', 'verysad');
    mysqli_set_charset($mysqli, "utf8");
    return $mysqli;
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
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="./style.css"/>
        <!-- datatables -->
        <script src="./js/jquery3.6.0.js"></script>
        <link rel="stylesheet" href="./addons/datatables.min.css">
        <link rel="stylesheet" type="text/css" href="./DataTables/datatables.min.css"/>
       
        <script src="./js/table2excel.js"></script>
        
        <script type="text/javascript" src="./js/jquery.min.js"></script>
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
                            <!-- <div class="col"> -->
                                <!-- OLD 選單 -->
                                <!-- <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample" 
                                    style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                                    功能選單    
                                </a>
                                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color:rgb(255, 251, 245); width:23%;">
                                    <div class="offcanvas-header">
                                        <h2 class="offcanvas-title" id="offcanvasExampleLabel" style="font-family:DFKai-sb; margin-left: 20px;">功能選單</h2>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" ></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <div class="cotainer">
                                            <div class="row"  style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./home.php'">首頁</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./medical_control.php'">看診確認</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./history_record.php'">歷史看診紀錄</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./attendance_record.php'">病人出席紀錄</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./leave.php'">請假</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./schedule.php'">編輯治療時段</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./item_dept.php'">編輯科別及處置代碼</button>
                                                </div>
                                            </div>
                                            <div class="row" style=" margin: 8px;">
                                                <div class="col" style=" padding: 0px;">
                                                    <button class="function_list_btn" onclick="location.href='./personal_info.php'">個人資料</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- 功能標題 -->
                                <!--<b style="font-family:'Times New Roman','標楷體';">首頁</b>-->
                            <!-- </div> -->
                            <!---週次切換--->
                            <div class="col-4 offset-4">
                                <button class="item_page_btn" onclick="get_table(-7);">上一週</button>
                                <button class="item_page_btn" onclick="get_table(0);">回本週</button>
                                <button class="item_page_btn" onclick="get_table(7);">下一週</button>
                            </div>
                            <div class="col-4">
                             
                                <script>
                                    const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
                                    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
                                </script>
                                
                                <div class="dropdown-center" style="float:right; --bs-btn-bg:red;margin-right: 50px;">
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
                                                    window.location.href="home.php" 
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
                <div class="container" style="text-align:center" id="testtest">
                    <!-- rrr -->
                    <div class="row align-items-center justify-content-center" style="display:flex;">
                        <div class="col-10" style="background-color:white; font-family:'Times New Roman','標楷體';">
                            <div id="0"></div>
                            <div id="1"></div>
                            <div id="2"></div>
                            <div id="3"></div>
                            <div id="4"></div>
                            <div class="row" style="text-align:center;border: black solid ;border-width:1px;"><center>午休<center></div>
                            <div id="5"></div>
                            <div id="6"></div>
                            <div id="7"></div>
                        </div>
                    </div>
                    <!-- rrr -->
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
                    <form action="./home_insert_patient.php" method="post">
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
<script>
$(document).ready(function() {
    $.ajax({
        url:"./home_ajax.php",
        type:"POST",
        data:{get_date : 'firstload'},
        datatype:"json",
        success:function(data){
            data = JSON.parse(data);
            for (var i = 0; i <= 7; i++) { 
                $('#'+i).html(data[i].sukejuru_row);
            } 
        },
        // error:function(){
        //     alert("error");
        // },
    })
});
var clock = setInterval(get_table , 1000);
function get_table(value) {
    $.ajax({
        url:"./home_ajax.php",
        type:"POST",
        data:{get_date : value},
        datatype:"json",
        success:function(data){
            // alert('123');
            data = JSON.parse(data);
            for (var i = 0; i <= 7; i++) {
                $('#'+i).html(data[i].sukejuru_row);
            } 
        },
        // error:function(){
        //     alert("error");
        // },
    })
}
</script>
