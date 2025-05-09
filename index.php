<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the statement
    $stmt = $mysqli->prepare("SELECT email, password, user_id FROM userregistration WHERE email = ? AND password = ?");
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();

    $stmt->bind_result($db_email, $db_password, $id);
    $rs = $stmt->fetch();
    $stmt->close();

    if ($rs) {
        $_SESSION['id'] = $id;
        $_SESSION['login'] = $db_email;

        echo "Logged in successfully. User ID: " . $_SESSION['id'] . ", Email: " . $_SESSION['login'];

        $uip = $_SERVER['REMOTE_ADDR'];
        $ldate = date('d/m/Y h:i:s', time());

        header("location: dashboard.php");
        exit;
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
    <meta name="theme-color" content="#3e454c">
    <title>Student Hostel Registration</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        function valid() {
            if (document.registration.password.value != document.registration.cpassword.value) {
                alert("Password and Re-Type Password Field do not match  !!");
                document.registration.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
    <style>
        body {
            background-image: url('img/user login.jpg');
            /* Specify the path to your background image */
            background-size: cover;
            /* Cover the entire background */
            background-position: center;
            /* Center the background image */
            background-repeat: no-repeat;
            /* Prevent the background from repeating */
        }

        .login-form {
            margin-top: 100px;
        }

        .btn-primary {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="color:white;">User Login</h2>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="login-form">
                                    <div class="col-md-8 col-md-offset-2">
                                        <form action="" class="mt" method="post">
                                            <label for="" class="text-uppercase text-sm"
                                                style="color:white;">Email</label>
                                            <input type="text" placeholder="Email" name="email" class="form-control mb">
                                            <label for="" class="text-uppercase text-sm"
                                                style="color:white;">Password</label>
                                            <input type="password" placeholder="Password" name="password"
                                                class="form-control mb">
                                            <button class="btn btn-primary btn-block" type="submit" name="login"
                                                value="Login">Login</button>
                                        </form>
                                        <div class="text-center text-light" style="color:white; margin-top: 17px;">
                                            <a href="forgot-password.php" style="color:white;">Forgot password?</a>
                                        </div>
                                    </div>
                                </div>
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