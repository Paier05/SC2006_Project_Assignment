<?php
session_start();
include '../config.php'; // Include your database connection

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $stall_owner = $_SESSION['user_id']; // Get the logged-in user's ID
} else {
    die("User not logged in.");
}

// Check if the status is set in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) { // Check if 'status' is set
        $status = $_POST['status']; // Get the status from the AJAX request

        // Set the opening status based on the button clicked
        $opening_status = ($status === 'open') ? true : false; // True for open, false for close

        // Prepare the SQL query to update the opening status
        $query = "UPDATE HawkerStalls SET opening_status = ? WHERE stall_owner = ?";
        $params = array($opening_status, $stall_owner);
        
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            // Print the SQL error details for debugging
            echo json_encode(array('error' => sqlsrv_errors()));
            exit; // Exit to prevent further processing
        }

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

        echo json_encode(array('success' => 'Status updated successfully.')); // Optional response
    } else {
        echo json_encode(array('error' => 'Status not set.'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method.'));
}
?>
