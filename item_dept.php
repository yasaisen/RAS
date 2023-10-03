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
<?php
function get_mysql(){
    return new mysqli('127.0.0.1:3306', 'root', '', 'verysad');
}
function get_item_delete(){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `dispositioncode`;";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        echo 
        '<tr style="background-color:rgb(255, 255, 255); border-color:rgb(165,165,165); border-width:2px; border-style:solid; height: 60px;">
            <td style="width:60%">
                '.$catch['disCodeName'].'
            </td>
            <td>
                <button class="item_page_btn" data-bs-toggle="modal" data-bs-target="#delete_Modal_'.$catch['disCodeName'].'" 
                                style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250); color:red;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                    刪除
                </button>
                <div class="modal fade" id="delete_Modal_'.$catch['disCodeName'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <br><center><b><font size="5">確定是否刪除</font></b></center></br>
                                    <br><center>確定要將處置代碼<b>'.$catch['disCodeName'].'<font color="red">刪除</font></b>嗎?<center></br>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="./item_dept_sql.php">
                                    <input name="item_delete" type="hidden" value="'.$catch['disCodeId'].'" >
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary"  data-bs-dismiss="modal">確定</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>';
    }
}
function get_dept_delete(){
    $mysqli = get_mysql();
    mysqli_set_charset($mysqli,"utf8");
    $sql = "SELECT * FROM `outpatientdepts`;";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        echo 
        '<tr style="background-color:rgb(255, 255, 255); border-color:rgb(165,165,165); border-width:2px; border-style:solid; height: 60px; ">
            <td style="width:60%">
                '.$catch['outDeptsName'].'
            </td>
            <td>
                <button class="item_page_btn" data-bs-toggle="modal" data-bs-target="#delete_Modal_'.$catch['outDeptsName'].'" 
                                style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250); color:red;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                    刪除
                </button>
                <div class="modal fade" id="delete_Modal_'.$catch['outDeptsName'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <br><center><b><font size="5">確定是否刪除</font></b></center></br>
                                    <br><center>確定要將門診科別<b>'.$catch['outDeptsName'].'<font color="red">刪除</font></b>嗎?<center></br>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="./item_dept_sql.php">
                                    <input name="dept_delete" type="hidden" value="'.$catch['outDeptsId'].'" >
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary"  data-bs-dismiss="modal">確定</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>';
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
        <link rel="stylesheet" href="./addons/datatables.min.css">
        <link rel="stylesheet" type="text/css" href="./DataTables/datatables.min.css"/>
        <script type="text/javascript" src="./DataTables/datatables.min.js"></script>
        <script src="./js/table2excel.js"></script>
        <script src="./js/jquery3.6.0.js"></script>
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
                            <div class="col">
                                <!-- 功能標題 -->
                                <b style="font-family:'Times New Roman','標楷體';">編輯代碼</b>
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
                                                    window.location.href="item_dept.php" 
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
                <div class="container">
                    <div class="row">
                        <div class="col" style=" font-family:'標楷體'; margin-left:10%; font-size:24px">
                            <b>處置代碼修改</b>
                        </div>
                        <div class="col" style="text-align:right; margin-right:10%;">
                            <!-- 匯出彈跳按鈕 -->
                            <button class="attendance_record_page_btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                                新增處置代碼
                            </button>
                            <!-- 匯出彈跳視窗 -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">新增處置代碼</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!--form-->
                                        <form action="./item_dept_sql.php" method="post" id="medical_item_add" name="medical_item_add">
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-size:20px;">
                                                        <div class="col">處置代碼</div>
                                                        <div class="col" style="justify-content:center;">
                                                            <input class="form-control" type="text" id="medical_item_name" name="medical_item_name"
                                                            maxlength="6" style="padding: 5px; margin: 3px; width: 75%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                <input type="submit" class="btn btn-primary" value='確認'>
                                            </div>
                                        </form>                               
                                    </div>
                                </div>
                            </div>
                            <!-- 匯出彈跳視窗 -->
                        </div>
                    </div>
                    <br>
                    <div>
                        <div>
                            <table class="item_table" align="center">
                                <?php get_item_delete(); ?>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col"style=" font-family:'標楷體';margin-left:10%;font-size:24px">
                            <b>門診科別修改</b>
                        </div>
                        <div class="col " style="text-align:right; margin-right:10%;">
                            <!-- 匯出彈跳按鈕 -->
                            <button class="attendance_record_page_btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2" 
                            style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                                新增門診科別
                            </button>
                            <!-- 匯出彈跳視窗 -->
                            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel2">新增門診科別</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!--form-->
                                        <form action="./item_dept_sql.php" method="post" id="medical_dept_add" name="medical_dept_add">
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-size:20px;">
                                                        <div class="col">門診科別</div>
                                                        <div class="col" style="justify-content:center;">
                                                            <input class="form-control" type="text" id="medical_dept_name" name="medical_dept_name"
                                                            maxlength="6" style="padding: 5px; margin: 3px; width: 75%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                <input type="submit" class="btn btn-primary" value='確認'>
                                            </div>
                                        </form>                               
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 匯出彈跳視窗 -->
                    </div>
                    <br>
                    <div>
                        <div>
                            <table class="item_table" align="center">
                                <?php get_dept_delete(); ?>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <br><br><br>
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
                    <form action="./item_dept_insert_patient.php" method="post">
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