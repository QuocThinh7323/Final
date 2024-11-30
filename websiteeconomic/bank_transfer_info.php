<?php
// Get total amount from URL
$total = isset($_GET['total']) ? floatval($_GET['total']) : 0;

// Check if the order ID is present
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id == 0) {
    // Redirect back to checkout if no valid order ID
    header("Location: checkout.php");
    exit();
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Card Payment Interface</title>
         <!-- jQuery UI CSS -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <!-- jQuery and jQuery UI JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

            .payment-form {
                display: flex;
                flex-direction: column;
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

            .form-group input:focus {
                border-color: #007bff;
            }

            .payment-summary {
                font-size: 16px;
                margin-bottom: 20px;
                text-align: center;
                color: red;
                font-weight: bold;
            }

            .btn-container {
                display: flex;
                justify-content: space-between;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                color: white;
            }

            .btn-submit {
                background-color: #007bff;
            }

            .btn-cancel {
                background-color: #ccc;
                color: #333;
            }

            .btn:hover {
                opacity: 0.9;
            }
        </style>
    </head>

    <body>

    <div class="payment-container">
        <h2 class="payment-header">Card Payment</h2>

        <div class="payment-summary">
            Total Amount: $ <?php echo number_format($total, 0); ?> 
        </div>

        <form action="process_payment.php" method="POST" class="payment-form">
            <!-- Card Holder Name -->
            <div class="form-group">
                <label for="cardholder-name">Card Holder Name</label>
                <input type="text" id="cardholder-name" name="cardholder_name" required>
            </div>

            <!-- Card Number -->
            <div class="form-group">
                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" name="card_number" maxlength="16" required>
            </div>

            <!-- Expiry Date with Datepicker -->
            <div class="form-group">
                <label for="expiry-date">Expiry Date (MM/YY)</label>
                <input type="text" id="expiry-date" name="expiry_date" required>    
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-submit">Submit</button>
                <!-- <button type="button" class="btn btn-cancel" onclick="window.location.href='checkout.php'">Cancel</button> -->
            </div>
        </form>
    </div>

    <script>
        // Sử dụng jQuery UI Datepicker
        $(document).ready(function () {
            $('#expiry-date').datepicker({
                dateFormat: 'mm/yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,

                // Để chỉ chọn tháng và năm
                onClose: function(dateText, inst) { 
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val($.datepicker.formatDate('mm/yy', new Date(year, month, 1)));
                }
            });

            // Chỉ hiện thị tháng và năm trong trường datepicker
            $('#expiry-date').focus(function () {
                $(".ui-datepicker-calendar").hide();
            });
        });
    </script>

</body>


    </html>

<?php
    // require_once('components/footer.php');
?>