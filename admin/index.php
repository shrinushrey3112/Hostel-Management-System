<?php
session_start();
include('includes/config.php');
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$stmt = $mysqli->prepare("SELECT username,email,password,id FROM admin WHERE (userName=?|| email=?) and password=? ");
	$stmt->bind_param('sss', $username, $username, $password);
	$stmt->execute();
	$stmt->bind_result($username, $username, $password, $id);
	$rs = $stmt->fetch();
	$_SESSION['id'] = $id;
	$uip = $_SERVER['REMOTE_ADDR'];
	$ldate = date('d/m/Y h:i:s', time());
	if ($rs) {
		header("location:admin-profile.php");
	} else {
		echo "<script>alert('Invalid Username/Email or password');</script>";
	}
}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Admin login</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
	<style>
		.btn-primary {
			font-family: 'Arial', sans-serif;
			font-size: 22px;
			height: 50px;
			border-radius: 17px;
		}

		.login-form {
			margin-top: 170px;
			font-size: 38px;
		}


		.large-heading {
			font-size: 58px;
			font-weight: bold;
			margin-top: 40px;
			color: #fff;
			text-shadow: 7px 7px 4px rgba(0, 0, 0, 0.5);
			white-space: nowrap;
			margin-left: -90px;
		}

		.larger-input {
			font-size: 19px;
			padding: 15px;
			height: 50px;
			width: 100%;
			box-sizing: border-box;
		}

		.form-control {
			border-radius: 17px;
			border: 1px solid #ccc;
		}

		.custom-font {
			font-family: 'Ubuntu', sans-serif;
		}
	</style>

	<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@700&display=swap" rel="stylesheet">

</head>

<body>

	<div class="login-page bk-img" style="background-image: url(img/login-bg.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center large-heading text-light custom-font">Hostel Management System</h1>
						<div class="login-form">
							<div class="col-md-8 col-md-offset-2">

								<form action="" class="mt" method="post">
									<input type="text" placeholder="Username" name="username"
										class="form-control mb larger-input">
									<input type="password" placeholder="Password" name="password"
										class="form-control mb larger-input">


									<input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</body>

</html>