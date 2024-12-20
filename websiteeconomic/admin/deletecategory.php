<?php


$delid = $_GET['id'];


require('../db/conn.php');

$sql_str = "delete from categories where id=$delid";
mysqli_query($conn, $sql_str);


header("location: listcats.php");

