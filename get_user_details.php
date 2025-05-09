<?php
include('includes/config.php');

header('Content-Type: application/json');

$response = [];

if (isset($_POST['regNo'])) {
    $regNo = $_POST['regNo'];
    $query = "SELECT firstName, middleName, lastName, gender, contactNo, email FROM userregistration WHERE regNo = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $regNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = $row;
    } else {
        $response = ['error' => 'No data found'];
    }
} else {
    $response = ['error' => 'Invalid request'];
}

echo json_encode($response);
?>