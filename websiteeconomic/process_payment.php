<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the posted form data
    $cardholder_name = $_POST['cardholder_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];

    // Here you would normally process the payment using a payment gateway API

    // For demonstration purposes, let's assume the payment was successful
    // You can customize this logic as needed

    // Redirect to the thank you page
    header("Location: thankyou.php");
    exit();
}
?>
