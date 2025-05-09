<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
	<title>Track Leave Requests</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">


	<style>
		.col-leave-start,
		.col-leave-end {
			width: 10%;
		}

		.col-reason {
			width: 60%;
		}

		.col-guardian-status {
			width: 10%;
		}

		.col-admin-decision {
			width: 10%;
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
						<h2 class="page-title" style="margin-top: 45px;"> Track Leave Requests</h2>
						<div class="panel panel-default">
							<div class="panel-heading">Leave Requests</div>
							<div class="panel-body">
								<table id="zctb" class="table table-bordered " cellspacing="0" width="100%">

									<tbody>

										<?php
										$user_id = $_SESSION['id'];
										$query = "SELECT leave_start_date, leave_end_date, reason, status, guardian_status FROM leave_requests WHERE student_id = ?";
										$stmt = $mysqli->prepare($query);
										$stmt->bind_param("i", $user_id);
										$stmt->execute();
										$result = $stmt->get_result();
										?>
										<tr>
											<th class="col-leave-start">From Date</th>
											<th class="col-leave-end">To Date</th>
											<th class="col-reason">Reason</th>
											<th class="col-guardian-status text-center">Guardian Status</th>
											<th class="col-admin-decision text-center">Admin Status</th>
										</tr>
										<?php while ($row = $result->fetch_assoc()) { ?>
											<tr>
												<td>
													<?php
													$startDate = new DateTime($row['leave_start_date']);
													echo $startDate->format('d-m-Y');
													?>
												</td>
												<td>
													<?php
													$endDate = new DateTime($row['leave_end_date']);
													echo $endDate->format('d-m-Y');
													?>
												</td>

												<td><?php echo $row['reason']; ?></td>
												<td class="text-center"><?php echo $row['guardian_status']; ?></td>
												<td class="text-center"><?php echo $row['status']; ?></td>
											</tr>
										<?php } ?>
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