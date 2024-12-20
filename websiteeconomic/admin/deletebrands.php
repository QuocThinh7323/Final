<?php


$delid = $_GET['id'];


require('../db/conn.php');

$sql_str = "delete from brands where id=$delid";
mysqli_query($conn, $sql_str);


header("location: listbrands.php");

