<?php
include('includes/config.php');

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Leave Request Response</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message-container {
            max-width: 500px;
            padding: 20px;
            border-radius: 8px;
            margin: 10px auto;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }
        .success {
            background-color: #e6ffe6;
            border: 1px solid #4caf50;
            color: #4caf50;
        }
        .error {
            background-color: #ffe6e6;
            border: 1px solid #f44336;
            color: #f44336;
        }
        .info {
            background-color: #e6f2ff;
            border: 1px solid #2196f3;
            color: #2196f3;
        }
        .message-container:hover {
            transform: scale(1.05);
        }
        h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            margin: 0;
        }
    </style>
</head>
<body>";


if (isset($_GET['id'], $_GET['action'])) {

    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $guardian_status = 'Approved';
    } elseif ($action == 'reject') {
        $guardian_status = 'Rejected';
    } else {
        echo "<div class='message-container error'>
                <h2>🚫 Invalid Action</h2>
                <p>The action you attempted is not valid.</p>
              </div>";
        exit;
    }

    $stmt = $mysqli->prepare("SELECT guardian_status FROM leave_requests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['guardian_status'] == 'Pending') {
            $update_stmt = $mysqli->prepare("UPDATE leave_requests SET guardian_status = ? WHERE id = ?");
            $update_stmt->bind_param("si", $guardian_status, $id);

            if ($update_stmt->execute()) {
                echo "<div class='message-container success'>
                        <h2>🎉 Thank You!</h2>
                        <p>Your decision has been recorded as: <b>$guardian_status</b>.</p>
                      </div>";
            } else {
                echo "<div class='message-container error'>
                        <h2>⚠️ Error</h2>
                        <p>Failed to record your decision. Please try again later.</p>
                      </div>";
            }
        } else {
            $existing_status = $row['guardian_status'];
            echo "<div class='message-container info'>
                    <h2>💡 Decision Already Recorded</h2>
                    <p>Your previous decision for this leave request was: <b>$existing_status</b>.</p>
                    <p>No further action is required.</p>
                  </div>";
        }
    } else {
        echo "<div class='message-container error'>
                <h2>🚫 Invalid Request</h2>
                <p>No leave request found with the provided ID. Please verify the link or contact support.</p>
              </div>";
    }
} else {
    echo "<div class='message-container error'>
            <h2>🚫 Invalid Request</h2>
            <p>Missing parameters. Please verify the link.</p>
          </div>";
}

echo "<script>
        setTimeout(function() {
            window.close();
        }, 5000); 
      </script>";

echo "</body></html>";
?>