<?php
    function lineBroadcast($text, $get_user_id){//U8fb34ed477bfbc93bf28e7eef500426f
        $channelToken = 'p1e9wbo/z9vYUoMWfnLBvewNbzub2duAdG5E6ncIsN8MIeDar3U7Mve1YbkbrtCapYP0eGKfLFW3c18N39Ufe9G4Mv6PtqwbbMIqU0GJVOvgEflRe3zzm4mb+8tRb8QCLCzGykSYx2bPuhEI0Z4nmgdB04t89/1O/w1cDnyilFU=';
        $headers = [
            'Authorization: Bearer ' . $channelToken,
            'Content-Type: application/json; charset=utf-8',
        ];
        $contentsArray = array(
            "type" => "bubble",
            "header" => array(
                "type" => "box",
                "layout" => "vertical",
                "contents" => array(
                    array(
                        'type' => 'text',
                        'text' => '您的密碼已變更為以下密碼',
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => ' ',
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => $text,
                        "wrap" => true
                    ),
                    array(
                        'type' => 'text',
                        'text' => ' ',
                        "wrap" => true
                    )
                )
            ),
            "size" => "kilo"
        );
        $post = [
            'to' => $get_user_id,
            "messages" => array(
                array(
                    'type' => 'flex',
                    'altText' => '您的密碼已變更為預設密碼。',
                    'contents' => $contentsArray
                )
            )
        ];
        $url = 'https://api.line.me/v2/bot/message/push';
        $post = json_encode($post);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $options = [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_POSTFIELDS => $post,
        ];
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
    }
    if(!isset($_SESSION))
    {
        session_start();
    }//判斷session是否已啟動
    if((!empty($_SESSION['check_word'])) && (!empty($_POST['checkword']))){
        if($_SESSION['check_word'] == $_POST['checkword'])
        {    
            $_SESSION['check_word'] = ''; //比對正確後，清空將check_word值
			header('content-Type: text/html; charset=utf-8');

			//找到使用者的信箱
			function get_mysql(){return new mysqli('localhost', 'root', '', 'verysad');}
			$mysqli = get_mysql();
			mysqli_set_charset($mysqli, "utf8");
			$phone = $_POST['phone'];
			$sql = "select LINE_userId, account.phone from account, patient where account.phone = '$phone' and patient.patientTel = '$phone'";
			$sql_reset_pwd = "UPDATE account SET account.password='$phone' ";
			//echo $sql;
			//echo$sql_reset_pwd;
			$result = $mysqli->query($sql);
			$row=mysqli_num_rows($result);
			if ($row == 0){
				echo "<script>alert('查無此電話號碼');</script>";
				echo "<script>setTimeout(function(){window.location.href='./forget.php';},1000);</script>";
			}else if($row == 1){
				$result_reset_pwd = $mysqli->query($sql_reset_pwd);
			}
			while ($catch = $result->fetch_assoc()){
				$LINE_userId=$catch['LINE_userId'];
				$new_pwd=$catch['phone'];
				//echo $LINE_userId;
				//echo $new_pwd;
				//寄密碼給使用者
				echo "<script>alert('已寄送密碼至您的LINE帳號中');</script>";
            	echo "<script>setTimeout(function(){window.location.href='./login.php';},1000);</script>";
			}
            lineBroadcast($new_pwd, $LINE_userId);
        }else
        {
            echo "
                <script>
                    alert('驗證碼輸入錯誤');
                </script>
                ";
            echo "
                <script>
                    setTimeout(function(){window.location.href='./forget.php';},1000);
                </script>
                ";//如果錯誤使用js 1秒後跳轉到忘記密碼頁面重試;
        }
    }else
    {
        echo "
                <script>
                    alert('未輸入驗證碼，請重新輸入一次!');
                </script>
                ";
            echo "
                <script>
                    setTimeout(function(){window.location.href='./forget.php';},1000);
                </script>
                ";//如果錯誤使用js 1秒後跳轉到忘記密碼頁面重試;
    }	
?>