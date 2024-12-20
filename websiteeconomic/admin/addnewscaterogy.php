<?php




require('../db/conn.php');


$name = $_POST['name'];
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));



$sql_str = "INSERT INTO `newscategories` (`name`, `slug`,  `status`) VALUES 
    ( '$name', 
    '$slug', 
    'Active');";

// echo $sql_str; exit;


mysqli_query($conn, $sql_str);


header("location: listnewscats.php");
?>