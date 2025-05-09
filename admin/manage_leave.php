<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $stmt = $mysqli->prepare("DELETE FROM leave_requests WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Leave request deleted successfully');</script>";
    echo "<script>window.location.href = 'manage_leave.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_request_id = $_POST['leave_request_id'];
    $admin_action = $_POST['action'];

    if ($admin_action == 'approve') {
        $status = 'Approved';
    } elseif ($admin_action == 'reject') {
        $status = 'Rejected';
    } else {
        die("Invalid action");
    }

    $stmt = $mysqli->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_request_id);
    $stmt->execute();
    $stmt->close();
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
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">


    <style>
        .col-student-id {
            width: 1%;
        }

        .col-student-name {
            width: 15%;
        }

        .col-leave-start,
        .col-leave-end {
            width: 10%;
        }

        .col-reason {
            width: 34%;
        }

        .col-guardian-status {
            width: 13%;
        }

        .col-admin-decision {
            width: 15.5%;
        }

        .col-action {
            width: 3%;
        }
    </style>


    <style>
        .text-center {
            text-align: center;
        }

        .col-action {
            text-align: center;
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
                        <h2 class="page-title" style="margin-top: 70;">Manage Leave Requests</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Manage Requests</div>
                            <div class="panel-body">
                                <table id="zctb" class="table table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="col-student-id">Student ID</th>
                                            <th class="col-student-name">Student Name</th>
                                            <th class="col-leave-start">From Date</th>
                                            <th class="col-leave-end">To Date</th>
                                            <th class="col-reason">Reason</th>
                                            <th class="col-guardian-status">Guardian Status</th>
                                            <th class="col-admin-decision">Admin Decision</th>
                                            <th class="col-action text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $mysqli->prepare("
                                                    SELECT 
                                                        lr.id AS leave_request_id, 
                                                        lr.student_id, 
                                                        lr.leave_start_date, 
                                                        lr.leave_end_date, 
                                                        lr.reason, 
                                                        lr.status, 
                                                        lr.guardian_status, 
                                                        CONCAT(r.firstName, ' ', r.middleName, ' ', r.lastName) AS student_name
                                                    FROM 
                                                        leave_requests lr
                                                    JOIN 
                                                        registration r 
                                                    ON 
                                                        lr.student_id = r.id
                                                ");

                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['student_id']; ?></td>
                                                <td><?php echo $row['student_name']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['leave_start_date'])); ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['leave_end_date'])); ?></td>
                                                <td><?php echo $row['reason']; ?></td>
                                                <td><?php echo $row['guardian_status']; ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'Pending') { ?>
                                                        <form method="post" action="">
                                                            <input type="hidden" name="leave_request_id"
                                                                value="<?php echo $row['leave_request_id']; ?>">
                                                            <button class="btn btn-primary btn-sm" type="submit" name="action"
                                                                value="approve">Approve</button>
                                                            <button class="btn btn-default btn-sm" type="submit" name="action"
                                                                value="reject">Reject</button>
                                                        </form>
                                                    <?php } else {
                                                        echo $row['status'];
                                                    } ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="manage_leave.php?del=<?php echo $row['leave_request_id']; ?>"
                                                        onclick="return confirm('Are you sure you want to delete this request?');">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php }
                                        $stmt->close();
                                        ?>
                                    </tbody>
                                </table>


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