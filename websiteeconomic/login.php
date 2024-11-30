<!DOCTYPE html>
<html lang="en">
<?php
require('./db/conn.php');
?>

<head>
	<meta charset="UTF-8">
	<meta name="description" content="QT Store">
	<meta name="keywords" content="QT Store, unica, creative, html">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>QT Store</title>

	<!-- Favicon -->
	<link rel="icon" href="./img/product/logomain2.png" type="image/x-icon">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="css/logintest.css" />

<body>

	<?php
	require('./db/conn.php'); // Bao gồm kết nối cơ sở dữ liệu

	function json_response($status, $message)
	{
		return json_encode(['status' => $status, 'message' => $message]);
	}

	function redirectBasedOnRole($role)
	{
		switch ($role) {
			case 'Admin':
				return 'admin/index.php';
			case 'User':
				return 'index.php';
			default:
				return 'admin/index.php';
		}
	}


	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Đăng ký người dùng
		if (isset($_POST['signup'])) {
			$username = $_POST['username'];
			$fullname = $_POST['fullname'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$confirmPassword = $_POST['confirm_password'];
			$phone_number = $_POST['phone_number'];



			// Validate password and confirm password
			if ($password !== $confirmPassword) {
				echo "<script>
					Swal.fire({
						icon: 'error',
						title: 'Registration Failed',
						text: 'Passwords do not match.',
						confirmButtonText: 'OK'
					}).then(function() {
						window.location.href = 'login.php'; // Reload the page after user confirms
					});
				  </script>";
			} else {
				// Hash the password
				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

				// Fetch the role_id for the role name 'User'
				$roleName = 'User'; // Change this as needed
				$stmtRole = $conn->prepare("SELECT role_id FROM roles WHERE role_name = ?");
				$stmtRole->bind_param("s", $roleName);
				$stmtRole->execute();
				$stmtRole->bind_result($roleId);
				$stmtRole->fetch();
				$stmtRole->close();

				// Check if role_id was found
				if (!$roleId) {
					echo "<script>
					Swal.fire({
						title: 'Registration Failed',
						html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/fluency/48/error.png\" alt=\"error\" /></div>Passwords do not match.',
						confirmButtonText: 'OK'
					}).then(function() {
						window.location.href = 'login.php'; // Reload the page after user confirms
					});
				</script>";
				} else {
					// Chuẩn bị câu truy vấn INSERT
					$stmt = $conn->prepare("INSERT INTO users (username, full_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)");
					$stmt->bind_param("sssss", $username, $fullname, $email, $hashedPassword, $phone_number);

					// Thực thi câu truy vấn
					if ($stmt->execute()) {
						$newUserId = $stmt->insert_id;

						// Insert user_role
						$stmtUserRole = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
						$stmtUserRole->bind_param("ii", $newUserId, $roleId);
						$stmtUserRole->execute();
						$stmtUserRole->close();

						echo "<script>
									Swal.fire({
										title: 'Registration Successful',
										html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/color/48/checked--v1.png\" alt=\"success\" /></div>New record created successfully.',
										confirmButtonText: 'OK'
									}).then(function() {
										window.location.href = 'login.php'; // Redirect to login page after user confirms
									});
							</script>";
					} else {
						$error_message = $stmt->error;
						echo "<script>
							Swal.fire({
								title: 'Registration Failed',
									html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/fluency/48/error.png\" alt=\"error\" /></div>Passwords do not match.',
						confirmButtonText: 'OK'
								text: 'Error: " . addslashes($error_message) . "',
								confirmButtonText: 'OK'
							}).then(function() {
								window.location.href = 'login.php'; // Reload the page after user confirms
							});
						  </script>";
					}

					// Đóng câu lệnh
					$stmt->close();
				}
			}
		}
	}
	// Xử lý đăng nhập người dùng
	if (isset($_POST['signin'])) {
		$username_email = $_POST['username_email'];
		$password = $_POST['password'];

		// Xử lý câu truy vấn để lấy thông tin người dùng và vai trò
		$sql = "SELECT u.user_id, u.username, u.password, r.role_name 
                FROM users u
                LEFT JOIN user_roles ur ON u.user_id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.role_id
                WHERE u.username = ? OR u.email = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $username_email, $username_email);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if (password_verify($password, $user['password'])) {
				// Lưu thông tin người dùng vào session
				session_start(); // Đảm bảo session đã được khởi tạo
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['role'] = $user['role_name'];

				// Điều hướng người dùng dựa trên vai trò
				echo "<script>
                        Swal.fire({
                            title: 'Login Successful',
							html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/color/48/checked--v1.png\" alt=\"success\" /></div>Welcome back, " . addslashes($user['username']) . "!',
                            timer: 2000,
							showConfirmButton: false
                        }).then(function() {
                            window.location.href = '" . redirectBasedOnRole($user['role_name']) . "';
                        });
                      </script>";
				exit();
			} else {
				// Thông báo lỗi mật khẩu không đúng bằng SweetAlert
				echo "<script>
						Swal.fire({
							title: 'Invalid Password',
							html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/fluency/48/error.png\" alt=\"error\" /></div>Please check your password and try again.',
							confirmButtonText: 'OK'
						}).then(function() {
							window.location.href = 'login.php'; // Reload or redirect to login page
						});
					</script>";
			}
		} else {
			// Thông báo lỗi người dùng không tồn tại bằng SweetAlert
			echo "<script>
					Swal.fire({
						title: 'User not found',
						html: '<div><img width=\"48\" height=\"48\" src=\"https://img.icons8.com/fluency/48/error.png\" alt=\"error\" /></div>No user found with that username or email.',
						confirmButtonText: 'OK'
					}).then(function() {
						window.location.href = 'login.php'; // Reload or redirect to login page
					});
				</script>";
		}
	}
	?>

	<div class="container"
		id="container">
		<div class="form-container sign-up-container">
			<form action="" method="POST" class="sign-up-form">
				<h1>Create Account</h1>
				<span>or use your email for registration</span>
				<input type="text" placeholder="Username" name="username" required />
				<input type="text" placeholder="Full Name" name="fullname" required />
				<input type="email" placeholder="Email" name="email" required />
				<input type="password" placeholder="Password" name="password" required />
				<input type="password" placeholder="Confirm Password" name="confirm_password" required />
				<input type="text" placeholder="Phone Number" name="phone_number" required />
				<button type="submit" name="signup">Sign Up</button>
			</form>
		</div>
		<div class="form-container sign-in-container">
			<form action="" method="POST" class="sign-in-form">
				<h1>Sign in</h1>
				<span>or use your account</span>
				<input type="email" placeholder="Email or Username" name="username_email" required />
				<input type="password" placeholder="Password" name="password" required />
				<a href="forgot_password.php">Forgot your password?</a>
				<button type="submit" name="signin">Sign In</button>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1>Welcome Back!</h1>
					<p>Please log in with your personal information to stay in touch with us</p>
					<button class="ghost" id="signIn">Sign In</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h1>Hello, Friend!</h1>
					<p>Start your journey with us by entering your personal information.</p>
					<button class="ghost" id="signUp">Sign Up</button>
				</div>
			</div>
		</div>
	</div>
	<script src="js/logintest.js"></script>
</body>