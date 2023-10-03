<?php 
session_start(); 
$_SESSION['account'];
if($_SESSION['account'] == null)
{
    echo "<script>alert('請先登入'); location.href = './login.php';</script>";
}
date_default_timezone_set("Asia/Taipei");
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
        echo '<option value="'.$catch['pTimeId'].'">'.explode(":", $catch['pTimeStart'])[0].':00~'.explode(":", $catch['pTimeEnd'])[0].':00'.'</option>';
    }
}
function ptimeid2str($get_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `ptime` WHERE `ptimeId`='".$get_id."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    return explode(":", $catch['pTimeStart'])[0].':00~'.explode(":", $catch['pTimeEnd'])[0].':00';
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
        <script src="#"></script>
        <script src="./js/jquery3.6.0.js"></script>
        <script src="./js/alert.js"></script>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>復健治療預約系統</title>
        <link rel="shortcut icon" href="./image/csh_icon.ico" type="image/x-icon">
    </head>
    <script type="text/javascript">
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
                                <b style="font-family:'Times New Roman','標楷體';">出席確認</b>
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
                                                    window.location.href="medical_control.php" 
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
                <!-- 表格datatables https://www.wfublog.com/2020/03/table-search-filter-jquery-datatables.html -->
                <script type="text/javascript" src="./DataTables/datatables.min.js"></script>
                <script type="text/javascript">
                    $(document).ready( function () {
                        $('#history_record_table').DataTable(
                            { language:
                                { 
                                    "emptyTable": "尚無資料", 
                                    "processing": "處理中...",
                                    "loadingRecords": "載入中...",
                                    "lengthMenu": "每頁顯示  _MENU_ 筆資料",
                                    "zeroRecords": "無搜尋結果",
                                    "info": "_START_ 至 _END_ / 共 _TOTAL_ 筆",
                                    "infoEmpty": "尚無資料",
                                    "infoFiltered": "(從 _MAX_ 筆資料過濾)",
                                    "infoPostFix": "",
                                    "search": "搜尋:",
                                    "paginate": { "first": "首頁", "last": "末頁", "next": "下一頁", "previous": "前一頁" }, 
                                    "aria": { "sortAscending": ": 升冪", "sortDescending": ": 降冪" } ,
                                }
                            }
                        );
                    } );
                </script>
                <!-- 表格內容 -->
                <div class="container" >
                    <div class="row align-items-center">
                        <div class="col" style="text-align:center; font-family:'Times New Roman','標楷體';">
                            <table id="history_record_table" class="display history_record_table">
                                <thead>
                                    <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;">
                                        <th style="width:16%">狀態</th>
                                        <th style="width:16%">日期(年月日)</th>
                                        <th style="width:16%">時段</th>
                                        <th style="width:11%">姓名</th>
                                        <th style="width:17%">出席確認</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    <?php
                                        $mysqli = get_mysql();
                                        $today = date('Y-m-d');

                                        $sql = "SELECT `reserveId`, `patientName`, `date`, `pTimeId`, `reserve`.`state` AS TEMP  
                                            FROM `reserve`, `patient` WHERE
                                            `reserve`.`patientId`=`patient`.`patientId` AND(
                                            `reserve`.`state`='true' OR
                                            `reserve`.`state`='looked' OR 
                                            `reserve`.`state`='unlook' )AND
                                            `reserve`.`date` = '".$today."' 
                                            ORDER BY `reserve`.`date` ASC;";
                                        $result = $mysqli->query($sql);
                                        $sendbackarray = array();
                                        while ($catch = $result->fetch_assoc()){
                                            echo '<tr style="border-color:rgb(165,165,165); border-width: 1px;">';
                                    ?>
                                        <td><?php echo state2str($catch['TEMP']); ?></td>
                                        <td><?php echo $catch['date']; ?></td>
                                        <td><?php echo ptimeId2str($catch['pTimeId']); ?></td>
                                        <td><?php echo $catch['patientName']; ?></td>
                                        <td>
                                        <?php
                                            if($catch['TEMP'] == 'true'){
                                                echo'<button type=button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#arr_Modal_'.$catch['reserveId'].'" 
                                                    style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                                        出席
                                                    </button>
                                                    <input name="reserveId" id="reserveId" type="hidden" value="'.$catch['reserveId'].'">
                                                    <button type=button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#unarr_Modal_'.$catch['reserveId'].'" 
                                                    style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                                        未到
                                                    </button>';
                                            }else if($catch['TEMP'] == 'looked'){
                                                echo '<button type=button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#config_arr_Modal_'.$catch['reserveId'].'" 
                                                    style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                                        編輯
                                                    </button>';
                                            }else if($catch['TEMP'] == 'unlook'){
                                                echo '<button type=button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#arr_Modal_'.$catch['reserveId'].'" 
                                                    style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                                        出席
                                                    </button>';
                                            }
                                            array_push($sendbackarray, $catch['reserveId']); ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <?php mess_box_maker($sendbackarray); ?>
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
                    <form action="./medical_control_session.php" method="post">
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
                                    <input type="text" name='insertPhone' style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;">
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
                                    <select name='insertTime' style="width:90px">
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
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="check(insertName.value); check(insertPhone.value) ;c();">存檔</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
function mess_box_maker($get_array){
    for ($i=0; $i<count($get_array); $i++){
        mess_box($get_array[$i]);
    }
}
function insertDispositioncode($selected_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM dispositioncode";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($selected_id == $catch['disCodeId']){
            echo '<option value="'.$catch['disCodeId'].'" selected>'.$catch['disCodeName'].'</option>';
        }else{
            echo '<option value="'.$catch['disCodeId'].'">'.$catch['disCodeName'].'</option>';
        }
    }
}
function insertOutDeptsName($selected_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM outpatientdepts";
    $result = $mysqli->query($sql);
    while ($catch = $result->fetch_assoc()){
        if ($selected_id == $catch['outDeptsId']){
            echo '<option value="'.$catch['outDeptsId'].'" selected>'.$catch['outDeptsName'].'</option>';
        }else{
            echo '<option value="'.$catch['outDeptsId'].'">'.$catch['outDeptsName'].'</option>';
        }
    }
}
function state2str($get_state){
    if ($get_state == 'true'){
        return '尚未確認';
    }else if ($get_state == 'looked'){
        return '<font color="green">已出席</font>';
    }else if ($get_state == 'unlook'){
        return '<font color="red">未到</font>';
    }
}
function mess_box($get_id){
    $mysqli = get_mysql();
    $sql = "SELECT `reserve`.`state` AS TEMP, `date`, `pTimeId`, `patientName`, `patientTel`, `reserveId` FROM `reserve`, `patient` WHERE `reserveId`='".$get_id."' AND `reserve`.`patientId`=`patient`.`patientId`;";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    $get_date = $catch['date'];
    $get_time = $catch['pTimeId'];
    $get_name = $catch['patientName'];
    $get_phone = $catch['patientTel'];
    $accountId = '1';
    $patientId = $catch['patientId'];
    $reserveId = $catch['reserveId'];

    if ($catch['TEMP'] == 'true'){

        echo '<div class="modal fade" id="unarr_Modal_'.$get_id.'" tabindex="-1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <br><center><b><font size="5">確定是否刪除?</font></b></center></br>
                            <br><center>確定<font color="red">'.$get_name.'</font>為未到<b></b>嗎?<center></br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="./medical_control_session2.php">
                            <input name="reserveId" id="reserveId" type="hidden" value="'.$get_id.'">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-primary"  data-bs-dismiss="modal">確定</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';


        $sql = "SELECT `disCodeId_1`, `disCodeId_2`, `disCodeId_3`, `outDeptsId` FROM `confirm` WHERE `patientId`='".$patientId."' ORDER BY `confirm`.`confirmId` DESC;";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();

        $disCodeId_1 = $catch['disCodeId_1'];
        $disCodeId_2 = $catch['disCodeId_2'];
        $disCodeId_3 = $catch['disCodeId_3'];
        $outDeptsId = $catch['outDeptsId'];

        echo ' <div class="modal fade" id="arr_Modal_'.$get_id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="font-family:標楷體;">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">選擇處置及科別</h5>
                    </div>
                    <div class="modal-body">
                        <form action="./medical_control_session3.php" method = "post">
                            <table class="record_table" align="center">
                                <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <th style="width:20%">日期(年月日)</th>
                                    <th style="width:20%">時段</th>
                                    <th style="width:10%">姓名</th>
                                    <th style="width:15%">手機電話</th>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">'.$get_date.'</font></td>
                                    <td><font size="3">'.ptimeid2str($get_time).'</font></td>
                                    <td><font size="3">'.$get_name.'</font></td>
                                    <td><font size="3">'.$get_phone.'</font></td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置一</font></td>
                                    <td><font size="3">
                                        <select name="select1" id="select1">
                                            <option value="0">請選擇</option>
                                            '; echo insertDispositioncode($disCodeId_1); echo '
                                        </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置二</font></td>
                                    <td><font size="3">
                                    <select name="select2" id="select2">
                                        <option value="0">請選擇</option>
                                        '; echo insertDispositioncode($disCodeId_2); echo '
                                    </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置三</font></td>
                                    <td><font size="3">
                                    <select name="select3" id="select3">
                                        <option value="0">請選擇</option>
                                        '; echo insertDispositioncode($disCodeId_3); echo '
                                    </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">門診科別</font></td>
                                    <td><font size="3">
                                    <select  name="select4" id="select4">
                                        <option value="0">請選擇</option>
                                        '; echo insertOutDeptsName($outDeptsId); echo '
                                    </select>
                                    </font>
                                    </td>
                                </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <input name="patientId" id="patientId" type="hidden" value="'.$patientId.'">
                                    <input name="reserveId" id="reserveId" type="hidden" value="'.$reserveId.'">
                                    <input name="accountId" id="accountId" type="hidden" value="'.$accountId.'">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary" onclick="doubleCheck_unlook();" >儲存</button>
                                </div>
                                    </form>
                                </div>
                        </div>
                </div>
        </div>';

    }else{

        $sql = "SELECT `confirmId`, `disCodeId_1`, `disCodeId_2`, `disCodeId_3`, `outDeptsId` 
            FROM `confirm` WHERE 
            `patientId`='".$patientId."' AND
            `reserveId`='".$reserveId."';";
        $result = $mysqli->query($sql);
        $catch = $result->fetch_assoc();

        $confirmId = $catch['confirmId'];
        $reserveId = $catch['reserveId'];

        $disCodeId_1 = $catch['disCodeId_1'];
        $disCodeId_2 = $catch['disCodeId_2'];
        $disCodeId_3 = $catch['disCodeId_3'];
        $outDeptsId = $catch['outDeptsId'];

        echo '<div class="modal fade" id="config_arr_Modal_'.$get_id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="font-family:標楷體;">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">編輯處置及科別</h5>
                    </div>
                    <div class="modal-body">
                        <form action="./medical_control_session4.php" method = "post">
                            <table class="record_table" align="center">
                                <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <th style="width:20%">日期(年月日)</th>
                                    <th style="width:20%">時段</th>
                                    <th style="width:10%">姓名</th>
                                    <th style="width:15%">手機電話</th>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">'.$get_date.'</font></td>
                                    <td><font size="3">'.ptimeid2str($get_time).'</font></td>
                                    <td><font size="3">'.$get_name.'</font></td>
                                    <td><font size="3">'.$get_phone.'</font></td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置一</font></td>
                                    <td><font size="3">
                                        <select name="select1" id="select1">
                                            <option value="0">請選擇</option>
                                            '; echo insertDispositioncode($disCodeId_1); echo '
                                        </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置二</font></td>
                                    <td><font size="3">
                                    <select name="select2" id="select2">
                                        <option value="0">請選擇</option>
                                        '; echo insertDispositioncode($disCodeId_2); echo '
                                    </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">處置三</font></td>
                                    <td><font size="3">
                                    <select name="select3" id="select3">
                                        <option value="0">請選擇</option>
                                        '; echo insertDispositioncode($disCodeId_3); echo '
                                    </select>
                                        </font>
                                    </td>
                                </tr>
                                <tr style="border-color:rgb(165,165,165); border-width: 1px;" align="center" valign="middle">
                                    <td><font size="3">門診科別</font></td>
                                    <td><font size="3">
                                    <select  name="select4" id="select4">
                                        <option value="0">請選擇</option>
                                        '; echo insertOutDeptsName($outDeptsId); echo '
                                    </select>
                                    </font>
                                    </td>
                                </tr>
                                </table>
                                </div>
                                <div class="modal-footer">
                                    <input name="confirmId" id="confirmId" type="hidden" value="'.$confirmId.'">
                                    <input name="reserveId" id="reserveId" type="hidden" value="'.$reserveId.'">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary" onclick="doubleCheck_unlook();" >修改</button>
                                </div>
                                    </form>
                                </div>
                        </div>
                </div>
        </div>';
    }
}
?>
</html>