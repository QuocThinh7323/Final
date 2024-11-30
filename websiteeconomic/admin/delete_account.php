<?php
// delete_account.php - Deleting an account
require('../db/conn.php');

$user_id = $_GET['user_id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);

header('Location: account_management.php');
exit;
?>
