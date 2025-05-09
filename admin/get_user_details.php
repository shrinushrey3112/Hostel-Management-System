<?php
include('includes/config.php');

if (isset($_POST['regNo'])) {
    $regNo = $_POST['regNo'];
    $query = "SELECT firstName, middleName, lastName, gender, contactNo, email FROM userregistration WHERE regNo = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $regNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $gender = isset($row['gender']) ? $row['gender'] : null;

        $response = [
            'firstName' => $row['firstName'],
            'middleName' => $row['middleName'],
            'lastName' => $row['lastName'],
            'gender' => $gender,
            'contactNo' => $row['contactNo'],
            'email' => $row['email'],
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}
?>