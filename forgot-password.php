<?php
session_start();
include('includes/config.php');

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$contact = $_POST['contact'];

	$stmt = $mysqli->prepare("SELECT password FROM userregistration WHERE email = ? AND contactNo = ?");
	$stmt->bind_param('ss', $email, $contact);
	$stmt->execute();
	$stmt->bind_result($password);
	$rs = $stmt->fetch();

	if ($rs) {
		if (sendPasswordEmail($email, $password)) {
			echo "<script>alert('Check your email for your password.');</script>";
		} else {
			echo "<script>alert('Failed to send email. Please try again later.');</script>";
		}
	} else {
		echo "<script>alert('Invalid Email or Contact No.');</script>";
	}
}

function sendPasswordEmail($email, $password)
{
	$mail = new PHPMailer(true);

	try {
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'agc.hostel.wardem@gmail.com';
		$mail->Password = 'rxny puef ltlz tnnb';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;

		// Recipients
		$mail->setFrom('agc.hostel.wardem@gmail.com', 'AGC Hostel');
		$mail->addAddress($email);

		// Email content
		$mail->isHTML(true);
		$mail->Subject = 'Your Login Password';
		$mail->Body = "
            <p>Dear User,</p>
            <p>As per your request, here is your password:</p>
            <p><strong>$password</strong></p>
            <p>We recommend changing your password immediately after logging in for security purposes.</p>
            <p>Best regards,<br>AGC Hostel Administration Team</p>
        ";

		$mail->AltBody = "Dear User,\n\nYour password is: $password\n\nWe recommend changing it immediately for security purposes.\n\nBest regards,\nAGC Hostel Administration Team";

		$mail->send();
		return true;
	} catch (Exception $e) {
		error_log("Mailer Error: " . $mail->ErrorInfo);
		return false;
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

	<title>User Forgot Password</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>

	<div class="login-page bk-img" style="background-image: url(img/login-bg.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold text-light mt-4x">Forgot Password</h1>
						<div class="well row pt-2x pb-3x bk-light" style="padding: 27px;">
							<div class="col-md-8 col-md-offset-2">
								<?php if (isset($_POST['login']) && !empty($pwd)) { ?>
									<p>Your Password is <?php echo $pwd; ?><br> Change the Password after Login</p>
								<?php } ?>
								<form action="" class="mt" method="post">
									<label for="" class="text-uppercase text-sm">Your Email</label>
									<input type="email" placeholder="Email" name="email" class="form-control mb"
										required="required">

									<label for="" class="text-uppercase text-sm">Your Contact no</label>
									<input type="text" name="contact" class="form-control mb" required="required"
										oninput="this.value = this.value.replace(/[^0-9+ ]/g, '')" maxlength="15"
										step="1" placeholder="Contact No.">
									<br>
									<button class="btn btn-primary btn-block" type="submit" name="login"
										Value="login">Submit</button>
								</form>
							</div>
						</div>
						<div class="text-center text-light">
							<h4><a href="index.php" class="text-light">Sign in?</a></h4>
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