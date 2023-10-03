<?php
function get_mysql(){
    $mysqli = new mysqli('localhost', 'root', '', 'verysad');
    mysqli_set_charset($mysqli, "utf8");
    return $mysqli;
}

$firstlook = $_POST['firstlook'] == 'on';
$patientName = $_POST['insertName'];
$patientTel = $_POST['insertPhone'];
$date = $_POST['insertDate'];
$pTimeId = $_POST['insertTime'];

function yoyaku($patientName, $patientTel, $date, $pTimeId){
    $mysqli = get_mysql();
    $sql = "SELECT `patientId` FROM `patient` WHERE `patientName` = '".$patientName."' AND `patientTel` = '".$patientTel."'; ";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    $patientId = $catch['patientId'];

    $sql = "SELECT 
            SUM(CASE WHEN `pTimeId`=1 THEN 1 ELSE 0 END) AS '1', 
            SUM(CASE WHEN `pTimeId`=2 THEN 1 ELSE 0 END) AS '2', 
            SUM(CASE WHEN `pTimeId`=3 THEN 1 ELSE 0 END) AS '3', 
            SUM(CASE WHEN `pTimeId`=4 THEN 1 ELSE 0 END) AS '4', 
            SUM(CASE WHEN `pTimeId`=5 THEN 1 ELSE 0 END) AS '5', 
            SUM(CASE WHEN `pTimeId`=6 THEN 1 ELSE 0 END) AS '6',
            SUM(CASE WHEN `pTimeId`=7 THEN 1 ELSE 0 END) AS '7'
            FROM `reserve` 
            WHERE (`state` = 'true' OR `state` = 'looked' OR `state` = 'unlook') AND `date` = '".$date."'
            GROUP BY `date`;";
    $result = $mysqli->query($sql);
    $catch = $result->fetch_assoc();
    if ((int)$catch[$pTimeId] >= 3){
        echo '<script>alert("該時段預約人數已達三人，請選擇其他時段") ; window.location="home.php"</script>';
    }else{
        $sql = "INSERT INTO `reserve` (`reserveId`, `patientId`, `date`, `pTimeId`, `state`) 
        VALUES (NULL, '".$patientId."', '".$date."', '".$pTimeId."', 'true');";
        $result = $mysqli->query($sql);
    }
    
}

if ($firstlook){
    $mysqli = get_mysql();
    $sql = "INSERT INTO `patient` (`patientId`, `patientName`, `patientTel`, `LINE_userId`, `state`, `registration`) 
        VALUES (NULL, '".$patientName."', '".$patientTel."', NULL, 'false', 'false');";
    $result = $mysqli->query($sql);
}
yoyaku($patientName, $patientTel, $date, $pTimeId);

header("Refresh:0;url=./leave.php");
?>