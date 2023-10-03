<?php
    session_start();
    $_SESSION['attendance_record_name'] = $_POST['attendance_record_name'];
    $_SESSION['attendance_record_start_date'] = $_POST['attendance_record_start_date'];
    $_SESSION['attendance_record_end_date'] = $_POST['attendance_record_end_date'];

    //echo $_SESSION['attendance_record_name'] ;
    echo '<br><br>';  
    //echo $_SESSION['attendance_record_start_date'];
    echo '<br><br>';  
    //echo $_SESSION['attendance_record_end_date'];

    header("Refresh:0;url=./attendance_record.php");
?>