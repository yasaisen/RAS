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
                                <b style="font-family:'Times New Roman','標楷體';">編輯病人資料</b>
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
                                                    window.location.href="patient.php" 
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
                        $('#patient_table').DataTable(
                            {
                                language: 
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
                        <table id="patient_table" class="display patient_table" style="font-size: 24px;">
                                <thead>
                                    <tr style="background-color: rgb(165,165,165); border-color:rgb(165,165,165); border-width: 1px;">
                                        <th style="font-size:24px">姓名</th>
                                        <th style="font-size:24px">電話</th>
                                        <th style="font-size:24px">編輯</th>
                                    </tr>
                                <thead>
                                <tbody>
                                    <?php
                                        $mysqli = get_mysql();
                                        $sql = "SELECT * FROM `patient` ORDER BY patientId ASC;";
                                        mysqli_set_charset($mysqli,"utf8");
                                        $result = $mysqli->query($sql);
                                        $sendbackarray = array();
                                        while ($catch = $result->fetch_assoc()){
                                            echo '<tr style="border-color:rgb(165,165,165); border-width: 1px;">';
                                    ?>
                                        <td style="font-size:24px"><?php echo $catch['patientName']; ?></td>
                                        <td style="font-size:24px"><?php echo $catch['patientTel']; ?></td>
                                        <td>
                                            <button class="history_record_page_btn" data-bs-toggle="modal" data-bs-target="#patient_<?php echo $catch['patientId']; ?>" 
                                                    style="font-family:DFKai-sb; font-size:20px; border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250); color:black;  padding-top: 1px;padding-bottom: 1px;padding-right: 6px;padding-left: 6px;">
                                                編輯
                                            </button>
                                            <div class="modal fade" id="patient_<?php echo $catch['patientId']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-family:'Times New Roman','標楷體';">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel"style="text-align:center;">編輯病人資料</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="./patient_sql.php" method="post">
                                                                <div class="container-fluid">                                            
                                                                    <div class="row" style=" margin-top: 10px; margin-bottom: 10px;">
                                                                        <div class="col" style="font-size:18px;">病人姓名</div>
                                                                        <div class="col">
                                                                            <input type="text" placeholder="<?php echo $catch['patientName'];?>" name='changeName' 
                                                                                style="font-size:18px; font-family:'Times New Roman','標楷體'; padding: 0px;">
                                                                        </div>
                                                                    </div><br>
                                                                    <div class="row" style=" margin-top: 10px; margin-bottom: 10px;">
                                                                        <div class="col" style="font-size:18px;">病人連絡電話</div>
                                                                        <div class="col">
                                                                            <input type="text" placeholder="<?php echo $catch['patientTel'];?>" name='changeTel' maxlength="10" 
                                                                                style="font-size:18px; font-family:'Times New Roman','標楷體'; padding: 0px;"
                                                                                onkeyup="value=value.replace(/[^0-9]/g,'')" onpaste="value=value.replace(/[^0-9]/g,'')" oncontextmenu = "value=value.replace(/[^0-9]/g,'')">
                                                                        </div>
                                                                    </div><br>
                                                                    <br>
                                                                    <input name="patientId" id="patientId" type="hidden" value="<?php echo $catch['patientId'];?>">
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" 
                                                                            style="border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">取消</button>
                                                                        <button type="submit" class="btn btn-primary" 
                                                                            style="border: 2px solid; border-radius: 8px;background:white; border-color: rgb(186, 225, 250);color:black;">確定</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                    <form action="./patient_insert_patient.php" method="post">
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