<?php
  # 連線資料庫
  $server = "localhost"; //資料庫位置
  $user = "root"; //資料庫管理者帳號
  $password=""; //資料庫管理者密碼 
  $name = "sad"; //資料庫名稱 
  $link =mysqli_connect($server,$user,$password,$name); //對資料庫連線
  //如果資料庫連線不成功顯示"Can not connect to the database"
  if(!$link) 
    die("Can not connect to the database");

  //KJ連線資料庫
  //function get_mysql(){return new mysqli('localhost', 'root', '', 'sad');}
?>