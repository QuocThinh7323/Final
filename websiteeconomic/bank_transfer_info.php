<?php
session_start();
require_once('./db/conn.php');


$payment_method = isset($_GET['method']) ? $_GET['method'] : 'bank_transfer';


$totalcheckout = isset($_GET['total']) ? floatval($_GET['total']) : 0;
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;


if ($order_id <= 0) {
    header("Location: checkout.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = false;

    if ($payment_method === 'bank_transfer') {
     
        $bank_name = $_POST['bank_name'] ?? '';
        $account_number = $_POST['account_number'] ?? '';
        $transfer_date = $_POST['transfer_date'] ?? '';

       
        $success = true; 
    } elseif ($payment_method === 'card_payment') {
       
        $cardholder_name = $_POST['cardholder_name'] ?? '';
        $card_number = $_POST['card_number'] ?? '';
        $expiry_date = $_POST['expiry_date'] ?? '';

        $success = true; 
    }

    
    if ($success) {
        header("Location: thankyou.php?order_id=$order_id&method=$payment_method");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Information</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
        }

        .payment-header {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h2 class="payment-header">
        <?php echo ucfirst(str_replace('_', ' ', $payment_method)); ?> Information
    </h2>

    <form action="bank_transfer_info.php?method=<?php echo $payment_method; ?>&order_id=<?php echo $order_id; ?>&total=<?php echo $totalcheckout; ?>" method="POST">
        <?php if ($payment_method === 'bank_transfer'): ?>
            <!-- Form Bank Transfer -->
            <div class="form-group">
                <label for="bank_name">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" required>
            </div>
            <div class="form-group">
                <label for="account_number">Account Number</label>
                <input type="text" id="account_number" name="account_number" required>
            </div>
            <div class="form-group">
                <label for="transfer_date">Transfer Date</label>
                <input type="date" id="transfer_date" name="transfer_date" required>
            </div>

        <?php elseif ($payment_method === 'card_payment'): ?>
            <!-- Form Card Payment -->
            <div class="form-group">
                <label for="cardholder_name">Card Holder Name</label>
                <input type="text" id="cardholder_name" name="cardholder_name" required>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" maxlength="16" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date (MM/YY)</label>
                <input type="text" id="expiry_date" name="expiry_date" required>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn-submit">Submit</button>
    </form>
</div>

</body>
</html>
