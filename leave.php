<?php
    date_default_timezone_set("Asia/Taipei");
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
        <script src="./js/jquery3.6.0.js"></script>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <title>復健治療預約系統</title>
        <link rel="shortcut icon" href="./image/csh_icon.ico" type="image/x-icon">
    </head>
    <script type="text/javascript">
        // function clean_session()
        // {
        // document.getElementById('checkbox_leave').value='';
        // document.getElementById('checkbox_business').value='';
        // document.getElementById('start_date').value='';
        // document.getElementById('end_date').value='';
        // }
        // function main_checker()
        // {
        //     var checkbox_personal = $("#checkbox_pesonal").val();
        //     var checkbox_sick = $("#checkbox_sick").val();
        //     var checkbox_business = $("#checkbox_business").val();
        //     var start = $("#start_date").val();
        //     var end = $("#end_date").val();


        //     var str_start = start.toString().split("-");
        //     var new_start = new Date(str_start[0],str_start[1],str_start[2]);
        //     var str_end = end.toString().split("-");
        //     var new_end = new Date(str_end[0],str_end[1],str_end[2]);
        //     var dayCount =(new_end - new_start);
        //     if(start!="" & end!="")
        //     {
        //         if(dayCount>=0)
        //         {
        //         document.leave_search.submit();
        //         }
        //         else
        //         {
        //             alert("請選擇正確的時間範圍");
        //             document.getElementById('checkbox_personal').value='';
        //             document.getElementById('checkbox_sick').value='';
        //             document.getElementById('checkbox_business').value='';
        //             document.getElementById('start_date').value='';
        //             document.getElementById('end_date').value='';
        //         }
        //     }
        //     else if(start=="" & end!="")
        //     {
        //         alert("請輸入開始日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        //     else if(start!="" & end=="")
        //     {
        //         alert("請輸入結束日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        //     else
        //     {
        //         alert("請輸入開始日期與結束日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        // }
        // function new_checker()
        // {
        //     var checkbox_person = $("#checkbox_person").val();
        //     var checkbox_sick = $("#checkbox_sick").val();
        //     var checkbox_business = $("#checkbox_business").val();
        //     var start = $("#start_date").val();
        //     var end = $("#end_date").val();


        //     var str_start = start.toString().split("-");
        //     var new_start = new Date(str_start[0],str_start[1],str_start[2]);
        //     var str_end = end.toString().split("-");
        //     var new_end = new Date(str_end[0],str_end[1],str_end[2]);
        //     var dayCount =(new_end - new_start);
        //     if(start!="" & end!="")
        //     {
        //         if(dayCount>=0)
        //         {
        //         document.leave_search.submit();
        //         }
        //         else
        //         {
        //             alert("請選擇正確的時間範圍");
        //             document.getElementById('checkbox_personal').value='';
        //             document.getElementById('checkbox_sick').value='';
        //             document.getElementById('checkbox_business').value='';
        //             document.getElementById('start_date').value='';
        //             document.getElementById('end_date').value='';
        //         }
        //     }
        //     else if(start=="" & end!="")
        //     {
        //         alert("請輸入開始日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        //     else if(start!="" & end=="")
        //     {
        //         alert("請輸入結束日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        //     else
        //     {
        //         alert("請輸入開始日期與結束日期");
        //         document.getElementById('checkbox_personal').value='';
        //         document.getElementById('checkbox_sick').value='';
        //         document.getElementById('checkbox_business').value='';
        //         document.getElementById('start_date').value='';
        //         document.getElementById('end_date').value='';
        //     }
        // }
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
                                <b style="font-family:'Times New Roman','標楷體';">請假</b>
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
                                                    window.location.href="leave.php" 
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
                            <form action="./leave_session.php" method="post" id="leave_search" name="leave_search">
                                <div class="col history_record_search_font" >
                                    <select name="reasontype"style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                        <option value="">請選擇假別</option>
                                        <?php session_start(); echo typeOptions($_SESSION['reasontype']); ?>
                                    </select>
                                    &nbsp;&nbsp;
                                    開始時間&nbsp;
                                    <!--datetimepicker-->
                                    <input type="date" id="history_record_start_date" name="start_date" value="<?php echo $_SESSION['start_date']; ?>"
                                        style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                    &nbsp;&nbsp;
                                    結束時間&nbsp;
                                    <!--datetimepicker-->
                                    <input type="date" id="history_record_end_date" name="end_date" value="<?php echo $_SESSION['end_date']; ?>"
                                        style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="submit" class="history_record_page_btn" onclick="main_checker()">查詢</button>
                            </form>
                                    <!--請假申請按鈕-->
                                    <button type="button" class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#new_Modal" 
                                        style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;float:right;">
                                    請假申請</button>                                  
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
                                        <th style="width:16%">請假時間 (起)</th>
                                        <th style="width:16%">請假時間 (迄)</th>
                                        <th style="width:11%">請假日數</th>
                                        <th style="width:17%">已接受/已預約</th>
                                        <th style="width:7%">類別</th>
                                        <th style="width:20%">事由說明</th>
                                        <th style="width:13%">修改</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    <?php
                                        $mysqli = get_mysql();

                                        $reasontype = $_SESSION['reasontype'];
                                        $start_date = $_SESSION['start_date'];
                                        $end_date = $_SESSION['end_date'];

                                        $sendbackarray = array();

                                        $today = date('Y-m-d');

                                        if ($start_date != '' && $end_date != ''){
                                            $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                (`dateStart` between '".$start_date."' and '".$end_date."') AND
                                                (`dateEnd` between '".$start_date."' and '".$end_date."');";
                                            if ($reasontype != ''){
                                                $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                    (`dateStart` between '".$start_date."' and '".$end_date."') AND
                                                    (`dateEnd` between '".$start_date."' and '".$end_date."') AND 
                                                    `type` = '".$reasontype."';";
                                            }
                                        }else if ($start_date != ''){
                                            $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                `dateStart` >= '".$start_date."';";
                                            if ($reasontype != ''){
                                                $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                    `dateStart` >= '".$start_date."' AND 
                                                    `type` = '".$reasontype."';";
                                            }
                                        }else if ($end_date != ''){
                                            $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                `dateStart` <= '".$end_date."';";
                                            if ($reasontype != ''){
                                                $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                    `dateStart` <= '".$end_date."' AND 
                                                    `type` = '".$reasontype."';";
                                            }
                                        }else{
                                            $sql = "SELECT * FROM `leavebustrip` WHERE 
                                            `dateStart` >= '".$today."';";
                                            if ($reasontype != ''){
                                                $sql = "SELECT * FROM `leavebustrip` WHERE 
                                                    `dateStart` >= '".$today."' AND 
                                                    `type` = '".$reasontype."';";
                                            }
                                        }
                                        // echo $sql;
                                        $result = $mysqli->query($sql);
                                        while ($catch = $result->fetch_assoc()){
                                            echo '<tr style="border-color:rgb(165,165,165); border-width: 1px;">';
                                    ?>
                                        <td><?php echo $catch['dateStart']; ?> <?php echo id2str($catch['pTimeStart'], 'start'); ?></td>
                                        <td><?php echo $catch['dateEnd']; ?> <?php echo id2str($catch['pTimeEnd'], 'end'); ?></td>
                                        <td><?php echo ((strtotime($catch['dateEnd']) - strtotime($catch['dateStart']))/86400)+1; ?></td>
                                        <td><?php echo $catch['get_counts'].'/'.$catch['all_counts']; ?>
                                            <button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#check_Modal_<?php echo $catch['leaveBusTripId']; ?>" 
                                                style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                                查看</button>
                                        </td>
                                        <td><?php echo type2str($catch['type']); ?></td>
                                        <td>
                                            <?php 
                                                if($catch['reason'] == ''){
                                                    echo '無';
                                                }else{
                                                    echo $catch['reason'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#delete_Modal_<?php echo $catch['leaveBusTripId']; ?>" 
                                            style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                            刪除</button>
                                            <button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#config_Modal_<?php echo $catch['leaveBusTripId']; ?>" 
                                            style="font-family:DFKai-sb; padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">
                                            編輯</button>
                                            <?php
                                                array_push($sendbackarray, $catch['leaveBusTripId']);
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
                <?php 
                    mess_box_maker($sendbackarray); 
                ?>
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
                    <form action="./leave_insert_patient.php" method="post">
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
    <div class="modal fade" id="new_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"style="text-align:center;">新增請假紀錄</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./leave_session.php" method="post" id="leave_search" name="leave_search">
                        <div class="container-fluid">                                            
                            <td>
                                <br>
                                請假日期(起)
                                <!--datetimepicker-->
                                <td class="col" style="justify-content:center;"><!-- new_start_date -->
                                    <input type="date" id="history_record_start_date" name="new_start_date" style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                </td>
                                請假時間(起)
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <select name="new_start_list" style="width:90px">
                                    <option value="">選擇時間</option>
                                        <?php pTimeOptions('start', ''); ?><!-- new_start_list -->
                                    </select>
                                </td>                                                    
                            </td>
                            <td>
                            <br>
                                請假日期(迄)
                                <!--datetimepicker-->
                                <td class="col" style="justify-content:center;"><!-- new_end_date -->
                                    <input type="date" id="history_record_end_date" name="new_end_date" style="font-size:12px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                </td>
                                請假時間(迄)
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <select name="new_end_list" style="width:90px">
                                        <option value="">選擇時間</option>
                                        <?php pTimeOptions('end', ''); ?><!-- new_end_list -->
                                    </select>
                                </td>
                            </td>
                            <br>
                            <br>
                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-family:'Times New Roman','標楷體';">
                                <div class="col">
                                假別
                                <select name="new_reasontype"style="font-size:15px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                    <option value="">請選擇假別</option>
                                    <?php echo typeOptions(''); ?>
                                </select>
                                </div>
                            </div>
                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-family:'Times New Roman','標楷體';">
                                <div class="col">
                                    事由說明<input name="reason">
                                </div>
                            </div>
                            <br>
                            <input name="from_new" type="hidden" value="true" />
                            <input name="from_update" type="hidden" value="" />
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="new_checker(); check(reason.value);">存檔</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
function get_mysql(){
    $mysqli = new mysqli('localhost', 'root', '', 'verysad');
    mysqli_set_charset($mysqli, "utf8");
    return $mysqli;
}
function pTimeOptions($startorend, $selected_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `ptime` ORDER BY `ptime`.`pTimeId` ASC;";
    $result = $mysqli->query($sql);
    while($catch = $result->fetch_assoc()){
        if ($selected_id == $catch['pTimeId']){
            if ($startorend == 'start'){
                echo '<option value="'.$catch['pTimeId'].'" selected>'.explode(":", $catch['pTimeStart'])[0].':00</option>';
            }else if ($startorend == 'end'){
                echo '<option value="'.$catch['pTimeId'].'" selected>'.explode(":", $catch['pTimeEnd'])[0].':00</option>';
            }
        }else{
            if ($startorend == 'start'){
                echo '<option value="'.$catch['pTimeId'].'">'.explode(":", $catch['pTimeStart'])[0].':00</option>';
            }else if ($startorend == 'end'){
                echo '<option value="'.$catch['pTimeId'].'">'.explode(":", $catch['pTimeEnd'])[0].':00</option>';
            }
        }
    }
}
function id2str($get_id, $startorend){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM ptime;";
    $result = $mysqli->query($sql);
    while($catch = $result->fetch_assoc()){
        if ($catch['pTimeId'] == $get_id && $startorend == 'start'){
            return explode(":", $catch['pTimeStart'])[0].':00';
        }else if ($catch['pTimeId'] == $get_id && $startorend == 'end'){
            return explode(":", $catch['pTimeEnd'])[0].':00';
        }
    }
}
function true2on($get_str){
    if ($get_str == 'true'){
        return 'checked';
    }else if ($get_str == 'false'){
        return '';
    }
}
function getornot2str($get_getornot){
    if ($get_getornot == 'true'){
        return '已回覆';
    }else if ($get_getornot == 'false'){
        return '尚未回覆';
    }
}
function type2str($get){
    if($get == 'personal'){
        return '事假';
    }else if($get == 'sick'){
        return '病假';
    }else if($get == 'business'){
        return '公出';
    }
}
function typeOptions($selected){
    if($selected == 'personal'){
        return '<option value="personal" name="new_checkbox_person" selected>事假</option>
            <option value="sick" name="new_checkbox_sick">病假</option>
            <option value="business" name="new_checkbox_business">公出</option>';

    }else if($selected == 'sick'){
        return '<option value="personal" name="new_checkbox_person">事假</option>
            <option value="sick" name="new_checkbox_sick" selected>病假</option>
            <option value="business" name="new_checkbox_business">公出</option>';

    }else if($selected == 'business'){
        return '<option value="personal" name="new_checkbox_person">事假</option>
            <option value="sick" name="new_checkbox_sick">病假</option>
            <option value="business" name="new_checkbox_business" selected>公出</option>';

    }else{
        return '<option value="personal" name="new_checkbox_person">事假</option>
            <option value="sick" name="new_checkbox_sick">病假</option>
            <option value="business" name="new_checkbox_business">公出</option>';
    }
}
function from_canceledrecorder($get_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `canceledrecorder`, `patient`, `reserve` WHERE `canceledrecorder`.`patientId`=`patient`.`patientId` AND `canceledrecorder`.`reserveId`=`reserve`.`reserveId`
    AND `leaveBusTripId`='".$get_id."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ($catch['patientName'] != ''){
        $sql = "SELECT * FROM `canceledrecorder`, `patient`, `reserve` WHERE `canceledrecorder`.`patientId`=`patient`.`patientId` AND `canceledrecorder`.`reserveId`=`reserve`.`reserveId`
        AND `leaveBusTripId`='".$get_id."';";
        $result = $mysqli->query($sql);
        echo '<table align="center">
        <thead>
            <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;" style="border-color:rgb(165,165,165); border-width: 1px;">
                <th>患者姓名</th>
                <th>患者連絡電話</th>
                <th>衝突日期</th>
                <th>衝突時段</th>
                <th>是否回覆</th>
            </tr>
        <thead>
        <tbody>';
        while($catch = $result->fetch_assoc()){
            echo '<tr style="border-color:rgb(165,165,165); border-width: 1px;">
                <td  align="center" valign="middle" >'.$catch['patientName'].'</td>   
                <td>'.$catch['patientTel'].'</td>
                <td>'.$catch['date'].'</td>
                <td>'.id2str($catch['pTimeId'], 'start').'~'.id2str($catch['pTimeId'], 'end').'</td>';
            if ($catch['getornot'] == 'true'){
                echo '<td><font color="green">'.getornot2str($catch['getornot']).'</font></td>';
            }else{
                echo '<td><font color="red">'.getornot2str($catch['getornot']).'</font></td>';
            }
            echo '</tr>';
        }
        echo '</tbody>
        </table>';
    }else{
        echo '<br><center><b><font size="5">無衝突的預約</font></b></center></br>';
    }
}
function mess_box_maker($get_array){
    for ($i=0; $i<count($get_array); $i++){
        mess_box($get_array[$i]);
    }
}
function mess_box($get_id){
    $mysqli = get_mysql();
    $sql = "SELECT * FROM `leavebustrip` WHERE `leaveBusTripId`='".$get_id."';";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();

    $get_start_date = $catch['dateStart'];
    $get_start_list = $catch['pTimeStart'];
    $get_end_date = $catch['dateEnd'];
    $get_end_list = $catch['pTimeEnd'];
    $get_type = $catch['type'];
    $get_reason = $catch['reason'];    
    
    echo '<div class="modal fade" id="config_Modal_'.$get_id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"style="text-align:center;">編輯請假紀錄</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./leave_session.php" method="post" id="leave_search" name="leave_search">
                        <div class="container-fluid">                                            
                            <td>
                                <br>
                                請假日期(起)
                                <!--datetimepicker-->
                                <td class="col" style="justify-content:center;"><!-- new_start_date -->
                                    <input type="date" id="history_record_start_date" name="new_start_date" style="font-size:12px; font-family:\'Times New Roman\',\'標楷體\'; padding: 0px;" value="'.$get_start_date.'">
                                </td>   
                                請假時間(起)
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <select name="new_start_list" style="width:90px"><!-- new_start_list -->
                                    <option value="">選擇時間</option>
                                        '; echo pTimeOptions("start", $get_start_list); echo '
                                    </select>
                                </td>                                                    
                            </td>
                            <td>
                            <br>
                                請假日期(迄)
                                <!--datetimepicker-->
                                <td class="col" style="justify-content:center;"><!-- new_end_date -->
                                    <input type="date" id="history_record_end_date" name="new_end_date" style="font-size:12px; font-family:\'Times New Roman\',\'標楷體\'; padding: 0px;" value="'.$get_end_date.'">
                                </td>
                                請假時間(迄)
                                <td class="col" style="justify-content:center;margin-top: 12px; margin-bottom: 15px">
                                    <select name="new_end_list" style="width:90px"><!-- new_end_list -->
                                        <option value="">選擇時間</option>
                                        '; echo pTimeOptions("end", $get_end_list); echo '
                                    </select>
                                </td>                                                        
                            </td>
                            <br>
                            <br>
                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-family:\'Times New Roman\',\'標楷體\';">
                                <div class="col">
                                    假別
                                    <select name="new_reasontype"style="font-size:15px; font-family:\'Times New Roman\',\'標楷體\'; padding: 0px;">
                                        <option value="">請選擇假別</option>
                                        '.typeOptions($get_type).'
                                    </select>
                                </div>
                            </div>
                            <div class="row" style=" margin-top: 10px; margin-bottom: 10px; font-family:\'Times New Roman\',\'標楷體\';">
                                <div class="col">
                                    事由說明<input name="reason" value="'.$get_reason.'">
                                </div>
                            </div>
                            <br>
                            <input name="from_new" type="hidden" value="true" />
                            <input name="from_update" type="hidden" value="'.$get_id.'" />
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" onclick="new_checker(); check(reason.value);">存檔</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    echo '<div class="modal fade" id="delete_Modal_'.$get_id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <br><center><b><font size="5">確定要刪除嗎</font></b></center></br>
                        <br><center>確定要將自<b>'.$get_start_date.' '.id2str($get_start_list, 'start').'到'.$get_end_date.' '.id2str($get_end_list, 'end').'</b>的'.type2str($catch['type']).'<font color="red">刪除</font>嗎?<center></br>
                    </div>
                </div>
                <div class="modal-footer">
                    <form method="post" action="./leave_session.php">
                        <input name="delete" type="hidden" value="'.$get_id.'" />
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary"  data-bs-dismiss="modal">確定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    echo '<div class="modal fade" id="check_Modal_'.$get_id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:DFKai-sb;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"style="text-align:center;">衝突預約名單</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container" >
                        <div class="row align-items-center">
                            <div class="col" style="text-align:center; font-family:"Times New Roman","標楷體";">
                                '; echo from_canceledrecorder($get_id); echo '
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">確定</button>
                </div>
            </div>
        </div>
    </div>';
}
?>
</html>