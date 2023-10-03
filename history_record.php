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
        <script src="./js/table2excel.js"></script>
        <script src="./js/jquery3.6.0.js"></script>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>復健治療預約系統</title>
        <link rel="shortcut icon" href="./image/csh_icon.ico" type="image/x-icon">
    </head>
    <script type="text/javascript">
        function clean_session()
        {
        document.getElementById('history_record_name').value='';
        document.getElementById('history_record_start_date').value='';
        document.getElementById('history_record_end_date').value='';
        }
        function check_time()
        {
            var name = $("#history_record_name").val();
            var start = $("#history_record_start_date").val();
            var end = $("#history_record_end_date").val();
            if(name = "")
            {
                alert("請輸入查詢姓名");
            }
            if(start!="" & end!="")
            {
                var str_start = start.toString().split("-");
                var new_start=new Date(str_start[0],str_start[1],str_start[2]);
                var str_end = end.toString().split("-");
                var new_end=new Date(str_end[0],str_end[1],str_end[2]);
                var dayCount =(new_end - new_start);
                if(dayCount>=0)
                {
                document.history_record_search.submit();
                }
                else
                {
                    alert("請選擇正確的時間範圍");
                    document.getElementById('history_record_name').value='';
                    document.getElementById('history_record_start_date').value='';
                    document.getElementById('history_record_end_date').value='';
                }
            }
            else if(start=="" & end!="")
            {
                alert("請輸入開始日期");
                document.getElementById('history_record_name').value='';
                document.getElementById('history_record_start_date').value='';
                document.getElementById('history_record_end_date').value='';
            }
            else if(start!="" & end=="")
            {
                alert("請輸入結束日期");
                document.getElementById('history_record_name').value='';
                document.getElementById('history_record_start_date').value='';
                document.getElementById('history_record_end_date').value='';
            }
            else
            {
                alert("請輸入開始日期與結束日期");
                document.getElementById('history_record_name').value='';
                document.getElementById('history_record_start_date').value='';
                document.getElementById('history_record_end_date').value='';
            }
        }
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
                                <b style="font-family:'Times New Roman','標楷體';">看診紀錄</b>
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
                                                    window.location.href="history_record.php" 
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
                <!-- 查詢&匯出 -->
                <div>
                    <div class="container" >
                        <div class="row align-items-center">
                            <form action="./history_record_session.php" method="post" id="history_record_search" name="history_record_search">
                                <div class="col history_record_search_font" >
                                    查詢姓名：
                                    <!--查詢姓名輸入框-->
                                    <input type="text" id="history_record_name" name="history_record_name" maxlength="5" value="<?php echo $_SESSION['history_record_name']?>"
                                        style="font-size:18px; padding: 1px; margin: 3px; width: 10%; font-family:'Times New Roman','標楷體';">
                                    <!--控制文字框只能輸入中文、英文、數字-->
                                    &nbsp;&nbsp;
                                    開始時間&nbsp;
                                    <!--datetimepicker-->
                                    <input type="date" id="history_record_start_date" name="history_record_start_date" value="<?php echo $_SESSION['history_record_start_date'] ?>"
                                        style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                    &nbsp;&nbsp;
                                    結束時間&nbsp;
                                    <!--datetimepicker-->
                                    <input type="date" id="history_record_end_date" name="history_record_end_date" value="<?php echo $_SESSION['history_record_end_date'] ?>"
                                        style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button class="history_record_page_btn" onclick="check_time(); check(history_record_name.value);">查詢</button>
                                </div>
                            </form > 
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
                                <style>td{text-align: center}</style>
                                <thead>
                                    <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;">
                                        <th>日期(年月日)</th>
                                        <th>時段</th>
                                        <th>姓名</th>
                                        <th>科別</th>
                                        <th>處置一</th>
                                        <th>處置二</th>
                                        <th>處置三</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    <!--跑回圈要整個tr去跑-->
                                    <!--<tr style="border-color:rgb(165,165,165); border-width: 1px;">
                                        <td>2022-10-24</td>
                                        <td>8:00:00~9:00:00</td>
                                        <td>王小明</td>
                                        <td>泌尿科</td>
                                        <td>處置一</td>
                                        <td>處置二</td>
                                        <td>處置三</td>
                                    </tr>-->
                                    <?php
                                        function id2str($get_id){
                                            $mysqli = get_mysql();
                                            $sql = "SELECT * FROM dispositionCode;";
                                            $result = $mysqli->query($sql);
                                            while($catch = $result->fetch_assoc()){
                                                if ($catch['disCodeId'] == $get_id){
                                                    return $catch['disCodeName'];
                                                }
                                            }
                                        }
                                        function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
                                        $mysqli = get_mysql();
                                        mysqli_set_charset($mysqli, "utf8");
                                        $name=$_SESSION['history_record_name'];
                                        $start_date=$_SESSION['history_record_start_date'];
                                        $end_date=$_SESSION['history_record_end_date'];
                                        if( $name == '' ) { 
                                            $sql = "
                                            select * 
                                            from 
                                                confirm, patient, reserve ,pTime, outpatientDepts, dispositionCode 
                                            where 
                                                patient.patientId=confirm.patientId 
                                                and reserve.reserveId=confirm.reserveId 
                                                and pTime.pTimeId=reserve.pTimeId and 
                                                outpatientDepts.outDeptsId=confirm.outDeptsId 
                                                and (reserve.date between '$start_date' and '$end_date') 
                                            GROUP BY confirm.confirmId;";
                                        }else{ 
                                            $sql = "
                                            select * 
                                            from 
                                                confirm, patient, reserve ,pTime, outpatientDepts, dispositionCode 
                                            where 
                                                patient.patientId=confirm.patientId 
                                                and reserve.reserveId=confirm.reserveId 
                                                and pTime.pTimeId=reserve.pTimeId and 
                                                outpatientDepts.outDeptsId=confirm.outDeptsId 
                                                and patient.patientName='$name' and (reserve.date between '$start_date' and '$end_date') 
                                            GROUP BY confirm.confirmId;";
                                        }
                                        //echo $sql;
                                        $result = $mysqli->query($sql);
                                        $sendbackarray = array();
                                        while ($catch = $result->fetch_assoc()){
                                            $history_record_pTimeStart = explode(':', $catch['pTimeStart'])[0].':'.explode(':', $catch['pTimeStart'])[1];
                                            $history_record_pTimeEnd = explode(':', $catch['pTimeEnd'])[0].':'.explode(':', $catch['pTimeEnd'])[1];
                                            echo '<tr style="border-color:rgb(165,165,165); border-width: 1px;">';
                                    ?>
                                        <td><?php echo $catch['date']; ?></td>
                                        <td><?php echo $history_record_pTimeStart ?>~<?php echo $history_record_pTimeEnd; ?></td>
                                        <td><?php echo $catch['patientName']; ?></td>
                                        <td><?php echo $catch['outDeptsName']; ?></td>
                                        <td>
                                            <?php 
                                                if($catch['disCodeId_1'] == '' || $catch['disCodeId_1'] == 0){echo 無;}
                                                else{echo id2str($catch['disCodeId_1']);}
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($catch['disCodeId_2'] == '' || $catch['disCodeId_2'] == 0){echo 無;}
                                                else{echo id2str($catch['disCodeId_2']);}
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if($catch['disCodeId_3'] == '' || $catch['disCodeId_3'] == 0){echo 無;}
                                                else{echo id2str($catch['disCodeId_3']);}
                                            ?>
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
                <!-- 匯出 -->
                <div class="container" >
                    <div class="col " style="text-align:center;">
                        <!-- 匯出按鈕 -->
                        <button class="history_record_page_btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                            style="font-family:DFKai-sb; font-size:20px; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                        匯出</button>
                        <!-- 匯出彈跳視窗 -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">確認匯出日期範圍</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px;">
                                                <div class="col">開始日期</div>
                                                <div class="col" style="justify-content:center; font-family:'Times New Roman','標楷體';">
                                                    <?php 
                                                        if($_SESSION['history_record_start_date'] == null){
                                                            echo '未指定開始日期';
                                                        }else{
                                                            echo $_SESSION['history_record_start_date'];
                                                        }
                                                    ?>
                                                    <!--
                                                    <input type="date" id="start" name="trip-start"value="2018-07-22"min="2018-01-01" max="2018-12-31"
                                                        style="font-size:15px; font-family:DFKai-sb; padding: 0px; margin-left: 15px">
                                                    -->
                                                </div>
                                            </div>
                                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px;">
                                                <div class="col">結束日期</div>
                                                <div class="col" style="justify-content:center; font-family:'Times New Roman','標楷體';">
                                                    <?php 
                                                        if($_SESSION['history_record_end_date'] == null){
                                                            echo '未指定開始日期';
                                                        }else{
                                                            echo $_SESSION['history_record_end_date'];
                                                        }
                                                    ?>
                                                    <!--
                                                    <input type="date" id="start" name="trip-start"value="2018-07-22"min="2018-01-01" max="2018-12-31"
                                                        style="font-size:15px; font-family:DFKai-sb; padding: 0px; margin-left: 15px">
                                                    -->
                                                </div>
                                            </div>
                                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px;">
                                                <div class="col">病患姓名</div>
                                                <div class="col" style="justify-content:center; font-family:'Times New Roman','標楷體';">
                                                    <?php 
                                                        if($_SESSION['history_record_name'] == null){
                                                            echo '未指定病患姓名';
                                                        }else{
                                                            echo $_SESSION['history_record_name'];
                                                        }
                                                    ?>
                                                    <!--
                                                        <input class="form-control" type="text" maxlength="5" style="padding: 5px; margin: 3px; width: 75%;">
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 原本按鈕觸發下載excel的程式
                                        document.getElementById('export_btn').addEventListener('click', function(){
                                            var table2excel = new Table2Excel();
                                            table2excel.export(document.querySelectorAll("#history_record_table"));
                                        })
                                    -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                            style="border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">取消</button>
                                        <button id="export_btn" type="button" class="btn btn-primary" onclick="check_download();clean_session()"
                                            style="border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">確定</button>
                                        <script>
                                            function check_download()
                                            {
                                                var name2='<?php echo $_SESSION['history_record_name'];?>';
                                                var start2='<?php echo $_SESSION['history_record_start_date'];?>';
                                                var end2='<?php echo $_SESSION['history_record_end_date'];?>';

                                                if(start2!="" & end2!="")
                                                {
                                                    var table2excel = new Table2Excel();
                                                    table2excel.export(document.querySelectorAll("#history_record_table"));
                                                }else if(start2=="" & end2!="")
                                                {
                                                    alert("請輸入開始日期");
                                                }
                                                else if(start2!="" & end2=="")
                                                {
                                                    alert("請輸入結束日期");
                                                }
                                                else
                                                {
                                                    alert("請先選擇開始日期與結束日期");
                                                }
                                            };
                                        </script>
                                        <?php
                                            $_SESSION['history_record_name'] = '';
                                            $_SESSION['history_record_start_date'] = '';
                                            $_SESSION['history_record_end_date'] = '';
                                        ?>
                                    </div>
                                </div>
                            </div>
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
                    <form action="./history_record_insert_patient.php" method="post">
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