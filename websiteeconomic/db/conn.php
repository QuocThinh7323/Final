<?php 
// db.php - Database connection file

// Define the connection variables for mysqli
$host = "localhost";
$dbname = "ecomerwebsite";
$username = "root";
$password = "";

// MySQLi Connection
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}
// echo "MySQLi Connected successfully";

// PDO Connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "PDO Connected successfully";
} catch (PDOException $e) {
    die("PDO Database connection failed: " . $e->getMessage());
}
?>