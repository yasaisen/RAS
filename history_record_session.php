<?php
    session_start();
    $_SESSION['history_record_name'] = $_POST['history_record_name'];
    $_SESSION['history_record_start_date'] = $_POST['history_record_start_date'];
    $_SESSION['history_record_end_date'] = $_POST['history_record_end_date'];

    //echo $_SESSION['history_record_name'] ;
    echo '<br><br>';  
    //echo $_SESSION['history_record_start_date'];
    echo '<br><br>';  
    //echo $_SESSION['history_record_end_date'];

    header("Refresh:0;url=./history_record.php");
?>