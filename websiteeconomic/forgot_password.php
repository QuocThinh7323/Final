<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$showAlert = false; 
$alertMessage = ""; 
$alertType = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];

  
  require_once('./db/conn.php');

 
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
   
    $token = bin2hex(random_bytes(50));

  
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    

   
    $reset_link = "http://localhost:81/websiteeconomic/reset_password.php?token=" . $token;

  
    $mail = new PHPMailer(true);

    try {
    
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'phanquocthinh004@gmail.com';
      $mail->Password   = 'rvkv tbip rcjb ztpb'; 
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

     
      $mail->setFrom('phanquocthinh004@gmail.com', 'Quoc Thinh');
      $mail->addAddress($email); 

     
      $email_template = '
                <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Identity - QT-Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007BFF;
            color: #ffffff;
            padding: 20px 0;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }
        .reset-link {
            display: inline-block;
            margin: 30px 0;
            padding: 12px 20px;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s;
        }
        .reset-link:hover {
            background-color: #0056b3;
        }
        .center-button {
            text-align: center; /* Canh giữa nút */
        }
        .footer {
            text-align: center;
            color: #888888;
            font-size: 12px;
            padding-top: 10px;
            border-top: 1px solid #dddddd;
            margin-top: 20px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            Verify Your Identity - QT-Store
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We detected an unusual sign-in attempt to your QT-Store account. If this was you, please click the link below to verify your identity and complete your sign-in:</p>
            
            <div class="center-button"> 
                <a href="' . $reset_link . '" class="reset-link">Verify Your Identity</a>
            </div>
            
            <p>(Note: This link will expire 10 minutes after it was sent.)</p>
            <p>If you did not request this, please <a href=" "http://localhost:81/websiteeconomic/reset_password.php?token=" ">reset your password</a> immediately.</p>
            <p>For any further assistance, feel free to contact our <a href="https://your-website.com/support">Support Team</a>.</p>
        </div>
        <div class="footer">
            <p>Thank you for using QT-Store.</p>
            <p>&copy; 2024 QT-Store. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

            ';

    
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset Request';
      $mail->Body    = $email_template;

      $mail->send();

    
      $showAlert = true;
      $alertMessage = 'A password reset link has been sent to your email.';
      $alertType = 'success';
    } catch (Exception $e) {
      $showAlert = true;
      $alertMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      $alertType = 'error';
    }
  } else {
    $showAlert = true;
    $alertMessage = "No user found with that email.";
    $alertType = 'error';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<link rel="icon" href="./img/product/logomain2.png" type="image/x-icon">

<head>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

  <!-- Password Reset Form -->
  <section class="bg-light p-3 p-md-4 p-xl-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-xxl-11">
          <div class="card border-light-subtle shadow-sm">
            <div class="row g-0">
              <div class="col-12 col-md-6">
                <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="img/hero/forgotpassword.png" alt="Password Reset">
              </div>
              <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                <div class="col-12 col-lg-11 col-xl-10">
                  <div class="card-body p-3 p-md-4 p-xl-5">
                    <div class="row">
                      <div class="col-12">
                        <div class="mb-5">
                          <h2 class="h4 text-center">Password Reset</h2>
                          <h3 class="fs-6 fw-normal text-secondary text-center m-0">Provide the email address associated with your account to recover your password.</h3>
                        </div>
                      </div>
                    </div>
                    <form action="forgot_password.php" method="POST">
                      <div class="row gy-3 overflow-hidden">
                        <div class="col-12">
                          <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                            <label for="email" class="form-label">Email</label>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                          </div>
                        </div>
                      </div>
                    </form>
                    <!-- <p class="text-center text-muted mt-3 mb-0">Don't have an account? <a href="./login.php">Sign Up</a>.</p> -->
                    <p class="text-center text-muted mt-3 mb-0">Back to sign in here <a href="./login.php"> Sign In</a>.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php if ($showAlert): ?>
    <script>
      Swal.fire({
        title: '<?php echo $alertMessage; ?>',
        icon: '<?php echo $alertType; ?>',
        confirmButtonText: 'OK'
      }).then((result) => {
  
        if (result.isConfirmed) {
          window.location.href = "login.php"; 
        }
      });
    </script>
  <?php endif; ?>

</body>

</html>