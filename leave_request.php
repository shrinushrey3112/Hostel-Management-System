<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_email = $_SESSION['login'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_start_date = $_POST['leave_start_date'];
    $leave_end_date = $_POST['leave_end_date'];
    $reason = $_POST['reason'];

    $stmt = $mysqli->prepare("SELECT user_id FROM userregistration WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_id = $row['user_id'];

        $stmt = $mysqli->prepare("INSERT INTO leave_requests (student_id, leave_start_date, leave_end_date, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $student_id, $leave_start_date, $leave_end_date, $reason);


        if ($stmt->execute()) {

            $leave_request_id = $stmt->insert_id;

            if (notifyGuardian($student_id, $user_email, $leave_start_date, $leave_end_date, $reason, $leave_request_id)) {
                $_SESSION['alert_message'] = ['type' => 'success', 'message' => 'Leave request submitted successfully.'];
            } else {
                $_SESSION['alert_message'] = ['type' => 'warning', 'message' => 'Leave request submitted, but failed to notify guardian.'];
            }
        } else {
            $_SESSION['alert_message'] = ['type' => 'danger', 'message' => 'Error inserting leave request: ' . $stmt->error];
        }
    } else {
        $_SESSION['alert_message'] = ['type' => 'danger', 'message' => 'No student found with the provided email address.'];
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


// Function to notify guardian
function notifyGuardian($student_id, $user_email, $leave_start, $leave_end, $reason, $leave_request_id)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT firstName, lastName, guardianName, guardianEmail FROM registration WHERE emailid = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $studentName = $row['firstName'] . " " . $row['lastName'];
        $guardianName = $row['guardianName'];
        $guardian_email = $row['guardianEmail'];

        // PHPMailer setup
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'agc.hostel.wardem@gmail.com';
            $mail->Password = 'rxny puef ltlz tnnb'; // 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('agc.hostel.wardem@gmail.com', 'AGC HOSTEL');
            $mail->addAddress($guardian_email, $guardianName);

            $formatted_leave_start = date("d-m-Y", strtotime($leave_start));
            $formatted_leave_end = date("d-m-Y", strtotime($leave_end));

            $mail->isHTML(true);
            $mail->Subject = 'Leave Request Notification';
            $mail->Body = "
                <p>Dear $guardianName,</p>

                <p>We hope this message finds you well. We would like to inform you that your ward, <strong>$studentName</strong>, has submitted a leave request as per the details below:</p>

                <table style=\"border-collapse: collapse; width: 100%; margin-top: 10px;\">
                     <tr>
                        <th style=\"border: 1px solid #ddd; padding: 8px; background-color: #ffe680; text-align: center; width: 25%;\">From Date</th>
                        <th style=\"border: 1px solid #ddd; padding: 8px; background-color: #ffe680; text-align: center; width: 25%;\">To Date</th>
                        <th style=\"border: 1px solid #ddd; padding: 8px; background-color: #ffe680; text-align: center; width: 50%;\">Reason</th>
                    </tr>
                    <tr>
                        <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">$formatted_leave_start</td>
                        <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">$formatted_leave_end</td>
                        <td style=\"border: 1px solid #ddd; padding: 8px; text-align: left;\">$reason</td>
                    </tr>
                </table>

                <p>To approve or reject the leave request, kindly click on the respective button below:</p>

                <div style=\"margin-top: 20px;\">
                <a href=\"http://localhost/hostel1/guardian_approval.php?action=approve&id=$leave_request_id\" 
                style=\"display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;\">
                Approve
                </a>
                <a href=\"http://localhost/hostel1/guardian_approval.php?action=reject&id=$leave_request_id\"
                style=\"display: inline-block; background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;\">
                Reject
                </a>
                </div>  

                <p>We kindly request you to respond at your earliest convenience to assist us in processing the request promptly.</p>

                <p>Thank you for your cooperation.</p>

                <p>Best regards,<br>AGC Hostel Administration Team</p>
            ";

            $mail->AltBody = "Dear $guardianName,

                We hope this message finds you well. We would like to inform you that your ward, $studentName, has submitted a leave request as per the details below:
                
                Leave Start Date: $formatted_leave_start
                Leave End Date: $formatted_leave_end
                Reason: $reason
                
                To approve or reject the leave request, please click on the respective links below:
                
                Approve: http://localhost/hostel1/guardian_approval.php?action=approve&student_id=$student_id&leave_start=$leave_start, 
                Reject: http://localhost/hostel1/guardian_approval.php?action=reject&student_id=$student_id&leave_start=$leave_start.    
                
                We kindly request you to respond at your earliest convenience to assist us in processing the request promptly.
                
                Thank you for your cooperation.
                
                Best regards,
                AGC Hostel Administration Team";


            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}<br>"; // Debugging output
            return false;
        }
    } else {
        echo "No guardian email found for the provided email: $user_email<br>"; // Debugging output
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
    <meta name="theme-color" content="#3e454c">
    <title>Make Leave Requests</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>

</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <h2 class="page-title" style="margin-top: 25px;">Make Leave Request </h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Fill all Info</div>
                                    <div class="panel-body">


                                        <?php
                                        if (isset($_SESSION['alert_message'])) {
                                            $alert = $_SESSION['alert_message'];
                                            echo "<script>alert('{$alert['message']}');</script>";
                                            unset($_SESSION['alert_message']);
                                        }
                                        ?>

                                        <form method="post" action="" class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">From Date</label>
                                                <div class="col-sm-8">
                                                    <input type="date" name="leave_start_date" id="leave_start_date"
                                                        required>
                                                    <span id="start_date_error" class="error-message"
                                                        style="color: red; display: none;">Please choose today or a
                                                        future date.</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">To Date</label>
                                                <div class="col-sm-8">
                                                    <input type="date" name="leave_end_date" id="leave_end_date"
                                                        required>
                                                    <span id="end_date_error" class="error-message"
                                                        style="color: red; display: none;">End date should be after the
                                                        start date.</span>
                                                </div>
                                            </div>


                                            <script>
                                                const today = new Date().toISOString().split('T')[0];

                                                document.getElementById('leave_start_date').setAttribute('min', today);

                                                function validateDates() {
                                                    const startDateField = document.getElementById('leave_start_date');
                                                    const endDateField = document.getElementById('leave_end_date');
                                                    const startDateError = document.getElementById('start_date_error');
                                                    const endDateError = document.getElementById('end_date_error');

                                                    const startDate = startDateField.value;
                                                    const endDate = endDateField.value;

                                                    if (startDate && startDate < today) {
                                                        startDateError.style.display = 'block';
                                                        startDateField.classList.add('invalid');
                                                    } else {
                                                        startDateError.style.display = 'none';
                                                        startDateField.classList.remove('invalid');
                                                    }

                                                    if (endDate && startDate) {
                                                        const minEndDate = new Date(startDate);
                                                        minEndDate.setDate(minEndDate.getDate() + 1);
                                                        const formattedMinEndDate = minEndDate.toISOString().split('T')[0];

                                                        if (endDate < formattedMinEndDate) {
                                                            endDateError.style.display = 'block';
                                                            endDateField.classList.add('invalid');
                                                        } else {
                                                            endDateError.style.display = 'none';
                                                            endDateField.classList.remove('invalid');
                                                        }
                                                    }
                                                }

                                                document.getElementById('leave_start_date').addEventListener('input', function () {

                                                    const endDateField = document.getElementById('leave_end_date');
                                                    const startDate = new Date(this.value);
                                                    startDate.setDate(startDate.getDate() + 1);
                                                    endDateField.min = startDate.toISOString().split('T')[0];
                                                    validateDates();
                                                });

                                                document.getElementById('leave_end_date').addEventListener('input', validateDates);
                                            </script>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Reason</label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" name="reason" class="form-control"
                                                        placeholder="Enter reason here" required="required"></textarea>
                                                </div>
                                            </div>


                                            <div class="col-sm-6 col-sm-offset-4">
                                                <button class="btn btn-primary" type="submit"
                                                    value="Submit Leave Request">Submit Leave Request</button>

                                            </div>
                                        </form>

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